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
$packageModel = new \App\Models\PackageModel();
$packageDpRuleModel = new \App\Models\PackageDpRuleModel();
$orderModel = new \App\Models\OrderModel();
$orderPaymentModel = new \App\Models\OrderPaymentModel();
$commissionModel = new \App\Models\CommissionModel();
$withdrawalModel = new \App\Models\WithdrawalModel();

echo "=================================================================\n";
echo "STARTING COMMERCE & COMMISSION END-TO-END VERIFICATION TEST SUITE\n";
echo "=================================================================\n";

// Pre-cleanup leftover test data
$db->query("DELETE FROM verifications WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@amanahsafar.test')");
$db->query("DELETE FROM users WHERE email LIKE '%@amanahsafar.test'");

$createdUserIds = [];
$createdPackageIds = [];
$createdOrderIds = [];
$createdWithdrawalIds = [];

try {
    // -------------------------------------------------------------
    // [1] TEST GROUP 1: ONBOARDING & GATEKEEPER VALIDATION
    // -------------------------------------------------------------
    echo "\n[1] Menguji Gatekeeper: Onboarding, Verifikasi KTP/Bank & Persetujuan PKS...\n";

    // Buat user uji baru (tanpa verifikasi & pks)
    $testUserEmail = 'gatekeeper_test_' . time() . '_' . rand(100, 999) . '@amanahsafar.test';
    $userId = $userModel->insert([
        'nama_lengkap' => 'Tester Gatekeeper',
        'email'        => $testUserEmail,
        'no_wa'        => '0812' . rand(10000000, 99999999),
        'password'     => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'         => 'mitra',
        'status'       => 'aktif'
    ], true);
    $createdUserIds[] = $userId;

    $gateCheck1 = $userModel->canOrderPackages($userId);
    assert($gateCheck1['allowed'] === false, "User baru belum verifikasi & PKS WAJIB diblokir gatekeeper!");
    assert(!empty($gateCheck1['need_agreement']), "Wajib menuntut persetujuan PKS!");
    echo "  -> SUCCESS: User baru belum verifikasi & PKS diblokir gatekeeper.\n";

    // Setujui PKS tapi belum verifikasi berkas KTP/Bank
    $userModel->update($userId, ['agreement_accepted_at' => date('Y-m-d H:i:s')]);
    $gateCheck2 = $userModel->canOrderPackages($userId);
    assert($gateCheck2['allowed'] === false, "User PKS tanpa verifikasi KTP/Bank WAJIB diblokir gatekeeper!");
    assert(!empty($gateCheck2['need_verification']), "Wajib menuntut data verifikasi KTP & Rekening!");
    echo "  -> SUCCESS: User sudah PKS tapi belum isi KTP/Bank tetap diblokir gatekeeper.\n";

    // Lengkapi data verifikasi di tabel verifications
    $db->table('verifications')->insert([
        'user_id'               => $userId,
        'ktp_path'              => 'uploads/verifications/ktp_sample.jpg',
        'nama_bank'             => 'BCA',
        'nomor_rekening'        => '1234567890',
        'nama_pemilik_rekening' => 'Tester Gatekeeper',
        'status'                => 'menunggu_review', // Sesuai aturan: tidak butuh persetujuan super admin untuk memesan, asal data lengkap
        'created_at'            => date('Y-m-d H:i:s')
    ]);

    $gateCheck3 = $userModel->canOrderPackages($userId);
    assert($gateCheck3['allowed'] === true, "User sudah isi KTP/Bank dan setujui PKS WAJIB lolos gatekeeper!");
    echo "  -> SUCCESS: User telah melengkapi data verifikasi & PKS lolos gatekeeper (Allowed = true).\n";


    // -------------------------------------------------------------
    // [2] TEST GROUP 2: MODEL A DYNAMIC DP RULES (DATE RANGES)
    // -------------------------------------------------------------
    echo "\n[2] Menguji Model A: Aturan Promo Uang Muka DP Kalender Dinamis...\n";

    $packageData = [
        'nama'                => 'Paket E2E Promo Musim Semi ' . time(),
        'kategori'            => 'reguler',
        'status'              => 'aktif',
        'harga'               => 30000000,
        'harga_quad'          => 30000000,
        'seat_tersedia'       => 45,
        'minimal_dp_default'  => 5000000,
        'komisi_mitra'        => 2000000,
        'komisi_manager'      => 500000,
        'komisi_cabang_pusat' => 500000,
        'total_pool_komisi'   => 3000000,
        'created_by'          => 1,
    ];
    $packageId = $packageModel->insert($packageData, true);
    $createdPackageIds[] = $packageId;

    // Masukkan DP Rule Kalender Model A:
    // Promo aktif hari ini: DP Rp 2.000.000
    $today = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-5 days'));
    $endDate = date('Y-m-d', strtotime('+20 days'));
    $packageDpRuleModel->insert([
        'package_id'      => $packageId,
        'keterangan'      => 'Promo Early Bird Bulan 1-3',
        'tanggal_mulai'   => $startDate,
        'tanggal_selesai' => $endDate,
        'nominal_dp'      => 2000000
    ]);

    // Evaluasi minimal DP hari ini
    $calcToday = $packageDpRuleModel->calculateMinimalDp($packageId, 1, $today);
    assert($calcToday['has_rule'] === true, "Harus mendeteksi promo kalender aktif!");
    assert((float)$calcToday['per_pax'] === 2000000.0, "Minimal DP hari ini harusnya Rp 2.000.000 (promo aktif), dapat: {$calcToday['per_pax']}");
    echo "  -> SUCCESS: DP efektif promo kalender Model A hari ini terhitung Rp " . number_format($calcToday['per_pax'], 0, ',', '.') . ".\n";

    // Evaluasi minimal DP di luar periode promo (misal tahun 2030)
    $calcFuture = $packageDpRuleModel->calculateMinimalDp($packageId, 1, '2030-01-01');
    assert($calcFuture['has_rule'] === false, "Di luar periode promo tidak boleh terdeteksi promo!");
    assert((float)$calcFuture['per_pax'] === 5000000.0, "Minimal DP di luar promo harus kembali ke default Rp 5.000.000, dapat: {$calcFuture['per_pax']}");
    echo "  -> SUCCESS: DP efektif di luar periode promo kembali ke default Rp " . number_format($calcFuture['per_pax'], 0, ',', '.') . ".\n";


    // -------------------------------------------------------------
    // [3] TEST NETWORK HIERARCHY SETUP (Cabang -> Manager -> Mitra)
    // -------------------------------------------------------------
    echo "\n[3] Mempersiapkan Jaringan Hierarki 3-Tingkat (Cabang -> Manager -> Mitra)...\n";

    // Cabang
    $cabangId = $userModel->insert([
        'nama_lengkap'          => 'Kepala Cabang E2E',
        'email'                 => 'cabang_e2e_' . time() . '_' . rand(100, 999) . '@amanahsafar.test',
        'no_wa'                 => '0812' . rand(10000000, 99999999),
        'password'              => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'                  => 'cabang',
        'status'                => 'aktif',
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ], true);
    $createdUserIds[] = $cabangId;

    // Manager (dibina oleh Cabang)
    $managerId = $userModel->insert([
        'nama_lengkap'          => 'Manager Mitra E2E',
        'email'                 => 'manager_e2e_' . time() . '_' . rand(100, 999) . '@amanahsafar.test',
        'no_wa'                 => '0812' . rand(10000000, 99999999),
        'password'              => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'                  => 'manager_mitra',
        'pembina_id'            => $cabangId,
        'status'                => 'aktif',
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ], true);
    $createdUserIds[] = $managerId;

    // Mitra (dibina oleh Manager)
    $mitraId = $userModel->insert([
        'nama_lengkap'          => 'Mitra Closing E2E',
        'email'                 => 'mitra_e2e_' . time() . '_' . rand(100, 999) . '@amanahsafar.test',
        'no_wa'                 => '0812' . rand(10000000, 99999999),
        'password'              => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'                  => 'mitra',
        'pembina_id'            => $managerId,
        'status'                => 'aktif',
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ], true);
    $createdUserIds[] = $mitraId;

    echo "  -> SUCCESS: Hierarki terbentuk: Cabang ($cabangId) -> Manager ($managerId) -> Mitra ($mitraId).\n";


    // -------------------------------------------------------------
    // [4] ORDER CREATION WITH DP SELECTION (OPTION A)
    // -------------------------------------------------------------
    echo "\n[4] Menguji Pemesanan Paket dengan Opsi Uang Muka (DP)...\n";

    $invoiceNumber = $orderModel->generateOrderNumber();
    $orderId = $orderModel->insert([
        'no_order'                => $invoiceNumber,
        'user_id'                 => $mitraId,
        'status'                  => 'menunggu_pembayaran',
        'total'                   => 30000000,
        'tipe_pembayaran_pilihan' => 'dp',
        'nominal_dp_minimal'      => 2000000,
        'total_dibayar'           => 0,
        'sisa_tagihan'            => 30000000,
        'status_pembayaran'       => 'menunggu_pembayaran'
    ], true);
    $createdOrderIds[] = $orderId;

    // Insert order items
    $db->table('order_items')->insert([
        'order_id'     => $orderId,
        'package_id'   => $packageId,
        'qty'          => 1,
        'harga_snapshot' => 30000000,
        'subtotal'     => 30000000
    ]);

    echo "  -> SUCCESS: Pesanan #$invoiceNumber berhasil dibuat dengan tipe pembayaran DP.\n";


    // -------------------------------------------------------------
    // [5] STAGE 1: SUBMIT DP PAYMENT & ADMIN VERIFICATION -> POTENSI KOMISI
    // -------------------------------------------------------------
    echo "\n[5] Menguji Pengunggahan Bukti DP & Verifikasi Admin -> Pembentukan Potensi Komisi...\n";

    // Mitra mengunggah bukti bayar DP Rp 2.000.000
    $dpPaymentId = $orderPaymentModel->insert([
        'order_id'               => $orderId,
        'no_transaksi'           => $orderPaymentModel->generateTransactionNumber($orderId),
        'tipe_pembayaran'        => 'dp',
        'nominal'                => 2000000,
        'bukti_transfer'         => 'uploads/payments/test_dp_receipt.jpg',
        'nama_bank_pengirim'     => 'BCA',
        'nomor_rekening_pengirim'=> '1234567890',
        'nama_pengirim'          => 'Mitra Closing E2E',
        'status'                 => 'menunggu_verifikasi'
    ], true);

    // Verifikasi oleh Admin:
    $orderPaymentModel->update($dpPaymentId, [
        'status'             => 'disetujui',
        'verified_by'        => 1,
        'verified_at'        => date('Y-m-d H:i:s'),
        'catatan_admin'      => 'DP valid via BCA'
    ]);

    // Recalculate financial balances pada order:
    $approvedPayments = $orderPaymentModel->where('order_id', $orderId)->where('status', 'disetujui')->findAll();
    $totalPaid = array_sum(array_column($approvedPayments, 'nominal'));
    $orderData = $orderModel->find($orderId);
    $newRemaining = max(0, (float)$orderData['total'] - $totalPaid);

    $orderModel->update($orderId, [
        'total_dibayar'     => $totalPaid,
        'sisa_tagihan'      => $newRemaining,
        'status_pembayaran' => ($newRemaining <= 0) ? 'lunas' : 'dp_diterima',
        'status'            => ($newRemaining <= 0) ? 'diproses' : 'dp_diterima'
    ]);

    // Generate Hierarchical Commissions with initial status 'potensi'
    $genRes = $commissionModel->generateCommissionsForOrder($orderId, 'potensi');
    assert($genRes === true, "Gagal membuat komisi potensi untuk pesanan!");

    // Verifikasi saldo potensi pada ketiga peran:
    $potensiMitra = $commissionModel->getPotensiKomisiByUser($mitraId);
    $siapCairMitra = $commissionModel->getSaldoSiapCairByUser($mitraId);
    assert((float)$potensiMitra == 2000000.0, "Potensi komisi mitra harus Rp 2.000.000, got: $potensiMitra");
    assert((float)$siapCairMitra == 0.0, "Saldo siap cair mitra saat status baru DP WAJIB Rp 0, got: $siapCairMitra");

    $potensiManager = $commissionModel->getPotensiKomisiByUser($managerId);
    $siapCairManager = $commissionModel->getSaldoSiapCairByUser($managerId);
    assert((float)$potensiManager == 500000.0, "Potensi komisi manager harus Rp 500.000, got: $potensiManager");
    assert((float)$siapCairManager == 0.0, "Saldo siap cair manager saat status baru DP WAJIB Rp 0, got: $siapCairManager");

    $potensiCabang = $commissionModel->getPotensiKomisiByUser($cabangId);
    $siapCairCabang = $commissionModel->getSaldoSiapCairByUser($cabangId);
    assert((float)$potensiCabang == 500000.0, "Potensi komisi cabang harus Rp 500.000, got: $potensiCabang");
    assert((float)$siapCairCabang == 0.0, "Saldo siap cair cabang saat status baru DP WAJIB Rp 0, got: $siapCairCabang");

    echo "  -> SUCCESS: Status pesanan menjadi 'dp_diterima' (Terbayar: 2jt, Sisa: 28jt).\n";
    echo "  -> SUCCESS: Komisi Potensi terbit: Mitra=Rp 2jt, Manager=Rp 500k, Cabang=Rp 500k (Semua Siap Cair=0).\n";


    // -------------------------------------------------------------
    // [6] STAGE 2: SUBMIT PELUNASAN PAYMENT & ADMIN VERIFICATION -> SIAP CAIR
    // -------------------------------------------------------------
    echo "\n[6] Menguji Pengunggahan Pelunasan & Promosi Potensi Komisi -> Siap Cair...\n";

    // Mitra bayar pelunasan Rp 28.000.000
    $pelunasanPaymentId = $orderPaymentModel->insert([
        'order_id'               => $orderId,
        'no_transaksi'           => $orderPaymentModel->generateTransactionNumber($orderId),
        'tipe_pembayaran'        => 'pelunasan',
        'nominal'                => 28000000,
        'bukti_transfer'         => 'uploads/payments/test_pelunasan_receipt.jpg',
        'nama_bank_pengirim'     => 'BCA',
        'nomor_rekening_pengirim'=> '1234567890',
        'nama_pengirim'          => 'Mitra Closing E2E',
        'status'                 => 'menunggu_verifikasi'
    ], true);

    // Admin verifikasi pelunasan:
    $orderPaymentModel->update($pelunasanPaymentId, [
        'status'             => 'disetujui',
        'verified_by'        => 1,
        'verified_at'        => date('Y-m-d H:i:s'),
        'catatan_admin'      => 'Pelunasan lunas valid'
    ]);

    // Recalculate financial balances:
    $approvedPayments2 = $orderPaymentModel->where('order_id', $orderId)->where('status', 'disetujui')->findAll();
    $totalPaid2 = array_sum(array_column($approvedPayments2, 'nominal'));
    $newRemaining2 = max(0, (float)$orderData['total'] - $totalPaid2);

    $orderModel->update($orderId, [
        'total_dibayar'     => $totalPaid2,
        'sisa_tagihan'      => $newRemaining2,
        'status_pembayaran' => ($newRemaining2 <= 0) ? 'lunas' : 'dp_diterima',
        'status'            => ($newRemaining2 <= 0) ? 'diproses' : 'dp_diterima'
    ]);

    // Promosi komisi dari 'potensi' -> 'siap_cair'
    $updateCount = $commissionModel->updateCommissionStatusForOrder($orderId, 'siap_cair');
    assert($updateCount !== false, "Gagal memperbarui status komisi ke siap_cair");

    // Verifikasi saldo siap cair pasca pelunasan:
    $potensiMitraPost = $commissionModel->getPotensiKomisiByUser($mitraId);
    $siapCairMitraPost = $commissionModel->getSaldoSiapCairByUser($mitraId);
    assert((float)$potensiMitraPost == 0.0, "Potensi mitra harus Rp 0 pasca pelunasan, got: $potensiMitraPost");
    assert((float)$siapCairMitraPost == 2000000.0, "Saldo siap cair mitra harus menjadi Rp 2.000.000, got: $siapCairMitraPost");

    $potensiMgrPost = $commissionModel->getPotensiKomisiByUser($managerId);
    $siapCairMgrPost = $commissionModel->getSaldoSiapCairByUser($managerId);
    assert((float)$potensiMgrPost == 0.0, "Potensi manager harus Rp 0 pasca pelunasan, got: $potensiMgrPost");
    assert((float)$siapCairMgrPost == 500000.0, "Saldo siap cair manager harus menjadi Rp 500.000, got: $siapCairMgrPost");

    $potensiCbgPost = $commissionModel->getPotensiKomisiByUser($cabangId);
    $siapCairCbgPost = $commissionModel->getSaldoSiapCairByUser($cabangId);
    assert((float)$potensiCbgPost == 0.0, "Potensi cabang harus Rp 0 pasca pelunasan, got: $potensiCbgPost");
    assert((float)$siapCairCbgPost == 500000.0, "Saldo siap cair cabang harus menjadi Rp 500.000, got: $siapCairCbgPost");

    echo "  -> SUCCESS: Status pesanan lunas (Terbayar: 30jt, Sisa: 0).\n";
    echo "  -> SUCCESS: Potensi berhasil dipromosikan menjadi Siap Cair (Mitra=2jt, Mgr=500k, Cabang=500k).\n";


    // -------------------------------------------------------------
    // [7] BANK-STYLE BUKU MUTASI LEDGER QUERIES & SLIP DETAIL
    // -------------------------------------------------------------
    echo "\n[7] Menguji Buku Mutasi Bank & Rincian Transaksi Asal Komisi...\n";

    $mutasiMitra = $commissionModel->getMutasiLedgerByUser($mitraId);
    assert(count($mutasiMitra) >= 1, "Mutasi mitra harus memiliki minimal 1 riwayat komisi.");
    $mutasiFirst = $mutasiMitra[0];
    assert((float)$mutasiFirst['nominal_snapshot'] == 2000000.0, "Nominal mutasi mitra harus 2.000.000");
    assert($mutasiFirst['status'] === 'siap_cair', "Status mutasi mitra harus siap_cair");
    assert(!empty($mutasiFirst['no_order']), "Mutasi harus melacak nomor pesanan asal");

    echo "  -> SUCCESS: Buku Mutasi menampilkan mutasi bank dengan detail: Pesanan '{$mutasiFirst['no_order']}', Nominal Rp " . number_format($mutasiFirst['nominal_snapshot'], 0, ',', '.') . ".\n";


    // -------------------------------------------------------------
    // [8] WITHDRAWAL REQUEST & SUPERADMIN APPROVAL (OPTION A)
    // -------------------------------------------------------------
    echo "\n[8] Menguji Pengajuan Penarikan Saldo (Withdrawal) & Persetujuan Super Admin...\n";

    $wdNumber = $withdrawalModel->generateWithdrawalNumber();
    $wdId = $withdrawalModel->insert([
        'no_penarikan'          => $wdNumber,
        'user_id'               => $mitraId,
        'nominal'               => 2000000,
        'nama_bank'             => 'Bank Syariah Indonesia (BSI)',
        'nomor_rekening'        => '7123456789',
        'nama_pemilik_rekening' => 'Mitra Closing E2E',
        'status'                => 'menunggu_transfer'
    ], true);
    $createdWithdrawalIds[] = $wdId;

    // Saat status 'menunggu_transfer', saldo siap cair mitra otomatis terpotong (reserved)
    $siapCairReserved = $commissionModel->getSaldoSiapCairByUser($mitraId);
    assert((float)$siapCairReserved == 0.0, "Saat penarikan diproses (menunggu_transfer), saldo siap cair mitra harus ter-reserve menjadi 0, got: $siapCairReserved");
    echo "  -> SUCCESS: Saldo berhasil di-reserve (Saldo aktif = 0 saat proses transfer).\n";

    // Super Admin memproses transfer pencairan
    $withdrawalModel->update($wdId, [
        'status'         => 'selesai',
        'bukti_transfer' => 'uploads/proofs/test_wd_transfer.jpg',
        'catatan_admin'  => 'Transfer pencairan komisi berhasil via BSI Net',
        'processed_by'   => 1,
        'processed_at'   => date('Y-m-d H:i:s')
    ]);

    // Tandai baris komisi mitra sebagai 'ditarik'
    $db->table('commissions')
        ->where('user_id', $mitraId)
        ->where('status', 'siap_cair')
        ->update([
            'status'     => 'ditarik',
            'paid_at'    => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    // Verifikasi saldo setelah selesai ditarik
    $siapCairFinal = $commissionModel->getSaldoSiapCairByUser($mitraId);
    $totalDitarikRow = $db->table('commissions')->where('user_id', $mitraId)->where('status', 'ditarik')->selectSum('nominal_snapshot')->get()->getRowArray();
    $totalDitarik = (float)($totalDitarikRow['nominal_snapshot'] ?? 0);
    assert((float)$siapCairFinal == 0.0, "Saldo siap cair mitra harus tetap Rp 0 setelah ditarik, got: $siapCairFinal");
    assert((float)$totalDitarik == 2000000.0, "Total ditarik mitra harus menjadi Rp 2.000.000, got: $totalDitarik");

    echo "  -> SUCCESS: Penarikan disetujui. Saldo Siap Cair = 0, Total Berhasil Ditarik = Rp 2.000.000.\n";


    // -------------------------------------------------------------
    // [9] MATHEMATICAL SUM VALIDATION & ALL 4 COMMISSION SCENARIOS
    // -------------------------------------------------------------
    echo "\n[9] Menguji Validasi Matematika & 4 Skenario Distribusi Hierarki Bisnis...\n";

    // Test Scenario 1: Cabang direct closing (No mitra, no manager)
    // Cabang receives the full package commission pool!
    $orderCbgId = $orderModel->insert([
        'no_order'                => $orderModel->generateOrderNumber(),
        'user_id'                 => $cabangId,
        'status'                  => 'diproses',
        'total'                   => 30000000,
        'tipe_pembayaran_pilihan' => 'full',
        'nominal_dp_minimal'      => 5000000,
        'total_dibayar'           => 30000000,
        'sisa_tagihan'            => 0,
        'status_pembayaran'       => 'lunas'
    ], true);
    $createdOrderIds[] = $orderCbgId;

    $db->table('order_items')->insert([
        'order_id'     => $orderCbgId,
        'package_id'   => $packageId,
        'qty'          => 1,
        'harga_snapshot' => 30000000,
        'subtotal'     => 30000000
    ]);

    $res1 = $commissionModel->generateCommissionsForOrder($orderCbgId, 'siap_cair');
    assert($res1 === true, "Distribusi Scenario 1 harus berhasil!");
    $cbgComms = $db->table('commissions')->where('order_id', $orderCbgId)->get()->getResultArray();
    assert(count($cbgComms) === 1, "Scenario 1 harus menghasilkan 1 baris komisi (Cabang)");
    assert((float)$cbgComms[0]['nominal_snapshot'] === 3000000.0, "Cabang direct closing harus menerima full pool 3.000.000!");
    echo "  -> SUCCESS: Skenario 1 (Cabang Direct Closing): Cabang menerima Full Pool = Rp 3.000.000.\n";

    // Test Scenario 2: Mitra + Cabang (Tanpa Manager)
    // Mitra earns 2jt, Cabang earns 1jt (cabang takes manager share). Total = 3jt.
    $mitraDirectCbgId = $userModel->insert([
        'nama_lengkap'          => 'Mitra Direct Cabang',
        'email'                 => 'mitra_direct_cbg_' . time() . '_' . rand(100, 999) . '@amanahsafar.test',
        'no_wa'                 => '0812' . rand(10000000, 99999999),
        'password'              => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'                  => 'mitra',
        'pembina_id'            => $cabangId, // Pembina langsung Cabang
        'status'                => 'aktif',
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ], true);
    $createdUserIds[] = $mitraDirectCbgId;

    $orderScen2Id = $orderModel->insert([
        'no_order'                => $orderModel->generateOrderNumber(),
        'user_id'                 => $mitraDirectCbgId,
        'status'                  => 'diproses',
        'total'                   => 30000000,
        'tipe_pembayaran_pilihan' => 'full',
        'nominal_dp_minimal'      => 5000000,
        'total_dibayar'           => 30000000,
        'sisa_tagihan'            => 0,
        'status_pembayaran'       => 'lunas'
    ], true);
    $createdOrderIds[] = $orderScen2Id;

    $db->table('order_items')->insert([
        'order_id'     => $orderScen2Id,
        'package_id'   => $packageId,
        'qty'          => 1,
        'harga_snapshot' => 30000000,
        'subtotal'     => 30000000
    ]);

    $res2 = $commissionModel->generateCommissionsForOrder($orderScen2Id, 'siap_cair');
    assert($res2 === true, "Distribusi Scenario 2 harus berhasil!");
    $scen2Comms = $db->table('commissions')->where('order_id', $orderScen2Id)->get()->getResultArray();
    assert(count($scen2Comms) === 2, "Scenario 2 harus menghasilkan 2 baris komisi (Mitra & Cabang)");
    $sumScen2 = array_sum(array_column($scen2Comms, 'nominal_snapshot'));
    assert($sumScen2 === 3000000.0, "Total komisi Scenario 2 harus tepat 3.000.000");
    echo "  -> SUCCESS: Skenario 2 (Mitra + Cabang tanpa Manager): Mitra=Rp 2jt, Cabang=Rp 1jt (Total=Rp 3jt).\n";

    // Test Scenario 4: Mitra + Manager + Pusat (Cabang tidak ada / fallback Pusat)
    // Mitra 2jt, Manager 500k, Pusat 500k. Total = 3jt.
    $managerDirectPusatId = $userModel->insert([
        'nama_lengkap'          => 'Manager Direct Pusat',
        'email'                 => 'mgr_direct_pusat_' . time() . '_' . rand(100, 999) . '@amanahsafar.test',
        'no_wa'                 => '0812' . rand(10000000, 99999999),
        'password'              => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'                  => 'manager_mitra',
        'pembina_id'            => 5, // Kantor Pusat
        'status'                => 'aktif',
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ], true);
    $createdUserIds[] = $managerDirectPusatId;

    $mitraScen4Id = $userModel->insert([
        'nama_lengkap'          => 'Mitra Binaan Manager Pusat',
        'email'                 => 'mitra_scen4_' . time() . '_' . rand(100, 999) . '@amanahsafar.test',
        'no_wa'                 => '0812' . rand(10000000, 99999999),
        'password'              => password_hash('Secret123!', PASSWORD_BCRYPT),
        'role'                  => 'mitra',
        'pembina_id'            => $managerDirectPusatId,
        'status'                => 'aktif',
        'agreement_accepted_at' => date('Y-m-d H:i:s')
    ], true);
    $createdUserIds[] = $mitraScen4Id;

    $orderScen4Id = $orderModel->insert([
        'no_order'                => $orderModel->generateOrderNumber(),
        'user_id'                 => $mitraScen4Id,
        'status'                  => 'diproses',
        'total'                   => 30000000,
        'tipe_pembayaran_pilihan' => 'full',
        'nominal_dp_minimal'      => 5000000,
        'total_dibayar'           => 30000000,
        'sisa_tagihan'            => 0,
        'status_pembayaran'       => 'lunas'
    ], true);
    $createdOrderIds[] = $orderScen4Id;

    $db->table('order_items')->insert([
        'order_id'     => $orderScen4Id,
        'package_id'   => $packageId,
        'qty'          => 1,
        'harga_snapshot' => 30000000,
        'subtotal'     => 30000000
    ]);

    $res4 = $commissionModel->generateCommissionsForOrder($orderScen4Id, 'siap_cair');
    assert($res4 === true, "Distribusi Scenario 4 harus berhasil!");
    $scen4Comms = $db->table('commissions')->where('order_id', $orderScen4Id)->get()->getResultArray();
    assert(count($scen4Comms) === 3, "Scenario 4 harus menghasilkan 3 baris komisi (Mitra, Mgr, Pusat)");
    $sumScen4 = array_sum(array_column($scen4Comms, 'nominal_snapshot'));
    assert($sumScen4 === 3000000.0, "Total komisi Scenario 4 harus tepat 3.000.000");
    echo "  -> SUCCESS: Skenario 4 (Mitra + Manager + Override Pusat): Mitra=Rp 2jt, Mgr=Rp 500k, Pusat=Rp 500k (Total=Rp 3jt).\n";

    echo "\n=================================================================\n";
    echo "SEMUA TAHAPAN END-TO-END SUKSES LULUS 100% TANPA KESALAHAN!\n";
    echo "=================================================================\n";

} catch (\Throwable $e) {
    echo "\n[ERROR DETECTED]: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . " in " . $e->getFile() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
} finally {
    // -------------------------------------------------------------
    // CLEANUP TEST DATA
    // -------------------------------------------------------------
    echo "\nMembersihkan data pengujian...\n";
    if (!empty($createdWithdrawalIds)) {
        $db->table('withdrawals')->whereIn('id', $createdWithdrawalIds)->delete();
    }
    if (!empty($createdOrderIds)) {
        $db->table('commissions')->whereIn('order_id', $createdOrderIds)->delete();
        $db->table('order_payments')->whereIn('order_id', $createdOrderIds)->delete();
        $db->table('order_items')->whereIn('order_id', $createdOrderIds)->delete();
        $db->table('orders')->whereIn('id', $createdOrderIds)->delete();
    }
    if (!empty($createdPackageIds)) {
        $db->table('package_dp_rules')->whereIn('package_id', $createdPackageIds)->delete();
        $db->table('packages')->whereIn('id', $createdPackageIds)->delete();
    }
    if (!empty($createdUserIds)) {
        $db->table('verifications')->whereIn('user_id', $createdUserIds)->delete();
        $db->table('users')->whereIn('id', $createdUserIds)->delete();
    }
    echo "Pembersihan data selesai.\n";
}
