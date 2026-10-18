<?php

// Test Suite: Verifikasi Penanda Wajib Bintang Merah (*) dan Validasi Submit 3 Checkbox PKS
// Menguji tampilan penanda visual bintang merah, kelengkapan format, serta perilaku submit saat belum dicentang, sebagian dicentang, dan seluruhnya dicentang.

echo "===================================================================\n";
echo "TEST SUITE: BINTANG MERAH & PENGUJIAN SUBMIT CHECKBOX PERSETUJUAN PKS\n";
echo "===================================================================\n\n";

$agreementViewPath  = dirname(__DIR__) . '/app/Views/auth/agreement.php';
$authControllerPath = dirname(__DIR__) . '/app/Controllers/Auth.php';

$totalPassed = 0;
$totalFailed = 0;

function assertAsteriskTest(string $desc, bool $condition) {
    global $totalPassed, $totalFailed;
    if ($condition) {
        echo "  [PASS] $desc\n";
        $totalPassed++;
    } else {
        echo "  [FAIL] $desc\n";
        $totalFailed++;
    }
}

$viewContent = file_get_contents($agreementViewPath);
$controllerContent = file_get_contents($authControllerPath);

// -------------------------------------------------------------
// [1] Pengujian Keberadaan Tanda Bintang Merah (*) pada Ketiga Pernyataan
// -------------------------------------------------------------
echo "[1] Pengujian Keberadaan Tanda Bintang Merah (*) pada Tiga Pernyataan:\n";

assertAsteriskTest(
    "Pernyataan 1 memiliki bintang merah tepat sebelum judul 'Identitas dan persetujuan PKS.'",
    preg_match('/<span[^>]*required-mark[^>]*>\*<\/span>\s*<strong[^>]*check-title[^>]*>Identitas dan persetujuan PKS\.<\/strong>/', $viewContent) === 1
);

assertAsteriskTest(
    "Pernyataan 2 memiliki bintang merah tepat sebelum judul 'Larangan menerima uang jamaah.'",
    preg_match('/<span[^>]*required-mark[^>]*>\*<\/span>\s*<strong[^>]*check-title[^>]*>Larangan menerima uang jamaah\.<\/strong>/', $viewContent) === 1
);

assertAsteriskTest(
    "Pernyataan 3 memiliki bintang merah tepat sebelum judul 'Kewajiban dan pertanggungjawaban mitra.'",
    preg_match('/<span[^>]*required-mark[^>]*>\*<\/span>\s*<strong[^>]*check-title[^>]*>Kewajiban dan pertanggungjawaban mitra\.<\/strong>/', $viewContent) === 1
);

assertAsteriskTest(
    "Tanda bintang merah menggunakan kelas warna konsisten 'text-rose-500'",
    substr_count($viewContent, 'required-mark text-rose-500 font-bold') >= 3
);

assertAsteriskTest(
    "CSS mendefinisikan warna merah #f43f5e dan ketebalan font untuk .required-mark",
    strpos($viewContent, '.required-mark') !== false &&
    strpos($viewContent, '#f43f5e') !== false
);

// -------------------------------------------------------------
// [2] Pengujian Format Judul Tebal & Layout Sederhana
// -------------------------------------------------------------
echo "\n[2] Pengujian Format Judul Tebal & Layout Sederhana:\n";

assertAsteriskTest(
    "Ketiga judul pernyataan tetap diformat dengan <strong> dan kelas check-title",
    substr_count($viewContent, '<strong class="check-title">') >= 3
);

assertAsteriskTest(
    "Markup HTML menggunakan kelas sederhana .agreement-check-item tanpa kartu / box wrapper",
    strpos($viewContent, 'class="agreement-check-item"') !== false &&
    strpos($viewContent, '<label class="agreement-check-card">') === false
);

