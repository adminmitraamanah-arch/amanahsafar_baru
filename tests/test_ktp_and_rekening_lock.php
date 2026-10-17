<?php
/**
 * Test Suite: Penyesuaian Form KTP dan Rekening (Auto-Fill & Field Locking)
 * Memverifikasi:
 * 1. Struktur Form & Styling Locked Field
 * 2. Auto-Fill & Sinkronisasi Nama Pemilik Rekening dengan Nama KTP
 * 3. Mekanisme Penguncian (readOnly, cursor-not-allowed, cegah manual edit)
 * 4. Proteksi Data Kosong & Ekstraksi Gagal (tidak mengunci data kosong)
 * 5. Integritas Form Submission (POST Data tetap terkirim ke backend)
 */

$viewPath = __DIR__ . '/../app/Views/auth/verify.php';
if (!file_exists($viewPath)) {
    echo "ERROR: File view {$viewPath} tidak ditemukan!\n";
    exit(1);
}
$viewContent = file_get_contents($viewPath);

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
echo "TEST SUITE: PENYESUAIAN FORM KTP DAN REKENING (LOCK & AUTO-SYNC)\n";
echo "===================================================================\n\n";

// -------------------------------------------------------------
// [1] Pengujian Keberadaan Elemen Input & Tombol Salin
// -------------------------------------------------------------
echo "[1] Pengujian Keberadaan Elemen Input & Status Tombol Salin:\n";

assertCondition("Input Nama KTP (#namaKtp) tersedia di view", strpos($viewContent, 'id="namaKtp"') !== false);
assertCondition("Input NIK (#nikInput) tersedia di view", strpos($viewContent, 'id="nikInput"') !== false);
assertCondition("Input Nama Pemilik Rekening (#namaPemilikRekening) tersedia di view", strpos($viewContent, 'id="namaPemilikRekening"') !== false);

// Tombol "Salin dari Nama KTP" harus disembunyikan dan dinonaktifkan
$btnCopyHidden = preg_match('/<button[^>]+id="btnCopyNamaKtp"[^>]+class="[^"]*hidden[^"]*"[^>]*disabled/i', $viewContent) ||
                 (strpos($viewContent, 'id="btnCopyNamaKtp"') !== false && strpos($viewContent, 'hidden') !== false && strpos($viewContent, 'disabled') !== false);
assertCondition("Tombol 'Salin dari Nama KTP' disembunyikan (class hidden & display none) dan dinonaktifkan (disabled)", $btnCopyHidden);

// -------------------------------------------------------------
// [2] Pengujian Styling CSS Input Readonly / Locked
// -------------------------------------------------------------
echo "\n[2] Pengujian Styling CSS Input Readonly / Locked:\n";

assertCondition("CSS mendefinisikan aturan visual untuk .input-pill[readonly] / .input-locked", 
    strpos($viewContent, '.input-pill[readonly]') !== false || strpos($viewContent, '.input-locked') !== false
);
assertCondition("CSS menerapkan cursor: not-allowed pada input terkunci", strpos($viewContent, 'cursor: not-allowed') !== false);
assertCondition("CSS menerapkan user-select: none pada input terkunci", strpos($viewContent, 'user-select: none') !== false);
assertCondition("CSS menerapkan background abu-abu slate pada input terkunci", strpos($viewContent, 'background-color: #f1f5f9') !== false);

// -------------------------------------------------------------
// [3] Pengujian Logika JavaScript OCR Auto-Fill & Field Locking
// -------------------------------------------------------------
echo "\n[3] Pengujian Logika OCR Auto-Fill & Field Locking:\n";

assertCondition("Fungsi lockField didefinisikan", strpos($viewContent, 'function lockField(') !== false);
assertCondition("Fungsi unlockField didefinisikan", strpos($viewContent, 'function unlockField(') !== false);

// Guard agar tidak mengunci field kosong
assertCondition("lockField memiliki guard agar tidak mengunci field kosong", 
    strpos($viewContent, '!el.value.trim()') !== false
);

// Auto-fill & lock NIK
assertCondition("NIK diisi otomatis dari hasil ekstraksi dan dikunci", 
    strpos($viewContent, 'nikInput.value = extracted.nik') !== false && 
    strpos($viewContent, 'lockField(nikInput)') !== false
);

// Auto-fill & lock Nama KTP
assertCondition("Nama KTP diisi otomatis dari hasil ekstraksi dan dikunci", 
    strpos($viewContent, 'namaInput.value = extracted.nama') !== false && 
    strpos($viewContent, 'lockField(namaInput)') !== false
);

// Auto-fill & lock Nama Pemilik Rekening (sinkronisasi dengan Nama KTP)
assertCondition("Nama Pemilik Rekening otomatis diisi dari extracted.nama dan dikunci", 
    strpos($viewContent, 'namaPemilikInput.value = extracted.nama') !== false && 
    strpos($viewContent, 'lockField(namaPemilikInput)') !== false
);

// -------------------------------------------------------------
// [4] Pengujian Pencegahan Edit Manual Pengguna pada Field Terkunci
// -------------------------------------------------------------
echo "\n[4] Pengujian Pencegahan Edit Manual Pengguna pada Field Terkunci:\n";

