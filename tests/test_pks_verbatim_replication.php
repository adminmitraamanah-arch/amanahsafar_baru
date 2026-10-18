<?php

// Test Verbatim Replication PKS Mitra Amanah
$truth = require __DIR__ . '/data/pks_reference_truth.php';

echo "=== MEMULAI PENGUJIAN VERBATIM PKS MITRA AMANAH (14 HALAMAN) ===\n\n";

$printViewPath = dirname(__DIR__) . '/app/Views/superadmin/verification_pks_print.php';
if (!file_exists($printViewPath)) {
    echo "FAIL: File verification_pks_print.php tidak ditemukan!\n";
    exit(1);
}

$printContent = file_get_contents($printViewPath);

// Normalisasi whitespace untuk komparasi teks
function cleanText(string $text): string {
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/<[^>]*>/', ' ', $text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = preg_replace('/\s+([.,;:!])/', '$1', $text);
    return trim($text);
}


$cleanPrint = cleanText($printContent);

$allPassed = true;
$totalChecked = 0;
$totalPassed = 0;

// 1. Uji setiap halaman dan seluruh paragrafnya
for ($p = 1; $p <= 14; $p++) {
    $pageData = $truth[$p] ?? null;
    if (!$pageData) {
        echo "FAIL: Data halaman $p tidak ada dalam ground truth!\n";
        $allPassed = false;
        continue;
    }

    echo "--- Memeriksa Halaman $p dari 14 ---\n";

    // Cek ID halaman
    $pageId = "id=\"page-$p\"";
    if (strpos($printContent, $pageId) === false) {
        echo "  [FAIL] Container Halaman $p ($pageId) tidak ditemukan!\n";
        $allPassed = false;
    } else {
        echo "  [OK] Container Halaman $p ditemukan.\n";
    }

    // Cek Running Footer
    $footerStr = "Halaman $p dari 14";
    if (strpos($printContent, $footerStr) === false) {
        echo "  [FAIL] Footer '$footerStr' tidak ditemukan di Halaman $p!\n";
        $allPassed = false;
    } else {
        echo "  [OK] Footer '$footerStr' terverifikasi.\n";
    }

    // Cek Running Header untuk Halaman 2-14
    if ($p > 1) {
        if (strpos($printContent, 'Perjanjian Kerja Sama Mitra Amanah') === false ||
            strpos($printContent, 'PT Amanah Tangguh Mandiri') === false) {
            echo "  [FAIL] Running Header tidak ditemukan di Halaman $p!\n";
            $allPassed = false;
        }
    }

    // Cek isi konten halaman
    $paragraphs = $pageData['content'] ?? [];
    foreach ($paragraphs as $idx => $item) {
        $totalChecked++;
        if (is_array($item)) {
            // Ini baris tabel matriks (Halaman 13)
            $kegiatan = cleanText($item['kegiatan']);
            $mitra = cleanText($item['mitra']);
            $perusahaan = cleanText($item['perusahaan']);

            $kOk = strpos($cleanPrint, $kegiatan) !== false;
            $mOk = strpos($cleanPrint, $mitra) !== false;
            $pOk = strpos($cleanPrint, $perusahaan) !== false;

            if ($kOk && $mOk && $pOk) {
                $totalPassed++;
                echo "  [OK] Matriks baris: {$item['kegiatan']}\n";
            } else {
                echo "  [FAIL] Matriks baris TIDAK COCOK: {$item['kegiatan']}\n";
                if (!$kOk) echo "    Missing kegiatan: $kegiatan\n";
                if (!$mOk) echo "    Missing mitra: $mitra\n";
                if (!$pOk) echo "    Missing perusahaan: $perusahaan\n";
                $allPassed = false;
            }
        } else {
            $expected = cleanText($item);
            if (strpos($cleanPrint, $expected) !== false) {
                $totalPassed++;
            } else {
                echo "  [FAIL] Paragraf TIDAK DITEMUKAN pada Halaman $p:\n";
                echo "    Expected: $expected\n";
                $allPassed = false;
            }
        }
    }
}

