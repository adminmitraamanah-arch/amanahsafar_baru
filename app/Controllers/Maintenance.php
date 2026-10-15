<?php

namespace App\Controllers;

use App\Models\LandingSettingModel;

class Maintenance extends BaseController
{
    public function index()
    {
        $settings = LandingSettingModel::getMap();

        $data = [
            'page_title' => 'Portal Dalam Pemeliharaan - Amanah Safar',
            'title' => $settings['portal_maintenance_title'] ?? 'Portal Sedang Dalam Pemeliharaan',
            'message' => $settings['portal_maintenance_message'] ?? 'Layanan pendaftaran baru dan dashboard kemitraan sedang ditingkatkan untuk kenyamanan Anda. Kami akan segera kembali beroperasi secara normal.',
            'estimated_end' => $settings['portal_maintenance_estimated_end'] ?? '',
            'whatsapp' => $settings['contact_whatsapp'] ?? '6281234567890',
            'whatsapp_cs_url' => $settings['whatsapp_cs_url'] ?? '',
        ];

        return view('maintenance', $data);
    }
}
