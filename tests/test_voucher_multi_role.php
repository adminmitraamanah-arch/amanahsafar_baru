<?php

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

$db = \Config\Database::connect();
$userModel = new \App\Models\UserModel();
$voucherModel = new \App\Models\VoucherModel();

echo "===============================================\n";
echo "STARTING VOUCHER MULTI-ROLE END-TO-END TEST\n";
echo "===============================================\n";

$createdUserIds = [];
$createdVoucherIds = [];

try {
    // 1. Uji Penerbitan Voucher Cabang (Pembina: Pusat ID 5)
    echo "\n[1] Menerbitkan Voucher Cabang (Pembina: Pusat ID 5)...\n";
    $cbgRes = $voucherModel->generateStructuredVouchers('cabang', 5, 1, null, 1);
    assert($cbgRes['status'] === true, 'Gagal menerbitkan voucher Cabang: ' . ($cbgRes['message'] ?? ''));
    $cbgCode = $cbgRes['generatedCodes'][0];
    assert(str_starts_with($cbgCode, 'CBG-PS-001-'), "Kode voucher Cabang wajib ber-prefix CBG-PS-001-: got $cbgCode");
    $cbgVoucher = $voucherModel->findByKode($cbgCode);
    $createdVoucherIds[] = $cbgVoucher['id'];
    assert($cbgVoucher['role_tujuan'] === 'cabang', "Role tujuan voucher wajib 'cabang'");
    assert((int)$cbgVoucher['pembina_id'] === 5, "Pembina ID voucher wajib 5");
    echo "  -> SUCCESS: Kode $cbgCode (Role: {$cbgVoucher['role_tujuan']}, Pembina: {$cbgVoucher['pembina_id']})\n";

    // 2. Uji Penerbitan Voucher Manager Mitra (Pembina: Cabang ID 6)
    echo "\n[2] Menerbitkan Voucher Manager Mitra (Pembina: Cabang ID 6)...\n";
    $mgrRes = $voucherModel->generateStructuredVouchers('manager_mitra', 6, 1, null, 1);
    assert($mgrRes['status'] === true, 'Gagal menerbitkan voucher Manager Mitra: ' . ($mgrRes['message'] ?? ''));
    $mgrCode = $mgrRes['generatedCodes'][0];
    assert(str_starts_with($mgrCode, 'MGR-CB-001-'), "Kode voucher Manager Mitra wajib ber-prefix MGR-CB-001-: got $mgrCode");
    $mgrVoucher = $voucherModel->findByKode($mgrCode);
    $createdVoucherIds[] = $mgrVoucher['id'];
    assert($mgrVoucher['role_tujuan'] === 'manager_mitra', "Role tujuan voucher wajib 'manager_mitra'");
    assert((int)$mgrVoucher['pembina_id'] === 6, "Pembina ID voucher wajib 6");
    echo "  -> SUCCESS: Kode $mgrCode (Role: {$mgrVoucher['role_tujuan']}, Pembina: {$mgrVoucher['pembina_id']})\n";

    // 3. Uji Penerbitan Voucher Mitra (Pembina: Manager ID 7)
    echo "\n[3] Menerbitkan Voucher Mitra (Pembina: Manager ID 7)...\n";
    $mitRes = $voucherModel->generateStructuredVouchers('mitra', 7, 1, null, 1);
    assert($mitRes['status'] === true, 'Gagal menerbitkan voucher Mitra: ' . ($mitRes['message'] ?? ''));
    $mitCode = $mitRes['generatedCodes'][0];
    assert(str_starts_with($mitCode, 'MIT-MG-001-'), "Kode voucher Mitra wajib ber-prefix MIT-MG-001-: got $mitCode");
    $mitVoucher = $voucherModel->findByKode($mitCode);
    $createdVoucherIds[] = $mitVoucher['id'];
    assert($mitVoucher['role_tujuan'] === 'mitra', "Role tujuan voucher wajib 'mitra'");
    assert((int)$mitVoucher['pembina_id'] === 7, "Pembina ID voucher wajib 7");
    echo "  -> SUCCESS: Kode $mitCode (Role: {$mitVoucher['role_tujuan']}, Pembina: {$mitVoucher['pembina_id']})\n";

    // 4. Uji Penolakan Hierarki Ilegal
    echo "\n[4] Pengujian Validasi Hierarki Ilegal...\n";
    $invalid1 = $voucherModel->generateStructuredVouchers('cabang', 7, 1, null, 1);
    assert($invalid1['status'] === false, "Penerbitan voucher Cabang dengan pembina Manager harus ditolak");
    echo "  -> SUCCESS: Cabang dengan pembina Manager berhasil ditolak ({$invalid1['message']})\n";

    $invalid2 = $voucherModel->generateStructuredVouchers('manager_mitra', 8, 1, null, 1); // ID 8 is Mitra
    assert($invalid2['status'] === false, "Penerbitan voucher Manager dengan pembina Mitra harus ditolak");
    echo "  -> SUCCESS: Manager dengan pembina Mitra berhasil ditolak ({$invalid2['message']})\n";

    // 5. Uji Registrasi dengan Voucher Cabang
    echo "\n[5] Simulasi Registrasi User Baru dengan Voucher Cabang ($cbgCode)...\n";
    $cbgCheck = $voucherModel->validateForRegistration($cbgCode);
    assert($cbgCheck['valid'] === true, "Validasi voucher Cabang gagal: " . $cbgCheck['message']);

    $db->transStart();
    $lockedCbg = $db->query("SELECT * FROM vouchers WHERE id = ? FOR UPDATE", [$cbgVoucher['id']])->getRowArray();
    assert($lockedCbg['status'] === 'tersedia');
    $cbgTargetRole = $lockedCbg['role_tujuan'];
    $cbgPembinaId = $lockedCbg['pembina_id'];

    $testCbgId = $userModel->insert([
        'nama_lengkap' => 'Unit Test Cabang Baru',
        'email' => 'unittest_cabang_' . time() . '@test.com',
        'no_wa' => '089999' . rand(100000, 999999),
        'domisili' => 'Kota Uji Coba',
        'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
        'role' => $cbgTargetRole,
        'pembina_id' => $cbgPembinaId,
        'status' => 'aktif',
        'verification_status' => 'belum_verifikasi'
    ]);
    assert($testCbgId > 0, "Gagal membuat user cabang");
    $createdUserIds[] = $testCbgId;

    if (in_array($cbgTargetRole, ['cabang', 'manager_mitra'])) {
        $kodePembinaAssigned = $userModel->getOrAssignKodePembina($testCbgId);
        assert(!empty($kodePembinaAssigned), "Cabang baru wajib memiliki kode_pembina");
    }

    $claimedCbg = $voucherModel->claimAtomically($cbgVoucher['id'], $testCbgId);
    assert($claimedCbg === true, "Gagal klaim voucher Cabang");
    $db->transComplete();

    $newCbgUser = $userModel->find($testCbgId);
    assert($newCbgUser['role'] === 'cabang', "Role user baru wajib 'cabang'");
    assert((int)$newCbgUser['pembina_id'] === 5, "Pembina ID user baru wajib 5 (Pusat)");
    $claimedCbgRow = $voucherModel->find($cbgVoucher['id']);
    assert($claimedCbgRow['status'] === 'terpakai', "Status voucher wajib 'terpakai'");
    assert((int)$claimedCbgRow['used_by'] === (int)$testCbgId, "used_by voucher wajib mencatat user id");
    echo "  -> SUCCESS: User ID $testCbgId berhasil terdaftar dengan Role: {$newCbgUser['role']}, Pembina ID: {$newCbgUser['pembina_id']}, Kode Pembina: {$newCbgUser['kode_pembina']}\n";

    // 6. Uji Registrasi dengan Voucher Manager Mitra
    echo "\n[6] Simulasi Registrasi User Baru dengan Voucher Manager ($mgrCode)...\n";
    $mgrCheck = $voucherModel->validateForRegistration($mgrCode);
    assert($mgrCheck['valid'] === true, "Validasi voucher Manager gagal: " . $mgrCheck['message']);

    $db->transStart();
    $lockedMgr = $db->query("SELECT * FROM vouchers WHERE id = ? FOR UPDATE", [$mgrVoucher['id']])->getRowArray();
    $mgrTargetRole = $lockedMgr['role_tujuan'];
    $mgrPembinaId = $lockedMgr['pembina_id'];

    $testMgrId = $userModel->insert([
        'nama_lengkap' => 'Unit Test Manager Baru',
        'email' => 'unittest_mgr_' . time() . '@test.com',
        'no_wa' => '089998' . rand(100000, 999999),
        'domisili' => 'Kota Uji Coba',
        'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
        'role' => $mgrTargetRole,
        'pembina_id' => $mgrPembinaId,
        'status' => 'aktif',
        'verification_status' => 'belum_verifikasi'
    ]);
    assert($testMgrId > 0, "Gagal membuat user manager");
    $createdUserIds[] = $testMgrId;

    if (in_array($mgrTargetRole, ['cabang', 'manager_mitra'])) {
        $kodePembinaAssigned = $userModel->getOrAssignKodePembina($testMgrId);
        assert(!empty($kodePembinaAssigned), "Manager baru wajib memiliki kode_pembina");
    }

    $claimedMgr = $voucherModel->claimAtomically($mgrVoucher['id'], $testMgrId);
    assert($claimedMgr === true, "Gagal klaim voucher Manager");
    $db->transComplete();

    $newMgrUser = $userModel->find($testMgrId);
    assert($newMgrUser['role'] === 'manager_mitra', "Role user baru wajib 'manager_mitra'");
    assert((int)$newMgrUser['pembina_id'] === 6, "Pembina ID user baru wajib 6 (Cabang)");
    echo "  -> SUCCESS: User ID $testMgrId berhasil terdaftar dengan Role: {$newMgrUser['role']}, Pembina ID: {$newMgrUser['pembina_id']}, Kode Pembina: {$newMgrUser['kode_pembina']}\n";

    // 7. Uji Registrasi dengan Voucher Mitra
    echo "\n[7] Simulasi Registrasi User Baru dengan Voucher Mitra ($mitCode)...\n";
    $mitCheck = $voucherModel->validateForRegistration($mitCode);
    assert($mitCheck['valid'] === true, "Validasi voucher Mitra gagal: " . $mitCheck['message']);

    $db->transStart();
    $lockedMit = $db->query("SELECT * FROM vouchers WHERE id = ? FOR UPDATE", [$mitVoucher['id']])->getRowArray();
    $mitTargetRole = $lockedMit['role_tujuan'];
    $mitPembinaId = $lockedMit['pembina_id'];

    $testMitId = $userModel->insert([
        'nama_lengkap' => 'Unit Test Mitra Baru',
        'email' => 'unittest_mit_' . time() . '@test.com',
        'no_wa' => '089997' . rand(100000, 999999),
        'domisili' => 'Kota Uji Coba',
        'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
        'role' => $mitTargetRole,
        'pembina_id' => $mitPembinaId,
        'status' => 'aktif',
        'verification_status' => 'belum_verifikasi'
    ]);
    assert($testMitId > 0, "Gagal membuat user mitra");
    $createdUserIds[] = $testMitId;

    $claimedMit = $voucherModel->claimAtomically($mitVoucher['id'], $testMitId);
    assert($claimedMit === true, "Gagal klaim voucher Mitra");
    $db->transComplete();

    $newMitUser = $userModel->find($testMitId);
    assert($newMitUser['role'] === 'mitra', "Role user baru wajib 'mitra'");
    assert((int)$newMitUser['pembina_id'] === 7, "Pembina ID user baru wajib 7 (Manager)");
    echo "  -> SUCCESS: User ID $testMitId berhasil terdaftar dengan Role: {$newMitUser['role']}, Pembina ID: {$newMitUser['pembina_id']}\n";

    // 8. Cek Tampilan Binaan (Hierarchy Check)
    echo "\n[8] Memverifikasi Penampakan Binaan di getBindaan()...\n";
    $pusatBinaan = $userModel->getBindaan(5, 'pusat');
    $pusatBinaanIds = array_column($pusatBinaan, 'id');
    assert(in_array($testCbgId, $pusatBinaanIds), "Pusat wajib dapat melihat Cabang baru dalam binaannya");

    $cabangBinaan = $userModel->getBindaan(6, 'cabang');
    $cabangBinaanIds = array_column($cabangBinaan, 'id');
    assert(in_array($testMgrId, $cabangBinaanIds), "Cabang ID 6 wajib dapat melihat Manager baru dalam binaannya");

    $mgrBinaan = $userModel->getBindaan(7, 'manager_mitra');
    $mgrBinaanIds = array_column($mgrBinaan, 'id');
    assert(in_array($testMitId, $mgrBinaanIds), "Manager ID 7 wajib dapat melihat Mitra baru dalam binaannya");

    echo "  -> SUCCESS: Semua pohon hierarki binaan (Pusat -> Cabang, Cabang -> Manager, Manager -> Mitra) valid dan sinkron!\n";

    echo "\n===============================================\n";
    echo "ALL 8 TEST STAGES PASSED FLAWLESSLY!\n";
    echo "===============================================\n";

} finally {
    echo "\nMembersihkan data pengujian...\n";
    foreach ($createdUserIds as $uid) {
        $db->table('verifications')->where('user_id', $uid)->delete();
        $db->table('users')->where('id', $uid)->delete();
    }
    foreach ($createdVoucherIds as $vid) {
        $db->table('vouchers')->where('id', $vid)->delete();
    }
    echo "Pembersihan data selesai.\n";
}
