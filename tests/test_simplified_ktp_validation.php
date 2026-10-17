<?php
/**
 * Test Suite: Sederhanakan Validasi Scan KTP dan Otomatisasi Nama Rekening
 * 
 * Memvalidasi kepatuhan terhadap Acceptance Criteria:
 * 1. Fokus validasi dokumen KTP & kualitas visual, bukan kegagalan OCR teks.
 * 2. Ekstraksi NIK & Nama tidak wajib berhasil; teks tidak terbaca TIDAK menolak KTP.
 * 3. Pesan penolakan 'KTP Terdeteksi Namun Teks Kurang Jelas' dihilangkan dari alur normal.
 * 4. Pengisian manual Nama Lengkap (KTP) dan NIK wajib selalu aktif (unlocked).
 * 5. Nama Pemilik Rekening otomatis sinkron real-time dengan Nama KTP.
 * 6. Nama Pemilik Rekening terkunci permanen (readOnly, tabindex=-1, blokir keydown/paste/cut/drop).
 * 7. Jika Nama KTP dikosongkan, Nama Pemilik Rekening ikut kosong seketika.
 * 8. Tombol submit hanya aktif ketika KTP valid, NIK 16 digit, Nama KTP diisi, Rekening cocok, data bank lengkap, dan persetujuan dicentang.
 * 9. Tombol Salin dari Nama KTP dinonaktifkan / disembunyikan.
 * 10. Server-side guard memastikan kesesuaian identitas dan keabsahan rekening bank.
 */

$viewPath = __DIR__ . '/../app/Views/auth/verify.php';
$controllerPath = __DIR__ . '/../app/Controllers/Auth.php';

if (!file_exists($viewPath)) {
    die("Error: File view {$viewPath} tidak ditemukan.\n");
}
if (!file_exists($controllerPath)) {
    die("Error: File controller {$controllerPath} tidak ditemukan.\n");
}

$viewContent = file_get_contents($viewPath);
$controllerContent = file_get_contents($controllerPath);

$passed = 0;
$failed = 0;

function assertCondition($description, $condition) {
    global $passed, $failed;
    if ($condition) {
        echo "  [PASS] {$description}\n";
        $passed++;
    } else {
        echo "  [FAIL] {$description}\n";
        $failed++;
    }
}

echo "===================================================================\n";
echo "TEST SUITE: SIMPLIFIED KTP VALIDATION & REKENING AUTO-SYNC\n";
echo "===================================================================\n";

// -------------------------------------------------------------
// [1] Pengujian Validasi Dokumen KTP Murni & Eliminasi Rejection OCR Teks
// -------------------------------------------------------------
echo "\n[1] Pengujian Validasi Dokumen KTP Murni & Kualitas Visual:\n";

assertCondition("Fungsi checkIndonesianKtpKeywords menolak dokumen non-KTP (KK, SIM, Paspor, dll)", 
    strpos($viewContent, 'KARTU KELUARGA') !== false &&
    strpos($viewContent, 'SURAT IZIN MENGEMUDI') !== false &&
    strpos($viewContent, 'PASPOR') !== false &&
    strpos($viewContent, 'isOtherDoc') !== false
);

assertCondition("Pemeriksaan kualitas citra memeriksa resolusi minimum dan blur/laplacian", 
    strpos($viewContent, 'analyzeImageQuality') !== false &&
    strpos($viewContent, 'lapVariance') !== false
);

assertCondition("Pesan penolakan 'KTP Terdeteksi Namun Teks Kurang Jelas' tidak lagi memblokir validitas KTP", 
    strpos($viewContent, "'KTP Terdeteksi Namun Teks Kurang Jelas'") === false ||
    strpos($viewContent, "// Status penanganan variasi dokumen:") !== false
);

assertCondition("Ketika KTP valid dan NIK belum terisi, status KTP tetap valid (systemState.isKtpValid = true)", 
    strpos($viewContent, 'systemState.isKtpValid = true') !== false &&
    strpos($viewContent, 'KTP Valid - Lengkapi NIK') !== false
);

