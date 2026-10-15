# Spec: Pengembangan Menu Tugas & Konten Promosi (Sistem Mitra Amanah)

## Objective
Mengembangkan modul **Tugas** pada Sistem Mitra Amanah menjadi pusat distribusi konten promosi terpadu. Super Admin dan Admin dapat membuat, mempublikasikan, mengedit, dan menghapus tugas promosi lengkap dengan media (gambar/video), copywriting, dan catatan instruksi. Pengguna dengan role Cabang, Manager Mitra, dan Mitra dapat melihat seluruh tugas yang telah dipublikasikan, memutar video langsung, melihat preview gambar, menyalin copywriting dengan 1-klik, dan mengunduh berkas konten melalui jalur unduhan yang terautentikasi dan aman.

Semua role menggunakan tampilan, struktur daftar, dan fitur kartu tugas yang konsisten (tanpa filter sekat hierarki pembinaan atau role pengguna).

---

## Assumptions
1. **Penyimpanan Berkas & Batas Ukuran:** Batas maksimum ukuran berkas upload konten adalah **600 MB** (baik gambar maupun video).
2. **Kompresi Otomatis (Auto-Compression):**
   - Untuk berkas gambar yang besar, sistem melakukan kompresi otomatis (optimasi dimensi dan kualitas JPEG/PNG/WebP) sebelum disimpan ke disk untuk menghemat kapasitas penyimpanan dan mempercepat loading saat diakses oleh mitra.
   - Untuk berkas video hingga batas 600 MB didukung secara aman dengan streaming pemutar video HTML5 dan chunking pengunduhan yang efisien.
3. **Keamanan Unduhan:** Akses unduhan konten (`download`) diatur melalui controller backend yang memverifikasi sesi login (`session('logged_in')`), bukan link file statis terbuka tanpa proteksi.
4. **Filter Publikasi:**
   - Super Admin & Admin dapat melihat semua tugas (baik berstatus `draft` maupun `published`) dan dapat memfilter berdasarkan status publikasi.
   - Cabang, Manager Mitra, dan Mitra hanya dapat melihat tugas yang berstatus `published`.
4. **Struktur Database:** Menambahkan kolom `kategori`, `copywriting`, `file_path`, `file_type`, `file_name`, `file_size`, dan `status` (`draft`/`published`) pada tabel `tasks` melalui migrasi database CodeIgniter 4 tanpa menghapus data yang sudah ada.

---

## Tech Stack
- **Framework:** CodeIgniter 4.7.4 (PHP 8.2+)
- **Database:** MySQL / MariaDB (tabel `tasks`, `task_files`, `users`)
- **Frontend / Styling:** Tailwind CSS + FontAwesome Icons (Aksen Ungu Brand Amanah Safar `#5e0078`)
- **Interaktivitas:** Vanilla JavaScript (Clipboard API, HTML5 Video Player, Toast Notification, `AmanahTable` pagination & sorting)

---

## Commands
- **Migrasi Database:** `c:\xampp\php\php.exe spark migrate`
- **Cek Status Rute:** `c:\xampp\php\php.exe spark routes`
- **Lint / Sintaks:** `c:\xampp\php\php.exe -l <filepath>`

---

## Project Structure
```
app/
├── Database/Migrations/
│   └── 2026-10-11-XXXXXX_UpdateTasksForPromotionalContent.php  → Migrasi kolom kategori, media, copywriting, status
├── Models/
│   └── TaskModel.php                                           → Allowed fields & query helper
├── Controllers/
│   ├── SuperAdmin/Task.php                                     → CRUD, upload media, toggle status, download (Super Admin)
│   ├── Admin/Task.php                                          → CRUD, upload media, toggle status, download (Admin & Pusat)
│   └── Member/Task.php                                         → Read-only view tugas published & download (Cabang, Manager, Mitra)
└── Views/
    ├── superadmin/tasks.php                                    → Tampilan kartu tugas + modal CRUD + preview + copy + download
    ├── admin/tasks.php                                         → Tampilan kartu tugas + modal CRUD + preview + copy + download
    └── member/tasks.php                                        → Tampilan kartu tugas member (Read-only + player + copy + download)
public/
└── uploads/tasks/                                              → Direktori publik/terproteksi untuk preview media promosi
```

---

## Data Schema & Migrasi (`tasks`)
Kolom baru yang ditambahkan:
- `kategori` (`VARCHAR(100)`): Nilai kategori konten (`Promosi Paket Umrah`, `Testimoni`, `Edukasi`, `Informasi`).
- `copywriting` (`TEXT` / `LONGTEXT`): Teks narasi/caption promosi yang siap disalin oleh mitra.
- `catatan` (`TEXT`): Instruksi tata cara pembagian konten ke media sosial / WhatsApp.
- `file_path` (`VARCHAR(255)`): Nama file acak yang tersimpan di disk.
- `file_type` (`VARCHAR(20)`): Jenis media (`image` atau `video`).
- `file_name` (`VARCHAR(255)`): Nama asli file saat diunggah (digunakan untuk header file download).
- `file_size` (`INT UNSIGNED`): Ukuran berkas dalam bytes.
- `status` (`ENUM('draft', 'published')` default `'published'`): Status penerbitan tugas.

---

## User Stories & Acceptance Criteria

