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
     * Validasi mendalam voucher untuk pendaftaran Mitra:
     * - Status voucher harus 'tersedia'
     * - Role tujuan wajib 'mitra'
     * - Belum kedaluwarsa jika ada expired_at
     * - Akun pembina langsung wajib ada, aktif, dan valid (Pusat / Cabang / Manager Mitra)
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

        if ($voucher['role_tujuan'] !== 'mitra') {
            return ['valid' => false, 'message' => 'Voucher ini tidak diperuntukkan bagi pendaftaran Mitra', 'voucher' => $voucher, 'pembina' => null];
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

        if (!in_array($pembina['role'], ['pusat', 'cabang', 'manager_mitra'])) {
            return ['valid' => false, 'message' => 'Role pembina pada voucher tidak valid', 'voucher' => $voucher, 'pembina' => $pembina];
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
}
