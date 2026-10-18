<?php

require __DIR__ . '/../app/Libraries/RegistrationWhatsApp.php';

use App\Libraries\RegistrationWhatsApp;

$checks = 0;
function check($condition, string $label): void
{
    global $checks;
    if (!$condition) {
        throw new RuntimeException($label);
    }
    $checks++;
}

foreach (['0812-3456-7890', '+62 812 3456 7890', '6281234567890', '006281234567890'] as $number) {
    check(RegistrationWhatsApp::normalizeNumber($number) === '6281234567890', 'Normalisasi nomor Indonesia');
}
check(RegistrationWhatsApp::normalizeNumber('+44 7700 900123') === '447700900123', 'Nomor internasional');
foreach (['', 'abc6281234567890', 'https://wa.me/6281234567890', '123', '+00012345678', '1234567890123456', '0812&text=x'] as $number) {
    try {
        RegistrationWhatsApp::normalizeNumber($number);
        throw new RuntimeException('Nomor invalid diterima: ' . $number);
    } catch (InvalidArgumentException $e) {
        $checks++;
    }
}
$user = ['nama_lengkap' => "Nur & Aisyah + أحمد", 'no_wa' => '081299999999', 'nik' => 'SECRET_NIK'];
$url = RegistrationWhatsApp::buildUrl('081234567890', $user, 'PKS/AS/202610/0042');
parse_str(parse_url($url, PHP_URL_QUERY), $query);
check(parse_url($url, PHP_URL_HOST) === 'wa.me', 'Tujuan tetap WhatsApp');
check(parse_url($url, PHP_URL_PATH) === '/6281234567890', 'Nomor tujuan');
check(str_contains($query['text'], "Nama: Nur & Aisyah + أحمد\n"), 'Encoding karakter dan newline');
check(str_contains($query['text'], 'Nomor PKS: PKS/AS/202610/0042'), 'Nomor PKS aktual');
check(!str_contains($url, 'SECRET_NIK'), 'NIK tidak disertakan');
check(!str_contains(urldecode(RegistrationWhatsApp::buildUrl('081234567890', $user, null)), 'Nomor PKS:'), 'PKS opsional');
check(str_starts_with(RegistrationWhatsApp::buildUrl('081298765432', $user, null), 'https://wa.me/6281298765432?'), 'Nomor berubah mengikuti input konfigurasi');
echo "PASS: {$checks} checks\n";
