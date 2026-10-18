<?php

// Test Suite: Fitur & UI/UX Tampilan Dokumen PKS dalam Format PDF Penuh
// Menguji rute, controller endpoint (streaming & download), viewer modal, fallback, dan integritas persetujuan.

echo "===================================================================\n";
echo "TEST SUITE: FITUR TAMPILAN DOKUMEN PKS PDF PENUH\n";
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

$agreementViewPath  = dirname(__DIR__) . '/app/Views/auth/agreement.php';
$routesPath         = dirname(__DIR__) . '/app/Config/Routes.php';
$authControllerPath = dirname(__DIR__) . '/app/Controllers/Auth.php';
$pksServicePath     = dirname(__DIR__) . '/app/Libraries/PksPdfService.php';

$totalPassed = 0;
$totalFailed = 0;

function assertCheck(string $desc, bool $condition) {
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
// [1] Pengujian Pendaftaran Rute PDF Baru di Routes.php
// -------------------------------------------------------------
echo "[1] Pengujian Rute PDF di app/Config/Routes.php:\n";
$routesContent = file_get_contents($routesPath);

assertCheck("Rute GET '/agreement/pdf' terdaftar dengan controller Auth::agreementPdf",
    strpos($routesContent, "\$routes->get('/agreement/pdf', 'Auth::agreementPdf'") !== false
);

assertCheck("Rute GET '/agreement/pdf/download' terdaftar dengan controller Auth::agreementPdfDownload",
    strpos($routesContent, "\$routes->get('/agreement/pdf/download', 'Auth::agreementPdfDownload'") !== false
);

assertCheck("Rute dilindungi filter portalMaintenance",
    strpos($routesContent, "'Auth::agreementPdf', ['filter' => 'portalMaintenance']") !== false &&
    strpos($routesContent, "'Auth::agreementPdfDownload', ['filter' => 'portalMaintenance']") !== false
);

// -------------------------------------------------------------
// [2] Pengujian PksPdfService Dukungan Draft Pratinjau
// -------------------------------------------------------------
echo "\n[2] Pengujian PksPdfService Dukungan Draft Pratinjau:\n";
$pksServiceContent = file_get_contents($pksServicePath);

assertCheck("PksPdfService::generate menerima parameter \$allowDraft = false",
    strpos($pksServiceContent, 'public function generate(int $userId, bool $force = false, bool $allowDraft = false)') !== false
);

assertCheck("PksPdfService memeriksa \$allowDraft saat agreement_accepted_at kosong",
    strpos($pksServiceContent, 'empty($user[\'agreement_accepted_at\']) && !$allowDraft') !== false
);

// -------------------------------------------------------------
// [3] Pengujian Auth Controller Endpoint (agreementPdf & agreementPdfDownload)
// -------------------------------------------------------------
echo "\n[3] Pengujian Auth.php untuk Method agreementPdf & agreementPdfDownload:\n";
$authContent = file_get_contents($authControllerPath);

assertCheck("Auth controller memiliki method agreementPdf()",
    strpos($authContent, 'public function agreementPdf()') !== false
);

assertCheck("Auth controller memiliki method agreementPdfDownload()",
    strpos($authContent, 'public function agreementPdfDownload()') !== false
);

assertCheck("agreementPdf memvalidasi session login aktif",
    strpos($authContent, '$userId = (int) session(\'user_id\');') !== false
);

assertCheck("agreementPdf mencegah akses dari akun administratif",
    strpos($authContent, "in_array((string) \$user['role'], ['admin', 'super_admin', 'superadmin'], true)") !== false
);

assertCheck("agreementPdf memvalidasi data identitas pendaftar lengkap",
    strpos($authContent, "empty(\$user['nama_lengkap']) || empty(\$verification['nik']) || empty(\$user['domisili']) || empty(\$user['no_wa'])") !== false
);

assertCheck("agreementPdf mengembalikan response inline PDF",
    strpos($authContent, "'Content-Type', 'application/pdf'") !== false &&
    strpos($authContent, "'Content-Disposition', 'inline; filename=\"'") !== false
);

assertCheck("agreementPdfDownload mengembalikan response attachment download PDF",
    strpos($authContent, "'Content-Disposition', 'attachment; filename=\"'") !== false
);

assertCheck("agreementPdf memiliki fallback aman jika berkas PDF belum terbentuk",
    strpos($authContent, "return view('superadmin/verification_pks_print'") !== false
);

// -------------------------------------------------------------
// [4] Pengujian UI/UX Minimalis: Satu Tombol Tunggal Buka PKS Lengkap
// -------------------------------------------------------------
echo "\n[4] Pengujian UI/UX Minimalis (Satu Tombol Tunggal Buka PKS Lengkap):\n";
$agreementContent = file_get_contents($agreementViewPath);

assertCheck("Terdapat Tombol Tunggal 'Buka PKS Lengkap' (#btnOpenPdfModal)",
    strpos($agreementContent, 'id="btnOpenPdfModal"') !== false &&
    strpos($agreementContent, 'Buka PKS Lengkap') !== false
);

assertCheck("Tombol Buka PKS Lengkap menggunakan gaya tombol utama aplikasi (btn-brand-primary)",
    strpos($agreementContent, 'id="btnOpenPdfModal" class="btn-brand-primary"') !== false
);

assertCheck("Tombol Buka PKS Lengkap mempertahankan ikon PDF",
    strpos($agreementContent, '<i class="fas fa-file-pdf"></i>') !== false
);

assertCheck("Bagian pratinjau teks dan scrollbar internal sudah tidak tampil di area pembukaan",
    strpos($agreementContent, 'Salinan Teks Perjanjian') === false &&
    strpos($agreementContent, 'pks-document-scroll') === false
);

assertCheck("Kartu informasi PDF (pks-pdf-card) dan badge DOKUMEN RESMI PDF sudah tidak tampil",
    strpos($agreementContent, 'pks-pdf-card') === false &&
    strpos($agreementContent, 'DOKUMEN RESMI PDF') === false
);

assertCheck("Tombol sekunder pembuka (Buka di Tab Baru & Unduh PDF) di luar modal sudah tidak tampil",
    strpos($agreementContent, 'title="Buka PDF di tab baru peramban"') === false &&
    strpos($agreementContent, 'title="Unduh salinan berkas PDF ke perangkat"') === false
);

// -------------------------------------------------------------
// -------------------------------------------------------------
// [5] Pengujian Elemen Modal PDF Viewer Ringkas & Area Baca Luas
// -------------------------------------------------------------
echo "\n[5] Pengujian Elemen Modal PDF Viewer Ringkas & Area Baca Luas:\n";

assertCheck("Terdapat Modal Container (#pksPdfModal & #pksModalContainer)",
    strpos($agreementContent, 'id="pksPdfModal"') !== false &&
    strpos($agreementContent, 'id="pksModalContainer"') !== false
);

assertCheck("Modal memiliki Iframe PDF (#pksPdfIframe) dengan data-src mengarah ke /agreement/pdf",
    strpos($agreementContent, 'id="pksPdfIframe"') !== false &&
    strpos($agreementContent, 'data-src="<?= base_url(\'/agreement/pdf\') ?>"') !== false
);

assertCheck("Modal memiliki loading indicator (#pksPdfLoading)",
    strpos($agreementContent, 'id="pksPdfLoading"') !== false
);

assertCheck("Modal memiliki error state fallback & tombol coba muat ulang (#pksPdfErrorState & #btnRetryPdf)",
    strpos($agreementContent, 'id="pksPdfErrorState"') !== false &&
    strpos($agreementContent, 'id="btnRetryPdf"') !== false
);

assertCheck("Modal header ringkas hanya memiliki judul dan satu tombol Tutup (#btnClosePdfModal)",
    strpos($agreementContent, 'id="pksModalTitle"') !== false &&
    strpos($agreementContent, 'id="btnClosePdfModal"') !== false
);

assertCheck("Kontrol yang mengganggu (fullscreen, tab baru, unduh) sudah dibersihkan dari toolbar",
    strpos($agreementContent, 'id="btnToggleFullscreen"') === false &&
    strpos($agreementContent, 'Buka di tab baru') === false &&
    strpos($agreementContent, 'Unduh berkas PDF') === false
);

assertCheck("Teks petunjuk panjang dan notice banner kuning sudah dibersihkan dari bagian bawah viewer",
    strpos($agreementContent, 'id="pksEmbedFallback"') === false &&
    strpos($agreementContent, 'Gunakan kontrol peramban di dalam dokumen') === false
);

assertCheck("Modal memiliki tombol navigasi Selesai Membaca & Kembali (#btnDoneReadingPdf)",
    strpos($agreementContent, 'id="btnDoneReadingPdf"') !== false &&
    strpos($agreementContent, 'Selesai Membaca & Kembali ke Persetujuan') !== false
);

// -------------------------------------------------------------
// [6] Pengujian Interaktivitas JavaScript Modal Viewer
// -------------------------------------------------------------
echo "\n[6] Pengujian Interaktivitas JavaScript:\n";

assertCheck("JavaScript menangani pemuatan dan pembukaan modal (loadPdf & openPdfModal)",
    strpos($agreementContent, "btnOpenModal.addEventListener('click', openPdfModal)") !== false &&
    strpos($agreementContent, "function loadPdf()") !== false
);

assertCheck("JavaScript menangani penutupan modal (btnCloseModal.addEventListener)",
    strpos($agreementContent, "btnCloseModal.addEventListener('click', closePdfModal)") !== false
);

assertCheck("JavaScript menangani tombol Selesai Membaca dan scroll kembali ke form",
    strpos($agreementContent, "formSection.scrollIntoView({ behavior: 'smooth', block: 'start' })") !== false
);

assertCheck("JavaScript menangani tombol coba muat ulang (btnRetryPdf)",
    strpos($agreementContent, "btnRetryPdf.addEventListener('click'") !== false
);

assertCheck("JavaScript menangani penutupan modal via tombol Escape",
    strpos($agreementContent, "e.key === 'Escape'") !== false
);

// -------------------------------------------------------------
// [7] Pengujian Integritas Persetujuan & Checkbox
// -------------------------------------------------------------
echo "\n[7] Pengujian Integritas Persetujuan & Form Checkbox:\n";

assertCheck("3 Checkbox wajib tetap berada di formulir pendaftaran",
    strpos($agreementContent, 'name="agree_klausul"') !== false &&
    strpos($agreementContent, 'name="agree_larangan_dana"') !== false &&
    strpos($agreementContent, 'name="agree"') !== false
);

assertCheck("Ketiga checkbox diinisialisasi dalam keadaan unchecked",
    strpos($agreementContent, 'chk1.checked = false') !== false &&
    strpos($agreementContent, 'chk2.checked = false') !== false &&
    strpos($agreementContent, 'chk3.checked = false') !== false
);

assertCheck("Tombol submit tetap disabled sebelum ketiga checkbox dicentang",
    strpos($agreementContent, 'id="btnSubmitAgreement" class="btn-brand-primary" disabled') !== false
);

assertCheck("Formulir persetujuan memvalidasi submit event secara ketat",
    strpos($agreementContent, "form.addEventListener('submit'") !== false
);

echo "\n===================================================================\n";
echo "RINGKASAN: $totalPassed BERHASIL, $totalFailed GAGAL\n";
echo "===================================================================\n";

if ($totalFailed > 0) {
    exit(1);
}
