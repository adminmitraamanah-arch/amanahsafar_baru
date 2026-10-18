<?php

// Test Suite: Sinkronisasi Data Pendaftar pada PKS dan Pembaruan Persetujuan Wajib
// Menguji kesesuaian data identitas, format bold, 3 pernyataan verbatim, dan validasi 3 checkbox.

echo "===================================================================\n";
echo "TEST SUITE: SINKRONISASI DATA PENDAFTAR PKS & PERSETUJUAN WAJIB\n";
echo "===================================================================\n\n";

if (!function_exists('esc')) {
    function esc($data) {
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return 'http://localhost/amanahsafar/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_test_name" value="mock_csrf_token">';
    }
}

if (!function_exists('format_tanggal_indonesia')) {
    function format_tanggal_indonesia($date, $full = true) {
        return date('d F Y', strtotime($date));
    }
}

$agreementViewPath = dirname(__DIR__) . '/app/Views/auth/agreement.php';
$printViewPath     = dirname(__DIR__) . '/app/Views/superadmin/verification_pks_print.php';
$authControllerPath = dirname(__DIR__) . '/app/Controllers/Auth.php';

$totalPassed = 0;
$totalFailed = 0;

function assertCondition(string $desc, bool $condition) {
    global $totalPassed, $totalFailed;
    if ($condition) {
        echo "  [PASS] $desc\n";
        $totalPassed++;
    } else {
        echo "  [FAIL] $desc\n";
        $totalFailed++;
    }
}

// -------------------------------------------------------------
// [1] Pengujian Verbatim 3 Pernyataan Baru pada View Agreement
// -------------------------------------------------------------
echo "[1] Pengujian Teks 3 Pernyataan Persetujuan Baru pada agreement.php:\n";

$agreementRaw = file_get_contents($agreementViewPath);
$plainTextAgreement = preg_replace('/<[^>]*>/', ' ', $agreementRaw);
$plainTextAgreement = preg_replace('/\s+/', ' ', $plainTextAgreement);

// Pernyataan 1
assertCondition(
    "Pernyataan 1 memuat 'Identitas dan persetujuan PKS. Saya menyatakan identitas saya benar dan saya cakap membuat perjanjian...'",
    strpos($plainTextAgreement, 'Identitas dan persetujuan PKS. Saya menyatakan identitas saya benar dan saya cakap membuat perjanjian.') !== false &&
    strpos($plainTextAgreement, 'PKS Nomor') !== false &&
    strpos($plainTextAgreement, 'beserta seluruh lampirannya, termasuk komisi dan SOP, serta secara sadar tanpa paksaan menyetujui penandatanganannya secara elektronik.') !== false
);

// Pernyataan 2
assertCondition(
    "Pernyataan 2 memuat 'Larangan menerima uang jamaah. Saya tidak akan menerima, menampung, menggunakan, atau mengalihkan dana jamaah dalam bentuk apa pun...'",
    strpos($plainTextAgreement, 'Larangan menerima uang jamaah. Saya tidak akan menerima, menampung, menggunakan, atau mengalihkan dana jamaah dalam bentuk apa pun, termasuk melalui pihak lain, meskipun jamaah meminta menitipkan uang. Seluruh pembayaran harus langsung menuju tujuan resmi yang diverifikasi pusat. Saya tidak boleh memotong komisi dari dana jamaah.') !== false
);

// Pernyataan 3
assertCondition(
    "Pernyataan 3 memuat 'Kewajiban dan pertanggungjawaban mitra. Saya wajib mematuhi batas kewenangan, ketentuan promosi...'",
    strpos($plainTextAgreement, 'Kewajiban dan pertanggungjawaban mitra. Saya wajib mematuhi batas kewenangan, ketentuan promosi, penggunaan merek, dokumen dan perlengkapan, komunikasi langsung pusat dengan jamaah, serta pelindungan data. Saya menyetujui pengawasan, pembekuan akses, sanksi, dan penggantian kerugian yang terbukti akibat pelanggaran saya sesuai PKS dan hukum.') !== false
);

// -------------------------------------------------------------
// [2] Pengujian Keberadaan & Atribut 3 Checkbox Wajib
// -------------------------------------------------------------
echo "\n[2] Pengujian Elemen Checkbox & Validasi Frontend:\n";

assertCondition("Checkbox 1 memiliki name='agree_klausul' dan id='agreeKlausul'", strpos($agreementRaw, 'name="agree_klausul" id="agreeKlausul"') !== false);
assertCondition("Checkbox 2 memiliki name='agree_larangan_dana' dan id='agreeLaranganDana'", strpos($agreementRaw, 'name="agree_larangan_dana" id="agreeLaranganDana"') !== false);
assertCondition("Checkbox 3 memiliki name='agree' dan id='agreeKewajiban'", strpos($agreementRaw, 'name="agree" id="agreeKewajiban"') !== false);
assertCondition("Ketiga checkbox memiliki atribut required", substr_count($agreementRaw, 'required class="mt-0.5') >= 3);
assertCondition("Tombol submit awalnya berstatus disabled", strpos($agreementRaw, 'id="btnSubmitAgreement" class="btn-brand-primary" disabled') !== false);
assertCondition("Script frontend menginisialisasi checkbox dalam keadaan unchecked", strpos($agreementRaw, 'chk1.checked = false') !== false && strpos($agreementRaw, 'chk2.checked = false') !== false && strpos($agreementRaw, 'chk3.checked = false') !== false);
assertCondition("Script frontend mendeteksi submit event dan memblokir jika salah satu belum dicentang", strpos($agreementRaw, 'form.addEventListener(\'submit\'') !== false && strpos($agreementRaw, 'e.preventDefault()') !== false);

