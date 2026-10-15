<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\LandingSettingModel;

class PortalMaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah status maintenance sedang aktif
        $isActive = LandingSettingModel::getVal('portal_maintenance_active', '0');
        if ($isActive !== '1') {
            return; // Mode maintenance tidak aktif, lewatkan semua request
        }

        // 2. Jika user saat ini sudah login sebagai Super Admin, berikan akses penuh
        $role = session('role');
        if (session('logged_in') && ($role === 'super_admin' || $role === 'superadmin')) {
            return;
        }

        // 3. Normalisasi path URI (kompatibel pada localhost subfolder XAMPP maupun domain hosting)
        $routeUri = function_exists('uri_string') ? trim(uri_string(), '/') : '';
        $rawPath = trim($request->getUri()->getPath(), '/');

        // Pengecualian halaman yang selalu boleh diakses:
        if ($routeUri === 'maintenance' || str_ends_with($rawPath, 'maintenance') ||
            $routeUri === 'logout' || str_ends_with($rawPath, 'logout')) {
            return;
        }

        // 4. Periksa apakah rute saat ini adalah halaman Login
        $isLoginRoute = ($routeUri === 'login') 
            || str_ends_with($rawPath, '/login') 
            || ($rawPath === 'login')
            || str_contains($rawPath, 'superadmin/login');

        // Parameter admin bypass via GET atau query string
        $isAdminParam = isset($_GET['admin']) 
            || isset($_GET['superadmin']) 
            || $request->getGet('admin') !== null 
            || $request->getGet('superadmin') !== null;

        if ($isLoginRoute) {
            // Jika POST login, izinkan lewat agar Auth::login memvalidasi kredensial
            $isPost = (method_exists($request, 'is') && $request->is('post')) 
                || ($request->getMethod() === 'POST')
                || (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST');

            if ($isPost) {
                return;
            }

            // Jika GET login dengan parameter admin bypass (?admin=1) atau rute superadmin/login, izinkan form tampil
            if ($isAdminParam || str_contains($rawPath, 'superadmin/login')) {
                return;
            }

            // Kunjungan biasa ke /login tanpa kunci admin diarahkan ke /maintenance
            return redirect()->to('/maintenance');
        }

        // 5. Untuk rute portal lainnya (register, dashboard member, app/*), alihkan ke maintenance
        if ($request->isAJAX()) {
            return service('response')
                ->setStatusCode(503)
                ->setJSON([
                    'status' => 'maintenance',
                    'message' => 'Portal kemitraan sedang dalam pemeliharaan berkala.',
                    'redirect' => base_url('/maintenance')
                ]);
        }

        return redirect()->to('/maintenance');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}
