<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\OrderModel;

class Package extends BaseController
{
    protected $packageModel;
    protected $orderModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session('role');
        if (in_array($role, ['super_admin', 'superadmin'])) {
            return redirect()->to('/superadmin/packages');
        }
        if (in_array($role, ['admin', 'pusat'])) {
            return redirect()->to('/admin/packages');
        }

        // Fetch active packages for member view
        $packages = $this->packageModel->getActive();

        $data = [
            'title' => 'Katalog Paket',
            'packages' => $packages,
            'userRole' => $role,
        ];

        return view('member/packages', $data);
    }

    public function order()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        // Strict RBAC: Only Mitra role can create orders
        if (session('role') !== 'mitra') {
            return redirect()->to('/app/packages')->with('error', 'Akses ditolak: Hanya role Mitra yang memiliki hak untuk melakukan pemesanan paket.');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/app/packages');
        }

        $packageId = (int)$this->request->getPost('package_id');
        $qty = (int)$this->request->getPost('qty');
        $catatan = trim($this->request->getPost('catatan') ?? '');

        if ($qty < 1) {
            return redirect()->back()->with('error', 'Jumlah pemesanan paket minimal 1.');
        }

        $package = $this->packageModel->find($packageId);
        if (!$package || $package['status'] !== 'aktif') {
            return redirect()->to('/app/packages')->with('error', 'Paket tidak ditemukan atau sudah tidak aktif.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $total = (float)$package['harga'] * $qty;
        $noOrder = $this->orderModel->generateOrderNumber();

        $orderId = $this->orderModel->insert([
            'no_order' => $noOrder,
            'user_id' => session('user_id'),
            'status' => 'menunggu_pembayaran',
            'total' => $total,
            'catatan' => $catatan
        ]);

        $db->table('order_items')->insert([
            'order_id' => $orderId,
            'package_id' => $packageId,
            'qty' => $qty,
            'harga_snapshot' => $package['harga'],
            'subtotal' => $total
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses pemesanan paket. Silakan coba beberapa saat lagi.');
        }

        return redirect()->to('/app/orders')->with('success', 'Pesanan ' . $noOrder . ' berhasil dibuat. Silakan lakukan pembayaran dan unggah bukti transfer.');
    }
}