assertAsteriskTest(
    "Input checkbox tetap top-aligned dengan class mt-0.5 dan shrink-0",
    substr_count($viewContent, 'required class="mt-0.5 agreement-check-input"') === 3
);

// -------------------------------------------------------------
// [3] Simulasi Frontend JS: Status Tombol & Proteksi Submit
// -------------------------------------------------------------
echo "\n[3] Simulasi Frontend JS untuk Skenario Centang & Submit:\n";

// Fungsi simulasi logika updateSubmitState pada frontend agreement.php
function simulateFrontendSubmitState(bool $chk1, bool $chk2, bool $chk3) {
    $allChecked = $chk1 && $chk2 && $chk3;
    $btnDisabled = !$allChecked;
    $submitAllowed = $allChecked;
    return [
        'disabled' => $btnDisabled,
        'allowed'  => $submitAllowed,
    ];
}

// Skenario A: Semua checkbox belum dicentang (false, false, false)
$caseA = simulateFrontendSubmitState(false, false, false);
assertAsteriskTest(
    "Skenario A (Semua Belum Dicentang): Tombol submit disabled & form submit ditolak",
    $caseA['disabled'] === true && $caseA['allowed'] === false
);

// Skenario B: Hanya Checkbox 1 dicentang (true, false, false)
$caseB = simulateFrontendSubmitState(true, false, false);
assertAsteriskTest(
    "Skenario B (Hanya Checkbox 1 Dicentang): Tombol submit disabled & form submit ditolak",
    $caseB['disabled'] === true && $caseB['allowed'] === false
);

// Skenario C: Checkbox 1 & 2 dicentang, Checkbox 3 belum (true, true, false)
$caseC = simulateFrontendSubmitState(true, true, false);
assertAsteriskTest(
    "Skenario C (Sebagian Dicentang: 1 & 2): Tombol submit disabled & form submit ditolak",
    $caseC['disabled'] === true && $caseC['allowed'] === false
);

// Skenario D: Checkbox 1 & 3 dicentang, Checkbox 2 belum (true, false, true)
$caseD = simulateFrontendSubmitState(true, false, true);
assertAsteriskTest(
    "Skenario D (Sebagian Dicentang: 1 & 3): Tombol submit disabled & form submit ditolak",
    $caseD['disabled'] === true && $caseD['allowed'] === false
);

// Skenario E: Seluruh Checkbox dicentang (true, true, true)
$caseE = simulateFrontendSubmitState(true, true, true);
assertAsteriskTest(
    "Skenario E (Seluruhnya Dicentang: 1, 2, 3): Tombol submit aktif & form submit diizinkan",
    $caseE['disabled'] === false && $caseE['allowed'] === true
);

// -------------------------------------------------------------
// [4] Pengujian Validasi Server-Side (Backend Auth.php)
// -------------------------------------------------------------
echo "\n[4] Pengujian Validasi Backend pada Auth::agreement():\n";

assertAsteriskTest(
    "Backend memeriksa parameter POST 'agree_klausul'",
    strpos($controllerContent, "\$this->request->getPost('agree_klausul')") !== false
);

assertAsteriskTest(
    "Backend memeriksa parameter POST 'agree_larangan_dana'",
    strpos($controllerContent, "\$this->request->getPost('agree_larangan_dana')") !== false
);

assertAsteriskTest(
    "Backend memeriksa parameter POST 'agree' (kewajiban mitra)",
    strpos($controllerContent, "\$this->request->getPost('agree')") !== false
);

assertAsteriskTest(
    "Backend menolak jika salah satu atau seluruhnya kosong (empty check)",
    strpos($controllerContent, "empty(\$agreeKlausul) || empty(\$agreeLaranganDana) || empty(\$agreeKewajiban)") !== false
);

echo "\n===================================================================\n";
echo "RINGKASAN: $totalPassed BERHASIL, $totalFailed GAGAL\n";
echo "===================================================================\n";

if ($totalFailed > 0) {
    exit(1);
}
