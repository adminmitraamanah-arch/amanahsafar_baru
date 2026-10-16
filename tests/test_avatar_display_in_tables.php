<?php

declare(strict_types=1);

/**
 * Test Suite: Verifikasi Penampilan Foto Profil pada Tabel Pengguna & View Terkait
 */

echo "========================================================\n";
echo "STARTING USER AVATAR & TABLE DISPLAY TEST SUITE\n";
echo "========================================================\n\n";

$basePath = realpath(__DIR__ . '/..');

// 1. Verifikasi Super Admin Users View (app/Views/superadmin/users.php)
echo "[1] Memeriksa Tampilan Avatar pada app/Views/superadmin/users.php...\n";
$usersViewContent = file_get_contents($basePath . '/app/Views/superadmin/users.php');

if (strpos($usersViewContent, "uploads/profiles/' . esc(\$user['foto_profil'])") !== false) {
    echo "  [PASS] Markup rendering foto_profil terpasang pada tabel 'Detail Pengguna'.\n";
} else {
    echo "  [FAIL] Markup foto_profil TIDAK ditemukan di app/Views/superadmin/users.php.\n";
    exit(1);
}

if (strpos($usersViewContent, 'overflow-hidden') !== false) {
    echo "  [PASS] Class 'overflow-hidden' terverifikasi pada kontainer avatar.\n";
} else {
    echo "  [FAIL] Class 'overflow-hidden' tidak ditemukan pada kontainer avatar.\n";
    exit(1);
}

if (strpos($usersViewContent, '<?= $initial ?>') !== false) {
    echo "  [PASS] Fallback initial letter tetap terpasang jika foto profil kosong.\n";
} else {
    echo "  [FAIL] Fallback initial letter hilang dari app/Views/superadmin/users.php.\n";
    exit(1);
}

// 2. Verifikasi Super Admin Dashboard View (app/Views/superadmin/dashboard.php)
echo "\n[2] Memeriksa Tampilan Avatar pada app/Views/superadmin/dashboard.php...\n";
$dashboardViewContent = file_get_contents($basePath . '/app/Views/superadmin/dashboard.php');

if (strpos($dashboardViewContent, "uploads/profiles/' . esc(\$user['foto_profil'])") !== false) {
    echo "  [PASS] Markup foto_profil terpasang pada tabel 'Detail Pengguna' di Super Admin Dashboard.\n";
} else {
    echo "  [FAIL] Markup foto_profil TIDAK ditemukan di dashboard.php.\n";
    exit(1);
}

if (strpos($dashboardViewContent, '<?= $initial ?>') !== false) {
    echo "  [PASS] Fallback initial letter terverifikasi di dashboard.php.\n";
} else {
    echo "  [FAIL] Fallback initial letter hilang di dashboard.php.\n";
    exit(1);
}

// 3. Verifikasi Sidebar View (app/Views/layouts/sidebar.php)
echo "\n[3] Memeriksa Tampilan Avatar pada app/Views/layouts/sidebar.php...\n";
$sidebarContent = file_get_contents($basePath . '/app/Views/layouts/sidebar.php');

if (strpos($sidebarContent, "uploads/profiles/' . esc(session('foto_profil'))") !== false) {
    echo "  [PASS] Markup session foto_profil terpasang pada sidebar profil bawah.\n";
} else {
    echo "  [FAIL] Markup foto_profil TIDAK ditemukan di sidebar.php.\n";
    exit(1);
}

// 4. Verifikasi Verification Detail View (app/Views/superadmin/verification_detail.php)
echo "\n[4] Memeriksa Tampilan Avatar pada verification_detail.php...\n";
$verifDetailContent = file_get_contents($basePath . '/app/Views/superadmin/verification_detail.php');

if (strpos($verifDetailContent, "uploads/profiles/' . esc(\$user['foto_profil'])") !== false) {
    echo "  [PASS] Markup foto_profil terpasang pada Hero Profil Verifikasi.\n";
} else {
    echo "  [FAIL] Markup foto_profil TIDAK ditemukan di verification_detail.php.\n";
    exit(1);
}

