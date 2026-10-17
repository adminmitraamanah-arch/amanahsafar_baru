# Task List: Implementasi Hasil Audit Konten, UI/UX, dan Fitur

- [x] Task 1: Pembersihan Teks, Copywriting & Redundansi Form
  - [x] 1.1: Perbaiki teks login form di `app/Views/auth/login.php` (T-01, T-02, T-03).
  - [x] 1.2: Perbaiki teks register form di `app/Views/auth/register.php` (T-04, T-05, T-06).
  - [x] 1.3: Perbaiki teks verify form di `app/Views/auth/verify.php` (T-07, T-08, T-09, T-10).
  - [x] 1.4: Perbaiki teks profile & package form di `app/Views/profile.php` dan `app/Views/superadmin/package_form.php` (T-15, T-16).
  - [x] 1.5: Verifikasi sintaks PHP dan buat commit lokal Tahap 1.

- [x] Task 2: Eliminasi Dead Code & Sinkronisasi Dokumen Hukum PKS
  - [x] 2.1: Hapus berkas view yatim `app/Views/landing_payungmadinah.php` (UI-04).
  - [x] 2.2: Hapus 4 berkas view mati di `app/Views/partners/` dan rapikan `app/Controllers/Partners.php` (UI-05).
  - [x] 2.3: Hapus box ringkasan 14 pasal usang dan rapikan tombol KTP di `app/Views/superadmin/verification_detail.php` (T-11, UI-06).
  - [x] 2.4: Verifikasi sintaks PHP dan buat commit lokal Tahap 2.

- [x] Task 3: Navigasi, Standarisasi Bahasa & Penyempurnaan UX
  - [x] 3.1: Hapus dot merah statis lonceng notifikasi di `app/Views/layouts/navbar.php` (UI-01).
  - [x] 3.2: Aktifkan link `/profile` di profil sidebar bawah & standarisasi bahasa menu sidebar di `app/Views/layouts/sidebar.php` (UI-02, T-13, T-14).
  - [x] 3.3: Standarisasi Bahasa Indonesia kartu statistik di `app/Views/superadmin/users.php` (T-12).
  - [x] 3.4: Auto-populate nama dari akun ke form verifikasi di `app/Controllers/Auth.php` dan `app/Views/auth/verify.php`.
  - [x] 3.5: Rapikan header duplikasi tombol di `app/Views/member/dashboard.php` (UI-08).
  - [x] 3.6: Verifikasi sintaks PHP dan buat commit lokal Tahap 3.

- [x] Task 4: Verifikasi Pengujian Menyeluruh
  - [x] 4.1: Jalankan sintaks linting `php -l` pada semua berkas yang diubah.
  - [x] 4.2: Jalankan test suite verifikasi.
  - [x] 4.3: Periksa `git status` dan buat laporan akhir.
