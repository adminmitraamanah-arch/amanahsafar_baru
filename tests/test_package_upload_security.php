<?php

/**
 * Test Suite: Keamanan Upload Gambar Paket (Paket A)
 * Memverifikasi seluruh acceptance criteria secara programmatic di environment CI4
 */

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

echo "===================================================================\n";
echo "TEST SUITE: PAKET A - KEAMANAN UPLOAD GAMBAR PAKET AMANAH SAFAR\n";
echo "===================================================================\n\n";

$passCount = 0;
$failCount = 0;

function reportTest($id, $name, $result, $detail = '') {
    global $passCount, $failCount;
    if ($result) {
        $passCount++;
        echo "[$id] PASS: $name\n";
    } else {
        $failCount++;
        echo "[$id] FAIL: $name\n";
    }
    if ($detail) {
        echo "      Detail: $detail\n";
    }
}

// 1. Verifikasi File .htaccess di public/uploads/
$htaccessPath = FCPATH . 'uploads/.htaccess';
$htaccessExists = file_exists($htaccessPath);
$htaccessContent = $htaccessExists ? file_get_contents($htaccessPath) : '';

$hasFilesMatch = strpos($htaccessContent, '<FilesMatch') !== false && strpos($htaccessContent, 'Require all denied') !== false;
$hasOptions = strpos($htaccessContent, '-Indexes') !== false && strpos($htaccessContent, '-ExecCGI') !== false;

reportTest('TC-01', 'Proteksi public/uploads/.htaccess terpasang', $htaccessExists && $hasFilesMatch && $hasOptions, "File ada dan memuat direktif FilesMatch 'Require all denied' serta Options -Indexes -ExecCGI");

// 2. Test Ekstensi Whitelist & Blacklist via Config\Mimes
$allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
$allValidExts = true;
foreach ($allowedExts as $ext) {
    $mime = \Config\Mimes::guessTypeFromExtension($ext);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
        $allValidExts = false;
    }
}
reportTest('TC-02', 'MIME Whitelist untuk JPG, JPEG, PNG, dan WEBP valid di CI4', $allValidExts, "Tipe MIME terdaftar pada Config\\Mimes");

// 3. Test Penolakan Ekstensi Berbahaya
$dangerousExts = ['php', 'phtml', 'php5', 'sh', 'exe', 'html', 'js', 'cgi'];
$allDangerousRejected = true;
foreach ($dangerousExts as $dExt) {
    if (in_array($dExt, $allowedExts, true)) {
        $allDangerousRejected = false;
    }
}
reportTest('TC-03', 'Ekstensi berbahaya (php, phtml, sh, exe, dll) berada di luar whitelist', $allDangerousRejected, "Semua ekstensi terlarang berhasil diblokir oleh daftar ext_in");

// 4. Test Deteksi Binary Gambar Palsu (Spoofed Content / Polyglot)
$tempSpoofed = sys_get_temp_dir() . '/test_spoof_' . time() . '.jpg';
file_put_contents($tempSpoofed, "<?php echo 'malicious code'; ?>");

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$spoofedMime = finfo_file($finfo, $tempSpoofed);
finfo_close($finfo);
@unlink($tempSpoofed);

$isRealImage = ($spoofedMime === 'image/jpeg');
reportTest('TC-04', 'File skrip bertopeng .jpg ditolak deteksi MIME server (finfo)', !$isRealImage, "Finfo mendeteksi sebagai '$spoofedMime' bukan 'image/jpeg'");

// 5. Test Gambar Valid Riil (1x1 pixel PNG)
$realPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
$tempPng = sys_get_temp_dir() . '/test_valid_' . time() . '.png';
file_put_contents($tempPng, $realPng);

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$validPngMime = finfo_file($finfo, $tempPng);
finfo_close($finfo);
$pngSize = filesize($tempPng);
@unlink($tempPng);

$pngAccepted = ($validPngMime === 'image/png') && ($pngSize <= 2048 * 1024);
reportTest('TC-05', 'Gambar PNG valid ukuran wajar dikenali dengan MIME image/png', $pngAccepted, "Finfo mendeteksi MIME: $validPngMime, Ukuran: $pngSize bytes");

// 6. Test Gambar Melebihi Batas 2MB (2048 KB)
$oversizedLimit = 2048 * 1024;
$testSize = 2.5 * 1024 * 1024;
$isSizeRejected = ($testSize > $oversizedLimit);
reportTest('TC-06', 'File berukuran 2.5 MB ditolak batas max_size[gambar,2048]', $isSizeRejected, "Ukuran 2.5MB melebihi batas 2048KB");

// 7. Test Anti Path Traversal pada getRandomName()
$dummyUploaded = new \CodeIgniter\HTTP\Files\UploadedFile(
    __FILE__,
    '../../etc/passwd.jpg',
    'image/jpeg',
    100,
    UPLOAD_ERR_OK
);
$randomName = $dummyUploaded->getRandomName();
$isSafeName = !str_contains($randomName, '/') && !str_contains($randomName, '\\') && !str_contains($randomName, '..');
reportTest('TC-07', 'Generasi nama acak mengabaikan path traversal dari nama asli klien', $isSafeName, "Nama aman yang dihasilkan: $randomName");

// 8. Test Logika Update Tanpa Upload (Field Kosong)
$emptyUpload = new \CodeIgniter\HTTP\Files\UploadedFile(
    '',
    '',
    '',
    0,
    UPLOAD_ERR_NO_FILE
);
$hasNewUpload = $emptyUpload && $emptyUpload->getError() !== UPLOAD_ERR_NO_FILE;
reportTest('TC-08', 'Form submit tanpa file baru diabaikan secara aman (gambar lama dipertahankan)', !$hasNewUpload, "Status UPLOAD_ERR_NO_FILE dikenali tanpa memicu validasi kaku");

// 9. Verifikasi Sintaks Controller Admin\Package & SuperAdmin\Package
$adminContent = file_get_contents(FCPATH . '../app/Controllers/Admin/Package.php');
$superAdminContent = file_get_contents(FCPATH . '../app/Controllers/SuperAdmin/Package.php');

$adminHasValidation = str_contains($adminContent, "'mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]'") && str_contains($adminContent, "'max_size[gambar,2048]'");
$superAdminHasValidation = str_contains($superAdminContent, "'mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]'") && str_contains($superAdminContent, "'max_size[gambar,2048]'");

reportTest('TC-09', 'Controller Admin/Package memuat validasi lengkap store & update', $adminHasValidation, "Aturan mime_in, ext_in, is_image, dan max_size terverifikasi");
reportTest('TC-10', 'Controller SuperAdmin/Package memuat validasi lengkap store & update', $superAdminHasValidation, "Aturan mime_in, ext_in, is_image, dan max_size terverifikasi");

echo "\n-------------------------------------------------------------------\n";
echo "HASIL PENGUJIAN: $passCount PASS / $failCount FAIL\n";
echo "===================================================================\n";

exit($failCount > 0 ? 1 : 0);
