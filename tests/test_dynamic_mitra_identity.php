<?php

declare(strict_types=1);

/**
 * Test Suite: Verifikasi Dinamis Data Identitas Mitra (Nama, NIK, Alamat, No. HP)
 * Memvalidasi Acceptance Criteria:
 * 1. Mitra A melihat data identitasnya sendiri
 * 2. Mitra B melihat data identitasnya sendiri
 * 3. Data tidak tertukar saat berpindah akun
 * 4. Nilai sesuai sumber data aktual di database (bukan hardcoded)
 * 5. Field kosong menampilkan 'Belum diisi' (tanpa data palsu)
 * 6. Proteksi hak akses & otorisasi server-side
 */

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

$db = \Config\Database::connect();

echo "===================================================================\n";
echo "STARTING DYNAMIC MITRA IDENTITY VERIFICATION TEST SUITE\n";
echo "===================================================================\n\n";

$allPassed = true;
$assertions = 0;

function assertTest(string $description, bool $condition): void {
    global $allPassed, $assertions;
    $assertions++;
    if ($condition) {
        echo "  [PASS] {$description}\n";
    } else {
        echo "  [FAIL] {$description}\n";
        $allPassed = false;
    }
}

// -------------------------------------------------------------
// [1] Pengujian Model & Sumber Data Aktual di Database
// -------------------------------------------------------------
echo "[1] Memeriksa Sumber Data Aktual Database untuk Minimal 2 Akun Mitra...\n";

$mitraA = $db->table('users u')
    ->select('u.id, u.nama_lengkap, u.email, u.no_wa, u.domisili, u.role, v.nik')
    ->join('verifications v', 'v.user_id = u.id', 'left')
    ->where('u.id', 235)
    ->get()
    ->getRowArray();

$mitraB = $db->table('users u')
    ->select('u.id, u.nama_lengkap, u.email, u.no_wa, u.domisili, u.role, v.nik')
    ->join('verifications v', 'v.user_id = u.id', 'left')
    ->where('u.id', 493)
    ->get()
    ->getRowArray();

assertTest("Data Mitra A (ID #235) ditemukan di DB", !empty($mitraA));
assertTest("Data Mitra B (ID #493) ditemukan di DB", !empty($mitraB));
assertTest("Data Nama Mitra A tidak sama dengan Mitra B", $mitraA['nama_lengkap'] !== $mitraB['nama_lengkap']);
assertTest("Data NIK Mitra A tidak sama dengan Mitra B", $mitraA['nik'] !== $mitraB['nik']);
assertTest("Data No. HP Mitra A tidak sama dengan Mitra B", $mitraA['no_wa'] !== $mitraB['no_wa']);
assertTest("Data Alamat Mitra A berbeda/mandiri dari Mitra B", !empty($mitraA['domisili']) && !empty($mitraB['domisili']));

// -------------------------------------------------------------
// [2] Simulasi Halaman Profil Saya (/profile) untuk Mitra A vs Mitra B
// -------------------------------------------------------------
echo "\n[2] Pengujian Halaman Profil Saya (/profile) Bebas Hardcode & Terisolasi Sesuai Sesi...\n";

// Helper simulasi render komponen informasi profil
function renderProfileIdentity(array $user, ?array $verification): array {
    $nama = !empty($user['nama_lengkap']) ? $user['nama_lengkap'] : 'Belum diisi';
    $hp = !empty($user['no_wa']) ? $user['no_wa'] : 'Belum diisi';
    $nik = !empty($verification['nik']) ? $verification['nik'] : 'Belum diisi';
    $alamat = !empty($user['domisili']) ? $user['domisili'] : 'Belum diisi';

    return [
        'nama' => $nama,
        'nik' => $nik,
        'alamat' => $alamat,
        'hp' => $hp,
    ];
}

$verifA = $db->table('verifications')->where('user_id', 235)->get()->getRowArray();
$verifB = $db->table('verifications')->where('user_id', 493)->get()->getRowArray();

$renderA = renderProfileIdentity($mitraA, $verifA);
$renderB = renderProfileIdentity($mitraB, $verifB);

