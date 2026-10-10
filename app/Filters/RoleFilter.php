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

        $userRole = session('role');
        
        if ($arguments) {
            $allowedRoles = is_array($arguments) ? $arguments : explode(',', $arguments);
            
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
