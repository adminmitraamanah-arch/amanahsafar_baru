# Konfirmasi pendaftaran melalui tautan WhatsApp

Implementasi mengikuti pembaruan instruksi tanpa Meta API/webhook.

## Perubahan

- `app/Controllers/Auth.php`: setelah persetujuan PKS berhasil, menuju halaman konfirmasi. Proses persetujuan, aktivasi, dan PDF tetap menggunakan mekanisme sebelumnya.
- `app/Config/Routes.php`: GET/POST halaman konfirmasi dengan filter maintenance dan CSRF.
- `app/Controllers/RegistrationConfirmation.php`: memvalidasi sesi dan akun, mewajibkan PKS sudah disetujui, membaca `contact_whatsapp` pada setiap request, mencatat klik pada tabel `audit_logs` yang sudah ada, lalu redirect 303 ke WhatsApp.
- `app/Libraries/RegistrationWhatsApp.php`: normalisasi nomor dan encoding pesan aktual; tidak menyertakan NIK. Nomor PKS menggunakan `PksPdfService::getPksNumber()`.
- `app/Views/auth/registration_confirmation.php`: tombol konfirmasi, status klik, error, dan panduan kembali. Tidak menyatakan pesan terkirim/diterima.
- `app/Views/shared/profile_pks_card.php`: tautan untuk membuka kembali halaman konfirmasi bagi mitra yang sudah menyetujui PKS.
- Dua tes baru: `tests/test_registration_whatsapp.php` dan `tests/test_registration_confirmation.php`.

Tidak ada dependency, tabel, perubahan dokumen historis, atau tombol baru di Super Admin. Audit menyimpan ID pengguna/entitas, jenis peristiwa, dan waktu; tidak menyimpan isi pesan atau IP. Klik ulang menambah peristiwa klik saja, tanpa membuat pendaftaran/PKS baru.

## Verifikasi aktual

- `C:\xampp\php\php.exe tests/test_registration_whatsapp.php`: 19 pemeriksaan lulus.
- `C:\xampp\php\php.exe tests/test_registration_confirmation.php`: 18 pemeriksaan integrasi lulus; memakai fixture database lokal dalam transaksi dan memastikan rollback. Termasuk penerima dinamis, sesi, manipulasi identitas, PKS belum disetujui, akun nonaktif, CSRF, nomor invalid, status klik, pengulangan, serta data akun/PKS tidak berubah.
- Lima suite regresi lulus: `test_pks_identity_and_agreements.php` (66), `test_pks_required_asterisk_and_submit.php` (17), `test_profile_pks_role_visibility.php`, `test_forced_onboarding_redirect.php` (dengan assertions aktif), `test_pks_pdf_viewer_feature.php` (36).
- PHP lint file PHP yang diubah dan pemeriksaan whitespace lulus. `spark routes` menunjukkan filter CSRF pada kedua route baru.
- Mutation check: membalik kondisi hasil insert audit membuat tes integrasi gagal; setelah kode dipulihkan, tes lulus lagi.
- Render template nyata menggunakan fixture diperiksa di browser pada tampilan sekitar 668px dan frame mobile 320px. Tidak ditemukan error/warning console pada preview. Frame 768px/1440px dimuat; pemeriksaan visual menyeluruh kedua ukuran tersebut belum dilakukan.

## Batasan

- Validasi nomor memeriksa format, bukan keberadaan akun WhatsApp.
- Tidak mengirim pesan nyata atau menguji aplikasi WhatsApp perangkat pengguna. Sistem memang tidak memverifikasi pengiriman/penerimaan pesan.
- Gangguan insert audit ditangani dengan error tanpa redirect; kegagalan database nyata belum disimulasikan.
- PHPUnit penuh tidak berjalan karena executable `vendor/phpunit/phpunit/phpunit` belum tersedia. Tidak memasang dependency tambahan.
- Tidak ada deployment atau push/pull GitHub.
