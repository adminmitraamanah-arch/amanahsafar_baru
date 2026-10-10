<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\VerificationModel;

class Verification extends BaseController
{
    protected $verificationModel;

    public function __construct()
    {
        $this->verificationModel = new VerificationModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $verifications = $this->verificationModel->where('status', 'menunggu_review')->findAll();
        $data = [
            'title' => 'Verifikasi Pengguna',
            'verifications' => $verifications,
        ];

        return view('superadmin/verifications', $data);
    }
}
