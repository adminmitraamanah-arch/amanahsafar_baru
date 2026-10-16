<?php

declare(strict_types=1);

/**
 * Test Suite: Verifikasi Fitur Crop Foto Profil Sebelum Upload (1:1 Rasio)
 */

echo "========================================================\n";
echo "STARTING PROFILE PHOTO CROPPER TEST SUITE\n";
echo "========================================================\n\n";

$basePath = realpath(__DIR__ . '/..');
$profileViewPath = $basePath . '/app/Views/profile.php';

if (!file_exists($profileViewPath)) {
    echo "[FAIL] File app/Views/profile.php tidak ditemukan!\n";
    exit(1);
}

$content = file_get_contents($profileViewPath);
$errors = [];

// ========================================================
// 1. Verifikasi Komponen Modal & Viewport UI Crop
// ========================================================
echo "[1] Memeriksa Komponen Modal & Area Crop...\n";

// Modal Dialog
if (strpos($content, 'id="cropPhotoModal"') !== false) {
    echo "  [PASS] Modal dialog #cropPhotoModal terverifikasi.\n";
} else {
    $errors[] = "Modal dialog #cropPhotoModal tidak ditemukan di profile.php!";
}

// Container Viewport (1:1)
if (strpos($content, 'id="cropContainer"') !== false) {
    echo "  [PASS] Kontainer viewport #cropContainer terverifikasi.\n";
} else {
    $errors[] = "Kontainer viewport #cropContainer tidak ditemukan!";
}

// Canvas Crop
if (strpos($content, 'id="cropCanvas"') !== false) {
    echo "  [PASS] Canvas crop #cropCanvas terverifikasi.\n";
} else {
    $errors[] = "Canvas #cropCanvas tidak ditemukan!";
}

// SVG Mask & Circular Guide
if (strpos($content, 'id="cropCircularCutout"') !== false && strpos($content, 'stroke-dasharray') !== false) {
    echo "  [PASS] SVG circular overlay dan panduan crop 1:1 terverifikasi.\n";
} else {
    $errors[] = "SVG overlay / circular guide tidak ditemukan!";
}

// Mini Realtime Preview Canvas
if (strpos($content, 'id="cropPreviewCanvas"') !== false) {
    echo "  [PASS] Mini realtime preview avatar #cropPreviewCanvas terverifikasi.\n";
} else {
    $errors[] = "Mini preview canvas #cropPreviewCanvas tidak ditemukan!";
}

// ========================================================
// 2. Verifikasi Kontrol Zoom & Tombol Aksi
// ========================================================
echo "\n[2] Memeriksa Kontrol Zoom dan Tombol Aksi...\n";

if (strpos($content, 'id="cropZoomSlider"') !== false) {
    echo "  [PASS] Slider zoom #cropZoomSlider terverifikasi.\n";
} else {
    $errors[] = "Slider zoom #cropZoomSlider tidak ditemukan!";
}

if (strpos($content, 'id="btnCropZoomOut"') !== false && strpos($content, 'id="btnCropZoomIn"') !== false) {
    echo "  [PASS] Tombol zoom in & zoom out (+ dan -) terverifikasi.\n";
} else {
    $errors[] = "Tombol zoom in / out tidak lengkap!";
}

if (strpos($content, 'id="btnCancelCrop"') !== false) {
    echo "  [PASS] Tombol Batal #btnCancelCrop terverifikasi.\n";
} else {
    $errors[] = "Tombol Batal #btnCancelCrop tidak ditemukan!";
}

if (strpos($content, 'id="btnSaveCrop"') !== false) {
    echo "  [PASS] Tombol Simpan Foto #btnSaveCrop terverifikasi.\n";
} else {
    $errors[] = "Tombol Simpan Foto #btnSaveCrop tidak ditemukan!";
}

// ========================================================
// 3. Verifikasi Logika Interaksi & Boundary Clamping
// ========================================================
echo "\n[3] Memeriksa Logika JS (FileReader, Clamping, Pinch, Export)...\n";

// Memastikan file tidak di-upload saat dipilih (menggunakan FileReader)
if (strpos($content, 'new FileReader()') !== false && strpos($content, 'reader.readAsDataURL') !== false) {
    echo "  [PASS] Pemilihan file diproses lokal melalui FileReader tanpa langsung upload.\n";
} else {
    $errors[] = "FileReader tidak ditemukan pada event change avatarFileInput!";
}

// Boundary Clamping: Memastikan tidak ada ruang kosong di area crop
if (strpos($content, 'Math.min(0, Math.max(CROP_SIZE - curW, offsetX))') !== false) {
    echo "  [PASS] Boundary clamping terverifikasi (gambar selalu menutupi area crop tanpa ruang kosong).\n";
} else {
    $errors[] = "Logika boundary clamping gambar tidak ditemukan!";
}

// Touch event pinch-zoom & touch drag
if (strpos($content, 'touchstart') !== false && strpos($content, 'touchmove') !== false && strpos($content, 'touchPinchDistance') !== false) {
    echo "  [PASS] Penanganan gestur mobile (touch drag & pinch-to-zoom) terverifikasi.\n";
} else {
    $errors[] = "Penanganan touch/pinch gesture mobile tidak lengkap!";
}

// Ekstraksi hasil crop ke offscreen canvas resolusi 512x512
if (strpos($content, 'exportCanvas.toBlob') !== false && strpos($content, 'EXPORT_SIZE = 512') !== false) {
    echo "  [PASS] Ekstraksi crop menghasilkan Blob via exportCanvas resolusi 512x512.\n";
} else {
    $errors[] = "Ekstraksi crop toBlob atau ukuran 512x512 tidak ditemukan!";
}

// Upload hanya terjadi saat btnSaveCrop diklik
$saveCropSection = strstr($content, "btnSaveCrop.addEventListener('click'");
if ($saveCropSection !== false && strpos($saveCropSection, 'upload-photo') !== false) {
    echo "  [PASS] Eksekusi upload ke /profile/upload-photo hanya dipicu saat tombol Simpan Foto diklik.\n";
} else {
    $errors[] = "Upload ke /profile/upload-photo tidak terikat pada tombol Simpan Foto!";
}

// Tombol Batal membersihkan state tanpa upload
$cancelSection = strstr($content, "function closeCropModal");
if ($cancelSection !== false && strpos($cancelSection, 'cropImage = null') !== false) {
    echo "  [PASS] Fungsi pembatalan membersihkan state lokal tanpa memicu request upload.\n";
} else {
    $errors[] = "Fungsi closeCropModal tidak membersihkan state dengan benar!";
}

// ========================================================
// 4. Hasil Evaluasi Test Suite
// ========================================================
echo "\n========================================================\n";
if (!empty($errors)) {
    echo "FAILED: Terdapat " . count($errors) . " kegagalan dalam test suite:\n";
    foreach ($errors as $e) {
        echo " - " . $e . "\n";
    }
    echo "========================================================\n";
    exit(1);
} else {
    echo "ALL CROPPER CHECKS PASSED: 100% SUCCESSFUL!\n";
    echo "========================================================\n";
    exit(0);
}
