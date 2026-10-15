# SPEC-bulk-delete-superadmin.md
# Spesifikasi Fitur: Select All & Hapus Massal Data untuk Super Admin

## 1. Latar Belakang & Tujuan
Super Admin membutuhkan kemampuan untuk memilih seluruh data (*Select All*) dan melakukan penghapusan massal (*Bulk Delete*) secara langsung pada modul-modul data utama:
1. **Kelola Voucher** (`/superadmin/vouchers`)
2. **Kelola Pengguna** (`/superadmin/users`)
3. **Katalog Paket Umroh** (`/superadmin/packages`)
4. **Konten & Tugas Promosi** (`/superadmin/tasks`)

Fitur ini menghemat waktu administrasi ketika Super Admin perlu membersihkan data pengujian, mereset kode voucher, menghapus akun tidak terpakai, atau mengelola katalog & konten tanpa harus menghapus satu per satu.

---

## 2. Kriteria Penerimaan (Acceptance Criteria)

### A. Modul Kelola Voucher (`/superadmin/vouchers`)
- **AC-V1:** Tabel voucher memiliki kolom checkbox di baris header (`#selectAllVouchers`) dan di setiap baris voucher (`.voucher-row-checkbox`).
- **AC-V2:** Menekan checkbox header memilih seluruh voucher yang aktif/ditampilkan, atau opsi memilih seluruh voucher di sistem.
- **AC-V3:** Ketika 1 atau lebih voucher dipilih, bilah aksi massal (*Floating / Sticky Bulk Action Bar*) muncul dengan informasi jumlah terpilih, tombol "Hapus Voucher Terpilih", dan tombol "Batal".
- **AC-V4:** Modal konfirmasi keamanan meminta persetujuan sebelum eksekusi penghapusan.
- **AC-V5:** Endpoint backend `POST /superadmin/vouchers/bulk-delete` menerima array `ids[]`, memverifikasi CSRF & sesi Super Admin, menghapus data dari database secara atomik/aman, dan mengembalikan pesan sukses.

### B. Modul Kelola Pengguna (`/superadmin/users`)
- **AC-U1:** Tabel pengguna memiliki kolom checkbox di header (`#selectAllUsers`) dan di setiap baris pengguna (`.user-row-checkbox`).
- **AC-U2:** Akun milik Super Admin yang sedang login (`isSelf`) dan akun Super Admin aktif terakhir diproteksi: checkbox dinonaktifkan (`disabled`) dan dilarang dihapus baik di UI maupun backend.
- **AC-U3:** Bilah aksi massal muncul saat pengguna dipilih, dengan tombol "Hapus Pengguna Terpilih".
- **AC-U4:** Endpoint backend `POST /superadmin/users/bulk-delete` memproses soft delete, mengubah status menjadi `nonaktif`, memutus relasi pembina (`pembina_id` anak menjadi `null`), serta mencabut sesi aktif (*revoke user sessions*).

### C. Modul Katalog Paket Umroh (`/superadmin/packages`)
- **AC-P1:** Terdapat checkbox "Pilih Semua" di toolbar atas, serta checkbox pemilihan di setiap kartu paket (tampilan Grid) dan di setiap baris tabel paket (tampilan List).
- **AC-P2:** Bilah aksi massal muncul saat 1 atau lebih paket dipilih, dengan tombol "Hapus Paket Terpilih".
- **AC-P3:** Endpoint backend `POST /superadmin/packages/bulk-delete` menghapus data paket dan membersihkan file gambar fisik dari server jika ada.

### D. Modul Konten & Tugas Promosi (`/superadmin/tasks`)
- **AC-T1:** Terdapat checkbox "Pilih Semua" di toolbar atas dan checkbox pemilihan di setiap kartu tugas.
- **AC-T2:** Bilah aksi massal muncul saat 1 atau lebih tugas dipilih, dengan tombol "Hapus Tugas Terpilih".
- **AC-T3:** Endpoint backend `POST /superadmin/tasks/bulk-delete` menghapus data tugas dan membersihkan file media fisik (gambar/video) via `MediaHelper::deleteFile()`.

### E. Kompatibilitas & Integritas Fitur Lain
- **AC-G1:** Seluruh fitur pencarian, filter status, pengurutan (*sorting*), pagination, modal form tambah/edit, detail preview, dan aksi tunggal yang ada tetap berfungsi normal 100%.

---

## 3. Desain Komponen & Routing
- Route baru di `app/Config/Routes.php` dalam grup `superadmin`:
  - `POST vouchers/bulk-delete` -> `SuperAdmin\Voucher::bulkDelete`
  - `POST users/bulk-delete` -> `SuperAdmin\User::bulkDelete`
  - `POST packages/bulk-delete` -> `SuperAdmin\Package::bulkDelete`
  - `POST tasks/bulk-delete` -> `SuperAdmin\Task::bulkDelete`

---

## 4. Rencana Pengujian
1. Unit/Integration Test CLI untuk setiap method `bulkDelete` (Voucher, User, Package, Task).
2. Verifikasi proteksi akun Super Admin sendiri (tidak bisa dihapus).
3. Verifikasi pembersihan file media fisik pada paket dan tugas.
4. Verifikasi bahwa UI view memuat elemen checkbox dan aksi massal.
