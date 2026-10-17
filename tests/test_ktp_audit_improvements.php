<?php
/**
 * Test Suite: Pengujian Perbaikan Audit Toleransi KTP & Penanganan Deadlock
 * Memverifikasi:
 * 1. Adaptive Contrast Stretch pada Canvas (Tidak merusak foto redup)
 * 2. Pelepasan Blacklist Nama (Nama sah seperti Nurul Islam, Made Desa diterima)
 * 3. Dual-pass OCR Fallback (Pass kedua pada citra asli jika data kurang)
 * 4. Eliminasi Deadlock Submit (Graceful fallback saat OCR kurang jelas)
 * 5. Sinkronisasi Real-time Nama Manual ke Rekening
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
echo "TEST SUITE: PERBAIKAN AUDIT KTP, ADAPTIVE CONTRAST & DEADLOCK FIX\n";
echo "===================================================================\n\n";

// [1] Preprocessing Adaptif (Histogram Percentile Stretch)
echo "[1] Pengujian Preprocessing Canvas Adaptif:\n";
assertCondition("Canvas preprocessing tidak lagi memuat hard cutoff gray < 115", strpos($viewContent, 'gray < 115') === false);
assertCondition("Canvas preprocessing menggunakan histogram dinamis 256 bin", strpos($viewContent, 'new Uint32Array(256)') !== false);
assertCondition("Canvas preprocessing menggunakan lookup table (LUT) adaptif", strpos($viewContent, 'const lut = new Uint8Array(256)') !== false);

// [2] Dual-Pass OCR Fallback
echo "\n[2] Pengujian Dual-Pass OCR Fallback:\n";
assertCondition("Logika Dual-Pass fallback didefinisikan saat ekstraksi belum lengkap", 
    strpos($viewContent, '(!extracted.nik || !extracted.nama) && preprocessedCanvas') !== false
);
assertCondition("Pass kedua mencoba pembacaan langsung dari tempImg", 
    strpos($viewContent, 'Tesseract.recognize(tempImg, \'eng\')') !== false
);

// [3] Validasi Nama Sah Berisi Kata Umum (Nurul Islam, Made Desa, dll)
echo "\n[3] Pengujian Ekstraksi Nama Sah Mengandung Kata Agama/Status/Wilayah:\n";

$testScript = <<<'JS'
const fs = require('fs');
const viewContent = fs.readFileSync('app/Views/auth/verify.php', 'utf8');

const jsSnippet = viewContent.substring(
    viewContent.indexOf('const VALID_PROV_CODES = new Set'),
    viewContent.indexOf('// Algoritma Kemiripan Teks Sederhana')
);

eval(jsSnippet);

const testCases = [
    { text: "NIK : 3201012345670001\nNAMA : MOHAMMAD NURUL ISLAM", expected: "MOHAMMAD NURUL ISLAM" },
    { text: "NIK : 5101012345670001\nNAMA : I MADE DESA ARTHA", expected: "I MADE DESA ARTHA" },
    { text: "NIK : 3171012345670002\nNAMA : SITI KAWINDAH", expected: "SITI KAWINDAH" },
    { text: "AGAMA : ISLAM", expected: null },
    { text: "STATUS PERKAWINAN : KAWIN", expected: null },
    { text: "KEWARGANEGARAAN : WNI", expected: null }
];

let allOk = true;
for (const tc of testCases) {
    const res = extractNamaFromText(tc.text, tc.text.includes('3201') ? '3201012345670001' : (tc.text.includes('5101') ? '5101012345670001' : null));
    if (res !== tc.expected) {
        allOk = false;
        console.error(`Gagal pada [${tc.text}]: didapat [${res}], ekspektasi [${tc.expected}]`);
    }
}
process.exit(allOk ? 0 : 1);
JS;

$output = [];
$returnVar = 0;
exec("node -e " . escapeshellarg($testScript), $output, $returnVar);
assertCondition("Nama 'MOHAMMAD NURUL ISLAM', 'I MADE DESA ARTHA', 'SITI KAWINDAH' diterima dan header KTP ditolak", $returnVar === 0);

// [4] Graceful Fallback & Eliminasi Submit Deadlock
echo "\n[4] Pengujian Graceful Fallback & Eliminasi Submit Deadlock:\n";
assertCondition("Field NIK dibuka kuncinya (unlockField) jika NIK tidak terbaca otomatis", 
    strpos($viewContent, 'else if (nikInput) {\n                    unlockField(nikInput);') !== false ||
    strpos($viewContent, 'unlockField(nikInput)') !== false
);
assertCondition("Field Nama dibuka kuncinya jika Nama tidak terbaca otomatis", 
    strpos($viewContent, 'unlockField(namaInput)') !== false
);
assertCondition("Input manual nama pengguna disinkronkan ke rekening jika tidak terkunci", 
    strpos($viewContent, 'if (namaPemilikInput && !namaPemilikInput.readOnly)') !== false
);
assertCondition("Status 'Dokumen KTP Terunggah & Data Lengkap' aktif saat data dilengkapi manual", 
    strpos($viewContent, 'Dokumen KTP Terunggah & Data Lengkap') !== false &&
    strpos($viewContent, 'Data Lengkap') !== false
);

echo "\n===================================================================\n";
echo "RINGKASAN: {$passed} BERHASIL, {$failed} GAGAL\n";
echo "===================================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
