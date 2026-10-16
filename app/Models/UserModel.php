<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'nama_lengkap',
        'email',
        'no_wa',
        'domisili',
        'foto_profil',
        'password_hash',
        'role',
        'pembina_id',
        'kode_pembina',
        'status',
        'verification_status',
        'agreement_accepted_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByNoWa($no_wa)
    {
        return $this->where('no_wa', $no_wa)->first();
    }

    public function getBindaan($userId, $role)
    {
        if ($role === 'pusat') {
            // Pusat melihat: Manager binaan Pusat, Cabang, mitra binaan manager Pusat, dan mitra binaan manager Cabang
            return $this->whereIn('role', ['cabang', 'manager_mitra', 'mitra'])
                ->where('status !=', 'nonaktif')
                ->findAll();
        } elseif ($role === 'cabang') {
            // Cabang melihat: Direct binaan (Manager & Mitra) + Mitra binaan di bawah Manager Cabang
            $db = \Config\Database::connect();
            $managerRows = $db->table('users')
                ->select('id')
                ->where('pembina_id', $userId)
                ->where('role', 'manager_mitra')
                ->where('deleted_at', null)
                ->get()
                ->getResultArray();

            $allowedPembinaIds = [(int)$userId];
            foreach ($managerRows as $m) {
                $allowedPembinaIds[] = (int)$m['id'];
            }

            return $this->whereIn('pembina_id', $allowedPembinaIds)
                ->whereIn('role', ['manager_mitra', 'mitra'])
                ->where('status !=', 'nonaktif')
                ->findAll();
        } elseif ($role === 'manager_mitra') {
            // Manager melihat: Mitra binaan sendiri
            return $this->where('pembina_id', $userId)
                ->where('role', 'mitra')
                ->where('status !=', 'nonaktif')
                ->findAll();
        }
        return [];
    }

    public function getHierarchyIds($userId, $role)
    {
        // Return array ID user yang ada dalam hierarki (untuk filter data)
        $ids = [$userId];
        $binaan = $this->getBindaan($userId, $role);
        foreach ($binaan as $b) {
            $ids[] = $b['id'];
        }
        return $ids;
    }

    /**
     * Mengambil atau menginisialisasi kode identitas unik pembina (3 digit) secara permanen.
     * Kode ini disimpan di kolom kode_pembina sehingga kebal terhadap perubahan nama akun.
     */
    public function getOrAssignKodePembina($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        if (!empty($user['kode_pembina'])) {
            return $user['kode_pembina'];
        }

        // Tentukan kode baru permanen
        $kodeBaru = '001';
        if ($user['role'] === 'pusat') {
            $kodeBaru = '001';
        } else {
            // Hitung nomor urut tertinggi untuk role ini
            $db = \Config\Database::connect();
            $maxRow = $db->query(
                "SELECT MAX(CAST(kode_pembina AS UNSIGNED)) AS max_val FROM users WHERE role = ? AND kode_pembina IS NOT NULL",
                [$user['role']]
            )->getRowArray();

            $maxVal = (int)($maxRow['max_val'] ?? 0);
            $kodeBaru = sprintf('%03d', $maxVal + 1);
        }

        $this->update($userId, ['kode_pembina' => $kodeBaru]);
        return $kodeBaru;
    }

    /**
     * Mapping prefix jenis pembina sesuai format:
     * - Pusat => PS
     * - Cabang => CB
     * - Manager Mitra => MG
     */
    public static function getPrefixJenisPembina($role)
    {
        return match ($role) {
            'pusat' => 'PS',
            'cabang' => 'CB',
            'manager_mitra' => 'MG',
            default => 'XX',
        };
    }

    /**
     * Mapping prefix role tujuan voucher:
     * - Cabang => CBG
     * - Manager Mitra => MGR
     * - Mitra => MIT
     */
    public static function getPrefixRoleTujuan($role)
    {
        return match ($role) {
            'cabang' => 'CBG',
            'manager_mitra' => 'MGR',
            'mitra' => 'MIT',
            default => 'MIT',
        };
    }

    /**
     * Label human-readable untuk role tujuan
     */
    public static function getRoleTujuanLabel($role)
    {
        return match ($role) {
            'cabang' => 'Cabang',
            'manager_mitra' => 'Manager Mitra',
            'mitra' => 'Mitra',
            default => ucfirst(str_replace('_', ' ', (string)$role)),
        };
    }

    /**
     * Memeriksa apakah pengguna telah melengkapi verifikasi berkas & PKS
     * sebagai syarat wajib (gatekeeper) pemesanan paket umrah.
     */
    public function canOrderPackages($userId): array
    {
        $user = $this->find($userId);
        if (!$user) {
            return ['allowed' => false, 'message' => 'Pengguna tidak ditemukan.'];
        }

        if ($user['status'] !== 'aktif') {
            return ['allowed' => false, 'message' => 'Akun Anda sedang dinonaktifkan.'];
        }

        // Cek PKS
        if (empty($user['agreement_accepted_at'])) {
            return [
                'allowed' => false,
                'message' => 'Anda belum menyetujui Perjanjian Kerja Sama (PKS). Silakan setujui PKS terlebih dahulu untuk dapat memesan paket.',
                'need_agreement' => true
            ];
        }

        // Cek Dokumen Verifikasi (KTP & Info Rekening Bank)
        $db = \Config\Database::connect();
        $verification = $db->table('verifications')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$verification || empty($verification['ktp_path']) || empty($verification['nama_bank']) || empty($verification['nomor_rekening'])) {
            return [
                'allowed' => false,
                'message' => 'Data identitas (KTP) dan rekening bank Anda belum lengkap. Silakan lengkapi data verifikasi terlebih dahulu.',
                'need_verification' => true
            ];
        }

        return ['allowed' => true, 'message' => 'Memenuhi syarat pemesanan.'];
    }

    /**
     * Memeriksa status onboarding (KYC & PKS) pengguna untuk Forced Onboarding Redirect.
     *
     * @param int $userId
     * @return array
     */
    public function getOnboardingStatus($userId): array
    {
        $user = $this->find($userId);
        if (!$user) {
            return [
                'completed'    => false,
                'step'         => 'login',
                'redirect_url' => '/login',
                'message'      => 'Pengguna tidak ditemukan.'
            ];
        }

        // 1. Role administratif (super_admin, superadmin, admin) bebas dari onboarding kemitraan
        if (in_array($user['role'], ['super_admin', 'superadmin', 'admin'])) {
            return [
                'completed'    => true,
                'step'         => 'done',
                'redirect_url' => ($user['role'] === 'admin') ? '/admin' : '/superadmin',
                'message'      => 'Akun administratif.'
            ];
        }

        // 2. Akun lama/seed yang sudah terverifikasi dan sudah PKS
        if ($user['verification_status'] === 'terverifikasi' && !empty($user['agreement_accepted_at'])) {
            return [
                'completed'    => true,
                'step'         => 'done',
                'redirect_url' => '/dashboard',
                'message'      => 'Onboarding telah lengkap.'
            ];
        }

        // 3. Cek data verifikasi KYC (KTP, NIK, dan Rekening Bank)
        $db = \Config\Database::connect();
        $verification = $db->table('verifications')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $hasKycData = $verification 
            && !empty($verification['ktp_path']) 
            && !empty($verification['nama_bank']) 
            && !empty($verification['nomor_rekening']);

        if (!$hasKycData && $user['verification_status'] === 'belum_verifikasi') {
            return [
                'completed'    => false,
                'step'         => 'verify',
                'redirect_url' => '/verify',
                'message'      => 'Silakan lengkapi data verifikasi identitas (KTP) dan rekening bank Anda terlebih dahulu.'
            ];
        }

        // 4. Cek Perjanjian Kerja Sama (PKS)
        if (empty($user['agreement_accepted_at'])) {
            return [
                'completed'    => false,
                'step'         => 'agreement',
                'redirect_url' => '/agreement',
                'message'      => 'Silakan setujui Perjanjian Kerja Sama (PKS) kemitraan Anda untuk mengaktifkan akun.'
            ];
        }

        return [
            'completed'    => true,
            'step'         => 'done',
            'redirect_url' => '/dashboard',
            'message'      => 'Onboarding telah lengkap.'
        ];
    }
}

