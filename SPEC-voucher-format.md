# SPEC-001: Implementasi Format Kode Voucher Mitra Amanah

## 1. Objective & Background
Mengimplementasikan format kode voucher terstruktur dan hierarkis pada Sistem Mitra Amanah dengan format:
`MIT-[JENIS PEMBINA]-[KODE PEMBINA]-[NOMOR URUT]`
Contoh: `MIT-MG-012-0001`.

Format ini memiliki arti jelas pada setiap segmen:
- `MIT`: Voucher khusus pendaftaran akun Mitra.
- `PS`: Pembina langsung adalah Kantor Pusat.
- `CB`: Pembina langsung adalah Cabang.
- `MG`: Pembina langsung adalah Manager Mitra.
- `012`: Kode identitas unik pembina (disimpan permanen di database `users.kode_pembina`).
- `0001`: Nomor urut voucher untuk pembina tersebut (4 digit dengan leading zero, bertambah otomatis).

## 2. Database Schema Changes (Migration)
### 2.1. Tabel `users`
- Tambah kolom `kode_pembina` (`VARCHAR(10) NULL`):
  - Menyimpan kode identitas permanen pembina (`001`, `002`, `012`, dst.).
  - Nilai ini independen dari nama akun atau domisili, sehingga jika nama pengguna berubah, kode voucher lama dan baru tetap konsisten.
  - Untuk role `pusat`: default `'001'` (karena Pusat hanya memiliki 1 akun).
  - Untuk role `cabang` dan `manager_mitra`: nomor urut 3 digit unik per role (`001`, `002`, `003`, dst.).

### 2.2. Tabel `vouchers`
- Tambah kolom `nomor_urut` (`INT(11) UNSIGNED NULL`):
  - Menyimpan angka nomor urut voucher untuk pembina terkait (`1`, `2`, `3`, dst.).
  - Indeks pada `(pembina_id, nomor_urut)` untuk pencarian MAX sequence cepat dan pencegahan duplikasi.
  - Kolom `expired_at` tetap terpisah bertipe `DATETIME` / `DATE`.

## 3. Business Logic & Generation Rules
1. **Penerbitan Otomatis**:
   - Super Admin memilih akun pembina langsung (`pembina_id`) dan `qty` (1 - 100).
   - Sistem mendeteksi role pembina:
     - `pusat` -> `'PS'`
     - `cabang` -> `'CB'`
     - `manager_mitra` -> `'MG'`
   - Sistem mengambil atau menginisialisasi `kode_pembina` secara permanen.
2. **Nomor Urut Per Pembina**:
   - Setiap pembina memiliki penghitung nomor urut mandiri yang dimulai dari `0001`.
   - Menggunakan transaksi database ber-kunci pesimistik (`SELECT MAX(nomor_urut) ... FOR UPDATE`) untuk mengamankan konkurensi (mencegah *race condition* saat beberapa voucher dibuat bersamaan).
3. **Format Kode Voucher**:
   - `MIT-{$jenis}-{$kodePembina}-{$nomorUrutFormatted}`
4. **Validasi & Registrasi**:
   - Registrasi calon Mitra (`/register`) tetap menggunakan kode voucher ini.
   - Sistem memvalidasi keaktifan voucher, masa berlaku, keaktifan akun pembina, serta relasi induk Manager Mitra.
   - Pendaftaran menetapkan akun Mitra baru dengan `pembina_id` yang sesuai dari data voucher.
   - Klaim voucher berlangsung atomik (`claimAtomically`).

## 4. Rencana File yang Diubah
1. `app/Database/Migrations/2026101101_AddVoucherFormatColumns.php` (Migration baru)
2. `app/Models/UserModel.php` (Menambahkan `kode_pembina` ke `allowedFields`, helper `getOrAssignKodePembina`)
3. `app/Models/VoucherModel.php` (Menambahkan `nomor_urut` ke `allowedFields`, helper `getNextNomorUrut`, parsing kode voucher)
4. `app/Controllers/SuperAdmin/Voucher.php` (Update `index` untuk passing `kode_pembina` & preview, update `generate` dengan format baru ber-transaksi dan locking)
5. `app/Views/superadmin/vouchers.php` (Update UI dropdown pembina dengan kode pembina, live preview kode voucher di modal)
6. `app/Controllers/Auth.php` (Memastikan regex / format baru kompatibel penuh dengan validasi & alur registrasi)

## 5. Rencana Pengujian (Test Suite)
1. **Pengujian Jenis Pembina Pusat**: Terbentuk `MIT-PS-001-XXXX`
2. **Pengujian Jenis Pembina Cabang**: Terbentuk `MIT-CB-XXX-XXXX`
3. **Pengujian Jenis Pembina Manager Mitra**: Terbentuk `MIT-MG-XXX-XXXX`
4. **Pengujian Nomor Urut Bertambah Otomatis**: Urutan 0001, 0002, 0003 per pembina
5. **Pengujian Independensi Urutan Antar-Pembina**: Pembina A dan Pembina B masing-masing mulai dari 0001
6. **Pengujian Permanensi Kode Pembina**: Mengubah nama akun tidak mengubah `kode_pembina`
7. **Pengujian Voucher Kedaluwarsa**: Voucher dengan `expired_at < now` ditolak saat registrasi
8. **Pengujian Voucher Terpakai**: Voucher yang sudah diklaim ditolak
9. **Pengujian Konkurensi / Race Condition**: Dua request penerbitan voucher bersamaan menghasilkan nomor urut unik tanpa duplikasi
