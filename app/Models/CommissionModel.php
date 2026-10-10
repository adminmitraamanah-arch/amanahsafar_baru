<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table = 'commissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id',
        'user_id',
        'nominal_snapshot',
        'status',
        'paid_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getByUser($userId)
    {
        $db = \Config\Database::connect();
        return $db->table('commissions')
            ->select('commissions.*, orders.no_order, orders.created_at as order_date')
            ->join('orders', 'orders.id = commissions.order_id')
            ->where('commissions.user_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function getTotalByUser($userId, $status = null)
    {
        $builder = $this->where('user_id', $userId);
        if ($status) {
            $builder->where('status', $status);
        }
        return $builder->selectSum('nominal_snapshot')->first();
    }
}
