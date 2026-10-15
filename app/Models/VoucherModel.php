<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'kode',
        'role_tujuan',
        'pembina_id',
        'nomor_urut',
        'status',
        'expired_at',
        'used_by',
        'used_at',
        'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function findByKode($kode)
    {
        return $this->where('kode', strtoupper(trim((string)$kode)))->first();
    }

    /**
     * Memeriksa validitas dasar voucher
     */
    public function isValid($kode)
    {
        $validation = $this->validateForRegistration($kode);
        return $validation['valid'];
    }

    /**
     * Validasi mendalam voucher untuk pendaftaran (Cabang, Manager Mitra, Mitra):
     * - Status voucher harus 'tersedia'
     * - Role tujuan wajib 'cabang', 'manager_mitra', atau 'mitra'
     * - Belum kedaluwarsa jika ada expired_at
     * - Akun pembina langsung wajib ada, aktif, dan sesuai hierarki role:
     *   * Cabang: pembina wajib Kantor Pusat ('pusat')
     *   * Manager Mitra: pembina wajib Kantor Pusat ('pusat') atau Cabang ('cabang')
     *   * Mitra: pembina wajib Pusat, Cabang, atau Manager Mitra
     * - Jika pembina Manager Mitra, relasi induknya (Pusat / Cabang) wajib valid dan aktif
     */
    public function validateForRegistration($kode)
    {
        $kode = strtoupper(trim((string)$kode));
        if (empty($kode)) {
            return ['valid' => false, 'message' => 'Kode voucher tidak boleh kosong', 'voucher' => null, 'pembina' => null];
        }

        $voucher = $this->findByKode($kode);
        if (!$voucher) {
            return ['valid' => false, 'message' => 'Kode voucher tidak ditemukan', 'voucher' => null, 'pembina' => null];
        }

        if ($voucher['status'] !== 'tersedia') {
            $msg = ($voucher['status'] === 'terpakai') 
                ? 'Kode voucher sudah pernah digunakan' 
                : 'Kode voucher sudah dinonaktifkan';
            return ['valid' => false, 'message' => $msg, 'voucher' => $voucher, 'pembina' => null];
        }

        if (!empty($voucher['expired_at']) && strtotime($voucher['expired_at']) < time()) {
            return ['valid' => false, 'message' => 'Kode voucher telah kedaluwarsa', 'voucher' => $voucher, 'pembina' => null];
        }

        $validRoles = ['cabang', 'manager_mitra', 'mitra'];
        if (!in_array($voucher['role_tujuan'], $validRoles, true)) {
            return ['valid' => false, 'message' => 'Role tujuan pada voucher tidak valid untuk pendaftaran akun', 'voucher' => $voucher, 'pembina' => null];
        }

        if (empty($voucher['pembina_id'])) {
            return ['valid' => false, 'message' => 'Voucher tidak memiliki akun pembina yang valid', 'voucher' => $voucher, 'pembina' => null];
        }

        $db = \Config\Database::connect();
        $pembina = $db->table('users')
            ->where('id', (int)$voucher['pembina_id'])
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$pembina) {
            return ['valid' => false, 'message' => 'Akun pembina yang tersimpan pada voucher tidak ditemukan', 'voucher' => $voucher, 'pembina' => null];
        }

        if ($pembina['status'] !== 'aktif') {
            return ['valid' => false, 'message' => 'Akun pembina pada voucher ini sedang dinonaktifkan', 'voucher' => $voucher, 'pembina' => $pembina];
        }

        // Validasi aturan hierarki berdasarkan role tujuan voucher
        if ($voucher['role_tujuan'] === 'cabang') {
            // Cabang wajib dibina oleh Kantor Pusat
            if ($pembina['role'] !== 'pusat') {
                return ['valid' => false, 'message' => 'Voucher untuk pendaftaran Cabang wajib dibina langsung oleh Kantor Pusat', 'voucher' => $voucher, 'pembina' => $pembina];
            }
        } elseif ($voucher['role_tujuan'] === 'manager_mitra') {
            // Manager Mitra wajib dibina oleh Kantor Pusat atau Cabang
            if (!in_array($pembina['role'], ['pusat', 'cabang'])) {
                return ['valid' => false, 'message' => 'Voucher untuk pendaftaran Manager Mitra wajib dibina langsung oleh Kantor Pusat atau Cabang', 'voucher' => $voucher, 'pembina' => $pembina];
            }
        } elseif ($voucher['role_tujuan'] === 'mitra') {
            // Mitra dapat dibina oleh Pusat, Cabang, atau Manager Mitra
            if (!in_array($pembina['role'], ['pusat', 'cabang', 'manager_mitra'])) {
                return ['valid' => false, 'message' => 'Role pembina pada voucher tidak valid untuk pendaftaran Mitra', 'voucher' => $voucher, 'pembina' => $pembina];
            }

            // Jika pembina adalah manager_mitra, periksa relasi induknya (Pusat atau Cabang)
            if ($pembina['role'] === 'manager_mitra') {
                if (empty($pembina['pembina_id'])) {
                    return ['valid' => false, 'message' => 'Manager Mitra pembina tidak memiliki akun induk yang sah', 'voucher' => $voucher, 'pembina' => $pembina];
                }

                $induk = $db->table('users')
                    ->where('id', (int)$pembina['pembina_id'])
                    ->where('deleted_at', null)
                    ->get()
                    ->getRowArray();

                if (!$induk || $induk['status'] !== 'aktif' || !in_array($induk['role'], ['pusat', 'cabang'])) {
                    return ['valid' => false, 'message' => 'Akun induk dari Manager Mitra pembina tidak aktif atau tidak valid', 'voucher' => $voucher, 'pembina' => $pembina];
                }
            }
        }

        return ['valid' => true, 'message' => 'Voucher valid', 'voucher' => $voucher, 'pembina' => $pembina];
    }

    /**
     * Mengklaim voucher secara atomik dengan database conditional update
     * Mencegah race condition (double claim) pada request concurrent.
     */
    public function claimAtomically($voucherId, $userId)
    {
        $db = \Config\Database::connect();
        $db->query(
            "UPDATE {$this->table} SET status = 'terpakai', used_by = ?, used_at = ? WHERE id = ? AND status = 'tersedia'",
            [$userId, date('Y-m-d H:i:s'), (int)$voucherId]
        );

        return ($db->affectedRows() > 0);
    }

    /**
     * Mem-parsing kode voucher berformat [ROLE]-[JENIS]-[KODE]-[URUT]
     * Mendukung: CBG, MGR, MIT
     */
    public function parseKodeVoucher($kode)
    {
        $kode = strtoupper(trim((string)$kode));
        if (preg_match('/^(CBG|MGR|MIT)-(PS|CB|MG)-([0-9]{3})-([0-9]{4})$/', $kode, $matches)) {
            $roleMap = [
                'CBG' => ['role' => 'cabang', 'nama' => 'Cabang'],
                'MGR' => ['role' => 'manager_mitra', 'nama' => 'Manager Mitra'],
                'MIT' => ['role' => 'mitra', 'nama' => 'Mitra'],
            ];
            $jenisMap = [
                'PS' => ['nama' => 'Kantor Pusat', 'role' => 'pusat'],
                'CB' => ['nama' => 'Cabang', 'role' => 'cabang'],
                'MG' => ['nama' => 'Manager Mitra', 'role' => 'manager_mitra'],
            ];

            return [
                'is_structured' => true,
                'prefix_role' => $matches[1],
                'role_tujuan' => $roleMap[$matches[1]]['role'] ?? 'mitra',
                'role_tujuan_nama' => $roleMap[$matches[1]]['nama'] ?? $matches[1],
                'jenis_kode' => $matches[2],
                'jenis_nama' => $jenisMap[$matches[2]]['nama'] ?? $matches[2],
                'jenis_role' => $jenisMap[$matches[2]]['role'] ?? null,
                'kode_pembina' => $matches[3],
                'nomor_urut' => $matches[4],
                'nomor_urut_int' => (int)$matches[4],
            ];
        }

        return [
            'is_structured' => false,
            'kode' => $kode,
        ];
    }

    /**
     * Membuat voucher berformat terstruktur ([ROLE]-[JENIS]-[KODE]-[URUT]) secara aman dan atomik.
     * Menggunakan locking SELECT FOR UPDATE untuk mencegah duplikasi nomor urut saat konkurensi tinggi.
     */
    public function generateStructuredVouchers($roleTujuan, $pembinaId = null, $qty = 1, $expiredAt = null, $createdBy = null)
    {
        // Dukungan backwards compatibility jika dipanggil dengan format lama: ($pembinaId, $qty, $expiredAt, $createdBy)
        if (is_numeric($roleTujuan) && !is_string($pembinaId)) {
            $createdBy = $expiredAt;
            $expiredAt = $qty;
            $qty = (int)$pembinaId;
            $pembinaId = (int)$roleTujuan;
            $roleTujuan = 'mitra';
        }

        $validRoles = ['cabang', 'manager_mitra', 'mitra'];
        $roleTujuan = strtolower(trim((string)$roleTujuan));
        if (!in_array($roleTujuan, $validRoles, true)) {
            $roleTujuan = 'mitra';
        }

        $db = \Config\Database::connect();
        $userModel = new UserModel();

        $pembinaId = (int)$pembinaId;
        $qty = max(1, min(100, (int)$qty));

        // 1. Ambil data pembina
        $pembina = $db->table('users')
            ->where('id', $pembinaId)
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$pembina) {
            return ['status' => false, 'message' => 'Akun pembina tidak ditemukan atau sedang dinonaktifkan.'];
        }

        // Validasi pembina berdasarkan hierarki role tujuan
        if ($roleTujuan === 'cabang') {
            if ($pembina['role'] !== 'pusat') {
                return ['status' => false, 'message' => 'Penerbitan voucher Cabang wajib memilih akun Kantor Pusat sebagai pembina langsung.'];
            }
        } elseif ($roleTujuan === 'manager_mitra') {
            if (!in_array($pembina['role'], ['pusat', 'cabang'])) {
                return ['status' => false, 'message' => 'Penerbitan voucher Manager Mitra wajib memilih Kantor Pusat atau Cabang sebagai pembina langsung.'];
            }
        } elseif ($roleTujuan === 'mitra') {
            if (!in_array($pembina['role'], ['pusat', 'cabang', 'manager_mitra'])) {
                return ['status' => false, 'message' => 'Role pembina tidak valid untuk penerbitan voucher Mitra.'];
            }

            // Jika pembina adalah manager_mitra, pastikan relasi induknya sah
            if ($pembina['role'] === 'manager_mitra') {
                if (empty($pembina['pembina_id'])) {
                    return ['status' => false, 'message' => 'Manager Mitra tidak memiliki akun induk yang sah.'];
                }
                $induk = $db->table('users')
                    ->where('id', (int)$pembina['pembina_id'])
                    ->where('status', 'aktif')
                    ->where('deleted_at', null)
                    ->whereIn('role', ['pusat', 'cabang'])
                    ->get()
                    ->getRowArray();
                if (!$induk) {
                    return ['status' => false, 'message' => 'Akun induk Manager Mitra tidak aktif atau tidak valid.'];
                }
            }
        }

        // 2. Tentukan prefix role tujuan dan prefix jenis pembina
        $rolePrefix = UserModel::getPrefixRoleTujuan($roleTujuan);
        $jenisCode = UserModel::getPrefixJenisPembina($pembina['role']);
        $kodePembina = $userModel->getOrAssignKodePembina($pembinaId);

        // 3. Mulai transaksi database untuk mengunci dan menghitung nomor urut
        $db->transStart();

        try {
            // Pessimistic lock pada tabel vouchers untuk pembina dan role_tujuan ini
            $maxRow = $db->query(
                "SELECT MAX(nomor_urut) AS max_urut FROM vouchers WHERE pembina_id = ? AND role_tujuan = ? FOR UPDATE",
                [$pembinaId, $roleTujuan]
            )->getRowArray();

            $lastUrut = (int)($maxRow['max_urut'] ?? 0);

            // Fallback jika belum ada nomor_urut tetapi sudah ada voucher berformat [ROLE]-[JENIS]-[KODE]-XXXX
            if ($lastUrut === 0) {
                $prefixLike = "{$rolePrefix}-{$jenisCode}-{$kodePembina}-%";
                $lastVoucher = $db->query(
                    "SELECT kode FROM vouchers WHERE pembina_id = ? AND role_tujuan = ? AND kode LIKE ? ORDER BY id DESC LIMIT 1 FOR UPDATE",
                    [$pembinaId, $roleTujuan, $prefixLike]
                )->getRowArray();

                if ($lastVoucher && preg_match('/-(\d{4})$/', $lastVoucher['kode'], $m)) {
                    $lastUrut = (int)$m[1];
                }
            }

            $generatedCodes = [];
            $batchData = [];

            for ($i = 1; $i <= $qty; $i++) {
                $currentUrut = $lastUrut + $i;
                $strUrut = sprintf('%04d', $currentUrut);
                $kodeVoucher = "{$rolePrefix}-{$jenisCode}-{$kodePembina}-{$strUrut}";

                $batchData[] = [
                    'kode' => $kodeVoucher,
                    'role_tujuan' => $roleTujuan,
                    'pembina_id' => $pembinaId,
                    'nomor_urut' => $currentUrut,
                    'status' => 'tersedia',
                    'expired_at' => $expiredAt,
                    'created_by' => $createdBy ?? 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $generatedCodes[] = $kodeVoucher;
            }

            $db->table('vouchers')->insertBatch($batchData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return ['status' => false, 'message' => 'Gagal menyimpan voucher ke dalam database.'];
            }

            return [
                'status' => true,
                'count' => $qty,
                'pembina' => $pembina,
                'roleTujuan' => $roleTujuan,
                'rolePrefix' => $rolePrefix,
                'jenisCode' => $jenisCode,
                'kodePembina' => $kodePembina,
                'startUrut' => $lastUrut + 1,
                'endUrut' => $lastUrut + $qty,
                'generatedCodes' => $generatedCodes,
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            return ['status' => false, 'message' => 'Gagal membuat voucher: ' . $e->getMessage()];
        }
    }
}