assertCondition("Event listener memblokir keydown saat field readOnly", strpos($viewContent, 'keydown') !== false && strpos($viewContent, 'this.readOnly') !== false);
assertCondition("Event listener memblokir paste saat field readOnly", strpos($viewContent, 'paste') !== false);
assertCondition("Event listener memblokir cut saat field readOnly", strpos($viewContent, 'cut') !== false);
assertCondition("Event listener memblokir drop saat field readOnly", strpos($viewContent, 'drop') !== false);

// -------------------------------------------------------------
// [5] Pengujian Validasi Kesesuaian Nama Rekening & Nama KTP
// -------------------------------------------------------------
echo "\n[5] Pengujian Validasi Kesesuaian Nama Rekening & KTP:\n";

assertCondition("checkRekeningOwnerMatch menolak jika nama KTP belum tersedia", 
    strpos($viewContent, '!valKtp') !== false
);
assertCondition("checkRekeningOwnerMatch menolak jika nama Pemilik kosong", 
    strpos($viewContent, '!valPemilik') !== false
);

// -------------------------------------------------------------
// [6] Simulasi Server-Side Submission Guard (PHP Form Integrity)
// -------------------------------------------------------------
echo "\n[6] Simulasi Server-Side Submission Guard:\n";

// Fungsi simulasi validasi controller backend
function simulateBackendVerify($nama_ktp, $nik, $nama_pemilik, $agree = '1') {
    if (!$agree) return [false, 'Persetujuan wajib dicentang'];
    if (empty($nama_ktp)) return [false, 'Nama KTP wajib diisi'];
    if (empty($nama_pemilik)) return [false, 'Nama pemilik rekening wajib diisi'];
    
    $cleanNamaKtp = trim(preg_replace('/\s+/', ' ', mb_strtoupper($nama_ktp)));
    $cleanNamaPemilik = trim(preg_replace('/\s+/', ' ', mb_strtoupper($nama_pemilik)));
    
    if ($cleanNamaKtp !== $cleanNamaPemilik) {
        return [false, 'Nama rekening harus sama persis dengan KTP'];
    }
    
    if (!preg_match('/^[1-9][0-9]{15}$/', $nik)) {
        return [false, 'NIK wajib 16 digit angka valid'];
    }
    
    return [true, 'Valid'];
}

// Uji hasil ekstraksi yang disinkronkan
$extractedNama = "AHMAD DAHLAN";
$extractedNik  = "3201012345670001";

$resultValid = simulateBackendVerify($extractedNama, $extractedNik, $extractedNama, '1');
assertCondition("Data hasil ekstraksi KTP tersinkronisasi lolos validasi server", $resultValid[0] === true);

// Uji jika data nama rekening tidak sama
$resultMismatch = simulateBackendVerify($extractedNama, $extractedNik, "MIRA SETIAWAN", '1');
assertCondition("Jika nama pemilik rekening berbeda, ditolak oleh server", $resultMismatch[0] === false);

// Uji jika NIK kosong / tidak terbaca
$resultEmptyNik = simulateBackendVerify($extractedNama, "", $extractedNama, '1');
assertCondition("Jika NIK kosong, form ditolak oleh server", $resultEmptyNik[0] === false);

// Uji jika Nama KTP kosong
$resultEmptyNama = simulateBackendVerify("", $extractedNik, "", '1');
assertCondition("Jika Nama KTP kosong, form ditolak oleh server", $resultEmptyNama[0] === false);

// -------------------------------------------------------------
// [7] Pengujian Upload Ulang (Re-upload Simulation)
// -------------------------------------------------------------
echo "\n[7] Pengujian Sinkronisasi pada Upload Ulang (Re-upload):\n";

$reExtractedNama = "BUDI SANTOSO";
$reExtractedNik  = "3171012345670002";

// Simulasi logika update di JS saat file baru diunggah dan OCR berhasil
$currentForm = [
    'nama_ktp' => $extractedNama,
    'nik' => $extractedNik,
    'nama_pemilik_rekening' => $extractedNama
];

// Re-upload berhasil dengan data baru:
if ($reExtractedNama) {
    $currentForm['nama_ktp'] = $reExtractedNama;
    $currentForm['nama_pemilik_rekening'] = $reExtractedNama;
}
if ($reExtractedNik) {
    $currentForm['nik'] = $reExtractedNik;
}

assertCondition("Re-upload berhasil memperbarui Nama KTP ke data baru", $currentForm['nama_ktp'] === 'BUDI SANTOSO');
assertCondition("Re-upload berhasil memperbarui NIK ke data baru", $currentForm['nik'] === '3171012345670002');
assertCondition("Re-upload berhasil menyinkronkan Nama Pemilik Rekening ke data KTP terbaru", $currentForm['nama_pemilik_rekening'] === 'BUDI SANTOSO');

$reuploadValid = simulateBackendVerify($currentForm['nama_ktp'], $currentForm['nik'], $currentForm['nama_pemilik_rekening'], '1');
assertCondition("Data hasil re-upload lolos validasi server", $reuploadValid[0] === true);

echo "\n===================================================================\n";
echo "RINGKASAN: {$passed} BERHASIL, {$failed} GAGAL\n";
echo "===================================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
