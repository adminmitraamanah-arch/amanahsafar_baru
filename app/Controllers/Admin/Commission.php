<?php

namespace App\Controllers\Admin;

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
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
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

        return view('admin/commissions', $data);
    }

    public function store()
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
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

        return redirect()->to('/admin/commissions')->with('success', 'Catatan komisi baru berhasil ditambahkan');
    }

    public function update($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $commission = $this->commissionModel->find($id);
        if (!$commission) {
            return redirect()->to('/admin/commissions')->with('error', 'Catatan komisi tidak ditemukan');
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

        return redirect()->to('/admin/commissions')->with('success', 'Data komisi berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $commission = $this->commissionModel->find($id);
        if (!$commission) {
            return redirect()->to('/admin/commissions')->with('error', 'Catatan komisi tidak ditemukan');
        }

        $this->commissionModel->delete($id);
        return redirect()->to('/admin/commissions')->with('success', 'Catatan komisi berhasil dihapus');
    }
}
