# SPEC-004: Sistem Forced Onboarding Redirect & Auto-Resume Gatekeeper

## 1. Objective & Scope
Mengamankan siklus hidup pendaftaran mitra (onboarding) pada platform Amanah Safar.
Ketika pendaftar telah memasukkan kode voucher dan menyelesaikan form registrasi awal di `/register` namun berhenti di tengah jalan (misalnya menutup browser saat verifikasi data atau PKS), sistem saat ini harus:
1. Menjaga akun dan voucher tetap terkunci aman di database.
2. Mengimplementasikan **Forced Onboarding Redirect (Auto-Resume Gatekeeper)**:
   - Jika pendaftar login kembali, sistem otomatis mendeteksi tahap terakhir yang tertunda dan langsung mengarahkannya ke halaman tersebut (`/verify` atau `/agreement`).
   - Mencegah pendaftar mengakses `/dashboard` dan rute member `/app/*` sebelum seluruh tahapan onboarding selesai 100%.
   - Mencegah pendaftar melompati tahap verifikasi identitas langsung ke tahap PKS.

---

## 2. Onboarding Lifecycle Stages
Siklus pendaftaran terbagi menjadi 3 tahapan sekuensial:

```
[Form Registrasi (/register)]
            │
            ▼ (Akun tercipta di DB, Voucher diklaim secara atomik)
[Tahap 1: Verifikasi Data KTP, NIK & Bank (/verify)]
            │
            ▼ (Data KYC tersimpan: status 'menunggu_review')
[Tahap 2: Tanda Tangan PKS Online (/agreement)]
            │
            ▼ (PKS disetujui: agreement_accepted_at terisi)
[Tahap 3: Onboarding Selesai - Akses Penuh (/dashboard & /app/*)]
```

### Kriteria Status Onboarding (`UserModel::getOnboardingStatus($userId)`):
1. **Akun Administratif** (`super_admin`, `superadmin`, `admin`):
   - Bebas dari onboarding kemitraan. Langsung diarahkan ke panel admin.
2. **Akun Terverifikasi Penuh** (`verification_status = 'terverifikasi'` dan `agreement_accepted_at IS NOT NULL`):
   - Status: `completed = true`, langsung diarahkan ke `/dashboard`.
3. **Pending Tahap 1 (`step = 'verify'`):**
   - Belum memiliki data verifikasi lengkap (KTP, NIK, atau Rekening Bank) dan status masih `belum_verifikasi`.
   - Arahkan ke: `/verify`.
4. **Pending Tahap 2 (`step = 'agreement'`):**
   - Data verifikasi lengkap (KTP, NIK, dan Rekening Bank terisi), tetapi `agreement_accepted_at IS NULL`.
   - Arahkan ke: `/agreement`.
5. **Selesai (`step = 'done'`):**
   - Data verifikasi terisi DAN `agreement_accepted_at IS NOT NULL`.
   - Status: `completed = true`, akses penuh ke `/dashboard`.

---

## 3. Titik Penegakan (Interception Points)

### Point A: `Auth::login()`
- Saat pendaftar memasukkan email & password di `/login`:
  - Sistem membuat sesi login normal.
  - Memeriksa status onboarding via `UserModel::getOnboardingStatus($user['id'])`.
  - Jika belum lengkap (`completed = false`), sistem langsung mengarahkan ke `redirect_url` yang tertunda dengan flash alert yang informatif.

### Point B: `Dashboard::index()`
- Jika pendaftar mengakses URL `/dashboard` secara langsung:
  - Memeriksa status onboarding.
  - Jika belum lengkap, langsung di-redirect ke `redirect_url` onboarding tertunda.

### Point C: `RoleFilter::before()`
- Mengamankan seluruh rute grup `/app/*` (`/app/dashboard`, `/app/packages`, `/app/orders`, `/app/commissions`, `/app/binaan`, `/app/tasks`).
  - Jika user ber-role mitra/cabang/manager mencoba mengakses rute member sebelum onboarding selesai, filter langsung mencegat dan me-redirect ke pending onboarding URL dengan peringatan.

### Point D: `Auth::agreement()`
- Mencegah bypass: Jika pengguna membuka `/agreement` namun belum menyelesaikan verifikasi berkas di `/verify`, sistem me-redirect pengguna kembali ke `/verify`.

---

## 4. Keamanan & Kompatibilitas Sistem Lain (Zero Regression)
- Akun Super Admin & Admin tetap berfungsi normal tanpa hambatan.
- Akun mitra yang sudah aktif (seperti seed account Cabang ID 6 dan Manager ID 7) tetap dapat mengakses dashboard secara normal.
- Fitur `/logout` tetap dapat diakses kapan saja oleh pendaftar di setiap tahap onboarding.
- Seluruh unit test suite dan E2E test yang ada tetap lulus 100%.
