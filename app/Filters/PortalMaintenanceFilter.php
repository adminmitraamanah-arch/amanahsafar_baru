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

        // 3. Ambil path URL saat ini
        $uri = service('uri');
        $path = trim($uri->getPath(), '/');

        // Pengecualian halaman yang selalu boleh diakses:
        if ($path === 'maintenance' || $path === 'logout') {
            return;
        }

        // 4. Periksa akses khusus halaman Login
        if ($path === 'login') {
            // Jika POST login, izinkan lewat agar Auth::login memvalidasi apakah kredensial Super Admin
            if ($request->getMethod() === 'POST' || (method_exists($request, 'is') && $request->is('post'))) {
                return;
            }

            // Jika GET login dengan parameter ?admin=1 atau ?superadmin=1, izinkan form login tampil
            $getParams = $request->getGet();
            if (isset($getParams['admin']) || isset($getParams['superadmin'])) {
                return;
            }

            // Kunjungan biasa ke /login diarahkan ke /maintenance
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
