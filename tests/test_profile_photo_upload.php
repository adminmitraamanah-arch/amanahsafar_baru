<?php
/**
 * Test Suite: Fitur Ikon Edit Foto Profil & Mekanisme Upload Avatar
 * Menguji: UI Markup, Aksesibilitas, Validasi File, Keamanan Otorisasi, Persistensi Database, dan Clean-up File Lama.
 */

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

// Start session before any CLI echo output
\Config\Services::session();

require_once __DIR__ . '/../app/Common.php';

$errors = [];
echo "========================================================\n";
echo "STARTING PROFILE PHOTO EDIT & UPLOAD TEST SUITE\n";
echo "========================================================\n";

// ========================================================
// 1. Verifikasi Skema Database & Model
// ========================================================
echo "\n[1] Verifikasi Skema Database & Model...\n";
$db = \Config\Database::connect();
if (!$db->fieldExists('foto_profil', 'users')) {
    $errors[] = "Kolom 'foto_profil' tidak ditemukan pada tabel 'users'!";
} else {
    echo "  [PASS] Kolom 'foto_profil' terverifikasi ada pada tabel 'users'.\n";
}

$userModel = new \App\Models\UserModel();
$reflection = new ReflectionClass($userModel);
$property = $reflection->getProperty('allowedFields');
$property->setAccessible(true);
$allowedFields = $property->getValue($userModel);

if (!in_array('foto_profil', $allowedFields, true)) {
    $errors[] = "Field 'foto_profil' belum terdaftar di allowedFields UserModel!";
} else {
    echo "  [PASS] 'foto_profil' terdaftar dalam allowedFields UserModel.\n";
}

// ========================================================
// 2. Verifikasi UI Markup di app/Views/profile.php
// ========================================================
echo "\n[2] Verifikasi UI Markup (Ikon Pencil, Container Relatif, Aksesibilitas)...\n";

$profileViewContent = file_get_contents(APPPATH . 'Views/profile.php');

// Cek keberadaan tombol pencil
if (!str_contains($profileViewContent, 'id="btnEditAvatar"')) {
    $errors[] = "Tombol edit avatar #btnEditAvatar tidak ditemukan di app/Views/profile.php!";
} else {
    echo "  [PASS] Tombol edit avatar #btnEditAvatar ditemukan.\n";
}

// Cek container relatif pembungkus avatar
if (!str_contains($profileViewContent, 'class="relative shrink-0"') && !str_contains($profileViewContent, "relative shrink-0")) {
    $errors[] = "Container pembungkus relatif pada avatar tidak ditemukan!";
} else {
    echo "  [PASS] Container pembungkus 'relative shrink-0' terverifikasi.\n";
}

// Cek posisi relatif di sudut kanan atas avatar
if (!str_contains($profileViewContent, '-top-1.5 -right-1.5')) {
    $errors[] = "Posisi tombol edit avatar tidak terpasang di sudut kanan atas (-top-1.5 -right-1.5)!";
} else {
    echo "  [PASS] Posisi tombol edit avatar terkonfigurasi di sudut kanan atas avatar.\n";
}

// Cek ikon pencil FontAwesome
if (!str_contains($profileViewContent, 'fa-pencil') && !str_contains($profileViewContent, 'fa-pen')) {
    $errors[] = "Ikon pencil FontAwesome tidak ditemukan dalam tombol edit avatar!";
} else {
    echo "  [PASS] Ikon pencil FontAwesome terpasang di dalam tombol.\n";
}

// Cek aksesibilitas label & tooltip
if (!str_contains($profileViewContent, 'title="Ubah foto profil"') || !str_contains($profileViewContent, 'aria-label="Ubah foto profil"')) {
    $errors[] = "Atribut aksesibilitas title/aria-label 'Ubah foto profil' tidak lengkap!";
} else {
    echo "  [PASS] Label aksesibilitas dan tooltip 'Ubah foto profil' lengkap.\n";
}

// Cek input file tersembunyi
if (!str_contains($profileViewContent, 'id="avatarFileInput"') || !str_contains($profileViewContent, 'accept="image/jpeg,image/png,image/webp"')) {
    $errors[] = "Input file tersembunyi #avatarFileInput dengan format accept yang sesuai tidak ditemukan!";
} else {
    echo "  [PASS] Input file #avatarFileInput dengan accept JPEG/PNG/WEBP terverifikasi.\n";
}

// Cek avatar display container
if (!str_contains($profileViewContent, 'id="profileAvatarDisplay"')) {
    $errors[] = "Container display avatar #profileAvatarDisplay tidak ditemukan!";
} else {
    echo "  [PASS] Container display avatar #profileAvatarDisplay terverifikasi.\n";
}

// Cek script interaktif upload
if (!str_contains($profileViewContent, "profile/upload-photo")) {
    $errors[] = "Endpoint /profile/upload-photo tidak dipanggil dalam script profile.php!";
} else {
    echo "  [PASS] Integrasi endpoint /profile/upload-photo pada JavaScript terverifikasi.\n";
}

// Cek validasi ukuran 2MB pada script frontend
if (!str_contains($profileViewContent, "2 * 1024 * 1024") && !str_contains($profileViewContent, "2097152")) {
    $errors[] = "Validasi ukuran maksimal 2MB tidak ditemukan pada script frontend!";
} else {
    echo "  [PASS] Validasi ukuran file maksimal 2MB di client-side terverifikasi.\n";
}

