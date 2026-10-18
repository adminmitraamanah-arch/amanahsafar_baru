<?php

namespace App\Controllers;

use App\Libraries\PksPdfService;
use App\Libraries\RegistrationWhatsApp;
use App\Models\LandingSettingModel;
use App\Models\UserModel;

class RegistrationConfirmation extends BaseController
{
    public function index()
    {
        if (!session('logged_in') || !session('user_id')) {
            return redirect()->to('/login');
        }
        $user = (new UserModel())->find(session('user_id'));
        if (!$user || $user['status'] !== 'aktif') {
            return redirect()->to('/login');
        }
        if (in_array($user['role'], ['admin', 'super_admin', 'superadmin'], true)) {
            return redirect()->to('/dashboard');
        }
        if (empty($user['agreement_accepted_at'])) {
            return redirect()->to('/agreement');
        }

        $error = null;
        $clickedAt = null;
        try {
            // Read the current consultation number on every GET and POST.
            $url = RegistrationWhatsApp::buildUrl(
                (string) LandingSettingModel::getVal('contact_whatsapp', ''),
                $user,
                (new PksPdfService())->getPksNumber($user)
            );
            $db = \Config\Database::connect();
            if ($this->request->is('post')) {
                $saved = $db->table('audit_logs')->insert([
                    'user_id' => $user['id'],
                    'aksi' => 'registration_whatsapp_clicked',
                    'entitas' => 'users',
                    'entitas_id' => $user['id'],
                    // The schema requires this field; no IP or message content is needed.
                    'ip_address' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                if (!$saved) {
                    throw new \RuntimeException('Audit insert failed');
                }
                return redirect()->to($url, 303)->withCookies();
            }
            $lastClick = $db->table('audit_logs')
                ->where('user_id', $user['id'])
                ->where('aksi', 'registration_whatsapp_clicked')
                ->where('entitas', 'users')
                ->where('entitas_id', $user['id'])
                ->orderBy('id', 'DESC')->get(1)->getRowArray();
            $clickedAt = $lastClick['created_at'] ?? null;
        } catch (\InvalidArgumentException $e) {
            $error = $e->getMessage();
        } catch (\Throwable $e) {
            $error = 'Konfirmasi belum dapat dicatat. Silakan muat ulang halaman dan coba kembali.';
        }

        $this->response->setHeader('Cache-Control', 'no-store');
        return view('auth/registration_confirmation', [
            'title' => 'Konfirmasi Pendaftaran',
            'user' => $user,
            'clickedAt' => $clickedAt,
            'confirmationError' => $error,
        ]);
    }
}
