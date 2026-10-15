# Plan: Implementasi Fitur Select All & Hapus Massal Super Admin

## Struktur Implementasi
Rencana dibagi menjadi 5 fase vertikal terstruktur:

1. **Fase 1: Routing & Backend Controllers**
   - Menambahkan 4 rute POST `bulk-delete` pada `app/Config/Routes.php`.
   - Menambahkan method `bulkDelete()` pada `SuperAdmin\Voucher.php`.
   - Menambahkan method `bulkDelete()` pada `SuperAdmin\User.php` (dengan proteksi self & last super admin).
   - Menambahkan method `bulkDelete()` pada `SuperAdmin\Package.php` (dengan pembersihan file gambar).
   - Menambahkan method `bulkDelete()` pada `SuperAdmin\Task.php` (dengan pembersihan file media).

2. **Fase 2: Frontend Modul Voucher (`superadmin/vouchers.php`)**
   - Tambah kolom checkbox di `thead` dan `tbody`.
   - Tambah floating bulk action bar dengan konfirmasi modal / prompt.
   - Tambah handler JS select-all & row selection yang sinkron dengan pagination.

3. **Fase 3: Frontend Modul Pengguna (`superadmin/users.php`)**
   - Tambah kolom checkbox di `thead` dan `tbody` (disabled untuk akun aktif sendiri).
   - Tambah floating bulk action bar.
   - Tambah handler JS select-all & row selection.

4. **Fase 4: Frontend Modul Paket (`superadmin/packages.php`) & Tugas (`superadmin/tasks.php`)**
   - Tambah checkbox "Pilih Semua" di toolbar atas.
   - Tambah checkbox pada masing-masing card & baris tabel.
   - Tambah floating bulk action bar & handler JS.

5. **Fase 5: Pengujian Otomatis & Verifikasi Akhir**
   - Buat skrip test CLI `tests/test_superadmin_bulk_delete.php` menguji seluruh endpoint & proteksi.
   - Jalankan `php -l` pada semua berkas yang dimodifikasi.
   - Verifikasi preservasi seluruh fitur eksisting.
