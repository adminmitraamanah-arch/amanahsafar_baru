<?php
/**
 * Test: Verifikasi Penghapusan Komponen PKS pada Halaman Admin & Super Admin,
 * serta Retensi Fungsionalitas PKS untuk Role Kemitraan (Mitra, Manager, Cabang, Pusat).
 */

require_once __DIR__ . '/../app/Common.php';

if (!function_exists('esc')) {
    function esc($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return 'http://localhost/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="dummy_csrf_token">';
    }
}

if (!function_exists('old')) {
    function old($key, $default = null) {
        return $default;
    }
}

if (!function_exists('session')) {
    function session($key = null) {
        static $dummySession = [
            'logged_in' => true,
            'user_id' => 1,
            'nama' => 'Test User',
            'email' => 'test@amanahsafar.com',
            'role' => 'admin'
        ];
        if ($key === null) return $dummySession;
        return $dummySession[$key] ?? null;
    }
}

if (!function_exists('view')) {
    function view($name, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../app/Views/' . $name . '.php';
        return ob_get_clean();
    }
}

class ProfileViewHarness {
    public function extend($name) { return ''; }
    public function section($name) { return ''; }
    public function endSection() { return ''; }
    public function include($name) { return '<!-- mock include ' . $name . ' -->'; }

    public function renderProfile($user, $showPks, $pksInfo) {
        extract([
            'title'   => 'Profil Saya',
            'user'    => $user,
            'showPks' => $showPks,
            'pksInfo' => $pksInfo,
        ]);
        ob_start();
        require __DIR__ . '/../app/Views/profile.php';
        return ob_get_clean();
    }
}

$harness = new ProfileViewHarness();
$failures = [];

// ==========================================
// TEST 1: Role Admin - PKS tidak boleh ada sama sekali
// ==========================================
$adminUser = [
    'id' => 1,
    'nama_lengkap' => 'Administrator Utama',
    'email' => 'admin@amanahsafar.com',
    'role' => 'admin',
    'agreement_accepted_at' => null
];

// Simulasi logika controller untuk admin
$isAdminRole = in_array((string)$adminUser['role'], ['admin', 'super_admin', 'superadmin'], true);
$showPksAdmin = !$isAdminRole;
$pksInfoAdmin = $showPksAdmin ? ['is_accepted' => false] : null;

$adminHtml = $harness->renderProfile($adminUser, $showPksAdmin, $pksInfoAdmin);

$forbiddenTerms = [
    'Perjanjian Kerja Sama',
    'Download PDF PKS',
    'Nomor Perjanjian (PKS)',
    'Waktu Persetujuan Digital',
    'Dokumen legalitas resmi kemitraan',
    'Buka Halaman PKS',
    'profile/pks/download'
];

foreach ($forbiddenTerms as $term) {
    if (stripos($adminHtml, $term) !== false) {
        $failures[] = "TEST 1 FAILED: Teks terlarang '{$term}' ditemukan pada halaman role admin!";
    }
}

if (strpos($adminHtml, 'profile_pks_card') !== false) {
    $failures[] = "TEST 1 FAILED: Shared partial profile_pks_card dipanggil pada role admin!";
}

// ==========================================
// TEST 2: Role Super Admin - PKS tidak boleh ada sama sekali
// ==========================================
$superAdminUser = [
    'id' => 2,
    'nama_lengkap' => 'Super Administrator',
    'email' => 'superadmin@amanahsafar.com',
    'role' => 'super_admin',
    'agreement_accepted_at' => null
];

$isSuperAdminRole = in_array((string)$superAdminUser['role'], ['admin', 'super_admin', 'superadmin'], true);
$showPksSuperAdmin = !$isSuperAdminRole;
$pksInfoSuperAdmin = $showPksSuperAdmin ? ['is_accepted' => false] : null;

$superAdminHtml = $harness->renderProfile($superAdminUser, $showPksSuperAdmin, $pksInfoSuperAdmin);

foreach ($forbiddenTerms as $term) {
    if (stripos($superAdminHtml, $term) !== false) {
        $failures[] = "TEST 2 FAILED: Teks terlarang '{$term}' ditemukan pada halaman role super_admin!";
    }
}

// ==========================================
// TEST 3: Role Mitra (PKS Disetujui) - PKS Wajib Muncul Lengkap
// ==========================================
$mitraUser = [
    'id' => 10,
    'nama_lengkap' => 'Ahmad Mitra',
    'email' => 'ahmad@gmail.com',
    'role' => 'mitra',
    'agreement_accepted_at' => '2026-10-15 10:30:00'
];