// 2. Periksa Seluruh 19 Pasal secara Eksplisit
echo "\n--- Memeriksa 19 Pasal Lengkap ---\n";
for ($pasal = 1; $pasal <= 19; $pasal++) {
    $pasalHeading = "PASAL $pasal";
    if (strpos($cleanPrint, $pasalHeading) !== false) {
        echo "  [OK] $pasalHeading terverifikasi.\n";
    } else {
        echo "  [FAIL] $pasalHeading TIDAK DITEMUKAN!\n";
        $allPassed = false;
    }
}

// 3. Periksa Lampiran 1 dan 2
echo "\n--- Memeriksa Lampiran 1 dan Lampiran 2 ---\n";
$lampiranChecks = [
    'LAMPIRAN 1',
    'RINGKASAN KEMITRAAN DAN KETENTUAN KOMISI',
    'LAMPIRAN 2',
    'KEWENANGAN DAN SOP MINIMUM',
    'Matriks kewenangan',
    'Alur minimum pelaksanaan',
    'Pengesahan Para Pihak',
    'PIHAK PERTAMA',
    'PT Amanah Safar',
    'M. Erwin Saputra',
    'Direktur Utama',
    'PIHAK KEDUA',
    'Mitra Amanah',
];

foreach ($lampiranChecks as $lc) {
    if (strpos($cleanPrint, cleanText($lc)) !== false) {
        echo "  [OK] $lc terverifikasi.\n";
    } else {
        echo "  [FAIL] $lc TIDAK DITEMUKAN!\n";
        $allPassed = false;
    }
}

echo "\n============================================\n";
echo "Ringkasan Validasi Verbatim Template Cetak:\n";
echo "Total Item Diperiksa: $totalChecked\n";
echo "Total Item Lulus    : $totalPassed\n";
if ($allPassed && $totalChecked === $totalPassed) {
    echo "STATUS: SEMUA 14 HALAMAN 100% VERBATIM LULUS!\n";
} else {
    echo "STATUS: ADA KETIDAKSESUAIAN!\n";
    exit(1);
}

// 4. Validasi Halaman Onboarding /agreement (app/Views/auth/agreement.php)
echo "\n=== MEMERIKSA VIEW ONBOARDING (app/Views/auth/agreement.php) ===\n";
$agreementViewPath = dirname(__DIR__) . '/app/Views/auth/agreement.php';
$agreementContent = file_get_contents($agreementViewPath);
$cleanAgreement = cleanText($agreementContent);

$agreementPassed = true;

// Validasi Tombol Tunggal Buka PKS Lengkap
if (strpos($agreementContent, 'id="btnOpenPdfModal"') !== false &&
    strpos($agreementContent, 'Buka PKS Lengkap') !== false &&
    strpos($agreementContent, 'fa-file-pdf') !== false) {
    echo "  [OK] agreement.php: Tombol Tunggal 'Buka PKS Lengkap' terverifikasi.\n";
} else {
    echo "  [FAIL] agreement.php: Tombol 'Buka PKS Lengkap' tidak ditemukan!\n";
    $agreementPassed = false;
}

// Validasi Penghapusan Scrollbar Teks Internal
if (strpos($agreementContent, 'pks-document-scroll') === false &&
    strpos($agreementContent, 'Salinan Teks Perjanjian') === false) {
    echo "  [OK] agreement.php: Area pembukaan bersih dari scrollbar pratinjau teks internal.\n";
} else {
    echo "  [FAIL] agreement.php: Masih terdapat scrollbar teks internal / salinan teks!\n";
    $agreementPassed = false;
}

// Validasi Modal PDF Viewer & Iframe Streaming
if (strpos($agreementContent, 'id="pksPdfModal"') !== false &&
    strpos($agreementContent, 'id="pksPdfIframe"') !== false &&
    strpos($agreementContent, '/agreement/pdf') !== false) {
    echo "  [OK] agreement.php: Modal PDF Viewer & Iframe streaming terverifikasi.\n";
} else {
    echo "  [FAIL] agreement.php: Modal PDF Viewer tidak lengkap!\n";
    $agreementPassed = false;
}

