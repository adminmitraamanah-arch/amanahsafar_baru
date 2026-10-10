<?php

namespace App\Controllers\SuperAdmin;

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
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Super Admin Dashboard',
            'total_users' => $this->userModel->countAllResults(),
            'total_orders' => $this->orderModel->countAllResults(),
            'recent_users' => $this->userModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
        ];

        return view('superadmin/dashboard', $data);
    }
}
