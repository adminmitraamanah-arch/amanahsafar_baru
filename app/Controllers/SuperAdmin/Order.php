<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderPaymentModel;
use App\Models\CommissionModel;

class Order extends BaseController
{
    protected $orderModel;
    protected $paymentModel;
    protected $commissionModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->paymentModel = new OrderPaymentModel();
        $this->commissionModel = new CommissionModel();
    }

    public function index()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('orders o')
            ->select('o.*, u.nama_lengkap as nama_mitra, u.email as email_mitra, u.role as role_mitra, u.no_wa as no_wa_mitra')
            ->join('users u', 'u.id = o.user_id')
            ->orderBy('o.id', 'DESC');

        $orders = $builder->get()->getResultArray();

        $data = [
            'title' => 'Kelola Pesanan Jamaah & Mitra',
            'orders' => $orders,
        ];

        return view('superadmin/orders', $data);
    }

    public function detail($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel->getOrderWithItems((int)$id);
        if (!$order) {
            return redirect()->to('/superadmin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $order['user_id'])->get()->getRowArray();
        $payments = $this->paymentModel->getPaymentsByOrder((int)$id);
        $commissions = $db->table('commissions c')
            ->select('c.*, u.nama_lengkap as nama_penerima, u.email as email_penerima')
            ->join('users u', 'u.id = c.user_id', 'left')
            ->where('c.order_id', (int)$id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Detail Pesanan ' . $order['no_order'],
            'order' => $order,
            'buyer' => $user,
            'payments' => $payments,
            'commissions' => $commissions,
        ];

        return view('superadmin/order_detail', $data);
    }

    public function verifyPayment($paymentId)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $payment = $this->paymentModel->find((int)$paymentId);
        if (!$payment) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $orderId = (int)$payment['order_id'];
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $action = $this->request->getPost('action'); // 'approve' or 'reject'
        $adminNotes = trim((string)$this->request->getPost('catatan_admin'));

        $db = \Config\Database::connect();
        $db->transStart();

        if ($action === 'approve') {
            // Update status pembayaran ini
            $this->paymentModel->update($paymentId, [
                'status' => 'diterima',
                'catatan_admin' => $adminNotes ?: 'Pembayaran telah diverifikasi dan disetujui.',
                'verified_by' => session('user_id'),
                'verified_at' => date('Y-m-d H:i:s'),
            ]);

            // Hitung akumulasi pembayaran yang telah disetujui
            $approvedSumRow = $db->table('order_payments')
                ->where('order_id', $orderId)
                ->where('status', 'diterima')
                ->selectSum('nominal')
                ->get()
                ->getRowArray();

            $totalDibayar = (float)($approvedSumRow['nominal'] ?? 0);
            $totalOrder = (float)$order['total'];
            $sisaTagihan = max(0, $totalOrder - $totalDibayar);

            $newStatusPembayaran = 'menunggu_pembayaran';
            $newStatusOrder = $order['status'];

            if ($sisaTagihan <= 0) {
                // Lunas penuh!
                $newStatusPembayaran = 'lunas';
                $newStatusOrder = 'diproses';

                // Jika komisi belum pernah di-generate, generate sekarang
                $commCount = $this->commissionModel->where('order_id', $orderId)->countAllResults();
                if ($commCount === 0) {
                    $this->commissionModel->generateCommissionsForOrder($orderId, 'siap_cair');
                } else {
                    // Update seluruh potensi komisi menjadi SIAP CAIR!
                    $this->commissionModel->updateCommissionStatusForOrder($orderId, 'siap_cair');
                }
            } else {
                // Pembayaran DP diterima
                $newStatusPembayaran = 'dp_diterima';
                $newStatusOrder = 'dp_diterima';

                // Generate POTENSI KOMISI jika belum ada
                $commCount = $this->commissionModel->where('order_id', $orderId)->countAllResults();
                if ($commCount === 0) {
                    $this->commissionModel->generateCommissionsForOrder($orderId, 'potensi');
                }
            }

            $this->orderModel->update($orderId, [
                'total_dibayar' => $totalDibayar,
                'sisa_tagihan' => $sisaTagihan,
                'status_pembayaran' => $newStatusPembayaran,
                'status' => $newStatusOrder,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses verifikasi pembayaran.');
            }

            $msg = ($newStatusPembayaran === 'lunas')
                ? 'Pembayaran pelunasan disetujui! Pesanan kini LUNAS dan komisi otomatis siap dicairkan.'
                : 'Pembayaran DP disetujui! Seat terkunci dan potensi komisi telah tercatat di sistem.';

            return redirect()->to('/superadmin/orders/' . $orderId)->with('success', $msg);
        } else {
            // Penolakan pembayaran
            $this->paymentModel->update($paymentId, [
                'status' => 'ditolak',
                'catatan_admin' => $adminNotes ?: 'Bukti pembayaran tidak valid atau dana belum masuk mutasi rekening.',
                'verified_by' => session('user_id'),
                'verified_at' => date('Y-m-d H:i:s'),
            ]);

            $db->transComplete();

            return redirect()->to('/superadmin/orders/' . $orderId)->with('warning', 'Pembayaran ditolak. Mitra dapat mengunggah bukti transfer perbaikan.');
        }
    }

    public function updateStatus($orderId)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $order = $this->orderModel->find((int)$orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $newStatus = $this->request->getPost('status');
        $validStatuses = ['menunggu_pembayaran', 'dp_diterima', 'diproses', 'selesai', 'dibatalkan'];
        if (!in_array($newStatus, $validStatuses)) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid.');
        }

        $this->orderModel->update($orderId, ['status' => $newStatus]);

        if ($newStatus === 'dibatalkan') {
            $this->commissionModel->updateCommissionStatusForOrder($orderId, 'dibatalkan');
        }

        return redirect()->to('/superadmin/orders/' . $orderId)->with('success', 'Status pesanan berhasil diperbarui menjadi ' . ucfirst(str_replace('_', ' ', $newStatus)) . '.');
    }
}
