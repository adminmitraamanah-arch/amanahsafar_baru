<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\CommissionModel;

class Commission extends BaseController
{
    protected $commissionModel;

    public function __construct()
    {
        $this->commissionModel = new CommissionModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $commissions = $this->commissionModel->findAll();
        $data = [
            'title' => 'Kelola Komisi',
            'commissions' => $commissions,
        ];

        return view('superadmin/commissions', $data);
    }
}
