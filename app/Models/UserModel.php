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
}
