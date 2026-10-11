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

        $userModel = new \App\Models\UserModel();

        // 1. Ambil daftar voucher beserta data pembina dan induk pembina
        $vouchers = $db->table('vouchers v')
            ->select('v.*, p.nama_lengkap as nama_pembina, p.role as role_pembina, p.domisili as domisili_pembina, p.kode_pembina as kode_pembina_user, pi.nama_lengkap as nama_induk, pi.role as role_induk, u.nama_lengkap as nama_pengguna')
            ->join('users p', 'p.id = v.pembina_id', 'left')
            ->join('users pi', 'pi.id = p.pembina_id', 'left')
            ->join('users u', 'u.id = v.used_by', 'left')
            ->orderBy('v.id', 'DESC')
            ->get()
            ->getResultArray();

        // Helper untuk memperkaya data akun pembina dengan kode_pembina & nomor urut berikutnya
        $enrichAccounts = function (array $accounts) use ($db, $userModel) {
            foreach ($accounts as &$acc) {
                if (empty($acc['kode_pembina'])) {
                    $acc['kode_pembina'] = $userModel->getOrAssignKodePembina($acc['id']);
                }
                $acc['jenis_prefix'] = \App\Models\UserModel::getPrefixJenisPembina($acc['role']);

                $maxRow = $db->query(
                    "SELECT MAX(nomor_urut) AS max_urut FROM vouchers WHERE pembina_id = ?",
                    [$acc['id']]
                )->getRowArray();
                $lastUrut = (int)($maxRow['max_urut'] ?? 0);

                if ($lastUrut === 0) {
                    $prefixLike = "MIT-{$acc['jenis_prefix']}-{$acc['kode_pembina']}-%";
                    $lastV = $db->query(
                        "SELECT kode FROM vouchers WHERE pembina_id = ? AND kode LIKE ? ORDER BY id DESC LIMIT 1",
                        [$acc['id'], $prefixLike]
                    )->getRowArray();
                    if ($lastV && preg_match('/-(\d{4})$/', $lastV['kode'], $m)) {
                        $lastUrut = (int)$m[1];
                    }
                }

                $acc['next_urut'] = $lastUrut + 1;
                $acc['next_urut_fmt'] = sprintf('%04d', $acc['next_urut']);
                $acc['preview_code'] = "MIT-{$acc['jenis_prefix']}-{$acc['kode_pembina']}-{$acc['next_urut_fmt']}";
            }
            return $accounts;
        };

        // 2. Akun Pusat yang aktif
        $rawPusat = $db->table('users')
            ->select('id, nama_lengkap, role, email, kode_pembina')
            ->where('role', 'pusat')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
        $pusatAccounts = $enrichAccounts($rawPusat);

        // 3. Seluruh akun Cabang yang valid dan aktif
        $rawCabang = $db->table('users')
            ->select('id, nama_lengkap, role, email, domisili, kode_pembina')
            ->where('role', 'cabang')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
        $cabangAccounts = $enrichAccounts($rawCabang);

        // 4. Seluruh Manager Mitra yang valid dan aktif beserta relasi induknya (Pusat / Cabang)
        $rawManager = $db->table('users m')
            ->select('m.id, m.nama_lengkap, m.role, m.email, m.domisili, m.kode_pembina, m.pembina_id, p.nama_lengkap as nama_induk, p.role as role_induk')
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
        $managerAccounts = $enrichAccounts($rawManager);

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

        // Format tanggal expired jika diberikan
        $expiredAt = null;
        if (!empty($expiredAtInput)) {
            $ts = strtotime($expiredAtInput);
            if ($ts !== false) {
                $expiredAt = date('Y-m-d 23:59:59', $ts);
            }
        }

        // Panggil method pembuatan voucher terstruktur aman konkurensi
        $result = $this->voucherModel->generateStructuredVouchers(
            $pembinaId,
            $qty,
            $expiredAt,
            session('user_id')
        );

        if (!$result['status']) {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }

        $firstCode = $result['generatedCodes'][0] ?? '';
        $lastCode = end($result['generatedCodes']);
        $codeRange = ($qty === 1) ? $firstCode : "{$firstCode} s/d {$lastCode}";
        $pembina = $result['pembina'];
        $pembinaLabel = esc($pembina['nama_lengkap']) . ' (' . ucfirst(str_replace('_', ' ', $pembina['role'])) . ')';

        return redirect()->back()->with('success', "Berhasil menerbitkan {$qty} voucher [{$codeRange}] untuk pembina langsung: {$pembinaLabel}");
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