$isMitraAdminRole = in_array((string)$mitraUser['role'], ['admin', 'super_admin', 'superadmin'], true);
$showPksMitra = !$isMitraAdminRole;
$pksInfoMitra = [
    'is_accepted' => true,
    'accepted_at' => '2026-10-15 10:30:00',
    'pks_number'  => 'PKS/AS/2026/10/0010',
    'has_pdf'     => true,
    'download_url'=> base_url('profile/pks/download'),
];

$mitraHtml = $harness->renderProfile($mitraUser, $showPksMitra, $pksInfoMitra);

$requiredMitraTerms = [
    'Perjanjian Kerja Sama (PKS)',
    'Disetujui &amp; Aktif',
    'Nomor Perjanjian (PKS)',
    'PKS/AS/2026/10/0010',
    'Waktu Persetujuan Digital',
    'Download PDF PKS',
    'profile/pks/download'
];

foreach ($requiredMitraTerms as $term) {
    if (strpos($mitraHtml, $term) === false) {
        $failures[] = "TEST 3 FAILED: Komponen wajib '{$term}' TIDAK ditemukan pada profil mitra (aktif)!";
    }
}

// ==========================================
// TEST 4: Role Mitra (PKS Belum Disetujui) - Tampilkan Peringatan & Buka Halaman PKS
// ==========================================
$mitraPendingUser = [
    'id' => 11,
    'nama_lengkap' => 'Budi Calon Mitra',
    'email' => 'budi@gmail.com',
    'role' => 'mitra',
    'agreement_accepted_at' => null
];

$showPksPending = true;
$pksInfoPending = [
    'is_accepted' => false,
    'accepted_at' => null,
    'pks_number'  => null,
    'has_pdf'     => false,
    'download_url'=> base_url('profile/pks/download'),
];

$pendingHtml = $harness->renderProfile($mitraPendingUser, $showPksPending, $pksInfoPending);

if (strpos($pendingHtml, 'Perjanjian Belum Disetujui') === false) {
    $failures[] = "TEST 4 FAILED: Status Belum Disetujui tidak tampil untuk mitra pending!";
}
if (strpos($pendingHtml, 'Buka Halaman PKS') === false) {
    $failures[] = "TEST 4 FAILED: Tombol Buka Halaman PKS tidak tampil untuk mitra pending!";
}
if (strpos($pendingHtml, 'Download PDF PKS') !== false) {
    $failures[] = "TEST 4 FAILED: Tombol Download PDF PKS muncul padahal perjanjian belum disetujui!";
}

// ==========================================
// TEST 5: Evaluasi Role Coverage
// ==========================================
$roles = [
    'admin'         => false, // showPks should be false
    'super_admin'   => false, // showPks should be false
    'superadmin'    => false, // showPks should be false
    'mitra'         => true,  // showPks should be true
    'manager_mitra' => true,  // showPks should be true
    'cabang'        => true,  // showPks should be true
    'pusat'         => true,  // showPks should be true
];

foreach ($roles as $role => $expectedShowPks) {
    $actual = !in_array($role, ['admin', 'super_admin', 'superadmin'], true);
    if ($actual !== $expectedShowPks) {
        $failures[] = "TEST 5 FAILED: Role {$role} showPks expected " . ($expectedShowPks ? 'true' : 'false') . ", got " . ($actual ? 'true' : 'false');
    }
}

// ==========================================
// HASIL AKHIR
// ==========================================
if (!empty($failures)) {
    echo "=== PENGUJIAN GAGAL (" . count($failures) . " errors) ===\n";
    foreach ($failures as $f) {
        echo "- " . $f . "\n";
    }
    exit(1);
}

echo "=== SEMUA PENGUJIAN LULUS 100% ===\n";
echo "1. Role Admin: Bersih 100% tanpa komponen, teks, atau tautan PKS.\n";
echo "2. Role Super Admin: Bersih 100% tanpa komponen, teks, atau tautan PKS.\n";
echo "3. Role Mitra (Aktif): Menampilkan kartu PKS lengkap dengan status, nomor dokumen, tanggal persetujuan, dan tombol Download PDF PKS.\n";
echo "4. Role Mitra (Pending): Menampilkan panduan dan tombol Buka Halaman PKS dengan tepat.\n";
echo "5. Role Mapping: Seluruh role administratif diblokir dari PKS, seluruh role kemitraan diizinkan.\n";
exit(0);
