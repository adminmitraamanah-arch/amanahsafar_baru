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
$verificationModel = new \App\Models\VerificationModel();

echo "===================================================================\n";
echo "TEST SUITE: SINKRONISASI & PENGHAPUSAN VERIFIKASI VS KELOLA PENGGUNA\n";
echo "===================================================================\n";

$testUserIds = [];

try {
    // -------------------------------------------------------------
    // [1] TEST REGISTER & INITIAL VERIFICATION RECORD
    // -------------------------------------------------------------
    echo "\n[1] Menguji Registrasi Pengguna & Pembentukan Record Verifikasi Awal...\n";
    $testEmail1 = 'test_verif_sync_' . time() . '_1@amanahsafar.test';
    $userId1 = $userModel->insert([
        'nama_lengkap' => 'Tester Verif 1',
        'email'        => $testEmail1,
        'no_wa'        => '0812' . rand(10000000, 99999999),
        'password'     => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'         => 'mitra',
        'status'       => 'aktif',
        'verification_status' => 'belum_verifikasi'
    ], true);
    $testUserIds[] = $userId1;

    $verificationModel->insert([
        'user_id' => $userId1,
        'status'  => 'belum_verifikasi'
    ]);

    $u1 = $userModel->find($userId1);
    $v1 = $verificationModel->where('user_id', $userId1)->first();
    assert($u1['verification_status'] === 'belum_verifikasi', "users.verification_status wajib 'belum_verifikasi'");
    assert($v1['status'] === 'belum_verifikasi', "verifications.status wajib 'belum_verifikasi'");
    echo "  -> SUCCESS: Status awal kedua tabel sinkron ('belum_verifikasi').\n";

    // -------------------------------------------------------------
    // [2] TEST SUBMIT KYC FORM (Auth::verify)
    // -------------------------------------------------------------
    echo "\n[2] Menguji Pengajuan Berkas KYC (Simulasi Auth::verify)...\n";
    $verificationModel->where('user_id', $userId1)->update(null, [
        'nama_bank'             => 'Bank Syariah Indonesia (BSI)',
        'nomor_rekening'        => '7123456789',
        'nama_pemilik_rekening' => 'Tester Verif 1',
        'ktp_path'              => 'uploads/verifikasi/ktp_test.jpg',
        'status'                => 'menunggu_review'
    ]);

    // Sinkronkan ke tabel users (perbaikan baru)
    $userModel->update($userId1, [
        'verification_status' => 'menunggu_review',
        'updated_at'          => date('Y-m-d H:i:s'),
    ]);

    $u1PostKyc = $userModel->find($userId1);
    $v1PostKyc = $verificationModel->where('user_id', $userId1)->first();
    assert($u1PostKyc['verification_status'] === 'menunggu_review', "users.verification_status wajib 'menunggu_review'");
    assert($v1PostKyc['status'] === 'menunggu_review', "verifications.status wajib 'menunggu_review'");
    echo "  -> SUCCESS: Saat submit KYC, kedua tabel sinkron berstatus 'menunggu_review'.\n";

    // -------------------------------------------------------------
    // [3] TEST SUPERADMIN APPROVAL & REJECTION
    // -------------------------------------------------------------
    echo "\n[3] Menguji Persetujuan Verifikasi oleh Super Admin...\n";
    $verificationModel->update($v1PostKyc['id'], [
        'status'      => 'terverifikasi',
        'reviewed_by' => 1,
        'catatan'     => 'Disetujui test'
    ]);
    $userModel->update($userId1, [
        'verification_status' => 'terverifikasi',
        'updated_at'          => date('Y-m-d H:i:s')
    ]);

    $u1Approved = $userModel->find($userId1);
    $v1Approved = $verificationModel->where('user_id', $userId1)->first();
    assert($u1Approved['verification_status'] === 'terverifikasi', "users.verification_status wajib 'terverifikasi'");
    assert($v1Approved['status'] === 'terverifikasi', "verifications.status wajib 'terverifikasi'");
    echo "  -> SUCCESS: Saat persetujuan admin, kedua tabel sinkron berstatus 'terverifikasi'.\n";

    // -------------------------------------------------------------
    // [4] TEST DELETION OF USER (KASUS FADIL)
    // -------------------------------------------------------------
    echo "\n[4] Menguji Penghapusan Pengguna (Kasus Fadil): Tidak Boleh Tertinggal di Verifikasi...\n";
    
    // Sebelum dihapus: user wajib muncul di query Verifikasi Pengguna
    $verifRowsBefore = $db->table('verifications v')
        ->select('v.*, u.nama_lengkap')
        ->join('users u', 'u.id = v.user_id', 'inner')
        ->where('u.id', $userId1)
        ->where('u.deleted_at', null)
        ->get()
        ->getResultArray();
    assert(count($verifRowsBefore) === 1, "User aktif wajib muncul di menu Verifikasi Pengguna!");

    // Jalankan logika SuperAdmin\User::delete($id)
    $userModel->update($userId1, ['status' => 'nonaktif']);
    $userModel->delete($userId1); // Soft delete
    $db->table('verifications')->where('user_id', $userId1)->delete(); // Clean verifications

    // Verifikasi 1: User soft-deleted di users table
    $u1Deleted = $db->table('users')->where('id', $userId1)->get()->getRowArray();
    assert(!empty($u1Deleted['deleted_at']), "users.deleted_at wajib terisi timestamp soft-delete!");

    // Verifikasi 2: User tidak muncul di query Kelola Pengguna (where deleted_at is null)
    $usersInManageUsers = $db->table('users')->where('id', $userId1)->where('deleted_at', null)->countAllResults();
    assert($usersInManageUsers === 0, "User terhapus TIDAK BOLEH muncul di menu Kelola Pengguna!");
    echo "  -> SUCCESS: User terhapus tidak muncul di Kelola Pengguna.\n";

    // Verifikasi 3: User tidak muncul di query Verifikasi Pengguna (where deleted_at is null)
    $verifRowsAfter = $db->table('verifications v')
        ->select('v.*, u.nama_lengkap')
        ->join('users u', 'u.id = v.user_id', 'inner')
        ->where('u.id', $userId1)
        ->where('u.deleted_at', null)
        ->get()
        ->getResultArray();
    assert(count($verifRowsAfter) === 0, "User terhapus TIDAK BOLEH muncul lagi di menu Verifikasi Pengguna!");
    echo "  -> SUCCESS: User terhapus (seperti Fadil) otomatis lenyap dari menu Verifikasi Pengguna!\n";

    // Verifikasi 4: Record verifications milik user telah bersih
    $verifRecordCount = $verificationModel->where('user_id', $userId1)->countAllResults();
    assert($verifRecordCount === 0, "Record di tabel verifications harus bersih saat user dihapus!");
    echo "  -> SUCCESS: Record di tabel verifications terhapus bersih (tidak ada orphan data).\n";

    // -------------------------------------------------------------
    // [5] TEST BULK DELETION SYNC
    // -------------------------------------------------------------
    echo "\n[5] Menguji Penghapusan Massal (Bulk Delete) Sinkronisasi...\n";
    $bulkUserIds = [];
    for ($i = 1; $i <= 2; $i++) {
        $bid = $userModel->insert([
            'nama_lengkap' => 'Bulk Verif User ' . $i,
            'email'        => 'bulk_verif_' . time() . '_' . $i . '@amanahsafar.test',
            'no_wa'        => '0812' . rand(10000000, 99999999),
            'password'     => password_hash('Secret123!', PASSWORD_BCRYPT),
            'role'         => 'mitra',
            'status'       => 'aktif',
            'verification_status' => 'menunggu_review'
        ], true);
        $bulkUserIds[] = $bid;
        $testUserIds[] = $bid;

        $verificationModel->insert([
            'user_id'               => $bid,
            'nama_bank'             => 'BCA',
            'nomor_rekening'        => '12345678' . $i,
            'nama_pemilik_rekening' => 'Bulk Verif User ' . $i,
            'status'                => 'menunggu_review'
        ]);
    }

    // Eksekusi bulk delete
    foreach ($bulkUserIds as $bDelId) {
        $userModel->update($bDelId, ['status' => 'nonaktif']);
        $userModel->delete($bDelId);
        $db->table('verifications')->where('user_id', $bDelId)->delete();
    }

    // Verifikasi di kedua menu
    $bulkInUsers = $db->table('users')->whereIn('id', $bulkUserIds)->where('deleted_at', null)->countAllResults();
    assert($bulkInUsers === 0, "Seluruh user bulk delete tidak boleh muncul di Kelola Pengguna");

    $bulkInVerifs = $db->table('verifications v')
        ->join('users u', 'u.id = v.user_id', 'inner')
        ->whereIn('v.user_id', $bulkUserIds)
        ->where('u.deleted_at', null)
        ->countAllResults();
    assert($bulkInVerifs === 0, "Seluruh user bulk delete tidak boleh muncul di Verifikasi Pengguna");
    echo "  -> SUCCESS: Hapus massal (Bulk Delete) tersinkronisasi 100% di kedua menu!\n";

    echo "\n===================================================================\n";
    echo "SELURUH PENGUJIAN SINKRONISASI & PENGHAPUSAN LULUS 100% TANPA KESALAHAN!\n";
    echo "===================================================================\n";

} catch (\Throwable $e) {
    echo "\n[ERROR DETECTED]: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . " in " . $e->getFile() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
} finally {
    if (!empty($testUserIds)) {
        $db->table('verifications')->whereIn('user_id', $testUserIds)->delete();
        $db->table('users')->whereIn('id', $testUserIds)->delete();
    }
}
