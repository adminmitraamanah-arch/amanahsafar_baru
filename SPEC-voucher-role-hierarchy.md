# SPEC-002: Sistem Voucher Multi-Role dan Penentu Hierarki Akses & Binaan

## 1. Objective & Scope
Memperluas fungsi sistem voucher pada platform Amanah Safar.
Sebelumnya voucher hanya diperuntukkan bagi pendaftaran `mitra`. Melalui revisi ini, voucher dapat di-generate oleh Super Admin untuk:
1. **Cabang** (`cabang`)
2. **Manager Mitra** (`manager_mitra`)
3. **Mitra** (`mitra`)

Saat calon pengguna mendaftar di `/register` dengan kode voucher, voucher ini berfungsi sebagai:
- **Penentu Role Akses**: Akun otomatis terdaftar dengan role yang sesuai (`cabang`, `manager_mitra`, atau `mitra`).
- **Penentu Binaan / Induk Hierarki**: Akun otomatis terhubung ke `pembina_id` yang ditentukan saat penerbitan voucher.

---

## 2. Struktur Format Kode Voucher
Format kode voucher:
`[ROLE_PREFIX]-[JENIS_PEMBINA]-[KODE_PEMBINA]-[NOMOR_URUT]`

### 2.1. Prefix Role Tujuan (`ROLE_PREFIX`):
- `CBG`: Voucher untuk pendaftaran akun **Cabang**
- `MGR`: Voucher untuk pendaftaran akun **Manager Mitra**
- `MIT`: Voucher untuk pendaftaran akun **Mitra**

### 2.2. Kode Jenis Pembina (`JENIS_PEMBINA`):
- `PS`: Pembina langsung adalah **Kantor Pusat** (`pusat`)
- `CB`: Pembina langsung adalah **Cabang** (`cabang`)
- `MG`: Pembina langsung adalah **Manager Mitra** (`manager_mitra`)

### 2.3. Kode Identitas Pembina (`KODE_PEMBINA`):
- 3 digit nomor urut unik identitas akun pembina (`001`, `002`, `012`, dst.) dari `users.kode_pembina`.

### 2.4. Nomor Urut Voucher (`NOMOR_URUT`):
- 4 digit leading zero (`0001`, `0002`, dst.) yang bertambah secara otomatis per pembina dan per role tujuan.

### Contoh Format:
- `CBG-PS-001-0001`: Voucher pendaftaran Cabang, pembina langsung Pusat #001, urutan ke-1.
- `MGR-CB-001-0001`: Voucher pendaftaran Manager Mitra, pembina langsung Cabang #001, urutan ke-1.
- `MGR-PS-001-0001`: Voucher pendaftaran Manager Mitra, pembina langsung Pusat #001, urutan ke-1.
- `MIT-MG-001-0001`: Voucher pendaftaran Mitra, pembina langsung Manager Mitra #001, urutan ke-1.
- `MIT-CB-001-0001`: Voucher pendaftaran Mitra, pembina langsung Cabang #001, urutan ke-1.
- `MIT-PS-001-0001`: Voucher pendaftaran Mitra, pembina langsung Pusat #001, urutan ke-1.

---

## 3. Aturan Matriks Hierarki (Role Tujuan vs Pembina)
| Role Tujuan | Pembina Langsung yang Diizinkan | Aturan & Validasi |
| :--- | :--- | :--- |
| **Cabang** (`cabang`) | **Kantor Pusat** (`pusat`) | Pembina wajib akun aktif ber-role `pusat`. |
| **Manager Mitra** (`manager_mitra`) | **Kantor Pusat** (`pusat`) ATAU **Cabang** (`cabang`) | Pembina wajib akun aktif ber-role `pusat` atau `cabang`. |
| **Mitra** (`mitra`) | **Kantor Pusat** (`pusat`), **Cabang** (`cabang`), ATAU **Manager Mitra** (`manager_mitra`) | Pembina wajib akun aktif. Jika Manager Mitra, akun induknya (Pusat/Cabang) wajib aktif dan valid. |

---

## 4. Alur Bisnis & Registrasi
1. **Super Admin Generate Voucher**:
   - Super Admin memilih **Role Tujuan** (`cabang`, `manager_mitra`, `mitra`).
   - UI secara reaktif memfilter pilihan Jenis Pembina dan Akun Pembina sesuai matriks hierarki:
     - Jika Cabang: Jenis Pembina terkunci ke Pusat.
     - Jika Manager Mitra: Pilihan Pusat atau Cabang.
     - Jika Mitra: Pilihan Pusat, Cabang, atau Manager Mitra.
   - Sistem menentukan nomor urut secara atomik dengan `SELECT FOR UPDATE` berbasis `(pembina_id, role_tujuan)`.
   - Batch insert kode voucher tersimpan di database dengan `role_tujuan` yang tepat.

2. **Pendaftaran Calon Pengguna (`/register`)**:
   - Calon pendaftar memasukkan kode voucher.
   - Sistem memvalidasi keaktifan voucher, status `'tersedia'`, masa berlaku, role tujuan yang sah, dan keaktifan pembina langsung.
   - Pendaftaran membuat akun user baru dengan `role = vouchers.role_tujuan` dan `pembina_id = vouchers.pembina_id`.
   - Jika role baru adalah `cabang` atau `manager_mitra`, inisialisasi `kode_pembina` otomatis agar siap menjadi pembina di masa depan.
   - Voucher diklaim secara atomik (`status = 'terpakai'`).
   - Pendaftar diarahkan ke alur onboarding KYC (`/verify`), Persetujuan PKS digital (`/agreement`), lalu aktivasi dan akses Dashboard.

---

## 5. Success Criteria & Verification
1. Super Admin dapat membuat voucher untuk Cabang, Manager Mitra, dan Mitra.
2. Format kode voucher tepat menggunakan prefix `CBG-`, `MGR-`, dan `MIT-`.
3. Cabang hanya bisa dibina oleh Pusat; Manager Mitra bisa dibina oleh Pusat atau Cabang; Mitra bisa dibina oleh Pusat, Cabang, atau Manager.
4. Saat registrasi, role akses akun baru ditentukan oleh voucher (Cabang mendapatkan role Cabang, Manager mendapatkan role Manager Mitra, Mitra mendapatkan role Mitra).
5. Relasi binaan `pembina_id` tercatat akurat dan muncul di daftar binaan pembina.
6. Seluruh fitur lama (pencarian voucher, sort, pagination, klaim atomik, verifikasi dokumen, PKS) tetap berjalan normal tanpa regresi.
