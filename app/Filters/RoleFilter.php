<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session('user_id');
        if ($userId) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);

            if (!$user || $user['status'] === 'nonaktif') {
                session()->remove(['user_id', 'email', 'nama', 'role', 'logged_in']);
                session()->regenerate(true);
                return redirect()->to('/login')->with('error', 'Akun anda di nonaktifkan');
            }

            // Cegat akses jika onboarding (KYC/PKS) belum tuntas
            $onboarding = $userModel->getOnboardingStatus($userId);
            if (!$onboarding['completed']) {
                if ($request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(403)
                        ->setJSON([
                            'status' => 'error',
                            'message' => $onboarding['message'],
                            'redirect' => $onboarding['redirect_url']
                        ]);
                }
                return redirect()->to($onboarding['redirect_url'])->with('warning', $onboarding['message']);
            }
        }

        $userRole = session('role');
        
        if ($arguments) {
            $allowedRoles = is_array($arguments) ? $arguments : explode(',', $arguments);
            
            // Normalize role aliases
            if (in_array('super_admin', $allowedRoles) && !in_array('superadmin', $allowedRoles)) {
                $allowedRoles[] = 'superadmin';
            }
            if (in_array('superadmin', $allowedRoles) && !in_array('super_admin', $allowedRoles)) {
                $allowedRoles[] = 'super_admin';
            }
            if (in_array('admin', $allowedRoles) && !in_array('pusat', $allowedRoles)) {
                $allowedRoles[] = 'pusat';
            }
            if (in_array('pusat', $allowedRoles) && !in_array('admin', $allowedRoles)) {
                $allowedRoles[] = 'admin';
            }

            if (!in_array($userRole, $allowedRoles)) {
                return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
            }
        }
        
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not needed
    }
}
