<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\VoucherModel;

class Voucher extends BaseController
{
    protected $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $vouchers = $this->voucherModel->findAll();
        $data = [
            'title' => 'Kelola Voucher',
            'vouchers' => $vouchers,
        ];

        return view('superadmin/vouchers', $data);
    }

    public function generate()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $qty = $this->request->getPost('qty');
        $role = $this->request->getPost('role');
        $pembina_id = $this->request->getPost('pembina_id');

        for ($i = 0; $i < $qty; $i++) {
            $kode = strtoupper(bin2hex(random_bytes(4)));
            
            $this->voucherModel->insert([
                'kode' => $kode,
                'role_tujuan' => $role,
                'pembina_id' => $pembina_id ?: null,
                'status' => 'tersedia',
                'created_by' => session('user_id')
            ]);
        }

        return redirect()->back()->with('success', "{$qty} voucher baru dibuat");
    }

    public function disable($id)
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher tidak ditemukan');
        }

        $this->voucherModel->update($id, ['status' => 'dinonaktifkan']);
        return redirect()->back()->with('deleted', 'Voucher dinonaktifkan');
    }
}
