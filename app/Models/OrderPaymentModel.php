<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderPaymentModel extends Model
{
    protected $table = 'order_payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id',
        'no_transaksi',
        'tipe_pembayaran',
        'nominal',
        'bukti_transfer',
        'nama_bank_pengirim',
        'nomor_rekening_pengirim',
        'nama_pengirim',
        'catatan',
        'status',
        'catatan_admin',
        'verified_by',
        'verified_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function generateTransactionNumber($orderId)
    {
        $prefix = 'PAY-' . date('Ymd') . '-' . str_pad((string)$orderId, 4, '0', STR_PAD_LEFT) . '-';
        $count = $this->where('order_id', (int)$orderId)->countAllResults();
        return $prefix . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);
    }

    public function getPaymentsByOrder($orderId)
    {
        return $this->where('order_id', (int)$orderId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getPendingPayments()
    {
        $db = \Config\Database::connect();
        return $db->table('order_payments op')
            ->select('op.*, o.no_order, o.user_id, o.total as order_total, u.nama_lengkap as nama_mitra, u.email as email_mitra, u.role as role_mitra')
            ->join('orders o', 'o.id = op.order_id')
            ->join('users u', 'u.id = o.user_id')
            ->where('op.status', 'menunggu_verifikasi')
            ->orderBy('op.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
}
