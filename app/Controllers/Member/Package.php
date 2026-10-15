<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Package extends BaseController
{
    protected $packageModel;
    protected $orderModel;
    protected $userModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
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
        $canOrder = $this->userModel->canOrderPackages(session('user_id'));
        $dpRuleModel = new \App\Models\PackageDpRuleModel();

        foreach ($packages as &$pkg) {
            $pkg['dp_info'] = $dpRuleModel->calculateMinimalDp($pkg['id'], 1);
        }

        $data = [
            'title' => 'Katalog Paket',
            'packages' => $packages,
            'userRole' => $role,
            'canOrder' => $canOrder,
        ];

        return view('member/packages', $data);
    }

    public function order()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        // Strict RBAC: Mitra, Manager Mitra, and Cabang can create orders
        $allowedOrderRoles = ['mitra', 'manager_mitra', 'cabang'];
        if (!in_array(session('role'), $allowedOrderRoles)) {
            return redirect()->to('/app/packages')->with('error', 'Akses ditolak: Akun Anda tidak memiliki hak akses untuk melakukan pemesanan paket.');
        }

        // Gatekeeper: Validasi kelengkapan verifikasi KTP, rekening bank, dan PKS
        $gateCheck = $this->userModel->canOrderPackages(session('user_id'));
        if (!$gateCheck['allowed']) {
            $targetRedirect = !empty($gateCheck['need_agreement']) ? '/agreement' : '/verify';
            return redirect()->to($targetRedirect)->with('warning', $gateCheck['message']);
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/app/packages');
        }

        $packageId = (int)$this->request->getPost('package_id');
        $qty = (int)$this->request->getPost('qty');
        $catatan = trim($this->request->getPost('catatan') ?? '');
        $tipePembayaran = $this->request->getPost('tipe_pembayaran') === 'full' ? 'full' : 'dp';

        if ($qty < 1) {
            return redirect()->back()->with('error', 'Jumlah pemesanan paket minimal 1.');
        }

        $package = $this->packageModel->find($packageId);
        if (!$package || $package['status'] !== 'aktif') {
            return redirect()->to('/app/packages')->with('error', 'Paket tidak ditemukan atau sudah tidak aktif.');
        }

        $dpRuleModel = new \App\Models\PackageDpRuleModel();
        $dpInfo = $dpRuleModel->calculateMinimalDp($packageId, $qty);
        $nominalDpMinimal = $dpInfo['total_min_dp'];

        $db = \Config\Database::connect();
        $db->transStart();

        $total = (float)$package['harga'] * $qty;
        $noOrder = $this->orderModel->generateOrderNumber();

        $orderId = $this->orderModel->insert([
            'no_order' => $noOrder,
            'user_id' => session('user_id'),
            'status' => 'menunggu_pembayaran',
            'status_pembayaran' => 'menunggu_pembayaran',
            'total' => $total,
            'tipe_pembayaran_pilihan' => $tipePembayaran,
            'nominal_dp_minimal' => $nominalDpMinimal,
            'total_dibayar' => 0.00,
            'sisa_tagihan' => $total,
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

        return redirect()->to('/app/orders/' . $orderId)->with('success', 'Pesanan ' . $noOrder . ' berhasil dibuat. Silakan lakukan pembayaran dan unggah bukti transfer.');
    }
}
