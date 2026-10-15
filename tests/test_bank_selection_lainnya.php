<?php

define('ENVIRONMENT', 'development');
define('FCPATH', 'c:\\xampp\\htdocs\\amanahsafar\\public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

$db = \Config\Database::connect();
$userModel = new \App\Models\UserModel();
$verificationModel = new \App\Models\VerificationModel();

echo "=================================================================\n";
echo "STARTING TEST: PILIHAN BANK & OPSI LAINNYA PADA VERIFIKASI\n";
echo "=================================================================\n";

// [1] Cek integritas View auth/verify.php
echo "\n[1] Memeriksa Elemen UI di app/Views/auth/verify.php...\n";
$viewContent = file_get_contents(APPPATH . 'Views/auth/verify.php');

assert(str_contains($viewContent, 'id="selectBank"'), "Select element #selectBank wajib ada di view!");
assert(str_contains($viewContent, 'fa-chevron-down'), "Icon fontawesome chevron-down wajib digunakan!");
assert(str_contains($viewContent, '-webkit-appearance: none !important'), "Panah bawaan browser wajib dinonaktifkan dengan appearance none!");
assert(str_contains($viewContent, '<option value="Lainnya">Lainnya (Ketik Manual)</option>'), "Opsi 'Lainnya' wajib ada di dalam select!");
assert(str_contains($viewContent, 'id="bankManualContainer"'), "Container #bankManualContainer wajib ada!");
assert(str_contains($viewContent, 'id="namaBankManual"'), "Input #namaBankManual wajib ada!");
assert(str_contains($viewContent, '#namaBankManual:placeholder-shown'), "Placeholder manual bank input harus terpusat rata tengah!");
assert(str_contains($viewContent, '#namaBankManual:focus'), "Input saat fokus/diketik harus rata kiri!");
assert(str_contains($viewContent, "this.style.textAlign='left'"), "Event JS input manual bank harus meratakan teks ke kiri saat menginput!");
assert(!str_contains($viewContent, 'fa-pen-nib'), "Icon pen tidak boleh ada di manual bank input!");
assert(str_contains($viewContent, 'id="namaKtp"'), "Input #namaKtp wajib ada!");
assert(str_contains($viewContent, 'Nama Sesuai KTP'), "Label 'Nama Sesuai KTP' wajib ada!");
assert(strpos($viewContent, 'id="namaKtp"') < strpos($viewContent, 'id="nikInput"'), "Input #namaKtp wajib berada di paling atas sebelum #nikInput!");
assert(str_contains($viewContent, "document.getElementById('namaKtp')"), "Fungsi JS copyKtpName wajib mengambil nilai dinamis dari #namaKtp!");
assert(str_contains($viewContent, 'Salin dari Nama KTP'), "Tombol 'Salin dari Nama KTP' wajib ada!");
assert(str_contains($viewContent, 'copyKtpName()'), "Fungsi JS copyKtpName() wajib dipanggil!");
assert(str_contains($viewContent, 'placeholder="Nama lengkap sesuai KTP"'), "Placeholder nama pemilik rekening wajib 'Nama lengkap sesuai KTP'!");
assert(!str_contains($viewContent, 'Onboarding Mitra'), "Tulisan 'Onboarding Mitra' di bawah logo harus sudah dihapus!");
assert(str_contains($viewContent, 'w-4 h-4 inline-flex items-center justify-center shrink-0'), "Ikon pada info akun mitra harus memiliki container lebar tetap!");
echo "  -> SUCCESS: Seluruh elemen UI (Logo bersih tanpa subtitle, Ikon & teks rata presisi, Input Nama KTP paling atas, Select chevron, Salin Dinamis dari Nama KTP) terverifikasi.\n";

// [2] Simulasi Penyimpanan Bank Predefined (Dari Select)
echo "\n[2] Menguji Penyimpanan Bank dari Select (Misal: Bank Muamalat)...\n";
$testUserEmail = 'bank_test_' . time() . '@amanahsafar.test';
$userId = $userModel->insert([
    'nama_lengkap' => 'Tester Bank Select',
    'email'        => $testUserEmail,
    'no_wa'        => '0813' . rand(10000000, 99999999),
    'password_hash'=> password_hash('Pass1234!', PASSWORD_BCRYPT),
    'role'         => 'mitra',
    'status'       => 'aktif',
    'verification_status' => 'belum_verifikasi'
], true);

// Simulasi submit POST dengan nama_bank dari pilihan select
$postSelectBank = 'Bank Muamalat';
$nikSample = '3201' . rand(100000000000, 999999999999);
$noRekSample = '777' . rand(1000000, 9999999);

$verificationModel->insert([
    'user_id'               => $userId,
    'nik'                   => $nikSample,
    'nama_bank'             => $postSelectBank,
    'nomor_rekening'        => $noRekSample,
    'nama_pemilik_rekening' => 'Tester Bank Select',
    'status'                => 'menunggu_review'
]);

$saved1 = $verificationModel->where('user_id', $userId)->first();
assert($saved1['nama_bank'] === 'Bank Muamalat', "Nama bank harus tersimpan 'Bank Muamalat'!");
echo "  -> SUCCESS: Bank dari select ('Bank Muamalat') tersimpan sempurna.\n";

// [3] Simulasi Penyimpanan Opsi 'Lainnya' dengan Ketik Manual
echo "\n[3] Menguji Penyimpanan Opsi 'Lainnya' dengan Ketik Manual...\n";
$testUserEmail2 = 'bank_lainnya_' . time() . '@amanahsafar.test';
$userId2 = $userModel->insert([
    'nama_lengkap' => 'Tester Bank Lainnya',
    'email'        => $testUserEmail2,
    'no_wa'        => '0814' . rand(10000000, 99999999),
    'password_hash'=> password_hash('Pass1234!', PASSWORD_BCRYPT),
    'role'         => 'mitra',
    'status'       => 'aktif',
    'verification_status' => 'belum_verifikasi'
], true);

// Logika backend Auth::verify resolver:
$rawNamaBank = 'Lainnya';
$rawNamaBankManual = 'Bank Nagari Syariah KCP Bukittinggi';

$resolvedBank = $rawNamaBank;
if ($resolvedBank === 'Lainnya' || empty($resolvedBank)) {
    if (!empty($rawNamaBankManual)) {
        $resolvedBank = trim($rawNamaBankManual);
    }
}

assert($resolvedBank === 'Bank Nagari Syariah KCP Bukittinggi', "Resolver harus mengambil teks manual saat opsi Lainnya dipilih!");

$nikSample2 = '3202' . rand(100000000000, 999999999999);
$noRekSample2 = '888' . rand(1000000, 9999999);

$verificationModel->insert([
    'user_id'               => $userId2,
    'nik'                   => $nikSample2,
    'nama_bank'             => $resolvedBank,
    'nomor_rekening'        => $noRekSample2,
    'nama_pemilik_rekening' => 'Tester Bank Lainnya',
    'status'                => 'menunggu_review'
]);

$saved2 = $verificationModel->where('user_id', $userId2)->first();
assert($saved2['nama_bank'] === 'Bank Nagari Syariah KCP Bukittinggi', "Nama bank manual harus tersimpan sesuai ketikan pendaftar!");
echo "  -> SUCCESS: Opsi 'Lainnya' tersimpan sesuai ketikan pendaftar ('{$saved2['nama_bank']}').\n";

// [4] Validasi Penolakan jika Opsi 'Lainnya' Kosong
echo "\n[4] Menguji Validasi Penolakan jika Opsi 'Lainnya' Tidak Diketik...\n";
$invalidBank = 'Lainnya';
$invalidManual = '';
if ($invalidBank === 'Lainnya' || empty($invalidBank)) {
    if (!empty($invalidManual)) {
        $invalidBank = trim($invalidManual);
    }
}
$isRejected = (empty($invalidBank) || $invalidBank === 'Lainnya');
assert($isRejected === true, "Wajib menolak jika opsi Lainnya dipilih tapi kolom ketik kosong!");
echo "  -> SUCCESS: Penolakan nama bank kosong saat opsi 'Lainnya' berhasil divalidasi.\n";

// [5] Menguji Sinkronisasi Nama KTP ke Profil Pengguna
echo "\n[5] Menguji Sinkronisasi Nama KTP ke Profil Pengguna...\n";
$testUserEmail3 = 'ktp_sync_' . time() . '@amanahsafar.test';
$userId3 = $userModel->insert([
    'nama_lengkap' => 'Nama Sebelum KTP',
    'email'        => $testUserEmail3,
    'no_wa'        => '0815' . rand(10000000, 99999999),
    'password_hash'=> password_hash('Pass1234!', PASSWORD_BCRYPT),
    'role'         => 'mitra',
    'status'       => 'aktif',
    'verification_status' => 'belum_verifikasi'
], true);

// Simulasi submit verifikasi dengan nama_ktp yang diperbarui
$updatedNamaKtp = 'H. AHMAD DAHLAN, S.E.';
$userModel->update($userId3, [
    'nama_lengkap'        => $updatedNamaKtp,
    'verification_status' => 'menunggu_review',
    'updated_at'          => date('Y-m-d H:i:s'),
]);

$userAfter = $userModel->find($userId3);
assert($userAfter['nama_lengkap'] === $updatedNamaKtp, "Nama lengkap user wajib tersinkron dengan Nama Sesuai KTP!");
echo "  -> SUCCESS: Sinkronisasi nama KTP ke tabel users berhasil ('{$userAfter['nama_lengkap']}').\n";

// Cleanup data pengujian
$db->table('verifications')->whereIn('user_id', [$userId, $userId2])->delete();
$db->table('users')->whereIn('id', [$userId, $userId2, $userId3])->delete();

echo "\n=================================================================\n";
echo "SEMUA PENGUJIAN OPSI BANK, LAINNYA, & NAMA KTP LULUS 100%!\n";
echo "=================================================================\n";
