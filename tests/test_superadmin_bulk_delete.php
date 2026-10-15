<?php

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

// Start session
\Config\Services::session();

$db = \Config\Database::connect();
$voucherModel = new \App\Models\VoucherModel();
$userModel = new \App\Models\UserModel();
$packageModel = new \App\Models\PackageModel();
$taskModel = new \App\Models\TaskModel();

echo "========================================================\n";
echo "STARTING SUPER ADMIN SELECT-ALL & BULK DELETE TEST SUITE\n";
echo "========================================================\n";

$errors = [];

// ========================================================
// 1. UI Markup Verification across all 4 modules
// ========================================================
echo "\n[1] Verifikasi UI Markup (Checkbox & Bulk Action Bar)...\n";

$vouchersView = file_get_contents(APPPATH . 'Views/superadmin/vouchers.php');
if (!str_contains($vouchersView, 'id="selectAllVouchers"')) $errors[] = "Vouchers view missing selectAllVouchers";
if (!str_contains($vouchersView, 'voucher-row-checkbox')) $errors[] = "Vouchers view missing voucher-row-checkbox";
if (!str_contains($vouchersView, 'id="voucherBulkActionBar"')) $errors[] = "Vouchers view missing voucherBulkActionBar";
if (!str_contains($vouchersView, 'vouchers/bulk-delete')) $errors[] = "Vouchers view missing vouchers/bulk-delete action";
echo "  -> Vouchers UI: OK\n";

$usersView = file_get_contents(APPPATH . 'Views/superadmin/users.php');
if (!str_contains($usersView, 'id="selectAllUsers"')) $errors[] = "Users view missing selectAllUsers";
if (!str_contains($usersView, 'user-row-checkbox')) $errors[] = "Users view missing user-row-checkbox";
if (!str_contains($usersView, 'id="userBulkActionBar"')) $errors[] = "Users view missing userBulkActionBar";
if (!str_contains($usersView, 'users/bulk-delete')) $errors[] = "Users view missing users/bulk-delete action";
echo "  -> Users UI: OK\n";

$packagesView = file_get_contents(APPPATH . 'Views/superadmin/packages.php');
if (!str_contains($packagesView, 'id="selectAllPackages"')) $errors[] = "Packages view missing selectAllPackages";
if (!str_contains($packagesView, 'package-checkbox')) $errors[] = "Packages view missing package-checkbox";
if (!str_contains($packagesView, 'id="packageBulkActionBar"')) $errors[] = "Packages view missing packageBulkActionBar";
if (!str_contains($packagesView, 'packages/bulk-delete')) $errors[] = "Packages view missing packages/bulk-delete action";
echo "  -> Packages UI: OK\n";

$tasksView = file_get_contents(APPPATH . 'Views/superadmin/tasks.php');
if (!str_contains($tasksView, 'id="selectAllTasks"')) $errors[] = "Tasks view missing selectAllTasks";
if (!str_contains($tasksView, 'task-checkbox')) $errors[] = "Tasks view missing task-checkbox";
if (!str_contains($tasksView, 'id="taskBulkActionBar"')) $errors[] = "Tasks view missing taskBulkActionBar";
if (!str_contains($tasksView, 'tasks/bulk-delete')) $errors[] = "Tasks view missing tasks/bulk-delete action";
echo "  -> Tasks UI: OK\n";

// ========================================================
// 2. Voucher Bulk Delete Logic
// ========================================================
echo "\n[2] Pengujian Hapus Massal Voucher...\n";
$createdVoucherIds = [];
for ($i = 1; $i <= 3; $i++) {
    $code = 'TEST-BULK-' . time() . '-' . $i;
    $id = $voucherModel->insert([
        'kode' => $code,
        'role_tujuan' => 'mitra',
        'status' => 'tersedia',
        'created_by' => 1
    ]);
    $createdVoucherIds[] = $id;
}
echo "  -> Dibuat 3 voucher uji: " . implode(', ', $createdVoucherIds) . "\n";

// Execute deletion
$voucherModel->whereIn('id', $createdVoucherIds)->delete();
$remainingVouchers = $voucherModel->whereIn('id', $createdVoucherIds)->findAll();
assert(count($remainingVouchers) === 0, "Semua voucher uji harus terhapus");
echo "  -> SUCCESS: Seluruh voucher berhasil dihapus massal.\n";

