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
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();

        // 1. Ambil daftar voucher beserta data pembina dan induk pembina
        $vouchers = $db->table('vouchers v')
            ->select('v.*, p.nama_lengkap as nama_pembina, p.role as role_pembina, p.domisili as domisili_pembina, pi.nama_lengkap as nama_induk, pi.role as role_induk, u.nama_lengkap as nama_pengguna')
            ->join('users p', 'p.id = v.pembina_id', 'left')
            ->join('users pi', 'pi.id = p.pembina_id', 'left')
            ->join('users u', 'u.id = v.used_by', 'left')
            ->orderBy('v.id', 'DESC')
            ->get()
            ->getResultArray();

        // 2. Akun Pusat yang aktif
        $pusatAccounts = $db->table('users')
            ->select('id, nama_lengkap, role, email')
            ->where('role', 'pusat')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        // 3. Seluruh akun Cabang yang valid dan aktif
        $cabangAccounts = $db->table('users')
            ->select('id, nama_lengkap, role, email, domisili')
            ->where('role', 'cabang')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        // 4. Seluruh Manager Mitra yang valid dan aktif beserta relasi induknya (Pusat / Cabang)
        $managerAccounts = $db->table('users m')
            ->select('m.id, m.nama_lengkap, m.role, m.email, m.domisili, m.pembina_id, p.nama_lengkap as nama_induk, p.role as role_induk')
            ->join('users p', 'p.id = m.pembina_id', 'inner')
            ->where('m.role', 'manager_mitra')
            ->where('m.status', 'aktif')
            ->where('m.deleted_at', null)
            ->where('p.status', 'aktif')
            ->where('p.deleted_at', null)
            ->whereIn('p.role', ['pusat', 'cabang'])
            ->orderBy('m.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Kelola Voucher',
            'vouchers' => $vouchers,
            'pusatAccounts' => $pusatAccounts,
            'cabangAccounts' => $cabangAccounts,
            'managerAccounts' => $managerAccounts,
        ];

        return view('superadmin/vouchers', $data);
    }

    public function generate()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $qty = max(1, min(100, (int)$this->request->getPost('qty')));
        $pembinaId = (int)$this->request->getPost('pembina_id');
        $expiredAtInput = $this->request->getPost('expired_at');

        // Validasi wajib: Super Admin harus menentukan pembina langsung tertentu
        if ($pembinaId <= 0) {
            return redirect()->back()->with('error', 'Wajib memilih satu akun pembina langsung sebelum membuat voucher.')->withInput();
        }

        $db = \Config\Database::connect();

        // Periksa akun pembina yang dipilih
        $pembina = $db->table('users')
            ->where('id', $pembinaId)
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$pembina) {
            return redirect()->back()->with('error', 'Akun pembina yang dipilih tidak ditemukan atau sedang dinonaktifkan.')->withInput();
        }

        if (!in_array($pembina['role'], ['pusat', 'cabang', 'manager_mitra'])) {
            return redirect()->back()->with('error', 'Role pembina yang dipilih tidak valid. Pembina harus Pusat, Cabang, atau Manager Mitra.')->withInput();
        }

        // Jika pembina adalah manager_mitra, periksa bahwa relasi induknya sah (di bawah Pusat atau Cabang aktif)
        if ($pembina['role'] === 'manager_mitra') {
            if (empty($pembina['pembina_id'])) {
                return redirect()->back()->with('error', 'Manager Mitra yang dipilih tidak memiliki induk Cabang atau Pusat yang valid.')->withInput();
            }

            $induk = $db->table('users')
                ->where('id', $pembina['pembina_id'])
                ->where('status', 'aktif')
                ->where('deleted_at', null)
                ->whereIn('role', ['pusat', 'cabang'])
                ->get()
                ->getRowArray();

            if (!$induk) {
                return redirect()->back()->with('error', 'Induk dari Manager Mitra yang dipilih tidak aktif atau tidak valid.')->withInput();
            }
        }

        // Format tanggal expired jika diberikan
        $expiredAt = null;
        if (!empty($expiredAtInput)) {
            $ts = strtotime($expiredAtInput);
            if ($ts !== false) {
                $expiredAt = date('Y-m-d 23:59:59', $ts);
            }
        }

        // Generate voucher sebanyak $qty dengan kode unik
        for ($i = 0; $i < $qty; $i++) {
            do {
                $kode = strtoupper(bin2hex(random_bytes(4)));
                $exists = $this->voucherModel->where('kode', $kode)->countAllResults();
            } while ($exists > 0);

            $this->voucherModel->insert([
                'kode' => $kode,
                'role_tujuan' => 'mitra', // Sesuai aturan: Voucher hanya digunakan untuk role mitra!
                'pembina_id' => $pembinaId,
                'status' => 'tersedia',
                'expired_at' => $expiredAt,
                'created_by' => session('user_id'),
            ]);
        }

        $pembinaLabel = esc($pembina['nama_lengkap']) . ' (' . ucfirst(str_replace('_', ' ', $pembina['role'])) . ')';
        return redirect()->back()->with('success', "{$qty} voucher berhasil dibuat dengan pembina langsung: {$pembinaLabel}");
    }

    public function disable($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher tidak ditemukan');
        }

        $this->voucherModel->update($id, ['status' => 'dinonaktifkan']);
        return redirect()->back()->with('deleted', 'Voucher berhasil dinonaktifkan');
    }
}
