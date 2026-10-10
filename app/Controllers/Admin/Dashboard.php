<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data = ['title' => 'Admin Dashboard'];
        return view('admin/dashboard', $data);
    }
}
