<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\VoucherModel;
use App\Models\VerificationModel;
use App\Libraries\PksPdfService;

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
        $isMaintenance = \App\Models\LandingSettingModel::getVal('portal_maintenance_active', '0') === '1';
        $rawPath = trim($this->request->getUri()->getPath(), '/');
        $isAdminAccess = isset($_GET['admin']) 
            || isset($_GET['superadmin']) 
            || $this->request->getGet('admin') !== null 
            || $this->request->getGet('superadmin') !== null
            || str_contains($rawPath, 'superadmin/login');

        // Jika mode maintenance aktif dan kunjungan biasa (bukan akses admin), alihkan ke maintenance
        if ($isMaintenance && !$isAdminAccess && !session('logged_in')) {
            if (!$this->request->is('post')) {
                return redirect()->to('/maintenance');
            }
        }

        if ($this->request->is('post')) {
            $email = trim($this->request->getPost('email') ?? '');
            $password = $this->request->getPost('password');

            $errorRedirect = function($msg) use ($isMaintenance, $isAdminAccess) {
                $target = ($isMaintenance && $isAdminAccess) ? base_url('/login?admin=1') : null;
                $res = $target ? redirect()->to($target) : redirect()->back();
                return $res->with('error', $msg)->withInput();
            };

            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                return $errorRedirect('akun belum terdaftar,silahkan daftar akun');
            }

            if (!password_verify($password, $user['password_hash'])) {
                return $errorRedirect('Email atau kata sandi tidak sesuai');
            }

            if ($user['status'] === 'nonaktif') {
                return $errorRedirect('Akun anda di nonaktifkan');
            }

            // Jika mode pemeliharaan portal aktif, tolak akses pengguna non-superadmin
            $isSuperAdmin = in_array($user['role'], ['super_admin', 'superadmin'], true);
            if ($isMaintenance && !$isSuperAdmin) {
                return redirect()->to('/maintenance')->with('error', 'Portal saat ini sedang dalam pemeliharaan sistem berkala. Akses login kemitraan dinonaktifkan sementara.');
            }

            // Login success - active users can login directly to dashboard
            session()->set([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama_lengkap'],
                'role' => $user['role'],
                'foto_profil' => $user['foto_profil'] ?? null,
                'logged_in' => true
            ]);

            $targetUrl = '/dashboard';
            if ($user['role'] === 'super_admin' || $user['role'] === 'superadmin') {
                $targetUrl = '/superadmin';
            } elseif ($user['role'] === 'admin') {
                $targetUrl = '/admin';
            } else {
                // Partner/member role: periksa kelengkapan onboarding
                $onboarding = $this->userModel->getOnboardingStatus($user['id']);
                if (!$onboarding['completed']) {
                    return redirect()->to($onboarding['redirect_url'])->with('warning', $onboarding['message']);
                }
            }

            return redirect()->to($targetUrl)->with('success', 'Berhasil masuk ke akun');
        }

        return view('auth/login', [
            'isMaintenance' => $isMaintenance,
            'isAdminAccess' => $isAdminAccess,
        ]);
    }

    public function register()
    {
        $isMaintenance = \App\Models\LandingSettingModel::getVal('portal_maintenance_active', '0') === '1';
        if ($isMaintenance) {
            return redirect()->to('/maintenance');
        }
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

                // Role dan hierarki binaan ditentukan langsung oleh voucher
                $targetRole = $locked['role_tujuan'] ?? $voucher['role_tujuan'] ?? 'mitra';
                $pembinaId = $locked['pembina_id'] ?? $voucher['pembina_id'] ?? null;

                $userId = $this->userModel->insert([
                    'nama_lengkap' => $nama,
                    'email' => $email,
                    'no_wa' => $no_wa,
                    'domisili' => $domisili,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                    'role' => $targetRole,
                    'pembina_id' => $pembinaId,
                    'status' => 'aktif',
                    'verification_status' => 'belum_verifikasi'
                ]);

                if (!$userId) {
                    $db->transRollback();
                    $dbErr = $db->error();
                    $errMsg = 'Gagal membuat akun pendaftar';
                    if (!empty($dbErr['message']) && str_contains($dbErr['message'], 'Duplicate entry')) {
                        if (str_contains($dbErr['message'], 'email')) {
                            $errMsg = 'Email tersebut sudah terdaftar pada sistem. Silakan gunakan email lain atau login ke akun Anda.';
                        } elseif (str_contains($dbErr['message'], 'no_wa')) {
                            $errMsg = 'Nomor WhatsApp tersebut sudah terdaftar pada sistem. Silakan gunakan nomor lain.';
                        }
                    }
                    return redirect()->back()->with('error', $errMsg)->withInput();
                }

                // Jika role baru adalah cabang atau manager_mitra, inisialisasi kode_pembina otomatis
                if (in_array($targetRole, ['cabang', 'manager_mitra'])) {
                    $this->userModel->getOrAssignKodePembina($userId);
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
            $nama_ktp = trim($this->request->getPost('nama_ktp') ?? '');
            $nik = trim($this->request->getPost('nik') ?? '');
            $nama_bank = trim($this->request->getPost('nama_bank') ?? '');
            if ($nama_bank === 'Lainnya' || empty($nama_bank)) {
                $manualBank = trim($this->request->getPost('nama_bank_manual') ?? '');
                if (!empty($manualBank)) {
                    $nama_bank = $manualBank;
                }
            }
            $nomor_rekening = trim($this->request->getPost('nomor_rekening') ?? '');
            $nama_pemilik = trim($this->request->getPost('nama_pemilik_rekening') ?? '');
            $agree = $this->request->getPost('agree_verification');

            // 0. Validasi Persetujuan Pernyataan
            if (!$agree) {
                return redirect()->back()->with('error', 'Anda wajib mencentang persetujuan pernyataan kebenaran data identitas dan rekening sebelum melanjutkan.')->withInput();
            }

            // Validasi Nama Sesuai KTP Wajib Diisi
            if (empty($nama_ktp)) {
                return redirect()->back()->with('error', 'Nama lengkap sesuai KTP wajib diisi.')->withInput();
            }

            // Validasi Nama Pemilik Rekening Wajib Diisi dan Harus Sesuai dengan Nama KTP
            if (empty($nama_pemilik)) {
                return redirect()->back()->with('error', 'Nama pemilik rekening wajib diisi.')->withInput();
            }

            $cleanNamaKtp = trim(preg_replace('/\s+/', ' ', mb_strtoupper($nama_ktp)));
            $cleanNamaPemilik = trim(preg_replace('/\s+/', ' ', mb_strtoupper($nama_pemilik)));

            if ($cleanNamaKtp !== $cleanNamaPemilik) {
                return redirect()->back()->with('error', 'Nama pemilik rekening harus sesuai dengan nama yang tertera pada KTP. Rekening wajib atas nama pribadi Anda.')->withInput();
            }

            // Validasi Nama Bank Wajib Diisi
            if (empty($nama_bank) || $nama_bank === 'Lainnya') {
                return redirect()->back()->with('error', 'Silakan pilih nama bank atau ketik nama bank Anda jika memilih opsi Lainnya.')->withInput();
            }

            // 1. Validasi NIK (Wajib 16 digit angka, tidak diawali angka 0, dan kode provinsi Indonesia valid)
            if (!preg_match('/^[1-9][0-9]{15}$/', $nik)) {
                return redirect()->back()->with('error', 'Nomor Induk Kependudukan (NIK) wajib 16 digit angka sesuai KTP resmi dan tidak boleh diawali angka 0.')->withInput();
            }

            $provCode = (int) substr($nik, 0, 2);
            $validProvCodes = [
                11, 12, 13, 14, 15, 16, 17, 18, 19, 21,
                31, 32, 33, 34, 35, 36,
                51, 52, 53,
                61, 62, 63, 64, 65,
                71, 72, 73, 74, 75, 76,
                81, 82,
                91, 92, 93, 94, 95, 96
            ];
            if (!in_array($provCode, $validProvCodes, true)) {
                return redirect()->back()->with('error', 'Format NIK tidak valid. Dua digit awal harus merupakan kode wilayah provinsi Indonesia yang sah.')->withInput();
            }

            // 2. Validasi NIK Unik di antara akun AKTIF (tidak boleh ganda)
            $db = \Config\Database::connect();
            $nikConflict = $db->table('verifications v')
                ->join('users u', 'u.id = v.user_id')
                ->where('v.nik', $nik)
                ->where('v.user_id !=', $user_id)
                ->where('u.deleted_at', null)
                ->countAllResults();

            if ($nikConflict > 0) {
                return redirect()->back()->with('error', 'Keamanan Sistem: NIK KTP ini sudah terdaftar pada akun mitra aktif lain. Satu NIK hanya berlaku untuk satu akun resmi.')->withInput();
            }

            // 3. Validasi Nomor Rekening Unik di antara akun AKTIF (tidak boleh ganda)
            $cleanRek = preg_replace('/[^0-9]/', '', $nomor_rekening);
            if (strlen($cleanRek) < 5) {
                return redirect()->back()->with('error', 'Nomor rekening bank tidak valid.')->withInput();
            }

            $rekConflict = $db->table('verifications v')
                ->join('users u', 'u.id = v.user_id')
                ->where('v.nama_bank', $nama_bank)
                ->where('v.nomor_rekening', $nomor_rekening)
                ->where('v.user_id !=', $user_id)
                ->where('u.deleted_at', null)
                ->countAllResults();

            if ($rekConflict > 0) {
                return redirect()->back()->with('error', 'Keamanan Sistem: Nomor rekening bank ini sudah terdaftar pada akun mitra aktif lain. Mohon gunakan rekening bank atas nama pribadi Anda.')->withInput();
            }

            // 4. Validasi dan Penanganan Dokumen KTP
            $existing = $this->verificationModel->where('user_id', $user_id)->first();
            $ktp = $this->request->getFile('ktp');
            $ktp_path = null;

            if (empty($existing['ktp_path']) && (!$ktp || !$ktp->isValid())) {
                return redirect()->back()->with('error', 'Dokumen identitas KTP wajib diunggah.')->withInput();
            }

            if ($ktp && $ktp->isValid() && !$ktp->hasMoved()) {
                // Validasi ukuran maksimal (5MB)
                if ($ktp->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->with('error', 'Ukuran foto KTP melebihi batas maksimal 5MB.')->withInput();
                }

                // Validasi ekstensi dan MIME type
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
                $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];
                $mimeType     = $ktp->getMimeType();
                $extension    = strtolower($ktp->getClientExtension() ?: $ktp->getExtension() ?: '');

                if (!in_array($mimeType, $allowedMimes, true) || !in_array($extension, $allowedExts, true)) {
                    return redirect()->back()->with('error', 'Format dokumen tidak didukung atau bukan format gambar resmi (JPG, PNG, WEBP).')->withInput();
                }

                // Validasi integritas dan resolusi gambar fisik
                $tempPath = $ktp->getTempName();
                $imageInfo = @getimagesize($tempPath);
                if ($imageInfo === false) {
                    return redirect()->back()->with('error', 'File yang diunggah rusak atau bukan file gambar yang valid.')->withInput();
                }

                $width = $imageInfo[0];
                $height = $imageInfo[1];
                if ($width < 400 || $height < 250) {
                    return redirect()->back()->with('error', 'Resolusi gambar KTP terlalu kecil atau tidak jelas. Mohon unggah foto KTP dengan resolusi lebih tinggi (minimal 400x250 piksel).')->withInput();
                }

                $ktp_path = $ktp->store('uploads/verifikasi');
            }
            $dataToSave = [
                'nik'                   => $nik,
                'nama_bank'             => $nama_bank,
                'nomor_rekening'        => $nomor_rekening,
                'nama_pemilik_rekening' => $nama_pemilik,
                'status'                => 'terverifikasi',
                'catatan'               => 'Verifikasi otomatis sistem: Data identitas dan rekening valid.'
            ];
            if ($ktp_path) {
                $dataToSave['ktp_path'] = $ktp_path;
            } elseif ($existing && !empty($existing['ktp_path'])) {
                $dataToSave['ktp_path'] = $existing['ktp_path'];
            }

            if ($existing) {
                $this->verificationModel->where('user_id', $user_id)->update(null, $dataToSave);
            } else {
                $dataToSave['user_id'] = $user_id;
                $this->verificationModel->insert($dataToSave);
            }

            // Sinkronkan status KYC dan nama resmi KTP pada tabel users agar konsisten di seluruh sistem
            $userUpdate = [
                'verification_status' => 'terverifikasi',
                'updated_at'          => date('Y-m-d H:i:s'),
            ];
            if (!empty($nama_ktp)) {
                $userUpdate['nama_lengkap'] = $nama_ktp;
                session()->set('nama', $nama_ktp);
            }
            $this->userModel->update($user_id, $userUpdate);

            return redirect()->to('/agreement')->with('success', 'Data identitas dan rekening berhasil diverifikasi. Silakan setujui Perjanjian Kerja Sama (PKS).');
        }

        $userId = session('user_id');
        $user = $this->userModel->find($userId);
        $verification = $this->verificationModel->where('user_id', $userId)->first();
        return view('auth/verify', [
            'user' => $user,
            'verification' => $verification
        ]);
    }

    public function agreement()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        // Mencegah bypass: Pengguna wajib menyelesaikan tahap 1 (verifikasi identitas) sebelum menyetujui PKS
        $onboarding = $this->userModel->getOnboardingStatus($userId);
        if (!$onboarding['completed'] && $onboarding['step'] === 'verify') {
            return redirect()->to('/verify')->with('warning', 'Silakan lengkapi data verifikasi identitas (KTP) dan rekening bank terlebih dahulu sebelum menyetujui PKS.');
        }

        if ($this->request->is('post')) {
            $user_id = session('user_id');

            $this->userModel->update($user_id, [
                'agreement_accepted_at' => date('Y-m-d H:i:s'),
                'status'                => 'aktif',
                'verification_status'   => 'terverifikasi'
            ]);

            // Pastikan baris verifikasi juga tersinkronisasi menjadi terverifikasi
            $this->verificationModel->where('user_id', $user_id)->set(['status' => 'terverifikasi'])->update();

            // Otomatisasi pembuatan dokumen fisik PDF PKS
            try {
                $pksPdfService = new PksPdfService();
                $pksPdfService->generate($user_id, true);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal generate PDF PKS setelah persetujuan: ' . $e->getMessage());
            }

            $user = $this->userModel->find($user_id);
            session()->set([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama_lengkap'],
                'role' => $user['role'],
                'foto_profil' => $user['foto_profil'] ?? null,
                'logged_in' => true
            ]);

            return redirect()->to('/dashboard')->with('success', 'Selamat! Perjanjian Kerja Sama telah disetujui. Akun kemitraan Anda telah aktif.');
        }

        $user = $this->userModel->find(session('user_id'));
        $verification = $this->verificationModel->where('user_id', $user['id'])->first();
        return view('auth/agreement', [
            'user'         => $user,
            'verification' => $verification,
        ]);
    }

    public function logout()
    {
        session()->remove(['user_id', 'email', 'nama', 'role', 'logged_in']);
        session()->regenerate(true);
        return redirect()->to('/login')->with('logout', 'Anda telah logout');
    }
}
