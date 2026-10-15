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

        // Partner/member role: cegat jika onboarding belum tuntas
        $userId = session('user_id');
        $onboarding = $this->userModel->getOnboardingStatus($userId);
        if (!$onboarding['completed']) {
            return redirect()->to($onboarding['redirect_url'])->with('warning', $onboarding['message']);
        }

        $data = [
            'title' => 'Dashboard',
            'user' => $this->userModel->find($userId)
        ];

        return view('member/dashboard', $data);
    }
}
