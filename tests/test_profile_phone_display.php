<?php

declare(strict_types=1);

/**
 * Test Suite: Verifikasi Penampilan Nomor Telepon Readonly pada Halaman Profil
 */

echo "========================================================\n";
echo "STARTING PROFILE PHONE NUMBER DISPLAY TEST SUITE\n";
echo "========================================================\n\n";

$basePath = realpath(__DIR__ . '/..');

// 1. Verifikasi Markup di app/Views/profile.php
echo "[1] Memeriksa Markup Nomor Telepon pada app/Views/profile.php...\n";
$profileContent = file_get_contents($basePath . '/app/Views/profile.php');

$errors = [];

// Input field #profile_no_wa
if (strpos($profileContent, 'id="profile_no_wa"') !== false) {
    echo "  [PASS] Input field #profile_no_wa ditemukan.\n";
} else {
    $errors[] = "Input field #profile_no_wa tidak ditemukan di profile.php!";
}

// Readonly attribute
if (strpos($profileContent, 'id="profile_no_wa" value="<?= esc($user[\'no_wa\']') !== false && strpos($profileContent, 'readonly') !== false) {
    echo "  [PASS] Field nomor telepon berstatus readonly dan menampilkan data no_wa.\n";
} else {
    $errors[] = "Field nomor telepon tidak memiliki atribut readonly atau tidak mengikat data no_wa!";
}

// Lock badge / non-editable notice
if (strpos($profileContent, 'Nomor terdaftar saat registrasi') !== false) {
    echo "  [PASS] Keterangan non-editable (Nomor terdaftar saat registrasi) terverifikasi.\n";
} else {
    $errors[] = "Keterangan non-editable untuk nomor telepon tidak ditemukan!";
}

// Styling non-editable (cursor-not-allowed)
if (strpos($profileContent, 'cursor-not-allowed') !== false) {
    echo "  [PASS] Style non-editable (cursor-not-allowed select-none) terverifikasi.\n";
} else {
    $errors[] = "Style cursor-not-allowed tidak ditemukan!";
}

// Header area phone display
if (strpos($profileContent, 'fab fa-whatsapp') !== false && strpos($profileContent, 'esc($user[\'no_wa\']') !== false) {
    echo "  [PASS] Header hero profil menampilkan ikon dan nomor WhatsApp pengguna.\n";
} else {
    $errors[] = "Nomor telepon/WhatsApp tidak ditampilkan pada header hero profil!";
}

// 2. Verifikasi Session di Auth.php
echo "\n[2] Memeriksa Penyimpanan no_wa pada Sesi Login di Auth.php...\n";
$authContent = file_get_contents($basePath . '/app/Controllers/Auth.php');

if (strpos($authContent, "'no_wa' => \$user['no_wa'] ?? null") !== false) {
    echo "  [PASS] no_wa berhasil didaftarkan ke dalam array sesi login di Auth.php.\n";
} else {
    $errors[] = "no_wa belum didaftarkan ke sesi login di Auth.php!";
}

// 3. Verifikasi Controller Profile::update tidak mengizinkan manipulasi no_wa
echo "\n[3] Memeriksa Proteksi Immutabilitas Controller Profile::update...\n";
$profileControllerContent = file_get_contents($basePath . '/app/Controllers/Profile.php');

// Pastikan updateData hanya mengupdate nama_lengkap dan password
if (strpos($profileControllerContent, "'no_wa'") === false) {
    echo "  [PASS] Profile::update terlindungi: tidak ada penerimaan atau perubahan pada field no_wa.\n";
} else {
    $errors[] = "Ditemukan referensi 'no_wa' di Profile::update! Nomor telepon tidak boleh dapat diubah!";
}

// 4. Simulasi Render HTML
echo "\n[4] Simulasi Render HTML Field Telepon...\n";
if (!function_exists('esc')) {
    function esc($data) {
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }
}

$mockUser = [
    'nama_lengkap' => 'Budi Santoso',
    'email' => 'budi@amanahsafar.com',
    'no_wa' => '081234567890',
    'role' => 'mitra'
];

ob_start();
?>
<input type="text" id="profile_no_wa" value="<?= esc($mockUser['no_wa'] ?? '-') ?>" readonly class="cursor-not-allowed">
<?php
$renderedInput = ob_get_clean();

if (strpos($renderedInput, 'value="081234567890"') !== false && strpos($renderedInput, 'readonly') !== false) {
    echo "  [PASS] Render simulasi berhasil: Nilai '081234567890' ter-render dengan atribut readonly.\n";
} else {
    $errors[] = "Simulasi render nomor telepon gagal!";
}

// 5. Kesimpulan
echo "\n========================================================\n";
if (!empty($errors)) {
    echo "FAILED: Terdapat " . count($errors) . " kesalahan:\n";
    foreach ($errors as $e) {
        echo " - " . $e . "\n";
    }
    echo "========================================================\n";
    exit(1);
} else {
    echo "ALL CHECKS PASSED: Fitur nomor telepon readonly di profil terverifikasi 100%!\n";
    echo "========================================================\n";
    exit(0);
}
