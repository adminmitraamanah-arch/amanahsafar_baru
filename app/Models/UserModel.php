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
            // Cabang melihat: Manager binaan Cabang dan mitra binaan manager Cabang
            return $this->where('pembina_id', $userId)
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
}

