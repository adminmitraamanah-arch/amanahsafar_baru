<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderPaymentModel;

class Order extends BaseController
{
    protected $orderModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->paymentModel = new OrderPaymentModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session('user_id');
        $orders = $this->orderModel->where('user_id', $userId)->orderBy('id', 'DESC')->findAll();

        $data = [
            'title' => 'Pesanan Saya',
            'orders' => $orders,
        ];

        return view('member/orders', $data);
    }

    public function detail($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel->getOrderWithItems((int)$id);
        
        if (!$order || $order['user_id'] != session('user_id')) {
            return redirect()->to('/app/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $payments = $this->paymentModel->getPaymentsByOrder((int)$id);

        $data = [
            'title' => 'Detail Pesanan ' . $order['no_order'],
            'order' => $order,
            'payments' => $payments,
        ];

        return view('member/order_detail', $data);
    }

    public function pay($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/app/orders/' . $id);
        }

        $order = $this->orderModel->find((int)$id);
        if (!$order || $order['user_id'] != session('user_id')) {
            return redirect()->to('/app/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        if (in_array($order['status_pembayaran'], ['lunas']) || in_array($order['status'], ['selesai', 'dibatalkan'])) {
            return redirect()->to('/app/orders/' . $id)->with('error', 'Pesanan ini sudah lunas atau sudah tidak aktif.');
        }

        $tipe = $this->request->getPost('tipe_pembayaran');
        if (!in_array($tipe, ['dp', 'pelunasan', 'full'])) {
            $tipe = 'dp';
        }

        $nominalStr = (string)$this->request->getPost('nominal');
        $nominal = (float)str_replace(['.', ','], ['', '.'], $nominalStr);

        if ($nominal <= 0) {
            return redirect()->back()->with('error', 'Nominal pembayaran tidak valid.');
        }

        // Validasi batas minimal DP jika pembayaran pertama
        if ($tipe === 'dp' && $order['total_dibayar'] == 0 && (float)$order['nominal_dp_minimal'] > 0) {
            if ($nominal < (float)$order['nominal_dp_minimal']) {
                return redirect()->back()->with('error', 'Nominal transfer minimal untuk uang muka (DP) adalah Rp ' . number_format($order['nominal_dp_minimal'], 0, ',', '.') . '.');
            }
        }

        // Upload berkas bukti transfer
        $fileBukti = $this->request->getFile('bukti_transfer');
        if (!$fileBukti || !$fileBukti->isValid() || $fileBukti->hasMoved()) {
            return redirect()->back()->with('error', 'Berkas bukti transfer wajib diunggah.');
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'application/pdf'];
        if (!in_array($fileBukti->getMimeType(), $allowedMimes)) {
            return redirect()->back()->with('error', 'Format berkas bukti transfer harus berupa gambar (JPG, PNG, WebP) atau PDF.');
        }

        $uploadDir = FCPATH . 'uploads/payments';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newName = $fileBukti->getRandomName();
        $fileBukti->move($uploadDir, $newName);
        $relativePath = 'uploads/payments/' . $newName;

        $noTransaksi = $this->paymentModel->generateTransactionNumber($id);

        $this->paymentModel->insert([
            'order_id' => (int)$id,
            'no_transaksi' => $noTransaksi,
            'tipe_pembayaran' => $tipe,
            'nominal' => $nominal,
            'bukti_transfer' => $relativePath,
            'nama_bank_pengirim' => trim((string)$this->request->getPost('nama_bank_pengirim')),
            'nomor_rekening_pengirim' => trim((string)$this->request->getPost('nomor_rekening_pengirim')),
            'nama_pengirim' => trim((string)$this->request->getPost('nama_pengirim')),
            'catatan' => trim((string)$this->request->getPost('catatan')),
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->to('/app/orders/' . $id)->with('success', 'Bukti transfer berhasil diunggah! Pembayaran Anda (' . $noTransaksi . ') sedang menunggu verifikasi admin.');
    }
}