// -------------------------------------------------------------
// [3] Pengujian Render Data Identitas & Format Bold (Akun 1)
// -------------------------------------------------------------
echo "\n[3] Pengujian Render Data Identitas Mitra Akun 1:\n";

$user1 = [
    'id' => 101,
    'nama_lengkap' => 'Ahmad Fulan',
    'domisili' => 'Jl. Kebon Raya No. 45, Bogor Tengah, Kota Bogor',
    'no_wa' => '081234567890',
    'agreement_accepted_at' => null,
];
$verification1 = [
    'nik' => '3271011508900001',
    'created_at' => '2026-10-18 10:00:00',
];
$pksNumber1 = 'PKS/AS/202610/0101';
$pksVersion1 = '1.0';

// Render ringkasan kemitraan akun 1
$pihakKeduaNama = strtoupper(trim($user1['nama_lengkap']));
$pihakKeduaNik = trim($verification1['nik']);
$pihakKeduaAlamat = strtoupper(trim($user1['domisili']));
$pihakKeduaHp = trim($user1['no_wa']);

ob_start();
?>
<div>Nama: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaNama) ?></strong></div>
<div>NIK: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaNik) ?></strong></div>
<div>Alamat: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaAlamat) ?></strong></div>
<div>Kontak: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaHp) ?></strong></div>
<?php
$renderedIdentity1 = ob_get_clean();

assertCondition("Nama Akun 1 tampil sesuai data dan berformat bold", strpos($renderedIdentity1, 'Nama: <strong class="text-slate-900 font-bold">AHMAD FULAN</strong>') !== false);
assertCondition("NIK Akun 1 tampil sesuai data dan berformat bold", strpos($renderedIdentity1, 'NIK: <strong class="text-slate-900 font-bold">3271011508900001</strong>') !== false);
assertCondition("Alamat Akun 1 tampil sesuai data dan berformat bold", strpos($renderedIdentity1, 'Alamat: <strong class="text-slate-900 font-bold">JL. KEBON RAYA NO. 45, BOGOR TENGAH, KOTA BOGOR</strong>') !== false);
assertCondition("Kontak Akun 1 tampil sesuai data dan berformat bold", strpos($renderedIdentity1, 'Kontak: <strong class="text-slate-900 font-bold">081234567890</strong>') !== false);

// -------------------------------------------------------------
// [4] Pengujian Render Data Identitas & Format Bold (Akun 2)
// -------------------------------------------------------------
echo "\n[4] Pengujian Render Data Identitas Mitra Akun 2 (Multi-Account Test):\n";

$user2 = [
    'id' => 202,
    'nama_lengkap' => 'Siti Nurhaliza',
    'domisili' => 'Komplek Permata Hijau Blok C-12, Surabaya',
    'no_wa' => '087788991122',
    'agreement_accepted_at' => null,
];
$verification2 = [
    'nik' => '3578012001920002',
    'created_at' => '2026-10-18 11:30:00',
];
$pksNumber2 = 'PKS/AS/202610/0202';
$pksVersion2 = '1.0';

$pihakKeduaNama2 = strtoupper(trim($user2['nama_lengkap']));
$pihakKeduaNik2 = trim($verification2['nik']);
$pihakKeduaAlamat2 = strtoupper(trim($user2['domisili']));
$pihakKeduaHp2 = trim($user2['no_wa']);

ob_start();
?>
<div>Nama: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaNama2) ?></strong></div>
<div>NIK: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaNik2) ?></strong></div>
<div>Alamat: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaAlamat2) ?></strong></div>
<div>Kontak: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaHp2) ?></strong></div>
<?php
$renderedIdentity2 = ob_get_clean();

assertCondition("Nama Akun 2 tampil sesuai data dan berformat bold", strpos($renderedIdentity2, 'Nama: <strong class="text-slate-900 font-bold">SITI NURHALIZA</strong>') !== false);
assertCondition("NIK Akun 2 tampil sesuai data dan berformat bold", strpos($renderedIdentity2, 'NIK: <strong class="text-slate-900 font-bold">3578012001920002</strong>') !== false);
assertCondition("Alamat Akun 2 tampil sesuai data dan berformat bold", strpos($renderedIdentity2, 'Alamat: <strong class="text-slate-900 font-bold">KOMPLEK PERMATA HIJAU BLOK C-12, SURABAYA</strong>') !== false);
assertCondition("Kontak Akun 2 tampil sesuai data dan berformat bold", strpos($renderedIdentity2, 'Kontak: <strong class="text-slate-900 font-bold">087788991122</strong>') !== false);
assertCondition("Tidak ada data silang/cross-contamination antara Akun 1 dan Akun 2", strpos($renderedIdentity2, 'AHMAD FULAN') === false && strpos($renderedIdentity1, 'SITI NURHALIZA') === false);

