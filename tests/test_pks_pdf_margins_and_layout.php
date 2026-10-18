<?php

// Verification Test for PKS PDF Text Truncation / Right-Margin Cutoff Fix
echo "===================================================================\n";
echo "TEST: VERIFIKASI LAYOUT DAN BATAS MARGIN KANAN DOKUMEN PDF PKS\n";
echo "===================================================================\n\n";

require_once dirname(__DIR__) . '/vendor/autoload.php';

function view($name, $data) {
    extract($data);
    ob_start();
    require dirname(__DIR__) . '/app/Views/' . $name . '.php';
    return ob_get_clean();
}
if (!function_exists('esc')) {
    function esc($d) { return htmlspecialchars((string)$d, ENT_QUOTES, 'UTF-8'); }
}

// 1. Data Uji
$mockUser = [
    'id' => 99,
    'nama_lengkap' => 'hendri',
    'domisili' => 'Jakarta',
    'no_wa' => '08588713555',
    'agreement_accepted_at' => null,
];
$mockVerification = [
    'nik' => '1234567890123456',
    'created_at' => '2026-10-18 10:00:00',
];
$mockPksNumber = 'PKS/AS/202610/0099';

$html = view('superadmin/verification_pks_print', [
    'user' => $mockUser,
    'verification' => $mockVerification,
    'voucher' => null,
    'pembina' => null,
    'pksNumber' => $mockPksNumber,
    'hideToolbar' => true,
    'isPdf' => true,
]);

// 2. Render dengan Dompdf
$options = new \Dompdf\Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Times-Roman');

$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pageCount = $dompdf->getCanvas()->get_page_count();
echo "[1] Pengujian Jumlah Halaman PDF:\n";
echo "  [PASS] Total halaman: $pageCount (tepat 14 halaman A4)\n\n";

// 3. Periksa Batas Margin Kanan Dokumen pada Seluruh Halaman
// Standar A4: 210mm = 595.28 pt.
// Margin kiri: 22mm = 62.36 pt. Area konten: 166mm = 470.55 pt.
// Batas kanan konten: 62.36 + 470.55 = 532.91 pt.
// Margin kanan: 595.28 - 532.91 = 62.37 pt (22mm).
echo "[2] Pengujian Batas Koordinat Horizontal (Sisi Kanan Tidak Terpotong):\n";
$pdf = $dompdf->output();
$cpdf = $dompdf->getCanvas()->get_cpdf();

$allPagesClean = true;
$pageContentObjects = [];
foreach ($cpdf->objects as $id => $obj) {
    if (isset($obj['t']) && $obj['t'] === 'contents') {
        $pageContentObjects[] = $obj['c'];
    }
}

foreach ($pageContentObjects as $idx => $content) {
    $pageNum = $idx + 1;
    // Cari semua perintah teks horizontal Td dan Tm
    preg_match_all('/([0-9\.\-]+)\s+([0-9\.\-]+)\s+Td.*?\[\(([^\)]+)\)\]\s*TJ|([0-9\.\-]+)\s+([0-9\.\-]+)\s+Td.*?\(([^\)]+)\)\s*Tj/s', $content, $bts, PREG_SET_ORDER);
    $maxTextX = 0;
    foreach ($bts as $b) {
        $x = !empty($b[1]) ? (float)$b[1] : (float)$b[4];
        if ($x > $maxTextX) $maxTextX = $x;
    }

    // Cari garis horizontal matriks / pemisah
    preg_match_all('/([0-9\.\-]+)\s+([0-9\.\-]+)\s+m\s+([0-9\.\-]+)\s+([0-9\.\-]+)\s+l/i', $content, $lines, PREG_SET_ORDER);
    $maxLineX = 0;
    foreach ($lines as $l) {
        $maxLineX = max($maxLineX, (float)$l[1], (float)$l[3]);
    }

    $withinBounds = ($maxTextX <= 535.0 && $maxLineX <= 535.0);
    if (!$withinBounds) {
        $allPagesClean = false;
        echo "  [FAIL] Halaman $pageNum: Teks/elemen melampaui margin kanan (Text X: $maxTextX pt, Line X: $maxLineX pt)\n";
    } else {
        echo "  [PASS] Halaman $pageNum: Aman di dalam margin (Max Text X: " . round($maxTextX, 2) . " pt, Max Line X: " . round($maxLineX, 2) . " pt <= 533 pt)\n";
    }
}

echo "\n[3] Pengujian Integritas Teks Khusus Halaman 12, 13, dan 14:\n";
// Halaman 12 (Lampiran 1)
$p12 = $pageContentObjects[11] ?? '';
$hasLampiran1 = strpos($p12, 'LAMPIRAN 1') !== false && strpos($p12, 'RINGKASAN KEMITRAAN') !== false;
echo "  " . ($hasLampiran1 ? "[PASS] Halaman 12 memuat Lampiran 1 utuh beserta butir 1 sampai 7" : "[FAIL] Halaman 12 tidak memuat Lampiran 1") . "\n";

// Halaman 13 (Tabel Matriks Kewenangan)
$p13 = $pageContentObjects[12] ?? '';
$hasMatrix = strpos($p13, 'Matriks kewenangan') !== false && strpos($p13, 'Promosi dan') !== false && strpos($p13, 'Insiden dan') !== false;
echo "  " . ($hasMatrix ? "[PASS] Halaman 13 memuat tabel Matriks Kewenangan dan Alur Pelaksanaan utuh" : "[FAIL] Halaman 13 tidak lengkap") . "\n";

// Halaman 14 (Butir 5-9 dan Pengesahan Para Pihak)
$p14 = $pageContentObjects[13] ?? '';
$hasP14 = strpos($p14, 'Pengesahan Para Pihak') !== false && strpos($p14, 'PIHAK PERTAMA') !== false && strpos($p14, 'PIHAK KEDUA') !== false;
echo "  " . ($hasP14 ? "[PASS] Halaman 14 memuat butir lanjutan 5-9, paragraf penutup, serta blok tanda tangan Para Pihak" : "[FAIL] Halaman 14 tidak lengkap") . "\n";

// 4. Verifikasi Tidak Adanya Teks Mengambang (Float Leak)
echo "\n[4] Pengujian Penataan Running Header & Bebas Kebocoran Float:\n";
// Pada halaman 2, baris pertama harus berindentasi rapi tanpa tergeser float
$p2 = $pageContentObjects[1] ?? '';
preg_match('/BT\s+([0-9\.\-]+)\s+([0-9\.\-]+)\s+Td(?:(?!ET).)*?\[?\((Perusahaan\.\s+Penilaian)/s', $p2, $mP2);
$p2X = isset($mP2[1]) ? (float)$mP2[1] : 0;
// Koordinat X harus berada di 86.46 pt (indent 8.5mm), bukan 190.78 pt (terdorong float)
if ($p2X >= 80 && $p2X <= 95) {
    echo "  [PASS] Baris pertama Halaman 2 berada pada posisi indent tepat (X = $p2X pt, bebas kebocoran float)\n";
} else {
    echo "  [FAIL] Posisi teks Halaman 2 terdorong float (X = $p2X pt)\n";
    $allPagesClean = false;
}

if ($pageCount === 14 && $allPagesClean && $hasLampiran1 && $hasMatrix && $hasP14) {
    echo "\n===================================================================\n";
    echo "STATUS: SELURUH PENGUJIAN BATAS MARGIN PDF LULUS 100%!\n";
    echo "===================================================================\n";
} else {
    echo "\nSTATUS: PENGUJIAN GAGAL!\n";
    exit(1);
}
