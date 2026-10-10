<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class Order extends BaseController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session('user_id');
        $orders = $this->orderModel->where('user_id', $userId)->findAll();

        $data = [
            'title' => 'Pesanan Saya',
            'orders' => $orders,
        ];

        return view('member/orders', $data);
    }

    public function detail($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel->find($id);
        
        if (!$order || $order['user_id'] != session('user_id')) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pesanan',
            'order' => $order,
        ];

        return view('member/order_detail', $data);
    }
}