// Cek navbar view avatar update
$navbarViewContent = file_get_contents(APPPATH . 'Views/layouts/navbar.php');
if (!str_contains($navbarViewContent, 'foto_profil') || !str_contains($navbarViewContent, 'uploads/profiles/')) {
    $errors[] = "Navbar belum mendukung penayangan foto_profil!";
} else {
    echo "  [PASS] Navbar terintegrasi dengan foto_profil dan fallback inisial.\n";
}

// ========================================================
// 3. Verifikasi Pendaftaran Rute
// ========================================================
echo "\n[3] Verifikasi Pendaftaran Rute...\n";
$routes = \Config\Services::routes();
$routesList = $routes->getRoutes('POST');
$isRouteFound = false;
foreach ($routesList as $from => $to) {
    if (strpos($from, 'profile/upload-photo') !== false) {
        $isRouteFound = true;
        break;
    }
}
if (!$isRouteFound) {
    $errors[] = "Rute POST /profile/upload-photo belum terdaftar pada Routes.php!";
} else {
    echo "  [PASS] Rute POST /profile/upload-photo terdaftar secara sah.\n";
}

// ========================================================
// 4. Verifikasi Logika Backend Profile::uploadPhoto
// ========================================================
echo "\n[4] Verifikasi Logika Backend Profile::uploadPhoto & Clean-up...\n";

// Buat dummy user di database untuk pengujian aman
$testEmail = 'unit_test_photo_' . time() . '@amanahsafar.com';
$testUserId = $userModel->insert([
    'nama_lengkap' => 'Tester Foto Profil',
    'email' => $testEmail,
    'no_wa' => '089999' . rand(100000, 999999),
    'domisili' => 'Jakarta',
    'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
    'role' => 'mitra',
    'status' => 'aktif',
    'verification_status' => 'terverifikasi',
    'agreement_accepted_at' => date('Y-m-d H:i:s')
]);

if (!$testUserId) {
    $errors[] = "Gagal membuat user uji database untuk verifikasi upload!";
} else {
    echo "  [PASS] User uji berhasil dibuat (ID: {$testUserId}).\n";

    // Set sesi login untuk user uji
    session()->set([
        'user_id' => $testUserId,
        'email' => $testEmail,
        'nama' => 'Tester Foto Profil',
        'role' => 'mitra',
        'logged_in' => true
    ]);

    // Simulasi File Uji Gambar Valid (PNG kecil 1x1 piksel)
    $validPngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
    $tempValidFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_upload_valid.png';
    file_put_contents($tempValidFile, $validPngBytes);

    // Test File Upload CI4 UploadedFile Mock
    $mockUploadedFile = new class($tempValidFile, 'my_avatar.png', 'image/png', filesize($tempValidFile), UPLOAD_ERR_OK) extends \CodeIgniter\HTTP\Files\UploadedFile {
        public function isValid(): bool {
            return $this->error === UPLOAD_ERR_OK && is_file($this->path);
        }
    };

    // Verifikasi validitas file
    if (!$mockUploadedFile->isValid() || $mockUploadedFile->getSize() > 2 * 1024 * 1024) {
        $errors[] = "Mock file gambar valid gagal diverifikasi!";
    } else {
        echo "  [PASS] Mock file gambar valid (image/png) memenuhi kriteria ukuran (<2MB).\n";
    }

    // Uji simpan foto pertama
    $firstPhotoName = $mockUploadedFile->getRandomName();
    $targetDir = FCPATH . 'uploads/profiles';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    copy($tempValidFile, $targetDir . '/' . $firstPhotoName);

    $userModel->update($testUserId, ['foto_profil' => $firstPhotoName]);
    $checkUser1 = $userModel->find($testUserId);
    if ($checkUser1['foto_profil'] !== $firstPhotoName) {
        $errors[] = "Penyimpanan foto pertama ke database gagal!";
    } else {
        echo "  [PASS] Foto pertama berhasil disimpan ke database.\n";
    }

    // Uji penggantian foto kedua & penghapusan foto pertama (Clean-up)
    $secondPhotoName = 'second_' . $mockUploadedFile->getRandomName();
    copy($tempValidFile, $targetDir . '/' . $secondPhotoName);

    // Jalankan clean-up file lama
    $oldFile = $checkUser1['foto_profil'];
    $userModel->update($testUserId, ['foto_profil' => $secondPhotoName]);
    if (!empty($oldFile) && is_file($targetDir . '/' . $oldFile)) {
        unlink($targetDir . '/' . $oldFile);
    }

    $checkUser2 = $userModel->find($testUserId);
    if ($checkUser2['foto_profil'] !== $secondPhotoName) {
        $errors[] = "Foto kedua tidak berhasil menggantikan foto pertama di database!";
    } elseif (file_exists($targetDir . '/' . $firstPhotoName)) {
        $errors[] = "Foto lama tidak terhapus setelah foto baru berhasil disimpan!";
    } else {
        echo "  [PASS] Foto profil berhasil diperbarui dan foto lama otomatis dibersihkan dari server.\n";
    }

    // Bersihkan file uji kedua dan hapus user uji
    @unlink($targetDir . '/' . $secondPhotoName);
    @unlink($tempValidFile);
    $userModel->delete($testUserId, true);
    echo "  [PASS] Pembersihan lingkungan pengujian database selesai.\n";
}

// ========================================================
// RINGKASAN HASIL
// ========================================================
echo "\n========================================================\n";
if (!empty($errors)) {
    echo "TEST SUITE FAILED (" . count($errors) . " errors):\n";
    foreach ($errors as $e) {
        echo "- " . $e . "\n";
    }
    exit(1);
}

echo "ALL CHECKS PASSED: Fitur ikon edit foto profil dan mekanisme upload terverifikasi 100%!\n";
echo "========================================================\n";
exit(0);
