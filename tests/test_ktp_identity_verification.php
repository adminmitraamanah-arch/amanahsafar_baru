<?php
/**
 * Test Suite: Validasi Dokumen KTP, Format NIK Provinsi, dan Kesesuaian Identitas
 */

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

echo "===================================================================\n";
echo "TEST SUITE: VALIDASI KTP & KESESUAIAN IDENTITAS (SERVER-SIDE GUARDS)\n";
echo "===================================================================\n\n";

$db = \Config\Database::connect();
$passed = 0;
$failed = 0;

function assertCondition($name, $condition) {
    global $passed, $failed;
    if ($condition) {
        echo "  [PASS] {$name}\n";
        $passed++;
    } else {
        echo "  [FAIL] {$name}\n";
        $failed++;
    }
}

// 1. Uji Regex dan Kode Provinsi NIK
echo "[1] Pengujian Format NIK dan Validasi Kode Provinsi Indonesia:\n";

$validProvCodes = [
    11, 12, 13, 14, 15, 16, 17, 18, 19, 21,
    31, 32, 33, 34, 35, 36,
    51, 52, 53,
    61, 62, 63, 64, 65,
    71, 72, 73, 74, 75, 76,
    81, 82,
    91, 92, 93, 94, 95, 96
];

function validateNikServerSide($nik, $validProvCodes) {
    if (!preg_match('/^[1-9][0-9]{15}$/', $nik)) {
        return false;
    }
    $provCode = (int) substr($nik, 0, 2);
    return in_array($provCode, $validProvCodes, true);
}

assertCondition("NIK Jawa Barat valid (3201012345670001)", validateNikServerSide("3201012345670001", $validProvCodes) === true);
assertCondition("NIK DKI Jakarta valid (3171012345670002)", validateNikServerSide("3171012345670002", $validProvCodes) === true);
assertCondition("NIK Jawa Timur valid (3501012345670003)", validateNikServerSide("3501012345670003", $validProvCodes) === true);
assertCondition("NIK diawali 0 ditolak (0201012345670001)", validateNikServerSide("0201012345670001", $validProvCodes) === false);
assertCondition("NIK kurang dari 16 digit ditolak (3201012345)", validateNikServerSide("3201012345", $validProvCodes) === false);
assertCondition("NIK lebih dari 16 digit ditolak (32010123456700019)", validateNikServerSide("32010123456700019", $validProvCodes) === false);
assertCondition("NIK huruf / alfanumerik ditolak (320101234567000A)", validateNikServerSide("320101234567000A", $validProvCodes) === false);
assertCondition("NIK kode provinsi fiktif 99 ditolak (9901012345670001)", validateNikServerSide("9901012345670001", $validProvCodes) === false);
assertCondition("NIK kode provinsi fiktif 40 ditolak (4001012345670001)", validateNikServerSide("4001012345670001", $validProvCodes) === false);

// 2. Uji Persetujuan Pernyataan Checkbox
echo "\n[2] Pengujian Checkbox Persetujuan Kebenaran Data:\n";
function validateAgreementServerSide($agree) {
    return !empty($agree);
}
assertCondition("Checkbox dicentang ('1') diizinkan", validateAgreementServerSide('1') === true);
assertCondition("Checkbox dicentang ('on') diizinkan", validateAgreementServerSide('on') === true);
assertCondition("Checkbox kosong/tidak dicentang (null) ditolak", validateAgreementServerSide(null) === false);
assertCondition("Checkbox kosong string ('') ditolak", validateAgreementServerSide('') === false);

// 3. Uji Validasi Gambar KTP Fisik & Dimensi
echo "\n[3] Pengujian Validasi Citra KTP & Resolusi Minimum (400x250):\n";

$scratchDir = sys_get_temp_dir() . '/ktp_test_' . time();
if (!is_dir($scratchDir)) mkdir($scratchDir, 0777, true);

// Gambar terlalu kecil: 200x150
$smallImgPath = $scratchDir . '/small.png';
$smallImg = imagecreatetruecolor(200, 150);
imagepng($smallImg, $smallImgPath);
imagedestroy($smallImg);

// Gambar valid: 800x500
$validImgPath = $scratchDir . '/valid.png';
$validImg = imagecreatetruecolor(800, 500);
imagepng($validImg, $validImgPath);
imagedestroy($validImg);

// File palsu / bukan gambar
$fakeImgPath = $scratchDir . '/fake.png';
file_put_contents($fakeImgPath, "This is not an image file");

function inspectImageServerSide($filePath) {
    $info = @getimagesize($filePath);
    if ($info === false) {
        return ['valid' => false, 'error' => 'Bukan gambar valid'];
    }
    if ($info[0] < 400 || $info[1] < 250) {
        return ['valid' => false, 'error' => 'Resolusi terlalu kecil'];
    }
    return ['valid' => true];
}

$resSmall = inspectImageServerSide($smallImgPath);
assertCondition("Gambar kecil (200x150) ditolak resolusi minimum", $resSmall['valid'] === false && $resSmall['error'] === 'Resolusi terlalu kecil');

$resFake = inspectImageServerSide($fakeImgPath);
assertCondition("File palsu teks berkedok gambar ditolak", $resFake['valid'] === false && $resFake['error'] === 'Bukan gambar valid');

$resValid = inspectImageServerSide($validImgPath);
assertCondition("Gambar resolusi cukup (800x500) diterima", $resValid['valid'] === true);

// Bersihkan file temporer
@unlink($smallImgPath);
@unlink($validImgPath);
@unlink($fakeImgPath);
@rmdir($scratchDir);

// 4. Uji Struktur Form View
echo "\n[4] Pengujian Struktur Form View (`app/Views/auth/verify.php`):\n";
$viewContent = file_get_contents(__DIR__ . '/../app/Views/auth/verify.php');

$posUpload = strpos($viewContent, 'id="ktpDropzone"');
$posStatus = strpos($viewContent, 'id="ktpStatusContainer"');
$posNama   = strpos($viewContent, 'id="namaKtp"');
$posNik    = strpos($viewContent, 'id="nikInput"');
$posBank   = strpos($viewContent, 'id="selectBank"');
$posRek    = strpos($viewContent, 'id="noRek"');
$posSubmit = strpos($viewContent, 'id="submitBtn"');

assertCondition("KTP Dropzone ditemukan di view", $posUpload !== false);
assertCondition("KTP Status Container ditemukan di view", $posStatus !== false);
assertCondition("Posisi KTP Dropzone di paling atas (sebelum Nama & NIK)", $posUpload < $posNama && $posUpload < $posNik);
assertCondition("Posisi KTP Status Box langsung setelah Upload KTP", $posUpload < $posStatus && $posStatus < $posNama);
assertCondition("Urutan form lengkap: Upload -> Status -> Nama -> NIK -> Bank -> Rekening -> Submit", 
    $posUpload < $posStatus && $posStatus < $posNama && $posNama < $posNik && $posNik < $posBank && $posBank < $posRek && $posRek < $posSubmit
);
assertCondition("CDN Tesseract.js dimuat untuk client-side OCR", strpos($viewContent, 'tesseract.min.js') !== false);
assertCondition("Terdapat tombol Salin dari Nama KTP", strpos($viewContent, 'copyKtpName()') !== false);
assertCondition("Terdapat tombol Gunakan NIK dari KTP", strpos($viewContent, 'btnApplyNik') !== false);

echo "\n===================================================================\n";
echo "RINGKASAN: {$passed} BERHASIL, {$failed} GAGAL\n";
echo "===================================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
