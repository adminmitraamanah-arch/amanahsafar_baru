<?php
/**
 * Test Suite: Pengujian Toleransi Pembacaan & Ekstraksi Teks KTP
 * Memverifikasi:
 * 1. Pelonggaran Threshold Kualitas Citra (aspek rasio, luminance, resolusi)
 * 2. Preprocessing Canvas (Scale Normalization, Kontras S-Curve, Red Channel)
 * 3. Ekstraksi NIK Toleran (Spasi dalam digit, typo N1K/NlK/NIR, OCR char confusion)
 * 4. Ekstraksi Nama Toleran (Keyword variasi NAM A/N AM A/HAM A, separator ; . -, posisi struktural)
 * 5. Pemisahan Status Kegagalan (NIK terbaca tapi Nama belum, Nama terbaca tapi NIK belum, bukan KTP)
 * 6. Penolakan NIK Palsu / Format Tidak Valid (Zero False Acceptance)
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
echo "TEST SUITE: TOLERANSI PEMBACAAN & EKSTRAKSI TEKS KTP (OCR RECOVERY)\n";
echo "===================================================================\n\n";

// -------------------------------------------------------------
// [1] Pengujian Pelonggaran Threshold Kualitas Citra & Preprocessing
// -------------------------------------------------------------
echo "[1] Pengujian Pelonggaran Threshold Kualitas Citra & Preprocessing:\n";

assertCondition("Fungsi preprocessKtpCanvas didefinisikan di view", strpos($viewContent, 'function preprocessKtpCanvas(') !== false);
assertCondition("Tesseract menggunakan preprocessedCanvas jika tersedia", strpos($viewContent, 'ocrSource = preprocessedCanvas || tempImg') !== false);
assertCondition("Toleransi aspek rasio diperluas untuk foto smartphone (0.45 - 3.2)", strpos($viewContent, 'ratio < 0.45 || ratio > 3.2') !== false);
assertCondition("Toleransi kegelapan dilonggarkan ke avgLum < 20", strpos($viewContent, 'avgLum < 20') !== false);
assertCondition("Toleransi silau dilonggarkan ke avgLum > 252", strpos($viewContent, 'avgLum > 252') !== false);

// -------------------------------------------------------------
// [2] Pengujian Pemisahan Status Kegagalan Ekstraksi
// -------------------------------------------------------------
echo "\n[2] Pengujian Pemisahan Status Kegagalan Ekstraksi (Pesan Spesifik):\n";

assertCondition("Status spesifik ketika NIK terbaca namun Nama belum jelas", 
    strpos($viewContent, 'NIK Terbaca, Nama Belum Terbaca Jelas') !== false && 
    strpos($viewContent, 'Nama Belum Terbaca') !== false
);

assertCondition("Status spesifik ketika Nama terbaca namun NIK belum jelas", 
    strpos($viewContent, 'Nama Terbaca, NIK Belum Terbaca') !== false && 
    strpos($viewContent, 'NIK Belum Terbaca') !== false
);

assertCondition("Status ketika keduanya belum terbaca", 
    strpos($viewContent, 'KTP Terdeteksi Namun Teks Kurang Jelas') !== false && 
    strpos($viewContent, 'Teks Kurang Jelas') !== false
);

// -------------------------------------------------------------
// [3] Simulasi Kasus Nyata Ekstraksi NIK & Nama (Benchmark Sebelum vs Sesudah)
// -------------------------------------------------------------
echo "\n[3] Pengujian Simulasi Ekstraksi dengan Beragam Skenario KTP:\n";

// Ekstraksi logika JS yang diuji menggunakan Node CLI
$benchmarkScript = <<<'JS'
const fs = require('fs');
const viewContent = fs.readFileSync('app/Views/auth/verify.php', 'utf8');

// Ekstrak fungsi JS yang relevan dari view
const jsSnippet = viewContent.substring(
    viewContent.indexOf('const VALID_PROV_CODES = new Set'),
    viewContent.indexOf('// Algoritma Kemiripan Teks Sederhana')
);

// Evaluasi fungsi di runtime
eval(jsSnippet);

const scenarios = [
    {
        name: "Foto KTP Jelas (Ideal)",
        text: "PROVINSI JAWA BARAT\nKABUPATEN BOGOR\nNIK : 3201012345670001\nNAMA : AHMAD DAHLAN\nTEMPAT/TGL LAHIR : BOGOR, 12-05-1990",
        expectedNik: "3201012345670001",
        expectedNama: "AHMAD DAHLAN",
        allowFail: false
    },
    {
        name: "Foto Cukup Jelas tetapi Spasi di NIK & Semicolon di NAMA",
        text: "PROVINSI DKI JAKARTA\nJAKARTA SELATAN\nNIK : 317101 234567 0002\nNAMA; BUDI SANTOSO\nTEMPAT/TGL LAHIR : JAKARTA, 01-01-1985",
        expectedNik: "3171012345670002",
        expectedNama: "BUDI SANTOSO",
        allowFail: false
    },
    {
        name: "Foto Sedikit Miring / Typo N1K dan NAM A dengan Spasi Digit",
        text: "PROVINSI JAWA TIMUR\nKOTA SURABAYA\nN1K : 3501 0123 4567 0003\nNAM A : SITI AISYAH\nTEMPAT/TGL LAHIR : SURABAYA, 10-10-1992",
        expectedNik: "3501012345670003",
        expectedNama: "SITI AISYAH",
        allowFail: false
    },
    {
        name: "Foto Pencahayaan Kurang Ideal / OCR Huruf Tertukar (O dan l di NIK)",
        text: "PROVINSI JAWA TENGAH\nKABUPATEN BANYUMAS\nNIK : 3302Ol2345670OO4\nNAMA : JOKO WIDODO\nTEMPAT/TGL LAHIR : BANYUMAS, 21-06-1961",
        expectedNik: "3302012345670004",
        expectedNama: "JOKO WIDODO",
        allowFail: false
    },
    {
        name: "Foto Pantulan Cahaya di Label NAMA (Label hilang, posisi setelah NIK)",
        text: "PROVINSI SUMATERA UTARA\nKOTA MEDAN\nNIK : 1271012345670007\nMIRA SETIAWAN\nTEMPAT/TGL LAHIR : MEDAN, 20-11-1993",
        expectedNik: "1271012345670007",
        expectedNama: "MIRA SETIAWAN",
        allowFail: false
    },
    {
        name: "Foto Terpotong di Atas (NIK dan Nama ada, tapi header terpotong)",
        text: "NIK : 3273012345670008\nNAMA : RIZKY PRATAMA\nALAMAT : JL RAYA NO 10",
        expectedNik: "3273012345670008",
        expectedNama: "RIZKY PRATAMA",
        allowFail: false
    },
    {
        name: "Foto Benar-benar Buram (Hanya terbaca teks acak)",
        text: "—--- —---\n... ... ...\nxys zzz ???",
        expectedNik: null,
        expectedNama: null,
        allowFail: false
    },
    {
        name: "Dokumen Bukan KTP (Sertifikat / Kartu Keluarga)",
        text: "KARTU KELUARGA\nNO 3201012345670001\nKEPALA KELUARGA : SUTISNA",
        expectedNik: null, // bukan pola KTP
        expectedNama: null,
        allowFail: false
    },
    {
        name: "False Acceptance Test: NIK Fiktif Provinsi 99 (Wajib Ditolak)",
        text: "PROVINSI FIKTIF\nNIK : 9901012345670001\nNAMA : FAKE PERSON",
        expectedNik: null, // prov 99 tidak ada
        expectedNama: "FAKE PERSON",
        allowFail: false
    }
];

let results = [];
for (const sc of scenarios) {
    const res = extractKtpData(sc.text);
    const nikMatch = (res.nik === sc.expectedNik);
    const namaMatch = (res.nama === sc.expectedNama);
    results.push({
        name: sc.name,
        nikMatch,
        namaMatch,
        actualNik: res.nik,
        actualNama: res.nama,
        pass: nikMatch && (sc.expectedNama === null ? res.nama === null || res.nama === sc.expectedNama : namaMatch)
    });
}
console.log(JSON.stringify(results));
JS;

$tempJsFile = __DIR__ . '/../tests/temp_benchmark.cjs';
file_put_contents($tempJsFile, $benchmarkScript);
$nodeOutput = shell_exec("node {$tempJsFile}");
@unlink($tempJsFile);

if (!empty($nodeOutput)) {
    $benchmarkResults = json_decode($nodeOutput, true);
    if (is_array($benchmarkResults)) {
        foreach ($benchmarkResults as $br) {
            assertCondition("Skenario '{$br['name']}': NIK dan Nama sesuai target", $br['pass'] === true);
        }
    } else {
        echo "  [FAIL] Gagal memparsing output benchmark: {$nodeOutput}\n";
        $failed++;
    }
} else {
    echo "  [FAIL] Gagal mengeksekusi benchmark Node.js\n";
    $failed++;
}

echo "\n===================================================================\n";
echo "RINGKASAN: {$passed} BERHASIL, {$failed} GAGAL\n";
echo "===================================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
