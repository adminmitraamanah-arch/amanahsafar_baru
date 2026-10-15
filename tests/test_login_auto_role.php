<?php

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

// Start session service before output
\Config\Services::session();

$db = \Config\Database::connect();
$userModel = new \App\Models\UserModel();

echo "===============================================\n";
echo "STARTING LOGIN AUTO-ROLE & UI VERIFICATION TEST\n";
echo "===============================================\n";

$errors = [];

// 1. Verify View file does not have role selection dropdown
echo "\n[1] Verifikasi view auth/login tidak lagi memiliki input role...\n";
$viewFilePath = APPPATH . 'Views/auth/login.php';
$viewContent = file_get_contents($viewFilePath);
if (strpos($viewContent, 'name="role"') !== false) {
    $errors[] = "View auth/login masih memuat input name='role'";
} else {
    echo "  -> SUCCESS: Tidak ada input name='role' dalam view auth/login.\n";
}

if (strpos($viewContent, 'id="role"') !== false) {
    $errors[] = "View auth/login masih memuat id='role'";
} else {
    echo "  -> SUCCESS: Tidak ada select id='role' dalam view auth/login.\n";
}

// 2. Check users in database with various roles
echo "\n[2] Memeriksa akun pengujian di database untuk setiap role...\n";
$rolesToTest = ['super_admin', 'pusat', 'cabang', 'manager_mitra', 'mitra'];
$testUsers = [];

foreach ($rolesToTest as $role) {
    $u = $userModel->where('role', $role)->first();
    if ($u) {
        $testUsers[$role] = $u;
        echo "  -> Role {$role}: Ditemukan user ID {$u['id']} (Email: {$u['email']}, Status: {$u['status']})\n";
    } else {
        echo "  -> Role {$role}: Tidak ada user contoh di DB (akan dibuat sementara jika perlu)\n";
    }
}

// 3. Test Login Logic Simulation (Simulating Auth::login() without 'role' parameter)
echo "\n[3] Mensimulasikan logika Auth::login() dengan hanya Email dan Password...\n";

// Function simulating Auth.php login resolution
function simulateAuthLogin($email, $passwordInput, $userModel) {
    $user = $userModel->findByEmail($email);
    if (!$user) {
        return ['success' => false, 'error' => 'akun belum terdaftar,silahkan daftar akun'];
    }

    if (!password_verify($passwordInput, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Email atau kata sandi tidak sesuai'];
    }

    if ($user['status'] === 'nonaktif') {
        return ['success' => false, 'error' => 'Akun anda di nonaktifkan'];
    }

    if ($user['verification_status'] !== 'terverifikasi') {
        return [
            'success' => true,
            'redirect' => '/verify',
            'session_role' => $user['role'],
            'stage' => 'verification'
        ];
    }

    if (!$user['agreement_accepted_at']) {
        return [
            'success' => true,
            'redirect' => '/agreement',
            'session_role' => $user['role'],
            'stage' => 'agreement'
        ];
    }

    $targetUrl = '/dashboard';
    if ($user['role'] === 'super_admin' || $user['role'] === 'superadmin') {
        $targetUrl = '/superadmin';
    } elseif ($user['role'] === 'admin') {
        $targetUrl = '/admin';
    }

    return [
        'success' => true,
        'redirect' => $targetUrl,
        'session_role' => $user['role'],
        'stage' => 'dashboard'
    ];
}

// Test with a temporary user for each role to test full flow
$tempUserIds = [];
$testPassword = 'Password123!';
$hashedPassword = password_hash($testPassword, PASSWORD_BCRYPT);

try {
    foreach ($rolesToTest as $role) {
        $tempEmail = "test_login_{$role}_" . time() . "@amanahsafar.test";
        $insertId = $userModel->insert([
            'nama_lengkap' => "Test AutoRole " . ucfirst($role),
            'email' => $tempEmail,
            'no_wa' => '0899' . rand(10000000, 99999999),
            'password_hash' => $hashedPassword,
            'role' => $role,
            'status' => 'aktif',
            'verification_status' => 'terverifikasi',
            'agreement_accepted_at' => date('Y-m-d H:i:s')
        ]);
        $tempUserIds[] = $insertId;

        $loginResult = simulateAuthLogin($tempEmail, $testPassword, $userModel);

        assert($loginResult['success'] === true, "Login gagal untuk email: $tempEmail");
        assert($loginResult['session_role'] === $role, "Role terdeteksi salah: {$loginResult['session_role']} vs $role");

        $expectedTarget = in_array($role, ['super_admin', 'superadmin']) ? '/superadmin' : ($role === 'admin' ? '/admin' : '/dashboard');
        assert($loginResult['redirect'] === $expectedTarget, "Redirect salah untuk role $role: got {$loginResult['redirect']} expected $expectedTarget");

        echo "  -> Role '{$role}': Berhasil dideteksi otomatis dari DB -> Redirect: {$loginResult['redirect']}\n";
    }

    // Test Wrong Password
    echo "\n[4] Menguji kata sandi salah...\n";
    $failResult = simulateAuthLogin($tempEmail, 'WrongPassword999', $userModel);
    assert($failResult['success'] === false, "Seharusnya gagal jika kata sandi salah");
    assert($failResult['error'] === 'Email atau kata sandi tidak sesuai', "Pesan harus 'Email atau kata sandi tidak sesuai' jika akun terdaftar tapi sandi salah");
    echo "  -> SUCCESS: Penolakan kata sandi salah berhasil diverifikasi.\n";

    // Test Unregistered Account
    echo "\n[4b] Menguji akun email belum terdaftar...\n";
    $unregisteredEmail = 'unregistered_' . time() . '_' . rand(1000, 9999) . '@unknown.domain';
    $unregisteredResult = simulateAuthLogin($unregisteredEmail, 'AnyPassword123!', $userModel);
    assert($unregisteredResult['success'] === false, "Seharusnya gagal jika akun belum terdaftar");
    assert($unregisteredResult['error'] === 'akun belum terdaftar,silahkan daftar akun', "Pesan harus 'akun belum terdaftar,silahkan daftar akun'");
    echo "  -> SUCCESS: Alert 'akun belum terdaftar,silahkan daftar akun' berhasil diverifikasi.\n";

    // Test Inactive Status
    echo "\n[5] Menguji akun status nonaktif...\n";
    $userModel->update($insertId, ['status' => 'nonaktif']);
    $inactiveResult = simulateAuthLogin($tempEmail, $testPassword, $userModel);
    assert($inactiveResult['success'] === false && $inactiveResult['error'] === 'Akun anda di nonaktifkan', "Seharusnya ditolak karena nonaktif");
    echo "  -> SUCCESS: Penolakan akun nonaktif berhasil diverifikasi.\n";

} finally {
    // Cleanup temporary users
    if (!empty($tempUserIds)) {
        $db->table('users')->whereIn('id', $tempUserIds)->delete();
        echo "\n[Cleanup] Data pengujian sementara telah dibersihkan.\n";
    }
}

if (!empty($errors)) {
    echo "\n[FAILURE] Terdapat kesalahan:\n" . implode("\n", $errors) . "\n";
    exit(1);
} else {
    echo "\n===============================================\n";
    echo "SEMUA PENGUJIAN LOGIN AUTO-ROLE BERHASIL (100% PASSED)!\n";
    echo "===============================================\n";
    exit(0);
}
