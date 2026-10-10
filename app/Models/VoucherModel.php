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
        return $this->where('kode', strtoupper(trim($kode)))->first();
    }

    public function isValid($kode)
    {
        $voucher = $this->findByKode($kode);
        if (!$voucher) return false;
        if ($voucher['status'] !== 'tersedia') return false;
        if ($voucher['expired_at'] && strtotime($voucher['expired_at']) < time()) return false;
        return true;
    }

    public function markAsUsed($kode, $userId)
    {
        $voucher = $this->findByKode($kode);
        if (!$voucher) return false;

        return $this->update($voucher['id'], [
            'status' => 'terpakai',
            'used_by' => $userId,
            'used_at' => date('Y-m-d H:i:s')
        ]);
    }
}
