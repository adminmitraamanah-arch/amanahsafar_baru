<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Binaan extends BaseController
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

        $userId = session('user_id');
        $role = session('role');
        $binaan = $this->userModel->getBindaan($userId, $role);

        $data = [
            'title' => 'Daftar Binaan',
            'binaan' => $binaan,
        ];

        return view('member/binaan', $data);
    }
}
