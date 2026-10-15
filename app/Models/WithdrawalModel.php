<?php

namespace App\Models;

use CodeIgniter\Model;

class WithdrawalModel extends Model
{
    protected $table = 'withdrawals';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'no_penarikan',
        'user_id',
        'nominal',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
        'status',
        'bukti_transfer',
        'catatan_admin',
        'processed_by',
        'processed_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function generateWithdrawalNumber()
    {
        $prefix = 'WD-' . date('Ymd') . '-';
        $last = $this->select('no_penarikan')
            ->like('no_penarikan', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $seq = 1;
        if ($last) {
            $parts = explode('-', $last['no_penarikan']);
            $seq = (int)end($parts) + 1;
        }

        return $prefix . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }

    public function getByUser($userId)
    {
        return $this->where('user_id', (int)$userId)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function getPendingRequests()
    {
        $db = \Config\Database::connect();
        return $db->table('withdrawals w')
            ->select('w.*, u.nama_lengkap as nama_user, u.email as email_user, u.role as role_user, u.no_wa')
            ->join('users u', 'u.id = w.user_id')
            ->where('w.status', 'menunggu_transfer')
            ->orderBy('w.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
}
