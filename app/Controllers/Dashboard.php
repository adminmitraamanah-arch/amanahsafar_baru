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
            $redirect = redirect()->to('/superadmin');
            foreach (['success', 'logout', 'deleted', 'deactivated', 'error', 'errors', 'warning', 'info'] as $k) {
                if ($v = session()->getFlashdata($k)) $redirect = $redirect->with($k, $v);
            }
            return $redirect;
        }
        if ($role === 'admin') {
            $redirect = redirect()->to('/admin');
            foreach (['success', 'logout', 'deleted', 'deactivated', 'error', 'errors', 'warning', 'info'] as $k) {
                if ($v = session()->getFlashdata($k)) $redirect = $redirect->with($k, $v);
            }
            return $redirect;
        }

        $data = [
            'title' => 'Dashboard',
            'user' => $this->userModel->find(session('user_id'))
        ];

        return view('member/dashboard', $data);
    }
}