// Periksa 3 checkbox persetujuan elektronik (Pasal 18 Ayat 2)
if (strpos($agreementContent, 'name="agree_klausul"') !== false &&
    strpos($agreementContent, 'name="agree_larangan_dana"') !== false &&
    strpos($agreementContent, 'name="agree"') !== false) {
    echo "  [OK] agreement.php: 3 Checkbox Persetujuan Terpisah (Pasal 18 Ayat 2) terverifikasi.\n";
} else {
    echo "  [FAIL] agreement.php: Checkbox persetujuan tidak lengkap!\n";
    $agreementPassed = false;
}

// Validasi Tombol Submit disabled sampai checkbox dicentang
if (strpos($agreementContent, 'id="btnSubmitAgreement"') !== false &&
    strpos($agreementContent, 'disabled') !== false) {
    echo "  [OK] agreement.php: Tombol submit terlindungi (disabled secara default).\n";
} else {
    echo "  [FAIL] agreement.php: Tombol submit tidak terlindungi!\n";
    $agreementPassed = false;
}

if (!$agreementPassed) {
    echo "STATUS AGREEMENT.PHP: GAGAL!\n";
    exit(1);
}
echo "STATUS AGREEMENT.PHP: SEMUA LENGKAP & UI STREAMLINED LULUS!\n";

// 5. Validasi Fisik Berkas PDF Hasil Generate (Tepat 14 Halaman)
echo "\n=== MEMERIKSA GENERATE PDF FISIK VIA HEADLESS BROWSER ===\n";
$browserCandidates = [
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
];
$browserBin = null;
foreach ($browserCandidates as $b) {
    if (file_exists($b)) {
        $browserBin = $b;
        break;
    }
}

if (!$browserBin) {
    echo "  [WARN] Browser headless tidak ditemukan, melewati uji cetak PDF.\n";
} else {
    $testHtmlPath = sys_get_temp_dir() . '/test_pks_verbatim_' . uniqid() . '.html';
    $testPdfPath  = sys_get_temp_dir() . '/test_pks_verbatim_' . uniqid() . '.pdf';

    $user = [
        'id' => 1,
        'nama_lengkap' => 'MITRA AMANAH VERBATIM TEST',
        'domisili' => 'KOTA BOGOR',
        'agreement_accepted_at' => date('Y-m-d H:i:s'),
    ];
    $verification = ['nik' => '3271012345670001'];
    $pksNumber = 'PKS/AS/' . date('Ym') . '/0001';
    $hideToolbar = true;
    $isPdf = true;

    if (!function_exists('esc')) {
        function esc($d) { return htmlspecialchars((string)$d, ENT_QUOTES, 'UTF-8'); }
    }

    ob_start();
    require $printViewPath;
    $renderHtml = ob_get_clean();

    file_put_contents($testHtmlPath, $renderHtml);

    $cmd = escapeshellarg($browserBin)
        . ' --headless --disable-gpu --run-all-compositor-stages-before-draw --no-pdf-header-footer '
        . escapeshellarg('--print-to-pdf=' . $testPdfPath) . ' '
        . escapeshellarg($testHtmlPath);

    exec($cmd, $pdfOutput, $retCode);

    if (file_exists($testHtmlPath)) {
        @unlink($testHtmlPath);
    }

    if ($retCode === 0 && file_exists($testPdfPath) && filesize($testPdfPath) > 1000) {
        $pdfBytes = file_get_contents($testPdfPath);
        preg_match_all('/\/Type\s*\/Page\b(?!\s*s)/', $pdfBytes, $matches);
        $pageCount = count($matches[0]);
        $fileSize = filesize($testPdfPath);
        @unlink($testPdfPath);

        echo "  [OK] PDF fisik berhasil di-generate ($fileSize bytes).\n";
        echo "  [OK] Jumlah halaman PDF: $pageCount halaman.\n";

        if ($pageCount === 14) {
            echo "STATUS GENERATE PDF: TEPAT 14 HALAMAN (LULUS 100%)!\n";
        } else {
            echo "STATUS GENERATE PDF: GAGAL (Jumlah halaman: $pageCount, diharapkan: 14)!\n";
            exit(1);
        }
    } else {
        echo "  [FAIL] Gagal menghasilkan dokumen PDF fisik!\n";
        exit(1);
    }
}


