<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\CommissionModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Commission extends BaseController
{
    protected $commissionModel;
    protected $orderModel;
    protected $userModel;

    public function __construct()
    {
        $this->commissionModel = new CommissionModel();
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $commissions = $db->table('commissions')
            ->select('commissions.*, users.nama_lengkap as user_nama, users.email as user_email, users.role as user_role, orders.no_order, orders.total as order_total')
            ->join('users', 'users.id = commissions.user_id', 'left')
            ->join('orders', 'orders.id = commissions.order_id', 'left')
            ->orderBy('commissions.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $orders = $this->orderModel->select('id, no_order, total, status')
            ->orderBy('id', 'DESC')
            ->findAll();

        $users = $this->userModel->select('id, nama_lengkap, email, role')
            ->whereIn('role', ['mitra', 'manager_mitra', 'cabang', 'pusat'])
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Kelola Komisi',
            'commissions' => $commissions,
            'orders' => $orders,
            'users' => $users,
        ];

        return view('superadmin/commissions', $data);
    }

    public function store()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $orderId = $this->request->getPost('order_id');
        $userId = $this->request->getPost('user_id');
        $nominal = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('nominal_snapshot'));
        $status = $this->request->getPost('status') ?? 'pending';
        $paidAt = $this->request->getPost('paid_at');

        if (empty($paidAt) && $status === 'dibayar') {
            $paidAt = date('Y-m-d H:i:s');
        } elseif ($status !== 'dibayar') {
            $paidAt = null;
        }

        $this->commissionModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'nominal_snapshot' => $nominal,
            'status' => $status,
            'paid_at' => $paidAt ?: null,
        ]);

        return redirect()->to('/superadmin/commissions')->with('success', 'Catatan komisi baru berhasil ditambahkan');
    }

    public function update($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $commission = $this->commissionModel->find($id);
        if (!$commission) {
            return redirect()->to('/superadmin/commissions')->with('error', 'Catatan komisi tidak ditemukan');
        }

        $orderId = $this->request->getPost('order_id');
        $userId = $this->request->getPost('user_id');
        $nominal = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('nominal_snapshot'));
        $status = $this->request->getPost('status') ?? 'pending';
        $paidAt = $this->request->getPost('paid_at');

        if (empty($paidAt) && $status === 'dibayar') {
            $paidAt = !empty($commission['paid_at']) ? $commission['paid_at'] : date('Y-m-d H:i:s');
        } elseif ($status !== 'dibayar') {
            $paidAt = null;
        }

        $this->commissionModel->update($id, [
            'order_id' => $orderId,
            'user_id' => $userId,
            'nominal_snapshot' => $nominal,
            'status' => $status,
            'paid_at' => $paidAt ?: null,
        ]);

        return redirect()->to('/superadmin/commissions')->with('success', 'Data komisi berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $commission = $this->commissionModel->find($id);
        if (!$commission) {
            return redirect()->to('/superadmin/commissions')->with('error', 'Catatan komisi tidak ditemukan');
        }

        $this->commissionModel->delete($id);
        return redirect()->to('/superadmin/commissions')->with('success', 'Catatan komisi berhasil dihapus');
    }

    public function withdrawals()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $withdrawals = $db->table('withdrawals w')
            ->select('w.*, u.nama_lengkap as nama_user, u.email as email_user, u.role as role_user, u.no_wa')
            ->join('users u', 'u.id = w.user_id')
            ->orderBy('w.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Kelola Penarikan Dana Komisi',
            'withdrawals' => $withdrawals,
        ];

        return view('superadmin/withdrawals', $data);
    }

    public function processWithdrawal($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/superadmin/withdrawals');
        }

        $wdModel = new \App\Models\WithdrawalModel();
        $wd = $wdModel->find((int)$id);
        if (!$wd) {
            return redirect()->back()->with('error', 'Data penarikan tidak ditemukan.');
        }

        $action = $this->request->getPost('action');
        $catatan = trim((string)$this->request->getPost('catatan_admin'));

        $db = \Config\Database::connect();
        $db->transStart();

        if ($action === 'approve') {
            $buktiName = null;
            $fileBukti = $this->request->getFile('bukti_transfer');
            if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
                $uploadDir = FCPATH . 'uploads/payments';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $buktiName = $fileBukti->getRandomName();
                $fileBukti->move($uploadDir, $buktiName);
                $buktiName = 'uploads/payments/' . $buktiName;
            }

            $wdModel->update($id, [
                'status' => 'selesai',
                'bukti_transfer' => $buktiName,
                'catatan_admin' => $catatan ?: 'Dana komisi telah berhasil ditransfer ke rekening penerima.',
                'processed_by' => session('user_id'),
                'processed_at' => date('Y-m-d H:i:s'),
            ]);

            // Mutasi komisi status menjadi 'ditarik'
            $userId = (int)$wd['user_id'];
            $nomRemaining = (float)$wd['nominal'];

            $commList = $db->table('commissions')
                ->where('user_id', $userId)
                ->where('status', 'siap_cair')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($commList as $c) {
                if ($nomRemaining <= 0) break;
                $cNom = (float)$c['nominal_snapshot'];
                $db->table('commissions')->where('id', $c['id'])->update([
                    'status' => 'ditarik',
                    'paid_at' => date('Y-m-d H:i:s')
                ]);
                $nomRemaining -= $cNom;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses persetujuan penarikan dana.');
            }

            return redirect()->to('/superadmin/withdrawals')->with('success', 'Permintaan penarikan ' . $wd['no_penarikan'] . ' sebesar Rp ' . number_format($wd['nominal'], 0, ',', '.') . ' berhasil disetujui.');
        } else {
            $wdModel->update($id, [
                'status' => 'ditolak',
                'catatan_admin' => $catatan ?: 'Permintaan penarikan ditolak oleh admin.',
                'processed_by' => session('user_id'),
                'processed_at' => date('Y-m-d H:i:s'),
            ]);

            $db->transComplete();

            return redirect()->to('/superadmin/withdrawals')->with('warning', 'Permintaan penarikan ' . $wd['no_penarikan'] . ' ditolak. Saldo dikembalikan ke saldo siap cair mitra.');
        }
    }
}
