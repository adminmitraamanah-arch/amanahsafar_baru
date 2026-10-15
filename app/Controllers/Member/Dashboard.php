<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\CommissionModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $orderModel;
    protected $commissionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->commissionModel = new CommissionModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session('user_id');
        $user = $this->userModel->find($userId);
        
        $potensiKomisi = $this->commissionModel->getPotensiKomisiByUser($userId);
        $saldoSiapCair = $this->commissionModel->getSaldoSiapCairByUser($userId);
        $binaanList = $this->userModel->getBinaan($userId);

        $data = [
            'title' => 'Member Dashboard',
            'user' => $user,
            'total_orders' => $this->orderModel->where('user_id', $userId)->countAllResults(),
            'potensi_komisi' => $potensiKomisi,
            'saldo_siap_cair' => $saldoSiapCair,
            'total_binaan' => count($binaanList ?? []),
            'recent_orders' => $this->orderModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->limit(5)->findAll(),
        ];

        return view('member/dashboard', $data);
    }
}
