<?php

// Verification Test for Apache / LiteSpeed app/.htaccess virtual routing and security
echo "===================================================================\n";
echo "TEST: VERIFIKASI KEAMANAN & ROUTING .HTACCESS UNTUK ROLE MEMBER (APP)\n";
echo "===================================================================\n\n";

$baseUrl = 'http://localhost/amanahsafar/';

// 1. Pengujian Rute Virtual Menu Member (Cabang, Mitra, Manager)
echo "[1] Pengujian Rute Virtual Menu (/app/...) terhadap Web Server:\n";
$routes = [
    'app/dashboard'   => 'Dashboard Member',
    'app/packages'    => 'Katalog Paket',
    'app/binaan'      => 'Binaan',
    'app/orders'      => 'Pesanan',
    'app/commissions' => 'Mutasi & Komisi',
    'app/tasks'       => 'Tugas & Promosi',
];

$allRoutesPass = true;
foreach ($routes as $route => $name) {
    $url = $baseUrl . $route;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Pada kondisi unauthenticated, rute harus di-handle oleh CodeIgniter (HTTP 302 redirect ke /login)
    // dan TIDAK BOLEH ditolak oleh web server dengan HTTP 403 Forbidden
    if ($httpCode === 403) {
        echo "  [FAIL] $name ($route) -> HTTP 403 FORBIDDEN (Ditolak oleh Web Server!)\n";
        $allRoutesPass = false;
    } elseif ($httpCode === 302 || $httpCode === 200) {
        echo "  [PASS] $name ($route) -> HTTP $httpCode (Berhasil diproses oleh CodeIgniter)\n";
    } else {
        echo "  [INFO] $name ($route) -> HTTP $httpCode\n";
    }
}

// 2. Pengujian Keamanan File Fisik Sensitif di dalam Folder app/
echo "\n[2] Pengujian Keamanan File Fisik Nyata di dalam Folder app/:\n";
$sensitiveFiles = [
    'app/Config/Database.php'       => 'Konfigurasi Database',
    'app/Controllers/Auth.php'      => 'Controller Auth',
    'app/Models/UserModel.php'      => 'Model User',
    'app/Views/welcome_message.php' => 'View PHP',
];

$allSecurityPass = true;
foreach ($sensitiveFiles as $file => $desc) {
    $url = $baseUrl . $file;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 403) {
        echo "  [PASS] $desc ($file) -> HTTP 403 FORBIDDEN (Aman: Akses langsung diblokir)\n";
    } else {
        echo "  [FAIL] $desc ($file) -> HTTP $httpCode (Bahaya: File tidak diblokir dengan 403!)\n";
        $allSecurityPass = false;
    }
}

echo "\n===================================================================\n";
if ($allRoutesPass && $allSecurityPass) {
    echo "STATUS: SELURUH PENGUJIAN ROUTING & KEAMANAN LULUS 100%!\n";
    echo "===================================================================\n";
    exit(0);
} else {
    echo "STATUS: PENGUJIAN GAGAL!\n";
    echo "===================================================================\n";
    exit(1);
}
