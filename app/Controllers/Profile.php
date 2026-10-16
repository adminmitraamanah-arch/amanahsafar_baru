<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\PksPdfService;

class Profile extends BaseController
{
    protected $userModel;
    protected $pksPdfService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->pksPdfService = new PksPdfService();
    }

    /**
     * Tampilkan halaman Profil Saya
     */
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Pengguna tidak ditemukan');
        }

        $userRole = (string) ($user['role'] ?? session('role') ?? '');
        $isAdminRole = in_array($userRole, ['admin', 'super_admin', 'superadmin'], true);

        $showPks = !$isAdminRole;
        $pksInfo = null;

        if ($showPks) {
            $isAccepted = !empty($user['agreement_accepted_at']);
            $pksInfo = [
                'is_accepted' => $isAccepted,
                'accepted_at' => $user['agreement_accepted_at'] ?? null,
                'pks_number'  => $isAccepted ? $this->pksPdfService->getPksNumber($user) : null,
                'has_pdf'     => $isAccepted && $this->pksPdfService->hasPdf($userId),
                'download_url'=> base_url('profile/pks/download'),
            ];
        }

        $data = [
            'title'   => 'Profil Saya',
            'user'    => $user,
            'showPks' => $showPks,
            'pksInfo' => $pksInfo,
        ];

        return view('profile', $data);
    }

    /**
     * Proses pembaruan data profil dan password akun login
     */
    public function update()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        // Keamanan: Hanya gunakan ID dari sesi login saat ini (anti manipulasi request)
        $userId = session('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Pengguna tidak ditemukan');
        }

        $nama = trim((string) ($this->request->getPost('nama_lengkap') ?? ''));
        $currentPassword = (string) ($this->request->getPost('current_password') ?? '');
        $newPassword = (string) ($this->request->getPost('new_password') ?? '');
        $confirmPassword = (string) ($this->request->getPost('confirm_password') ?? '');

        // 1. Validasi Nama Profil
        if (empty($nama)) {
            return redirect()->back()->with('error', 'Nama profil tidak boleh kosong')->withInput();
        }

        if (mb_strlen($nama) < 3) {
            return redirect()->back()->with('error', 'Nama profil minimal 3 karakter')->withInput();
        }

        if (mb_strlen($nama) > 100) {
            return redirect()->back()->with('error', 'Nama profil maksimal 100 karakter')->withInput();
        }

        $updateData = [
            'nama_lengkap' => $nama
        ];

        // 2. Validasi & Pembaruan Password
        $hasPasswordInput = !empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword);

        if ($hasPasswordInput) {
            if (empty($currentPassword)) {
                return redirect()->back()->with('error', 'Password lama wajib diisi untuk mengubah password')->withInput();
            }

            // Verifikasi password lama sesuai hash di database
            if (!password_verify($currentPassword, $user['password_hash'])) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai')->withInput();
            }

            if (empty($newPassword)) {
                return redirect()->back()->with('error', 'Password baru tidak boleh kosong')->withInput();
            }

            if (strlen($newPassword) < 8) {
                return redirect()->back()->with('error', 'Password baru minimal 8 karakter')->withInput();
            }

            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok')->withInput();
            }

            if ($currentPassword === $newPassword) {
                return redirect()->back()->with('error', 'Password baru harus berbeda dengan password lama')->withInput();
            }

            // Hashing aman menggunakan BCRYPT
            $updateData['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        // 3. Simpan perubahan ke database
        $this->userModel->update($userId, $updateData);

        // 4. Perbarui nama pada session aktif agar langsung berubah di dropdown dan header
        session()->set('nama', $nama);

        $successMessage = $hasPasswordInput 
            ? 'Profil dan password berhasil diperbarui' 
            : 'Perubahan data profil tersimpan';

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Unduh berkas fisik PDF PKS resmi milik pengguna yang sedang login
     */
    public function downloadPks()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        // Keamanan Server-Side Otorisasi: Hanya user_id dari sesi login aktif (anti-tampering)
        $userId = (int) session('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Akun administratif (admin, super_admin, superadmin) tidak memiliki PKS kemitraan
        if (in_array((string) $user['role'], ['admin', 'super_admin', 'superadmin'], true)) {
            return redirect()->back()->with('error', 'Akun administratif tidak memiliki dokumen Perjanjian Kerja Sama (PKS).');
        }

        // Validasi: Pengguna wajib telah menyetujui PKS
        if (empty($user['agreement_accepted_at'])) {
            return redirect()->to('/agreement')->with('warning', 'Anda belum menyetujui Perjanjian Kerja Sama (PKS). Silakan baca dan setujui PKS terlebih dahulu.');
        }

        // Cek ketersediaan file fisik. Jika belum ada (misal generate sebelumnya belum selesai / file terhapus), lakukan auto-recovery
        if (!$this->pksPdfService->hasPdf($userId)) {
            $generated = $this->pksPdfService->generate($userId, true);
            if (!$generated || !is_file($generated)) {
                return redirect()->back()->with('error', 'Gagal memproses berkas PDF PKS. Silakan hubungi admin atau coba beberapa saat lagi.');
            }
        }

        $pdfPath = $this->pksPdfService->getPdfPath($userId);
        if (!is_file($pdfPath)) {
            return redirect()->back()->with('error', 'Berkas PDF PKS tidak ditemukan.');
        }

        $cleanUserName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $user['nama_lengkap'] ?? 'Mitra');
        $downloadFileName = 'PKS_Amanah_Safar_' . $cleanUserName . '_' . $userId . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $downloadFileName . '"')
            ->setHeader('Content-Length', (string) filesize($pdfPath))
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->setHeader('Pragma', 'public')
            ->setBody(file_get_contents($pdfPath));
    }
}

