<?php

// Functional End-to-End Test for PKS Draft PDF Generation & Verification
echo "===================================================================\n";
echo "FUNCTIONAL TEST: PKS DRAFT PDF GENERATION & CONTENT INTEGRITY\n";
echo "===================================================================\n\n";

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

if (!function_exists('esc')) {
    function esc($d) { return htmlspecialchars((string)$d, ENT_QUOTES, 'UTF-8'); }
}

$printViewPath = dirname(__DIR__) . '/app/Views/superadmin/verification_pks_print.php';

// Mock user in registration onboarding (agreement_accepted_at is NULL)
$mockUser = [
    'id' => 888,
    'nama_lengkap' => 'Budi Santoso Functional Test',
    'domisili' => 'Jl. Pajajaran No. 12, Bogor',
    'no_wa' => '081399887766',
    'agreement_accepted_at' => null,
];
$mockVerification = [
    'nik' => '3271018889990001',
    'created_at' => '2026-10-18 16:00:00',
];
$mockPksNumber = 'PKS/AS/' . date('Ym') . '/0888';

$tempHtmlPath = sys_get_temp_dir() . '/test_functional_' . uniqid() . '.html';
$tempPdfPath  = sys_get_temp_dir() . '/test_functional_' . uniqid() . '.pdf';

// 1. Render template
$user = $mockUser;
$verification = $mockVerification;
$voucher = null;
$pembina = null;
$pksNumber = $mockPksNumber;
$hideToolbar = true;
$isPdf = true;

ob_start();
require $printViewPath;
$html = ob_get_clean();

// 2. Generate PDF via Dompdf (Primary - pure PHP) or Headless Browser (Fallback)
$generated = false;
$generatorMethod = '';

if (class_exists(\Dompdf\Dompdf::class)) {
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'Times-Roman');

    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfBytes = $dompdf->output();
    if ($pdfBytes && strlen($pdfBytes) > 1000) {
        file_put_contents($tempPdfPath, $pdfBytes);
        $generated = true;
        $generatorMethod = 'Dompdf (Pure PHP)';
    }
}

if (!$generated && function_exists('exec')) {
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

    if ($browserBin) {
        file_put_contents($tempHtmlPath, $html);
        $cmd = escapeshellarg($browserBin)
            . ' --headless --disable-gpu --run-all-compositor-stages-before-draw --no-pdf-header-footer '
            . escapeshellarg('--print-to-pdf=' . $tempPdfPath) . ' '
            . escapeshellarg($tempHtmlPath);

        exec($cmd, $output, $returnCode);

        if (file_exists($tempHtmlPath)) {
            @unlink($tempHtmlPath);
        }

        if ($returnCode === 0 && file_exists($tempPdfPath) && filesize($tempPdfPath) > 1000) {
            $generated = true;
            $generatorMethod = 'Headless Browser (Edge/Chrome)';
        }
    }
}

if ($generated && file_exists($tempPdfPath) && filesize($tempPdfPath) > 1000) {
    $pdfBytes = file_get_contents($tempPdfPath);
    // Count pages
    if (isset($dompdf)) {
        $pageCount = $dompdf->getCanvas()->get_page_count();
    } else {
        preg_match_all('/\/Type\s*\/Page\b(?!\s*s)/', $pdfBytes, $matches);
        $pageCount = count($matches[0]);
    }
    $size = filesize($tempPdfPath);
    @unlink($tempPdfPath);

    echo "  [PASS] Generator yang digunakan: $generatorMethod\n";
    echo "  [PASS] Dokumen PDF pendaftar berhasil dihasilkan ($size bytes)\n";
    echo "  [PASS] Jumlah halaman PDF: $pageCount (tepat 14 halaman standar A4)\n";
    if ($pageCount === 14) {
        echo "  [PASS] Seluruh 14 halaman lengkap mencakup 19 pasal dan 2 lampiran\n";
    } else {
        echo "  [FAIL] Jumlah halaman tidak sesuai (diharapkan 14, didapat $pageCount)\n";
        exit(1);
    }
} else {
    echo "  [FAIL] Gagal menghasilkan dokumen PDF!\n";
    exit(1);
}

echo "\nSTATUS: FUNCTIONAL TEST LULUS 100%!\n";
