<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OrderModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $orderModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session('user_id');
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'Member Dashboard',
            'user' => $user,
            'total_orders' => $this->orderModel->where('user_id', $userId)->countAllResults(),
            'recent_orders' => $this->orderModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->limit(5)->findAll(),
        ];

        return view('member/dashboard', $data);
    }
}
