<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ActiveUserFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session('logged_in')) {
            $userId = session('user_id');
            if ($userId) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);

                // If user doesn't exist or is deactivated, terminate session immediately
                if (!$user || $user['status'] === 'nonaktif') {
                    session()->remove(['user_id', 'email', 'nama', 'role', 'logged_in']);
                    session()->regenerate(true);

                    if ($request->isAJAX()) {
                        return service('response')
                            ->setStatusCode(403)
                            ->setJSON([
                                'status' => 'error',
                                'message' => 'Akun anda di nonaktifkan'
                            ]);
                    }

                    return redirect()->to('/login')->with('error', 'Akun anda di nonaktifkan');
                }
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not needed
    }
}