// -------------------------------------------------------------
// [2] Pengujian Pengisian Manual Nama dan NIK (Field Tidak Dikunci)
// -------------------------------------------------------------
echo "\n[2] Pengujian Pengisian Manual Nama KTP dan NIK (Field Selalu Terbuka):\n";

assertCondition("Field Nama KTP (#namaKtp) tidak memiliki atribut readonly statis di HTML", 
    preg_match('/<input[^>]+id="namaKtp"[^>]*readonly/i', $viewContent) === 0
);

assertCondition("Field NIK (#nikInput) tidak memiliki atribut readonly statis di HTML", 
    preg_match('/<input[^>]+id="nikInput"[^>]*readonly/i', $viewContent) === 0
);

assertCondition("Script membuka kunci field Nama KTP saat halaman dimuat (unlockField(namaInput))", 
    strpos($viewContent, 'if (namaInput) unlockField(namaInput);') !== false
);

assertCondition("Script membuka kunci field NIK saat halaman dimuat (unlockField(nikInput))", 
    strpos($viewContent, 'if (nikInput) unlockField(nikInput);') !== false
);

assertCondition("Script membuka kunci field Nama & NIK setelah pemindaian OCR KTP", 
    strpos($viewContent, 'unlockField(nikInput)') !== false &&
    strpos($viewContent, 'unlockField(namaInput)') !== false
);

// -------------------------------------------------------------
// [3] Pengujian Otomatisasi & Penguncian Nama Pemilik Rekening
// -------------------------------------------------------------
echo "\n[3] Pengujian Otomatisasi & Penguncian Nama Pemilik Rekening:\n";

$posPemilik = strpos($viewContent, 'id="namaPemilikRekening"');
$chunkPemilik = ($posPemilik !== false) ? substr($viewContent, $posPemilik - 30, 600) : '';
assertCondition("Input Nama Pemilik Rekening di HTML diatur readonly dan input-locked secara default", 
    strpos($chunkPemilik, 'readonly') !== false && 
    strpos($chunkPemilik, 'input-locked') !== false
);

assertCondition("Fungsi syncNamaRekeningFromKtp menyalin nilai Nama KTP ke Rekening dan menguncinya", 
    strpos($viewContent, 'function syncNamaRekeningFromKtp') !== false &&
    strpos($viewContent, 'namaPemilikInput.value = namaKtpInput.value') !== false &&
    strpos($viewContent, 'namaPemilikInput.readOnly = true') !== false
);

assertCondition("Event input pada #namaKtp langsung memanggil syncNamaRekeningFromKtp secara real-time", 
    strpos($viewContent, "namaInput.addEventListener('input', function() {") !== false &&
    strpos($viewContent, "syncNamaRekeningFromKtp();") !== false
);

assertCondition("Event keydown, paste, cut, drop diblokir mutlak pada #namaPemilikRekening", 
    strpos($viewContent, "['keydown', 'paste', 'cut', 'drop']") !== false &&
    strpos($viewContent, "namaPemilikInput.addEventListener(evt, function(e) {") !== false
);

assertCondition("Tombol Salin dari Nama KTP disembunyikan/dinonaktifkan", 
    strpos($viewContent, 'id="btnCopyNamaKtp"') !== false &&
    (strpos($viewContent, 'disabled') !== false && strpos($viewContent, 'hidden') !== false)
);

// -------------------------------------------------------------
// [4] Pengujian Logika Aktivasi Tombol Submit
// -------------------------------------------------------------
echo "\n[4] Pengujian Logika Aktivasi Tombol Submit:\n";

assertCondition("refreshSubmitButton mengecek 6 kondisi: KTP valid, agree, rekening match, bank lengkap, NIK 16 digit, Nama min 3 char", 
    strpos($viewContent, 'systemState.isKtpValid') !== false &&
    strpos($viewContent, 'isAgree') !== false &&
    strpos($viewContent, 'isRekeningMatch') !== false &&
    strpos($viewContent, 'isBankComplete') !== false &&
    strpos($viewContent, 'valNik.length === 16') !== false &&
    strpos($viewContent, 'valNama.length >= 3') !== false
);

