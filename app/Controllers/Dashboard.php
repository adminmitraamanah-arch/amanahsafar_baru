<?php

namespace App\Controllers;

use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session('role');
        if ($role === 'super_admin' || $role === 'superadmin') {
            return redirect()->to('/superadmin');
        }
        if ($role === 'admin') {
            return redirect()->to('/admin');
        }

        $data = [
            'title' => 'Dashboard',
            'user' => $this->userModel->find(session('user_id'))
        ];

        return view('member/dashboard', $data);
    }
}
