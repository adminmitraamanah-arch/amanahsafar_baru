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
            if ($s = session()->getFlashdata('success')) $redirect = $redirect->with('success', $s);
            if ($e = session()->getFlashdata('error')) $redirect = $redirect->with('error', $e);
            return $redirect;
        }
        if ($role === 'admin') {
            $redirect = redirect()->to('/admin');
            if ($s = session()->getFlashdata('success')) $redirect = $redirect->with('success', $s);
            if ($e = session()->getFlashdata('error')) $redirect = $redirect->with('error', $e);
            return $redirect;
        }

        $data = [
            'title' => 'Dashboard',
            'user' => $this->userModel->find(session('user_id'))
        ];

        return view('member/dashboard', $data);
    }
}
