# UI_UX_RULES.md --- Aturan UI/UX Proyek

Aturan ini wajib diterapkan setiap kali membuat, memperbaiki, atau
mengembangkan UI pada proyek ini.

## Prinsip Utama

**Jangan berinisiatif menambahkan sesuatu hanya karena menurut AI
desainnya akan lebih bagus.** Buat perubahan sekecil mungkin yang sudah
cukup untuk memenuhi permintaan pengguna. Jika tidak diminta, tidak
diperlukan, dan tidak diwajibkan demi fungsi atau aksesibilitas, jangan
tambahkan.

Prioritas keputusan: 1. Instruksi eksplisit pengguna. 2. Kebutuhan
fungsional dan aturan bisnis yang sudah berlaku. 3. Kesetiaan terhadap
referensi atau konten sumber yang diberikan. 4. Konsistensi dengan
design system proyek. 5. Kesederhanaan, keterbacaan, dan estetika.

Prinsip minimalis tidak boleh menjadi alasan untuk mengabaikan
instruksi, mengubah konten, menghapus fungsi, atau mengurangi informasi
penting.

## 1. Prinsip Desain Utama

-   Utamakan desain minimalis, bersih, profesional, modern, dan mudah
    dipahami.
-   Terapkan prinsip **Less is More**. Jangan menambahkan elemen hanya
    agar halaman terlihat ramai atau kompleks.
-   Setiap elemen harus memiliki tujuan yang jelas.
-   Prioritaskan hierarki visual, keterbacaan, whitespace, dan kemudahan
    penggunaan.
-   Jangan mengubah identitas visual proyek tanpa instruksi eksplisit.

## 2. Aturan Konten dan Teks

-   Gunakan kalimat singkat, langsung, dan informatif.
-   Hindari paragraf panjang, pengulangan informasi, jargon pemasaran,
    dan kata-kata berlebihan.
-   Hero section maksimal memiliki 1 headline, 1 deskripsi singkat, dan
    1--2 CTA.
-   Headline idealnya 5--12 kata.
-   Deskripsi hero maksimal 20--30 kata.
-   Setiap card cukup memiliki judul, deskripsi maksimal 1--2 kalimat,
    dan informasi penting jika diperlukan.
-   Jangan membuat teks dummy, statistik, testimoni, badge, atau klaim
    yang tidak memiliki sumber data.
-   Jangan menambahkan paragraf pembuka, penutup, tooltip, helper text,
    badge, label, placeholder, atau notifikasi baru jika tidak diminta
    dan tidak diperlukan.
-   Batas jumlah kata bersifat panduan, bukan alasan untuk meringkas
    dokumen hukum, konten wajib, instruksi keselamatan, atau teks yang
    harus lengkap.
-   Jika pengguna meminta teks sama persis dengan sumber, jangan
    mengubah kata, tanda baca, urutan, atau isi sumber.

## 3. Aturan Struktur Halaman

-   Buat hanya section yang benar-benar diperlukan sesuai tujuan
    halaman.
-   Jangan menambahkan section, fitur, menu, atau komponen tanpa alasan
    yang jelas.
-   Hindari pengulangan konten dengan format berbeda.
-   Gunakan gambar yang relevan untuk membantu menyampaikan informasi,
    bukan menggantinya dengan banyak teks.
-   Jangan membuat terlalu banyak card dalam satu tampilan.
-   Jangan menghapus elemen penting hanya karena dianggap kurang
    minimalis.

## 4. Aturan Visual

-   Gunakan whitespace yang cukup agar halaman terasa lega.
-   Batasi penggunaan warna, ukuran font, border, shadow, badge, dan
    dekorasi.
-   Hindari gradient jika tidak diizinkan oleh desain proyek.
-   Animasi harus halus, ringan, dan tidak mengganggu.
-   Pastikan desain responsif pada desktop, tablet, dan mobile.
-   Gunakan komponen yang konsisten dengan design system proyek.
-   Periksa warna, font, ukuran teks, radius, border, shadow, spacing,
    dan ikon yang sudah digunakan sebelum membuat variasi baru.
-   Gunakan kembali komponen yang sudah tersedia jika sesuai.
-   Jangan menambahkan library UI atau ikon baru tanpa kebutuhan yang
    jelas.

## 5. Batasan Perubahan

-   Jangan mengubah fitur, alur bisnis, struktur database, API,
    autentikasi, atau hak akses tanpa instruksi dan kebutuhan yang
    jelas.
-   Jangan menambah fitur hanya berdasarkan asumsi sendiri.
-   Periksa komponen dan halaman yang sudah ada sebelum membuat komponen
    baru.
-   Pertahankan identitas visual dan aturan desain proyek.
-   Jika pengguna hanya meminta perubahan pada satu komponen, jangan
    mengubah bagian lain yang tidak berkaitan.
-   Jangan melakukan refactor, redesign, atau optimasi di luar scope.
-   Laporkan temuan di luar scope secara terpisah; jangan langsung
    mengerjakannya.

