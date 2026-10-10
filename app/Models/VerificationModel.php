<?php

namespace App\Models;

use CodeIgniter\Model;

class VerificationModel extends Model
{
    protected $table = 'verifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'ktp_path',
        'swafoto_path',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
        'status',
        'reviewed_by',
        'catatan'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getByUser($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

    public function getPending()
    {
        return $this->where('status', 'menunggu_review')
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }
}
