<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'no_order',
        'user_id',
        'status',
        'total',
        'tipe_pembayaran_pilihan',
        'nominal_dp_minimal',
        'total_dibayar',
        'sisa_tagihan',
        'status_pembayaran',
        'bukti_transfer',
        'catatan'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Ymd') . '-';
        $lastOrder = $this->select('no_order')
            ->like('no_order', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $seq = 1;
        if ($lastOrder) {
            $parts = explode('-', $lastOrder['no_order']);
            $seq = (int)end($parts) + 1;
        }

        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        if (!$order) return null;

        $db = \Config\Database::connect();
        $items = $db->table('order_items')
            ->select('order_items.*, packages.nama as paket_nama')
            ->join('packages', 'packages.id = order_items.package_id')
            ->where('order_items.order_id', $orderId)
            ->get()
            ->getResultArray();

        $order['items'] = $items;

        $payments = $db->table('order_payments')
            ->where('order_id', $orderId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
        $order['payments'] = $payments;

        return $order;
    }
}