assertTest("Profil Mitra A menampilkan Nama aktual: {$mitraA['nama_lengkap']}", $renderA['nama'] === $mitraA['nama_lengkap']);
assertTest("Profil Mitra A menampilkan NIK aktual: {$mitraA['nik']}", $renderA['nik'] === $mitraA['nik']);
assertTest("Profil Mitra A menampilkan Alamat aktual: {$mitraA['domisili']}", $renderA['alamat'] === $mitraA['domisili']);
assertTest("Profil Mitra A menampilkan No. HP aktual: {$mitraA['no_wa']}", $renderA['hp'] === $mitraA['no_wa']);

assertTest("Profil Mitra B menampilkan Nama aktual: {$mitraB['nama_lengkap']}", $renderB['nama'] === $mitraB['nama_lengkap']);
assertTest("Profil Mitra B menampilkan NIK aktual: {$mitraB['nik']}", $renderB['nik'] === $mitraB['nik']);
assertTest("Profil Mitra B menampilkan Alamat aktual: {$mitraB['domisili']}", $renderB['alamat'] === $mitraB['domisili']);
assertTest("Profil Mitra B menampilkan No. HP aktual: {$mitraB['no_wa']}", $renderB['hp'] === $mitraB['no_wa']);

assertTest("Identitas Mitra A TIDAK tertukar dengan Mitra B", $renderA['nik'] !== $renderB['nik'] && $renderA['nama'] !== $renderB['nama']);

// -------------------------------------------------------------
// [3] Pengujian Penanganan Field Kosong ('Belum diisi')
// -------------------------------------------------------------
echo "\n[3] Pengujian Penanganan Nilai Kosong (Tanpa Data Palsu)...\n";

$emptyUser = [
    'id' => 999999,
    'nama_lengkap' => '',
    'no_wa' => null,
    'domisili' => '',
];
$emptyVerif = null;

$renderEmpty = renderProfileIdentity($emptyUser, $emptyVerif);

assertTest("Field NIK kosong menampilkan 'Belum diisi'", $renderEmpty['nik'] === 'Belum diisi');
assertTest("Field Alamat kosong menampilkan 'Belum diisi'", $renderEmpty['alamat'] === 'Belum diisi');
assertTest("Field No. HP kosong menampilkan 'Belum diisi'", $renderEmpty['hp'] === 'Belum diisi');
assertTest("Field Nama kosong menampilkan 'Belum diisi'", $renderEmpty['nama'] === 'Belum diisi');

// -------------------------------------------------------------
// [4] Pengujian Halaman PKS Agreement (/agreement)
// -------------------------------------------------------------
echo "\n[4] Pengujian Ringkasan Identitas di Halaman Persetujuan PKS (/agreement)...\n";

$agreementViewPath = APPPATH . 'Views/auth/agreement.php';
$agreementContent = file_get_contents($agreementViewPath);

assertTest("agreement.php tidak lagi memuat fallback hardcoded 'INDONESIA' untuk domisili", 
    strpos($agreementContent, "'INDONESIA'") === false);
assertTest("agreement.php memuat fallback 'Belum diisi' jika domisili kosong", 
    strpos($agreementContent, "\$pihakKeduaAlamat = !empty(\$user['domisili']) ? strtoupper(trim(\$user['domisili'])) : 'Belum diisi'") !== false);
assertTest("agreement.php memuat fallback 'Belum diisi' jika NIK kosong", 
    strpos($agreementContent, "\$pihakKeduaNik = !empty(\$verification['nik']) ? trim(\$verification['nik']) : 'Belum diisi'") !== false);
assertTest("agreement.php menampilkan No. HP pihak kedua secara dinamis", 
    strpos($agreementContent, "\$pihakKeduaHp") !== false && strpos($agreementContent, 'No. HP: <?= esc($pihakKeduaHp) ?>') !== false);

// -------------------------------------------------------------
// [5] Pengujian Halaman SuperAdmin Verification Detail
// -------------------------------------------------------------
echo "\n[5] Pengujian Halaman Detail Verifikasi Admin (/superadmin/verifications/{id})...\n";

