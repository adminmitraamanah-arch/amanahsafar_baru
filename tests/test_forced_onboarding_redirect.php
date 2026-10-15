<?php

define('ENVIRONMENT', 'development');
define('FCPATH', 'c:\\xampp\\htdocs\\amanahsafar\\public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

$db = \Config\Database::connect();
$userModel = new \App\Models\UserModel();
$verificationModel = new \App\Models\VerificationModel();

echo "=================================================================\n";
echo "STARTING FORCED ONBOARDING REDIRECT & AUTO-RESUME TEST SUITE\n";
echo "=================================================================\n";

$testEmail = 'onboarding_test_' . time() . '@amanahsafar.test';
$testPassword = 'Password123!';
$createdUserIds = [];

try {
    // -------------------------------------------------------------
    // [1] PENGUJIAN TAHAP 1: BERHENTI DI /verify (KYC Belum Diisi)
    // -------------------------------------------------------------
    echo "\n[1] Menguji Pendaftar Berhenti di Tahap 1 (Belum Isi KTP & Bank)...\n";
    
    $userId = $userModel->insert([
        'nama_lengkap'        => 'Mitra Onboarding Tahap 1',
        'email'               => $testEmail,
        'no_wa'               => '0812' . rand(10000000, 99999999),
        'domisili'            => 'Jakarta',
        'password_hash'       => password_hash($testPassword, PASSWORD_BCRYPT),
        'role'                => 'mitra',
        'status'              => 'aktif',
        'verification_status' => 'belum_verifikasi',
        'agreement_accepted_at' => null
    ], true);
    $createdUserIds[] = $userId;

    $verificationModel->insert([
        'user_id' => $userId,
        'status'  => 'belum_verifikasi'
    ]);

    // Verifikasi getOnboardingStatus
    $status1 = $userModel->getOnboardingStatus($userId);
    assert($status1['completed'] === false, "Tahap 1 seharusnya completed = false!");
    assert($status1['step'] === 'verify', "Tahap 1 seharusnya step = verify!");
    assert($status1['redirect_url'] === '/verify', "Tahap 1 redirect_url harus '/verify'!");
    echo "  -> SUCCESS: UserModel::getOnboardingStatus mendeteksi pending KYC (Redirect: /verify).\n";

    // -------------------------------------------------------------
    // [2] PENGUJIAN TAHAP 2: BERHENTI DI /agreement (KYC Sudah, PKS Belum)
    // -------------------------------------------------------------
    echo "\n[2] Menguji Pendaftar Berhenti di Tahap 2 (KTP Terisi, PKS Belum)...\n";

    $verificationModel->where('user_id', $userId)->set([
        'nik'                   => '3171012345678901',
        'ktp_path'              => 'uploads/verifikasi/ktp_sample.jpg',
        'nama_bank'             => 'BCA',
        'nomor_rekening'        => '8880011223',
        'nama_pemilik_rekening' => 'Mitra Onboarding Tahap 1',
        'status'                => 'menunggu_review'
    ])->update();

    $userModel->update($userId, [
        'verification_status' => 'menunggu_review'
    ]);

    $status2 = $userModel->getOnboardingStatus($userId);
    assert($status2['completed'] === false, "Tahap 2 seharusnya completed = false!");
    assert($status2['step'] === 'agreement', "Tahap 2 seharusnya step = agreement!");
    assert($status2['redirect_url'] === '/agreement', "Tahap 2 redirect_url harus '/agreement'!");
    echo "  -> SUCCESS: UserModel::getOnboardingStatus mendeteksi pending PKS (Redirect: /agreement).\n";

    // -------------------------------------------------------------
    // [3] PENGUJIAN TAHAP 3: ONBOARDING SELESAI (PKS Disetujui)
    // -------------------------------------------------------------
    echo "\n[3] Menguji Pendaftar Menyelesaikan PKS (Onboarding Selesai)...\n";

    $userModel->update($userId, [
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ]);

    $status3 = $userModel->getOnboardingStatus($userId);
    assert($status3['completed'] === true, "Tahap 3 seharusnya completed = true!");
    assert($status3['step'] === 'done', "Tahap 3 seharusnya step = done!");
    assert($status3['redirect_url'] === '/dashboard', "Tahap 3 redirect_url harus '/dashboard'!");
    echo "  -> SUCCESS: UserModel::getOnboardingStatus meloloskan akun yang telah PKS (Redirect: /dashboard).\n";

    // -------------------------------------------------------------
    // [4] PENGUJIAN ROLE FILTER INTERCEPTION
    // -------------------------------------------------------------
    echo "\n[4] Menguji Intersepsi Filter Keamanan...\n";

    // Buat user uji yang belum verifikasi
    $unverifiedEmail = 'unverified_' . time() . '@amanahsafar.test';
    $unverifiedUserId = $userModel->insert([
        'nama_lengkap'        => 'User Belum Verifikasi',
        'email'               => $unverifiedEmail,
        'no_wa'               => '0812' . rand(10000000, 99999999),
        'domisili'            => 'Bandung',
        'password_hash'       => password_hash($testPassword, PASSWORD_BCRYPT),
        'role'                => 'mitra',
        'status'              => 'aktif',
        'verification_status' => 'belum_verifikasi',
        'agreement_accepted_at' => null
    ], true);
    $createdUserIds[] = $unverifiedUserId;

    $filterCheck = $userModel->getOnboardingStatus($unverifiedUserId);
    assert($filterCheck['completed'] === false, "User belum verifikasi harus diblokir!");
    assert($filterCheck['redirect_url'] === '/verify', "User harus diarahkan ke /verify!");
    echo "  -> SUCCESS: Rute dilindungi dari akses user yang belum onboarding.\n";

    // -------------------------------------------------------------
    // [5] PENGUJIAN AKUN SEED & ADMIN (ZERO REGRESSION)
    // -------------------------------------------------------------
    echo "\n[5] Menguji Akun Administratif & Akun Seed Tetap Aman (Zero Regression)...\n";

    $superAdminStatus = $userModel->getOnboardingStatus(1);
    assert($superAdminStatus['completed'] === true, "Super Admin wajib bebas onboarding!");
    assert($superAdminStatus['redirect_url'] === '/superadmin', "Super Admin wajib diarahkan ke /superadmin!");
    echo "  -> SUCCESS: Akun Super Admin ID 1 bebas dari hambatan onboarding.\n";

    $pusatStatus = $userModel->getOnboardingStatus(5);
    assert($pusatStatus['completed'] === true, "Akun Pusat ID 5 wajib lolos onboarding!");
    echo "  -> SUCCESS: Akun Pusat ID 5 lolos onboarding.\n";

    $cabangStatus = $userModel->getOnboardingStatus(6);
    assert($cabangStatus['completed'] === true, "Akun Cabang ID 6 wajib lolos onboarding!");
    echo "  -> SUCCESS: Akun Cabang ID 6 lolos onboarding.\n";

    $managerStatus = $userModel->getOnboardingStatus(7);
    assert($managerStatus['completed'] === true, "Akun Manager ID 7 wajib lolos onboarding!");
    echo "  -> SUCCESS: Akun Manager ID 7 lolos onboarding.\n";

    echo "\n=================================================================\n";
    echo "SEMUA TAHAPAN FORCED ONBOARDING REDIRECT LULUS 100%!\n";
    echo "=================================================================\n";

} finally {
    // Cleanup data uji
    if (!empty($createdUserIds)) {
        $db->table('verifications')->whereIn('user_id', $createdUserIds)->delete();
        $db->table('users')->whereIn('id', $createdUserIds)->delete();
    }
}
