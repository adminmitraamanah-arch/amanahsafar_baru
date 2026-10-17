# Aturan Kerja Agent AI

Aturan ini berlaku untuk agent AI yang bekerja pada proyek `amanahsafar`. Baca sebelum melakukan perubahan dan gunakan bersama `AGENTS.md`, `rules_ui.md` (untuk pekerjaan antarmuka/UI/UX), serta skill yang relevan.


## 1. Fokus pada instruksi pengguna

- Kerjakan hanya perubahan yang diminta pengguna dan perubahan pendukung yang benar-benar diperlukan untuk memenuhi permintaan tersebut.
- Jangan membuat, menambah, menghapus, atau mengubah fitur, file, tampilan, dependensi, konfigurasi, maupun struktur sistem di luar cakupan permintaan.
- Jangan melakukan refactor, perapian format, optimasi, atau perbaikan masalah lain yang tidak diminta. Laporkan temuan di luar cakupan tanpa mengubahnya.
- Jika perubahan membutuhkan perluasan cakupan atau instruksi ambigu memengaruhi hasil, jelaskan kebutuhan tersebut dan minta klarifikasi sebelum mengerjakan bagian itu.
- Permintaan pemeriksaan, penjelasan, atau "jangan ubah apa pun" hanya mengizinkan pembacaan dan analisis.

## 2. Gunakan bukti, jangan mengarang

- Baca kode dan konfigurasi terkait sebelum mengubahnya. Ikuti pola yang benar-benar ada dalam proyek.
- Jangan mengarang kebutuhan, API, nama file, struktur database, penyebab bug, maupun hasil pengujian.
- Bedakan fakta yang sudah diperiksa dari dugaan. Jika bukti belum cukup, lakukan pemeriksaan yang relevan atau tanyakan informasi yang diperlukan.
- Lakukan perubahan sekecil mungkin yang menyelesaikan permintaan. Pertahankan perilaku lain yang tidak diminta untuk diubah.

## 3. Verifikasi dan lindungi pekerjaan yang sudah ada

- Periksa `git status` dan diff sebelum mengedit untuk mengenali perubahan yang sudah ada.
- Jangan menimpa, membatalkan, atau menyertakan perubahan pengguna maupun pekerjaan lain ke dalam commit tugas tanpa instruksi yang jelas.
- Setelah perubahan, tinjau diff dan jalankan pemeriksaan yang sesuai dengan perubahan. Untuk dokumentasi saja, periksa isi, tautan lokal, dan whitespace; tidak perlu menjalankan tes aplikasi yang tidak terkait.
- Jangan menghapus tes, menonaktifkan pemeriksaan, atau menurunkan standar hanya agar hasil terlihat berhasil.
- Jangan menyatakan perubahan berhasil atau tes lulus jika belum diverifikasi. Laporkan keterbatasan pemeriksaan secara jujur.

## 4. Wajib commit ke Git lokal

- Setiap satu unit perubahan tugas selesai dan diverifikasi, segera buat commit Git lokal sebelum melanjutkan ke unit berikutnya atau mengakhiri tugas. Jangan menunggu pengguna meminta commit lagi.
- Commit mencakup perubahan file yang relevan, termasuk dokumentasi dan konfigurasi. Tidak perlu commit kosong untuk tugas yang hanya membaca atau tidak menghasilkan perubahan.
- Stage hanya file atau bagian diff milik tugas tersebut; jangan memakai `git add .` tanpa memeriksa seluruh perubahan yang akan masuk.
- Periksa staged diff sebelum commit. Jangan menyertakan rahasia, kredensial, atau file lingkungan sensitif.
- Gunakan pesan commit yang jelas sesuai perubahan, misalnya `fix: perbaiki validasi formulir` atau `docs: tambahkan aturan kerja agent`.
- Jika verifikasi atau commit gagal, selesaikan penyebab yang masih dalam cakupan tugas. Jika terhalang, laporkan penyebab dan perubahan yang belum di-commit; jangan mengklaim sudah melakukan commit.
- Jangan melakukan amend atau menulis ulang riwayat commit tanpa instruksi pengguna.

## 5. Push dan pull GitHub wajib konfirmasi

- Sebelum setiap push atau pull ke repository GitHub, ajukan pertanyaan konfirmasi yang menyebut operasi, remote/repository, branch, dan dampak singkatnya.
- Tunggu persetujuan eksplisit pengguna untuk operasi tersebut sebelum menjalankannya. Diam atau tidak menjawab bukan persetujuan.
- Izin mengubah file atau membuat commit lokal bukan izin push maupun pull. Persetujuan operasi sebelumnya tidak berlaku otomatis untuk operasi berikutnya.
- Aturan ini berlaku untuk perintah Git, tombol sinkronisasi, skrip, alias, maupun alat lain yang melakukan push atau pull. Jangan melewati konfirmasi melalui mekanisme lain.
- Jangan mengganti pull dengan fetch lalu merge/rebase untuk melewati kewajiban konfirmasi.
- Jika pengguna belum menyetujui, pertahankan hasil dalam commit lokal dan laporkan statusnya.

## 6. Laporan akhir

- Sampaikan secara singkat perubahan yang dibuat, pemeriksaan beserta hasilnya, dan hash commit lokal.
- Jelaskan jika masih ada pekerjaan yang terhalang atau perubahan yang belum di-commit.
- Nyatakan status push/pull dengan akurat. Jangan mengklaim GitHub sudah diperbarui hanya karena commit lokal berhasil.
