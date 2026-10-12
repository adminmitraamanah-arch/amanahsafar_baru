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
            $email = trim($this->request->getPost('email') ?? '');
            $password = $this->request->getPost('password');
            $role = $this->request->getPost('role');

            $user = $this->userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return redirect()->back()->with('error', 'Email atau kata sandi tidak sesuai')->withInput();
            }

            if (!empty($role) && $user['role'] !== $role) {
                return redirect()->back()->with('warning', 'Role yang dipilih tidak sesuai dengan data login')->withInput();
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

            // Validate voucher with hierarchical & active rules
            $voucherCheck = $this->voucherModel->validateForRegistration($kode_voucher);
            if (!$voucherCheck['valid']) {
                return redirect()->back()->with('error', $voucherCheck['message'])->withInput();
            }

            $voucher = $voucherCheck['voucher'];

            // Create user
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Pessimistic lock for atomic claim check
                $locked = $db->query("SELECT * FROM vouchers WHERE id = ? FOR UPDATE", [$voucher['id']])->getRowArray();

                if (!$locked || $locked['status'] !== 'tersedia' || ($locked['expired_at'] && strtotime($locked['expired_at']) < time())) {
                    $db->transRollback();
                    return redirect()->back()->with('error', 'Kode voucher tidak valid atau sudah terpakai')->withInput();
                }

                // Role is strictly 'mitra' as mandated by voucher policy
                $targetRole = 'mitra';

                $userId = $this->userModel->insert([
                    'nama_lengkap' => $nama,
                    'email' => $email,
                    'no_wa' => $no_wa,
                    'domisili' => $domisili,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                    'role' => $targetRole,
                    'pembina_id' => $voucher['pembina_id'],
                    'status' => 'aktif',
                    'verification_status' => 'belum_verifikasi'
                ]);

                if (!$userId) {
                    $db->transRollback();
                    return redirect()->back()->with('error', 'Gagal membuat akun pendaftar')->withInput();
                }

                // Mark voucher as used atomically
                $claimed = $this->voucherModel->claimAtomically($voucher['id'], $userId);
                if (!$claimed) {
                    $db->transRollback();
                    return redirect()->back()->with('error', 'Kode voucher telah digunakan oleh pendaftar lain. Silakan gunakan voucher baru.')->withInput();
                }

                // Create verification record
                $this->verificationModel->insert([
                    'user_id' => $userId,
                    'status' => 'belum_verifikasi'
                ]);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    return redirect()->back()->with('error', 'Gagal memproses pendaftaran. Silakan coba lagi.')->withInput();
                }

                session()->set([
                    'user_id' => $userId,
                    'role' => $targetRole,
                    'email' => $email
                ]);

                return redirect()->to('/verify');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal mendaftar: ' . $e->getMessage())->withInput();
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

            $existing = $this->verificationModel->where('user_id', $user_id)->first();
            $dataToSave = [
                'nama_bank' => $nama_bank,
                'nomor_rekening' => $nomor_rekening,
                'nama_pemilik_rekening' => $nama_pemilik,
                'status' => 'menunggu_review'
            ];
            if ($ktp_path) {
                $dataToSave['ktp_path'] = $ktp_path;
            } elseif ($existing && !empty($existing['ktp_path'])) {
                $dataToSave['ktp_path'] = $existing['ktp_path'];
            }
            if ($swafoto_path) {
                $dataToSave['swafoto_path'] = $swafoto_path;
            } elseif ($existing && !empty($existing['swafoto_path'])) {
                $dataToSave['swafoto_path'] = $existing['swafoto_path'];
            }

            if ($existing) {
                $this->verificationModel->where('user_id', $user_id)->update(null, $dataToSave);
            } else {
                $dataToSave['user_id'] = $user_id;
                $this->verificationModel->insert($dataToSave);
            }

            return redirect()->to('/agreement')->with('success', 'Data verifikasi berhasil dikirim');
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

            $user = $this->userModel->find($user_id);
            session()->set([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama_lengkap'],
                'role' => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to('/dashboard')->with('success', 'Selamat! Perjanjian Kerja Sama telah disetujui. Akun kemitraan Anda telah aktif.');
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
