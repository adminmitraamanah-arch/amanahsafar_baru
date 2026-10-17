# Plan: Implementasi Perubahan Berdasarkan Hasil Audit Konten, UI/UX, dan Fitur

## Referensi Sumber
- Hasil Audit: `AUDIT_KONTEN_DAN_FITUR.md`
- Panduan Aturan Kerja: `rules.md`
- Panduan UI/UX: `rules_ui.md`
- Panduan Agent: `AGENTS.md`

## Pembagian Tahap Kerja (Vertical Slices)

### Tahap 1: Pembersihan Teks, Copywriting, dan Redundansi Form
- `app/Views/auth/login.php`:
  - Ringkas label "Email Aktif" -> "Alamat Email", placeholder `nama@email.com`, hapus helper repetitif (T-01).
  - Ringkas placeholder kata sandi (T-02).
  - Ringkas footer kartu disclaimer (T-03).
- `app/Views/auth/register.php`:
  - Ringkas callout info voucher kemitraan agar tidak mengulang panel kiri (T-04).
  - Rapikan placeholder kode voucher menjadi `Contoh: VCR-ABC123` (T-05).
  - Rapikan placeholder nama lengkap menjadi `Nama sesuai KTP` (T-06).
- `app/Views/auth/verify.php`:
  - Ringkas teks deskripsi pada status box KTP (T-07).
  - Ringkas label dan placeholder nama KTP, hilangkan helper repetitif 3x (T-08).
  - Ringkas label NIK dan helper 16 digit (T-09).
  - Satukan pesan error dan helper pemilik rekening bank agar tidak duplikat (T-10).
- `app/Views/profile.php`:
  - Ringkas teks helper ubah password (T-16).
- `app/Views/superadmin/package_form.php`:
  - Hilangkan pengulangan prefiks "Contoh:" pada placeholder form paket (T-15).

### Tahap 2: Eliminasi Dead Code & Sinkronisasi Dokumen Hukum PKS
- Hapus berkas orphaned: `app/Views/landing_payungmadinah.php` (UI-04).
- Hapus berkas view mati: `app/Views/partners/login.php`, `register.php`, `verification.php`, `agreement.php` (UI-05).
- Sederhanakan `app/Controllers/Partners.php` dan `app/Config/Routes.php` (UI-05).
- `app/Views/superadmin/verification_detail.php`:
  - Hapus box scroll ringkasan 14 pasal usang yang bertentangan dengan single source of truth 19 pasal (T-11).
  - Pertahankan dan perjelas rujukan ke dokumen PKS 19 pasal resmi dan tombol cetak PDF.
  - Rapikan 3 tombol aksi KTP berdampingan menjadi 2 tombol ("Perbesar" & "Unduh") (UI-06).

### Tahap 3: Navigasi, Standarisasi Bahasa & Penyempurnaan UX
- `app/Views/layouts/navbar.php`:
  - Hapus dot merah statis palsu pada tombol lonceng notifikasi (UI-01).
- `app/Views/layouts/sidebar.php`:
  - Hubungkan kartu profil bawah sidebar ke `/profile` (UI-02).
  - Standarisasi istilah bahasa Indonesia:
    - SuperAdmin: `Tasks` -> `Tugas & Promosi`, `Vouchers` -> `Voucher Kemitraan`, `Users` -> `Data Pengguna`, `Verifications` -> `Verifikasi Pengguna`, `Landing Page` -> `Kelola Landing Page` (T-13, T-14).
    - Admin, Pusat, Member: `Tasks` -> `Tugas & Promosi`. `Orders` di member/pusat -> `Pesanan`.
- `app/Views/superadmin/users.php`:
  - Standarisasi label kartu statistik ke Bahasa Indonesia baku (T-12).
- `app/Controllers/Auth.php` & `app/Views/auth/verify.php`:
  - Auto-populate nama lengkap pengguna dari akun terdaftar ke form verifikasi KTP dan pemilik rekening bank.
- `app/Views/member/dashboard.php`:
  - Rapikan header dan tombol duplikat (UI-08).

### Tahap 4: Verifikasi Menyeluruh & Testing
- Jalankan sintaks linting `php -l` pada semua berkas PHP yang dimodifikasi.
- Jalankan automated tests terkait.
- Buat commit Git lokal untuk setiap tahap selesai sesuai `rules.md`.
