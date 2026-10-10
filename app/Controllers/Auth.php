<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\VoucherModel;
use App\Models\VerificationModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $voucherModel;
    protected $verificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->voucherModel = new VoucherModel();
        $this->verificationModel = new VerificationModel();
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $role = $this->request->getPost('role');

            $user = $this->userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return redirect()->back()->with('error', 'Email, password, atau akses tidak sesuai');
            }

            if ($user['role'] !== $role) {
                return redirect()->back()->with('error', 'Email, password, atau akses tidak sesuai');
            }

            if ($user['status'] === 'nonaktif') {
                return redirect()->back()->with('error', 'Akun anda di nonaktifkan');
            }

            // Check onboarding
            if ($user['verification_status'] !== 'terverifikasi') {
                session()->set('user_id', $user['id']);
                session()->set('role', $user['role']);
                return redirect()->to('/verify');
            }

            if (!$user['agreement_accepted_at']) {
                session()->set('user_id', $user['id']);
                session()->set('role', $user['role']);
                return redirect()->to('/agreement');
            }

            // Login success
            session()->set([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama_lengkap'],
                'role' => $user['role'],
                'logged_in' => true
            ]);

            $targetUrl = '/dashboard';
            if ($user['role'] === 'super_admin' || $user['role'] === 'superadmin') {
                $targetUrl = '/superadmin';
            } elseif ($user['role'] === 'admin') {
                $targetUrl = '/admin';
            }

            return redirect()->to($targetUrl)->with('success', 'Berhasil masuk ke akun');
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->is('post')) {
            $nama = $this->request->getPost('nama_lengkap');
            $email = $this->request->getPost('email');
            $no_wa = $this->request->getPost('no_wa');
            $domisili = $this->request->getPost('domisili');
            $password = $this->request->getPost('password');
            $confirm_password = $this->request->getPost('confirm_password');
            $kode_voucher = strtoupper(trim($this->request->getPost('kode_voucher')));

            // Validasi
            if ($password !== $confirm_password) {
                return redirect()->back()->with('error', 'Password tidak cocok')->withInput();
            }

            if (strlen($password) < 8) {
                return redirect()->back()->with('error', 'Password minimal 8 karakter')->withInput();
            }

            if ($this->userModel->findByEmail($email)) {
                return redirect()->back()->with('error', 'Email sudah terdaftar')->withInput();
            }

            if ($this->userModel->findByNoWa($no_wa)) {
                return redirect()->back()->with('error', 'Nomor WhatsApp sudah terdaftar')->withInput();
            }

            // Validate voucher
            if (!$this->voucherModel->isValid($kode_voucher)) {
                return redirect()->back()->with('error', 'Kode voucher tidak valid atau sudah dipakai')->withInput();
            }

            $voucher = $this->voucherModel->findByKode($kode_voucher);

            // Create user
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                $userId = $this->userModel->insert([
                    'nama_lengkap' => $nama,
                    'email' => $email,
                    'no_wa' => $no_wa,
                    'domisili' => $domisili,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                    'role' => $voucher['role_tujuan'],
                    'pembina_id' => $voucher['pembina_id'],
                    'status' => 'aktif',
                    'verification_status' => 'belum_verifikasi'
                ]);

                // Mark voucher as used
                $this->voucherModel->markAsUsed($kode_voucher, $userId);

                // Create verification record
                $this->verificationModel->insert([
                    'user_id' => $userId,
                    'status' => 'belum_verifikasi'
                ]);

                $db->transComplete();

                session()->set([
                    'user_id' => $userId,
                    'role' => $voucher['role_tujuan'],
                    'email' => $email
                ]);

                return redirect()->to('/verify');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal mendaftar: ' . $e->getMessage());
            }
        }

        return view('auth/register');
    }

    public function verify()
    {
        if (!session('user_id')) {
            return redirect()->to('/login');
        }

        if ($this->request->is('post')) {
            $user_id = session('user_id');
            $nama_bank = $this->request->getPost('nama_bank');
            $nomor_rekening = $this->request->getPost('nomor_rekening');
            $nama_pemilik = $this->request->getPost('nama_pemilik_rekening');

            // Handle file uploads
            $ktp = $this->request->getFile('ktp');
            $swafoto = $this->request->getFile('swafoto');

            $ktp_path = null;
            $swafoto_path = null;

            if ($ktp && $ktp->isValid() && !$ktp->hasMoved()) {
                $ktp_path = $ktp->store('uploads/verifikasi');
            }

            if ($swafoto && $swafoto->isValid() && !$swafoto->hasMoved()) {
                $swafoto_path = $swafoto->store('uploads/verifikasi');
            }

            $this->verificationModel->where('user_id', $user_id)->update(null, [
                'ktp_path' => $ktp_path,
                'swafoto_path' => $swafoto_path,
                'nama_bank' => $nama_bank,
                'nomor_rekening' => $nomor_rekening,
                'nama_pemilik_rekening' => $nama_pemilik,
                'status' => 'menunggu_review'
            ]);

            return redirect()->to('/agreement');
        }

        $user = $this->userModel->find(session('user_id'));
        return view('auth/verify', ['user' => $user]);
    }

    public function agreement()
    {
        if (!session('user_id')) {
            return redirect()->to('/login');
        }

        if ($this->request->is('post')) {
            $user_id = session('user_id');

            $this->userModel->update($user_id, [
                'agreement_accepted_at' => date('Y-m-d H:i:s'),
                'status' => 'aktif'
            ]);

            return redirect()->to('/dashboard');
        }

        $user = $this->userModel->find(session('user_id'));
        return view('auth/agreement', ['user' => $user]);
    }

    public function logout()
    {
        session()->remove(['user_id', 'email', 'nama', 'role', 'logged_in']);
        session()->regenerate(true);
        return redirect()->to('/login')->with('logout', 'Anda telah logout');
    }
}