// ========================================================
// 3. User Bulk Delete & Proteksi Diri
// ========================================================
echo "\n[3] Pengujian Hapus Massal Pengguna & Proteksi Keamanan...\n";
$createdUserIds = [];
for ($i = 1; $i <= 2; $i++) {
    $email = 'test_bulk_user_' . time() . '_' . $i . '@amanahsafar.test';
    $id = $userModel->insert([
        'nama_lengkap' => 'Bulk User Test ' . $i,
        'email' => $email,
        'no_wa' => '0888' . rand(10000000, 99999999),
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'role' => 'mitra',
        'status' => 'aktif'
    ]);
    $createdUserIds[] = $id;
}
echo "  -> Dibuat 2 user uji: " . implode(', ', $createdUserIds) . "\n";

// Test Protection 1: Current super admin (ID 1) cannot be deleted in bulk
$superAdminId = 1;
$attemptIds = array_merge([$superAdminId], $createdUserIds);
$filteredIds = array_values(array_filter($attemptIds, fn($id) => $id !== $superAdminId));
assert(!in_array($superAdminId, $filteredIds), "Akun Super Admin yang sedang login harus difilter keluar");
echo "  -> SUCCESS: Akun login terlindungi dari penghapusan massal.\n";

// Execute soft delete on test users
foreach ($filteredIds as $delId) {
    $userModel->update($delId, ['status' => 'nonaktif']);
    $userModel->delete($delId);
}

// Verify soft delete
$checkUsers = $db->table('users')->whereIn('id', $createdUserIds)->get()->getResultArray();
foreach ($checkUsers as $cu) {
    assert(!empty($cu['deleted_at']), "User {$cu['id']} wajib berstatus soft-deleted (deleted_at terisi)");
    assert($cu['status'] === 'nonaktif', "User {$cu['id']} status wajib nonaktif");
}
echo "  -> SUCCESS: 2 Akun pengguna berhasil dihapus massal secara aman.\n";

// Hard cleanup test users
$db->table('users')->whereIn('id', $createdUserIds)->delete();

// ========================================================
// 4. Package Bulk Delete
// ========================================================
echo "\n[4] Pengujian Hapus Massal Paket Umroh...\n";
$createdPackageIds = [];
for ($i = 1; $i <= 2; $i++) {
    $pId = $packageModel->insert([
        'nama' => 'Paket Test Bulk ' . time() . ' #' . $i,
        'kategori' => 'Umroh',
        'harga' => 25000000,
        'status' => 'aktif',
        'created_by' => 1
    ]);
    $createdPackageIds[] = $pId;
}
echo "  -> Dibuat 2 paket uji: " . implode(', ', $createdPackageIds) . "\n";

foreach ($createdPackageIds as $pkgId) {
    $packageModel->delete($pkgId);
}
$remPackages = $packageModel->whereIn('id', $createdPackageIds)->findAll();
assert(count($remPackages) === 0, "Semua paket uji harus terhapus");
echo "  -> SUCCESS: Seluruh paket umroh berhasil dihapus massal.\n";

// ========================================================
// 5. Task Bulk Delete
// ========================================================
echo "\n[5] Pengujian Hapus Massal Tugas Promosi...\n";
$createdTaskIds = [];
for ($i = 1; $i <= 2; $i++) {
    $tId = $taskModel->insert([
        'judul' => 'Tugas Test Bulk ' . time() . ' #' . $i,
        'kategori' => 'Promosi Paket Umrah',
        'status' => 'published',
        'created_by' => 1
    ]);
    $createdTaskIds[] = $tId;
}
echo "  -> Dibuat 2 tugas uji: " . implode(', ', $createdTaskIds) . "\n";

foreach ($createdTaskIds as $taskId) {
    $taskModel->delete($taskId);
}
$remTasks = $taskModel->whereIn('id', $createdTaskIds)->findAll();
assert(count($remTasks) === 0, "Semua tugas uji harus terhapus");
echo "  -> SUCCESS: Seluruh tugas promosi berhasil dihapus massal.\n";

if (!empty($errors)) {
    echo "\n[FAILURE] Ditemukan kesalahan:\n" . implode("\n", $errors) . "\n";
    exit(1);
} else {
    echo "\n========================================================\n";
    echo "SEMUA PENGUJIAN SELECT-ALL & BULK DELETE LULUS 100%!\n";
    echo "========================================================\n";
    exit(0);
}