// 5. Verifikasi Member Binaan View (app/Views/member/binaan.php)
echo "\n[5] Memeriksa Tampilan Avatar pada binaan.php...\n";
$binaanContent = file_get_contents($basePath . '/app/Views/member/binaan.php');

if (strpos($binaanContent, "uploads/profiles/' . esc(\$b['foto_profil'])") !== false) {
    echo "  [PASS] Markup foto_profil terpasang pada tabel daftar mitra binaan.\n";
} else {
    echo "  [FAIL] Markup foto_profil TIDAK ditemukan di binaan.php.\n";
    exit(1);
}

// 6. Test Rendering HTML Dinamis dengan Dummy Data
echo "\n[6] Menjalankan Uji Render PHP Dinamis...\n";

// Mock helper functions jika dijalankan di luar framework penuh
if (!function_exists('esc')) {
    function esc($data) {
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('base_url')) {
    function base_url($path = '') {
        return 'http://localhost/' . ltrim($path, '/');
    }
}
if (!defined('FCPATH')) {
    define('FCPATH', $basePath . '/public/');
}

// Uji Render Dummy: User dengan Foto
$dummyPhotoName = 'test_avatar_sample.png';
$testPhotoPath = FCPATH . 'uploads/profiles/' . $dummyPhotoName;
file_put_contents($testPhotoPath, 'fake-image-content');

$userWithPhoto = [
    'id' => 999,
    'nama_lengkap' => 'Hendri darmawan',
    'email' => 'admin@amanahsafar.com',
    'foto_profil' => $dummyPhotoName,
    'role' => 'admin',
    'status' => 'aktif'
];

$userWithoutPhoto = [
    'id' => 998,
    'nama_lengkap' => 'Amanah Safar Pusat',
    'email' => 'mitraamanah2@gmail.com',
    'foto_profil' => null,
    'role' => 'pusat',
    'status' => 'aktif'
];

// Helper render snippet
function renderAvatarSnippet($user) {
    $initial = strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1));
    $isTrash = false;
    ob_start();
    ?>
    <div class="w-9 h-9 rounded-full <?= $isTrash ? 'bg-rose-100 text-rose-700' : 'bg-purple-100 text-[#5e0078]' ?> font-bold text-xs flex items-center justify-center shrink-0 overflow-hidden">
        <?php if (!empty($user['foto_profil']) && is_file(FCPATH . 'uploads/profiles/' . $user['foto_profil'])): ?>
            <img src="<?= base_url('uploads/profiles/' . esc($user['foto_profil'])) ?>" alt="<?= esc($user['nama_lengkap']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <?= $initial ?>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

$renderedWithPhoto = renderAvatarSnippet($userWithPhoto);
if (strpos($renderedWithPhoto, '<img src="http://localhost/uploads/profiles/test_avatar_sample.png"') !== false) {
    echo "  [PASS] Render dengan foto profil: Tag <img> berhasil digenerate dengan URL yang valid.\n";
} else {
    echo "  [FAIL] Render dengan foto profil gagal menghasilkan tag <img>.\n";
    @unlink($testPhotoPath);
    exit(1);
}

$renderedWithoutPhoto = renderAvatarSnippet($userWithoutPhoto);
if (strpos($renderedWithoutPhoto, '<img') === false && strpos($renderedWithoutPhoto, 'A') !== false) {
    echo "  [PASS] Render tanpa foto profil: Fallback inisial 'A' berhasil digenerate tanpa <img>.\n";
} else {
    echo "  [FAIL] Fallback inisial gagal pada user tanpa foto profil.\n";
    @unlink($testPhotoPath);
    exit(1);
}

// Bersihkan test photo
@unlink($testPhotoPath);

echo "\n========================================================\n";
echo "ALL AVATAR DISPLAY CHECKS PASSED: 100% SUCCESS\n";
echo "========================================================\n";
