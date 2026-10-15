# Task List: Fitur Select All & Hapus Massal Data Super Admin

- [x] Task 1: Rute & Backend Bulk Delete Controllers
  - Acceptance: Rute `vouchers/bulk-delete`, `users/bulk-delete`, `packages/bulk-delete`, `tasks/bulk-delete` terdaftar. Keempat controller memiliki method `bulkDelete()` dengan validasi CSRF, otentikasi Super Admin, dan proteksi keselamatan.
  - Verify: Syntax lint `php -l` lulus pada Routes dan semua controller.
  - Files: `app/Config/Routes.php`, `app/Controllers/SuperAdmin/Voucher.php`, `app/Controllers/SuperAdmin/User.php`, `app/Controllers/SuperAdmin/Package.php`, `app/Controllers/SuperAdmin/Task.php`

- [x] Task 2: UI Select All & Hapus Massal pada Kelola Voucher
  - Acceptance: Checkbox header `#selectAllVouchers`, checkbox di setiap baris, bilah aksi massal muncul saat dipilih, konfirmasi hapus terhubung ke rute bulk delete.
  - Verify: DOM inspection & pagination sync check.
  - Files: `app/Views/superadmin/vouchers.php`

- [x] Task 3: UI Select All & Hapus Massal pada Kelola Pengguna
  - Acceptance: Checkbox header `#selectAllUsers`, checkbox di setiap baris (disabled untuk `isSelf`), bilah aksi massal dengan konfirmasi.
  - Verify: DOM inspection & self-account disabled check.
  - Files: `app/Views/superadmin/users.php`

- [x] Task 4: UI Select All & Hapus Massal pada Katalog Paket & Tugas
  - Acceptance: Checkbox "Pilih Semua" di toolbar, checkbox pada kartu Grid dan baris List, bilah aksi massal dengan konfirmasi.
  - Verify: DOM inspection pada grid card & table rows.
  - Files: `app/Views/superadmin/packages.php`, `app/Views/superadmin/tasks.php`

- [x] Task 5: Pengujian Otomatis & Verifikasi Akhir
  - Acceptance: Test script `tests/test_superadmin_bulk_delete.php` menguji penghapusan massal voucher, user, paket, tugas, dan proteksi akun Super Admin. Lulus 100%.
  - Verify: Menjalankan test via CLI PHP.
  - Files: `tests/test_superadmin_bulk_delete.php`