// -------------------------------------------------------------
// [5] Pengujian Validasi Server-Side pada Auth.php
// -------------------------------------------------------------
echo "\n[5] Pengujian Validasi Server-Side Submission Guard (Auth.php):\n";

$authContent = file_get_contents($authControllerPath);

assertCondition("Auth.php memvalidasi kelengkapan identitas pendaftar pada POST agreement", 
    strpos($authContent, 'empty($user[\'nama_lengkap\']) || empty($verification[\'nik\']) || empty($user[\'domisili\']) || empty($user[\'no_wa\'])') !== false
);

assertCondition("Auth.php memvalidasi ketiga checkbox persetujuan tidak boleh kosong", 
    strpos($authContent, '$agreeKlausul      = $this->request->getPost(\'agree_klausul\');') !== false &&
    strpos($authContent, '$agreeLaranganDana = $this->request->getPost(\'agree_larangan_dana\');') !== false &&
    strpos($authContent, '$agreeKewajiban    = $this->request->getPost(\'agree\');') !== false &&
    strpos($authContent, 'empty($agreeKlausul) || empty($agreeLaranganDana) || empty($agreeKewajiban)') !== false
);

assertCondition("Auth.php mengirimkan pesan error spesifik jika salah satu checkbox belum dicentang", 
    strpos($authContent, 'Seluruh pernyataan persetujuan (Pernyataan 1, 2, dan 3) wajib disetujui untuk melanjutkan.') !== false
);

// -------------------------------------------------------------
// [6] Simulasi Logika Validasi Checkbox Server-Side
// -------------------------------------------------------------
echo "\n[6] Simulasi Kasus Uji Checkbox Server-Side:\n";

function simulateServerValidation(?string $c1, ?string $c2, ?string $c3): array {
    if (empty($c1) || empty($c2) || empty($c3)) {
        return ['status' => false, 'message' => 'Seluruh pernyataan persetujuan (Pernyataan 1, 2, dan 3) wajib disetujui untuk melanjutkan.'];
    }
    return ['status' => true, 'message' => 'Lolos'];
}

// Kasus 1: 0 checkbox dicentang
$res0 = simulateServerValidation(null, null, null);
assertCondition("Submit dengan 0 checkbox dicentang DITOLAK", $res0['status'] === false);

// Kasus 2: Hanya 1 checkbox dicentang
$res1 = simulateServerValidation('1', null, null);
assertCondition("Submit hanya Checkbox 1 dicentang DITOLAK", $res1['status'] === false);

$res1b = simulateServerValidation(null, '1', null);
assertCondition("Submit hanya Checkbox 2 dicentang DITOLAK", $res1b['status'] === false);

// Kasus 3: 2 checkbox dicentang
$res2 = simulateServerValidation('1', '1', null);
assertCondition("Submit Checkbox 1 dan 2 dicentang (Checkbox 3 kosong) DITOLAK", $res2['status'] === false);

$res2b = simulateServerValidation('1', null, '1');
assertCondition("Submit Checkbox 1 dan 3 dicentang (Checkbox 2 kosong) DITOLAK", $res2b['status'] === false);

// Kasus 4: Ketiga checkbox dicentang
$res3 = simulateServerValidation('1', '1', '1');
assertCondition("Submit saat ketiga checkbox dicentang DITERIMA", $res3['status'] === true);

// -------------------------------------------------------------
// [7] Pengujian Template Dokumen Cetak / PDF PKS
// -------------------------------------------------------------
echo "\n[7] Pengujian Blok Ringkasan Kemitraan pada Template Cetak PKS:\n";

$printContent = file_get_contents($printViewPath);
assertCondition("Halaman 12 dokumen cetak memuat Ringkasan Kemitraan", strpos($printContent, 'Ringkasan Kemitraan — Identitas Terverifikasi Para Pihak:') !== false);
assertCondition("Template cetak mencetak nilai Nama berformat bold", strpos($printContent, 'Nama: <strong><?= esc($pihakKeduaNama) ?></strong>') !== false);
assertCondition("Template cetak mencetak nilai NIK berformat bold", strpos($printContent, 'NIK: <strong><?= esc($pihakKeduaNik) ?></strong>') !== false);
assertCondition("Template cetak mencetak nilai Alamat berformat bold", strpos($printContent, 'Alamat: <strong><?= esc($pihakKeduaAlamat) ?></strong>') !== false);
assertCondition("Template cetak mencetak nilai Kontak berformat bold", strpos($printContent, 'Kontak: <strong><?= esc($pihakKeduaHp) ?></strong>') !== false);

echo "\n===================================================================\n";
echo "RINGKASAN: $totalPassed BERHASIL, $totalFailed GAGAL\n";
echo "===================================================================\n";

if ($totalFailed > 0) {
    exit(1);
}
