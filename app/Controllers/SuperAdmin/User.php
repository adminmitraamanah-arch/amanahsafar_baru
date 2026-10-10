<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $users = $this->userModel->findAll();
        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $users,
        ];

        return view('superadmin/users', $data);
    }
}