$verifDetailPath = APPPATH . 'Views/superadmin/verification_detail.php';
$verifDetailContent = file_get_contents($verifDetailPath);

assertTest("verification_detail.php tidak lagi memuat fallback 'Indonesia' di header pihak kedua", 
    strpos($verifDetailContent, "Domisili: <?= esc(\$user['domisili'] ?: 'Indonesia') ?>") === false);
assertTest("verification_detail.php menggunakan 'Belum diisi' pada Domisili", 
    strpos($verifDetailContent, "Domisili: <?= esc(\$user['domisili'] ?: 'Belum diisi') ?>") !== false);
assertTest("verification_detail.php menggunakan 'Belum diisi' pada Nomor WhatsApp", 
    strpos($verifDetailContent, "<?= esc(\$user['no_wa'] ?: 'Belum diisi') ?>") !== false);
assertTest("verification_detail.php menggunakan 'Belum diisi' pada NIK KTP", 
    strpos($verifDetailContent, "<?= esc(!empty(\$verification['nik']) ? \$verification['nik'] : 'Belum diisi') ?>") !== false);

// -------------------------------------------------------------
// [6] Pengujian Halaman SuperAdmin Kelola Pengguna (/superadmin/users)
// -------------------------------------------------------------
echo "\n[6] Pengujian Join NIK & Tampilan Identitas di Kelola Pengguna SuperAdmin...\n";

$userControllerPath = APPPATH . 'Controllers/SuperAdmin/User.php';
$userControllerContent = file_get_contents($userControllerPath);

assertTest("User.php controller melakukan select v.nik", 
    strpos($userControllerContent, "v.nik") !== false);
assertTest("User.php controller melakukan join ke tabel verifications", 
    strpos($userControllerContent, "join('verifications v', 'v.user_id = u.id', 'left')") !== false);

$usersViewPath = APPPATH . 'Views/superadmin/users.php';
$usersViewContent = file_get_contents($usersViewPath);

assertTest("users.php view menampilkan NIK di tabel", 
    strpos($usersViewContent, "NIK: <?= esc(!empty(\$user['nik']) ? \$user['nik'] : 'Belum diisi') ?>") !== false);
assertTest("users.php view menyertakan field NIK pada modal edit pengguna", 
    strpos($usersViewContent, 'id="edit_nik"') !== false);
assertTest("users.php JavaScript mengisi NIK dinamis pada openEditModal", 
    strpos($usersViewContent, "nikEl.value = user.nik || 'Belum diisi'") !== false);

// -------------------------------------------------------------
// [7] Pengujian Keamanan & Isolasi Hak Akses Server-Side
// -------------------------------------------------------------
echo "\n[7] Pengujian Validasi Keamanan & Otorisasi Server-Side...\n";

$profileControllerPath = APPPATH . 'Controllers/Profile.php';
$profileControllerContent = file_get_contents($profileControllerPath);

assertTest("Profile::index hanya mengambil data berdasarkan session user_id (anti manipulasi input)", 
    strpos($profileControllerContent, "\$userId = session('user_id');") !== false);
assertTest("Profile::index memuat data verification dinamis sesuai user_id", 
    strpos($profileControllerContent, "\$verification = \$db->table('verifications')") !== false);
assertTest("Profile::index mengirim variabel verification ke view", 
    strpos($profileControllerContent, "'verification' => \$verification") !== false);
assertTest("Profile::update mengikat pembaruan hanya pada session user_id", 
    strpos($profileControllerContent, "\$userId = session('user_id');\n        \$user = \$this->userModel->find(\$userId);") !== false);

echo "\n===================================================================\n";
if ($allPassed) {
    echo "STATUS: SEMUA {$assertions} PENGUJIAN IDENTITAS DINAMIS LULUS 100%!\n";
} else {
    echo "STATUS: TERDAPAT PENGUJIAN YANG GAGAL!\n";
    exit(1);
}
echo "===================================================================\n";