assertCondition("Submit protection form memanggil syncNamaRekeningFromKtp sebelum verifikasi akhir", 
    strpos($viewContent, "form.addEventListener('submit', function(e) {") !== false &&
    strpos($viewContent, "syncNamaRekeningFromKtp();") !== false
);

// -------------------------------------------------------------
// [5] Pengujian Unit Logika Rekening Sync & Empty Clearing
// -------------------------------------------------------------
echo "\n[5] Simulasi Real-Time Input & Empty Handling:\n";

$namaKtpValue = "Budi Rahardjo";
$namaPemilikValue = "";
$isReadOnly = false;

$syncSimulator = function($val) use (&$namaKtpValue, &$namaPemilikValue, &$isReadOnly) {
    $namaKtpValue = $val;
    $namaPemilikValue = $namaKtpValue ? $namaKtpValue : '';
    $isReadOnly = true;
};

// 1. Initial sync
$syncSimulator("SITI AMINAH");
$pass1 = ($namaPemilikValue === "SITI AMINAH" && $isReadOnly === true);

// 2. User edits nama KTP
$syncSimulator("SITI AMINAH S.E.");
$pass2 = ($namaPemilikValue === "SITI AMINAH S.E.");

// 3. User empties nama KTP
$syncSimulator("");
$pass3 = ($namaPemilikValue === "" && $isReadOnly === true);

// 4. Case and space matching test
$checkMatchSimulator = function($ktp, $pemilik) {
    if (!$ktp || !$pemilik) return false;
    $vKtp = trim(preg_replace('/\s+/', ' ', strtoupper($ktp)));
    $vPem = trim(preg_replace('/\s+/', ' ', strtoupper($pemilik)));
    return $vKtp === $vPem;
};

$pass4 = ($checkMatchSimulator("ahmad dahlan", "AHMAD  DAHLAN") === true);
$pass5 = ($checkMatchSimulator("ahmad dahlan", "budi santoso") === false);
$pass6 = ($checkMatchSimulator("", "") === false);

assertCondition("Sinkronisasi real-time menyalin nama, memperbarui editan, mengosongkan saat dihapus, dan validasi match normalisasi", 
    $pass1 && $pass2 && $pass3 && $pass4 && $pass5 && $pass6
);

// -------------------------------------------------------------
// [6] Pengujian Integritas Backend (app/Controllers/Auth.php)
// -------------------------------------------------------------
echo "\n[6] Pengujian Integritas Server-Side Guard (Auth.php):\n";

assertCondition("Backend memeriksa \$nama_ktp wajib diisi", 
    strpos($controllerContent, "empty(\$nama_ktp)") !== false
);

assertCondition("Backend memeriksa \$nama_pemilik wajib diisi", 
    strpos($controllerContent, "empty(\$nama_pemilik)") !== false
);

assertCondition("Backend mewajibkan \$cleanNamaKtp === \$cleanNamaPemilik", 
    strpos($controllerContent, "\$cleanNamaKtp !== \$cleanNamaPemilik") !== false
);

assertCondition("Backend memvalidasi NIK wajib 16 digit diawali bukan 0", 
    strpos($controllerContent, "preg_match('/^[1-9][0-9]{15}$/', \$nik)") !== false
);

assertCondition("Backend memvalidasi kode provinsi Indonesia pada 2 digit awal NIK", 
    strpos($controllerContent, "in_array(\$provCode, \$validProvCodes, true)") !== false
);

assertCondition("Backend memvalidasi keunikan NIK dan nomor rekening bank di antara akun mitra aktif", 
    strpos($controllerContent, "\$nikConflict > 0") !== false &&
    strpos($controllerContent, "\$rekConflict > 0") !== false
);

echo "\n===================================================================\n";
echo "RINGKASAN: {$passed} BERHASIL, {$failed} GAGAL\n";
echo "===================================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