## 6. Fungsionalitas dan Interaksi

-   Setiap tombol, input, dropdown, tab, modal, dan kontrol interaktif
    harus berfungsi sesuai tujuannya.
-   Jangan membuat tombol atau elemen interaktif yang hanya terlihat
    aktif tetapi tidak memiliki fungsi.
-   Pertahankan validasi form, loading state, error state, notifikasi,
    dan mekanisme penyimpanan yang sudah ada.
-   Berikan feedback yang jelas saat tindakan berhasil, gagal, atau
    sedang diproses jika diperlukan.
-   Jangan mengubah alur bisnis, API, database, autentikasi, atau hak
    akses demi menyederhanakan UI.
-   Jangan menghilangkan informasi atau tindakan penting demi mengurangi
    jumlah elemen.

## 7. Responsivitas dan Aksesibilitas

-   Pastikan halaman berfungsi pada desktop, tablet, dan mobile.
-   Cegah teks terpotong, elemen bertumpuk, tombol sulit ditekan, dan
    horizontal overflow yang tidak diperlukan.
-   Gunakan ukuran teks yang mudah dibaca dan kontras warna yang
    memadai.
-   Jangan mengandalkan warna saja untuk menyampaikan status atau
    informasi penting.
-   Sediakan label yang jelas untuk input dan nama aksesibel untuk
    tombol ikon.
-   Pastikan interaksi utama dapat digunakan dengan keyboard jika
    relevan.
-   Jangan menghilangkan fungsi penting pada mobile hanya untuk
    menyederhanakan tampilan.

## 8. Referensi Desain

-   Jika pengguna memberikan screenshot, mockup, PDF, atau desain
    referensi, jadikan referensi tersebut acuan utama sesuai instruksi.
-   Pertahankan struktur, elemen penting, hierarki, dan konten yang
    diminta.
-   Jangan menambahkan konsep desain sendiri yang mengubah arah
    referensi.
-   Jika referensi bertentangan dengan instruksi eksplisit pengguna,
    prioritaskan instruksi pengguna.
-   Jangan menganggap teks pada referensi boleh ditulis ulang jika
    pengguna meminta kesamaan persis.
-   Jika bagian referensi tidak jelas atau tidak dapat dibaca, jangan
    menebak bagian yang penting; laporkan atau minta klarifikasi.

## 9. Workflow Wajib

Sebelum implementasi: 1. Periksa struktur proyek, halaman, komponen, dan
design system yang tersedia. 2. Pahami tujuan utama dan scope perubahan.
3. Identifikasi fitur serta perilaku yang harus dipertahankan. 4.
Tentukan perubahan minimum yang diperlukan. 5. Periksa status Git dan
perubahan lokal jika pekerjaan melibatkan kode.

Setelah implementasi: 1. Periksa tampilan aktual, bukan hanya kode. 2.
Bandingkan hasil dengan instruksi dan referensi pengguna. 3. Periksa
responsivitas desktop, tablet, dan mobile sesuai kebutuhan. 4. Pastikan
interaksi utama berfungsi. 5. Periksa error dan regresi fitur terkait.
6. Periksa diff untuk memastikan tidak ada perubahan di luar scope. 7.
Laporkan hasil pengujian dengan jujur.

## 10. Checklist Sebelum Selesai

-   Apakah ada teks yang bisa dipersingkat tanpa mengubah makna atau
    melanggar sumber?
-   Apakah ada section atau card yang tidak diperlukan?
-   Apakah ada informasi yang berulang?
-   Apakah fokus utama halaman langsung terlihat?
-   Apakah whitespace sudah cukup?
-   Apakah desain tetap konsisten dengan proyek?
-   Apakah fitur lama tetap berfungsi?
-   Apakah hasil sesuai dengan instruksi dan referensi?
-   Apakah ada perubahan di luar scope yang harus dibatalkan?

Jika suatu elemen tidak membantu pengguna memahami informasi atau
menyelesaikan tujuannya, jangan tambahkan elemen tersebut.

## 11. Verifikasi dan Laporan

-   Jalankan pengujian yang relevan jika lingkungan memungkinkan.
-   Jangan menyatakan pengujian berhasil jika tidak benar-benar
    dijalankan.
-   Bedakan status **SUDAH DIUJI** dan **BELUM DIUJI**.
-   Laporkan secara ringkas apa yang berubah, file yang diubah, hasil
    pengujian, dan hal yang belum diverifikasi.
-   Jangan menulis laporan panjang atau menambahkan saran di luar
    kebutuhan pengguna.

## Target Akhir

Halaman yang sederhana, elegan, mudah dipindai, konsisten, responsif,
dan profesional --- bukan halaman yang memiliki elemen paling banyak.

**Aturan terakhir: jika tidak diminta, tidak diperlukan, dan tidak
diwajibkan demi fungsi atau aksesibilitas, jangan tambahkan.**