### 1. Pengelolaan Tugas (Super Admin & Admin)
- **Kriteria Penerimaan 1.1:** Form modal tambah dan edit tugas memiliki field: Judul Konten, Kategori (Dropdown dengan opsi: Promosi Paket Umrah, Testimoni, Edukasi, Informasi), Upload Media (Gambar / Video), Teks Copywriting, Catatan/Instruksi Pembagian, Tenggat Waktu (opsional), dan Status Publikasi (`draft` atau `published`).
- **Kriteria Penerimaan 1.2:** Validasi backend menolak jenis berkas yang tidak diizinkan (hanya mengizinkan `image/png, image/jpeg, image/jpg, image/webp` dan `video/mp4, video/webm, video/quicktime`) serta batas ukuran berkas maksimum.
- **Kriteria Penerimaan 1.3:** Saat mengedit, Admin dapat mengganti file media atau tetap mempertahankan berkas lama. Jika file diganti, berkas lama otomatis dihapus dari server.
- **Kriteria Penerimaan 1.4:** Super Admin dan Admin dapat menghapus tugas (soft delete) dengan konfirmasi aman.
- **Kriteria Penerimaan 1.5:** Super Admin dan Admin dapat melihat badge status (`Published` / `Draft`).

### 2. Tampilan Kartu Tugas (Semua Role)
- **Kriteria Penerimaan 2.1:** Seluruh role melihat tampilan desain berbentuk kartu responsif yang konsisten dengan tema Amanah Safar (`#5e0078`).
- **Kriteria Penerimaan 2.2:** Bagian atas kartu menampilkan judul, badge kategori berwarna, dan tanggal dibuat.
- **Kriteria Penerimaan 2.3:** Jika media adalah video, tampil pemutar video `<video controls>` langsung di kartu. Jika gambar, tampil preview gambar resolusi penuh/terpotong rapi dengan kemampuan klik untuk memperbesar/melihat.
- **Kriteria Penerimaan 2.4:** Bagian Copywriting ditampilkan rapi dalam container teks dengan tombol **Salin Copywriting**. Menekan tombol ini otomatis menyalin teks ke clipboard dan memunculkan toast/notifikasi feedback: *"Copywriting berhasil disalin!"*.
- **Kriteria Penerimaan 2.5:** Bagian Catatan/Instruksi disajikan dengan format jelas (ikon instruksi, background soft).
- **Kriteria Penerimaan 2.6:** Tombol **Download Konten** memicu pengunduhan berkas media asli ke perangkat pengguna, disertai notifikasi: *"Unduhan dimulai..."*.

### 3. Hak Akses & Keamanan Backend
- **Kriteria Penerimaan 3.1:** Route pembuatan (`store`), pembaruan (`update`), dan penghapusan (`delete`) dilindungi middleware role filter di backend. Pengguna `mitra`, `manager_mitra`, atau `cabang` yang mencoba mengakses endpoint tersebut akan ditolak (403 / redirect error).
- **Kriteria Penerimaan 3.2:** Pengguna `mitra`, `manager_mitra`, dan `cabang` hanya mengambil tugas dengan filter database `status = 'published'`. Tugas berstatus `draft` tidak akan pernah dikirim ke frontend member.
- **Kriteria Penerimaan 3.3:** Endpoint unduhan berkas memverifikasi bahwa pengguna memiliki sesi login yang valid (`session('logged_in')`) sebelum menyajikan file.

---

## Testing Strategy
1. **Migrasi Database:** Uji eksekusi migrasi tanpa error dan verifikasi skema kolom tabel `tasks`.
2. **Pengujian Controller & Auth:**
   - Uji pembuatan tugas dengan upload gambar (PNG/JPG) dan video (MP4).
   - Uji penolakan akses POST update/delete dari role non-admin.
   - Uji filter draft vs published pada session member vs admin.
3. **Pengujian UI & Interaktivitas:**
   - Verifikasi pemutaran video langsung di browser.
   - Verifikasi penyalinan clipboard dengan notifikasi toast.
   - Verifikasi download berkas dengan nama asli.
   - Verifikasi responsivitas pada layar mobile dan desktop.

---

## Boundaries
- **Always:**
  - Pertahankan fitur lain (CSRF token, sorting, pagination `AmanahTable`, sistem login/auth).
  - Gunakan badge kategori yang jelas dan desain selaras dengan warna ungu `#5e0078`.
  - Hapus file fisik lama saat file diganti atau saat tugas dihapus jika diperlukan.
- **Ask First:**
  - Mengubah struktur tabel lain selain tabel `tasks` dan `task_files`.
- **Never:**
  - Mengizinkan unduhan file oleh pengguna tanpa login (*unauthenticated access*).
  - Mengizinkan upload berkas berekstensi berbahaya (misal: `.php`, `.sh`, `.exe`).
  - Menghapus fungsionalitas pencarian, filter, dan pagination yang sudah ada.

---

## Success Criteria
1. Super Admin dan Admin dapat membuat tugas lengkap dengan gambar/video, copywriting, kategori, catatan instruksi, dan status publikasi.
2. Cabang, Manager Mitra, dan Mitra melihat kartu tugas yang sama persis (hanya yang sudah dipublikasikan), bisa memutar video langsung, melihat gambar, menyalin copywriting dalam 1 klik, dan mengunduh konten.
3. Tampilan kartu responsif, elegan, seragam di semua role.
4. Semua pengujian lulus dan tidak ada error pada sistem.
