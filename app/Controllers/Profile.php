<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

        $data = [
            'title' => 'Profil Saya',
            'user' => $user
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
}
