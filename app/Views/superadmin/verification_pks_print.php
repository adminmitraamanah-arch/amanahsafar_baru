<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKS - <?= esc($pksNumber ?? 'PKS/AS') ?> - <?= esc($user['nama_lengkap'] ?? 'Mitra') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f1f5f9;
            color: #000000;
            font-size: 10pt;
            line-height: 1.42;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Screen Action Bar */
        .action-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-bottom: 1px solid #cbd5e1;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .action-bar .title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 15px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }
        .btn-print {
            background-color: #5e0078;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #4a005f;
        }
        .btn-close {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-close:hover {
            background-color: #e2e8f0;
        }

        /* Page Structure (Exact 14-page A4 sheet replication) */
        @page {
            size: 210mm 297mm;
            margin: 0;
        }
        .pks-document {
            width: 100%;
        }
        .pks-page {
            width: 210mm;
            height: 297mm;
            min-height: 297mm;
            max-height: 297mm;
            padding: 16mm 22mm 14mm 22mm;
            margin: 18px auto;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
        }

        .pks-running-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8.5pt;
            color: #1e293b;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .pks-running-header.empty-header {
            visibility: hidden;
            margin-bottom: 0;
            padding-bottom: 0;
            height: 0;
        }

        .pks-page-body {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.42;
        }

        .pks-running-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8.5pt;
            color: #1e293b;
            padding-top: 6px;
            margin-top: auto;
        }

        /* Headings and Typography */
        .doc-main-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-top: 6px;
            margin-bottom: 4px;
        }
        .doc-sub-title {
            text-align: center;
            font-size: 10pt;
            font-weight: normal;
            margin-bottom: 12px;
        }
        .title-divider {
            border-top: 1.5px solid #000000;
            margin-bottom: 14px;
        }
        .pasal-title {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
            margin-bottom: 2px;
        }
        .pasal-subtitle {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .pks-p {
            margin-bottom: 6px;
            text-indent: 0;
            text-align: justify;
            text-justify: inter-word;
        }
        .lampiran-title {
            text-align: center;
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 6px;
            margin-bottom: 2px;
        }
        .lampiran-subtitle {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .section-label {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 6px;
            margin-bottom: 6px;
        }

        /* Matriks Table on Page 13 */
        .matriks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 10px;
            font-size: 8pt;
            line-height: 1.25;
        }
        .matriks-table th {
            background-color: #1e3a5f;
            color: #ffffff;
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            text-align: center;
            font-weight: bold;
            font-size: 8.5pt;
        }
        .matriks-table td {
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            text-align: left;
        }
        .matriks-table td.col-activity {
            width: 22%;
            font-weight: bold;
            color: #0f172a;
        }
        .matriks-table td.col-mitra {
            width: 39%;
        }
        .matriks-table td.col-perusahaan {
            width: 39%;
        }

        /* Signatures Container on Page 14 */
        .signatures-wrapper {
            margin-top: 10px;
            width: 100%;
        }
        .signatures-heading {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .signatures-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }
        .sig-col {
            width: 48%;
            font-size: 9.5pt;
        }
        .sig-col .sig-party-role {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .sig-col .sig-party-name {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .sig-box {
            height: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 6px 0;
            border-radius: 6px;
        }
        .sig-seal {
            border: 1.5px dashed #059669;
            background: #ecfdf5;
            color: #047857;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 8pt;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            text-align: center;
            line-height: 1.35;
        }
        .sig-seal.pending {
            border-color: #94a3b8;
            background: #f8fafc;
            color: #64748b;
        }
        .sig-desc {
            font-size: 8.5pt;
            line-height: 1.3;
        }

        /* Print Overrides */
        @media print {
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .action-bar {
                display: none !important;
            }
            .pks-page {
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always !important;
                break-after: page !important;
            }
        }

        <?php if (!empty($isPdf)): ?>
        body {
            background: #ffffff !important;
        }
        .pks-page {
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }
        <?php endif; ?>
    </style>
</head>
<body>

    <?php if (empty($hideToolbar)): ?>
    <!-- Action Toolbar (Hidden during print / PDF generation) -->
    <div class="action-bar">
        <div class="title">
            <i class="fas fa-file-contract text-purple-700"></i>
            <span>Dokumen Resmi PKS Digital &bull; <?= esc($pksNumber ?? 'PKS/AS') ?></span>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="action-btn btn-print">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="action-btn btn-close">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="pks-document">

        <!-- ========================================== -->
        <!-- HALAMAN 1 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-1">
            <div class="pks-running-header empty-header"></div>
            <div class="pks-page-body">
                <div class="doc-main-title">PERJANJIAN KERJA SAMA MITRA AMANAH</div>
                <div class="doc-sub-title">PT Amanah Tangguh Mandiri dengan merek Amanah Safar</div>
                <div class="title-divider"></div>

                <p class="pks-p">Perjanjian ini dibuat oleh dan antara para pihak berikut.</p>
                <p class="pks-p">1. PT AMANAH TANGGUH MANDIRI, dengan merek AMANAH SAFAR, berkedudukan di Gedung Graha Pool Lt. 3 Unit 303-304, Jl. Merdeka No. 110, Ciwaringin, Bogor Tengah, Kota Bogor, Jawa Barat, diwakili oleh M. Erwin Saputra selaku Direktur Utama atau pejabat yang memperoleh kewenangan sah darinya, selanjutnya disebut Perusahaan atau Pusat.</p>
                <p class="pks-p">2. Orang perseorangan yang nama, NIK, alamat lengkap, dan kontaknya telah diverifikasi serta ditampilkan dalam Ringkasan Kemitraan yang disetujui bersamaan dengan Perjanjian ini, bertindak untuk diri sendiri, selanjutnya disebut Mitra.</p>

                <p class="pks-p">Perusahaan dan Mitra selanjutnya disebut Para Pihak. Perusahaan menyediakan produk sesuai perizinan berusaha yang berlaku. Mitra bermaksud membantu pemasaran melalui Program Mitra Amanah. Para Pihak sepakat mengikatkan diri pada ketentuan berikut.</p>

                <p class="pks-p">Ringkasan Kemitraan memuat nomor perjanjian, versi naskah, identitas Para Pihak, dan kontak resmi yang dapat diperiksa sebelum persetujuan. Catatan waktu dilengkapi saat masing-masing pihak menyetujui. Ringkasan disimpan bersama naskah ini dan menjadi bagian yang tidak terpisahkan dari Perjanjian. Tanggal efektif mengikuti Pasal 14 dan Pasal 18, bukan tanggal versi naskah.</p>

                <div class="pasal-title">PASAL 1</div>
                <div class="pasal-subtitle">PENGERTIAN DAN RUANG LINGKUP</div>
                <p class="pks-p">(1) Produk adalah layanan umrah, haji khusus, perjalanan wisata, atau layanan lain yang secara sah disediakan Perusahaan dan dibuka untuk pemasaran oleh Mitra sesuai perizinan masing-masing.</p>
                <p class="pks-p">(2) Jamaah mencakup calon jamaah atau pelanggan yang diperkenalkan Mitra. Tagihan Resmi adalah tagihan yang diterbitkan Pusat, memuat identitas pemesanan, produk, nominal, peruntukan, dan tujuan pembayaran yang telah diperiksa Perusahaan.</p>
                <p class="pks-p">(3) Kanal Resmi adalah aplikasi, situs, alamat surat elektronik, atau nomor kontak Pusat yang identitasnya ditampilkan dalam Ringkasan Kemitraan. Mitra tidak berwenang menetapkan atau mengubah Kanal Resmi.</p>
                <p class="pks-p">(4) Konfirmasi Komisi adalah kesepakatan tertulis atau elektronik mengenai tarif dan syarat komisi produk sebagaimana diatur dalam Pasal 9 dan Lampiran 1. SOP dalam Lampiran 2 mengatur alur minimum kerja sama.</p>
                <p class="pks-p">(5) Kerja sama meliputi pengenalan calon jamaah, penyampaian informasi resmi, dan pendampingan administratif terbatas. Penyelenggaraan layanan serta hubungan kontraktual dengan jamaah tetap dikelola Perusahaan.</p>
                <p class="pks-p">(6) Hari Kerja adalah Senin sampai Jumat selain hari libur nasional. Tenggat yang hanya menyebut hari tanpa kata kerja dihitung sebagai hari kalender.</p>

                <div class="pasal-title">PASAL 2</div>
                <div class="pasal-subtitle">STATUS MITRA DAN BATAS KEWENANGAN</div>
                <p class="pks-p">(1) Mitra merupakan pihak independen dalam hubungan kemitraan. Perjanjian ini tidak memberikan kedudukan sebagai direksi, pegawai, pemegang izin, kantor cabang, atau penerima kuasa umum</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 1 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 2 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-2">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">Perusahaan. Penilaian hubungan kerja dan kewajiban perizinan tetap mengikuti pelaksanaan sebenarnya serta hukum yang berlaku.</p>
                <p class="pks-p">(2) Mitra hanya berwenang menyampaikan materi resmi yang berlaku, memperkenalkan calon jamaah, dan membantu administrasi sesuai SOP. Mitra tidak berwenang menandatangani kontrak atas nama Perusahaan, membuat atau mengubah harga, tagihan, kuitansi, paket, jadwal, jaminan layanan, atau keputusan keberangkatan.</p>
                <p class="pks-p">(3) Surat tugas, ID mitra, akses aplikasi, pelatihan, dan penggunaan merek tidak memberikan kuasa menerima uang. Larangan menerima dana dalam Pasal 6 tidak dapat dikecualikan melalui surat tugas, chat, persetujuan jamaah, atau penugasan Mitra berdasarkan Perjanjian ini.</p>
                <p class="pks-p">(4) Mitra tidak boleh menunjuk subagen, meminjamkan akun, atau mengalihkan kerja sama. Orang yang direkomendasikan menjadi mitra wajib diverifikasi dan membuat perjanjian langsung dengan Perusahaan sebelum bertindak.</p>
                <p class="pks-p">(5) Mitra dilarang menyelenggarakan paket sendiri atas nama Perusahaan atau menggunakan kerja sama ini sebagai pinjam izin. Tindakan yang memerlukan kewenangan atau izin lain harus diproses tersendiri sesuai hukum dan tidak dianggap diberikan oleh Perjanjian ini.</p>

                <div class="pasal-title">PASAL 3</div>
                <div class="pasal-subtitle">HAK DAN KEWAJIBAN PERUSAHAAN</div>
                <p class="pks-p">(1) Perusahaan berhak menentukan produk yang dipasarkan, memeriksa identitas dan kepatuhan Mitra, mengawasi kegiatan yang terkait dengan kerja sama, serta melakukan pengamanan dan pengakhiran sesuai Perjanjian ini.</p>
                <p class="pks-p">(2) Perusahaan menyediakan informasi produk, materi promosi, ketentuan komisi, SOP, tujuan pembayaran, dan Kanal Resmi yang jelas. Perusahaan memastikan produk yang dipasarkan sesuai dengan izin serta kapasitas layanan yang dimilikinya.</p>
                <p class="pks-p">(3) Perusahaan menerbitkan perjanjian layanan dengan jamaah, mengelola pendaftaran, memverifikasi penerimaan dana, dan menyampaikan konfirmasi langsung mengenai pembayaran serta status layanan.</p>
                <p class="pks-p">(4) Perusahaan menjalankan kewajiban penyelenggaraan, pelaporan, pelindungan jamaah, penanganan pengaduan, dan pelindungan data sesuai peraturan. Perusahaan tetap wajib mengawasi orang yang diberi akses terhadap proses dan data di bawah kendalinya.</p>
                <p class="pks-p">(5) Perusahaan membayar komisi yang telah menjadi hak Mitra sesuai Pasal 9, menjaga bukti transaksi, dan menyediakan kesempatan klarifikasi atas pembatasan hak atau dugaan pelanggaran.</p>

                <div class="pasal-title">PASAL 4</div>
                <div class="pasal-subtitle">HAK DAN KEWAJIBAN MITRA</div>
                <p class="pks-p">(1) Mitra berhak memperoleh materi resmi, pembinaan, informasi referral yang relevan, serta komisi sesuai kesepakatan. Akses data dibatasi pada kebutuhan tugas dan tidak memberikan hak mengubah status pembayaran atau keberangkatan.</p>
                <p class="pks-p">(2) Mitra wajib memberikan data dan informasi yang benar, memperbarui kontak atau alamat paling lambat 3 Hari Kerja setelah berubah, mengikuti SOP, dan menjaga kerahasiaan serta dokumen jamaah.</p>
                <p class="pks-p">(3) Mitra wajib memperkenalkan diri sebagai Mitra Pemasaran Amanah Safar dan menjelaskan bahwa transaksi, pembayaran, serta keputusan layanan dilakukan Pusat. Mitra tidak boleh memberi kesan bahwa dirinya memiliki kewenangan lebih luas.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 2 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 3 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-3">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">(4) Mitra wajib melaporkan dugaan penyalahgunaan uang, identitas, dokumen, atau pengaduan yang berisiko segera setelah diketahui, paling lambat 6 jam, melalui Kanal Resmi. Laporan dilengkapi bukti yang tersedia tanpa menunggu bukti lengkap.</p>
                <p class="pks-p">(5) Mitra wajib membantu penelusuran transaksi, penanganan pengaduan, dan serah terima yang relevan. Mitra tidak boleh menyembunyikan jamaah, menghapus bukti, memberikan data palsu, meminta kode rahasia bank jamaah, atau menggunakan keadaan darurat sebagai alasan menerima uang.</p>
                <p class="pks-p">(6) Biaya pemasaran yang dikeluarkan Mitra menjadi tanggungannya sendiri kecuali telah disetujui tertulis untuk ditanggung Perusahaan. Mitra dilarang mengatasnamakan Perusahaan untuk berutang, memungut sumbangan, atau meminta pembayaran lain tanpa dasar yang sah.</p>

                <div class="pasal-title">PASAL 5</div>
                <div class="pasal-subtitle">PENDAFTARAN DAN KOMUNIKASI DENGAN JAMAAH</div>
                <p class="pks-p">(1) Sebelum meminta pembayaran atau pemberian perlengkapan, Mitra wajib mengarahkan calon jamaah ke Pusat dan menyerahkan data pendaftaran melalui kanal yang ditentukan dengan dasar pemrosesan data yang sah.</p>
                <p class="pks-p">(2) Pusat memverifikasi identitas serta nomor kontak jamaah atau wakil keluarga yang sah. Nomor Mitra tidak boleh menjadi satu-satunya kontak. Mitra dilarang menguasai akses jamaah atau menghalangi komunikasi langsung dengan Pusat.</p>
                <p class="pks-p">(3) Pusat menyampaikan penawaran dan perjanjian layanan langsung kepada jamaah, termasuk harga, fasilitas, jadwal atau estimasi yang relevan, pembayaran, perubahan, pembatalan, dan pengembalian dana. Jamaah harus mempunyai kesempatan memeriksa serta menyetujuinya.</p>
                <p class="pks-p">(4) Pencatatan lead di aplikasi tidak dengan sendirinya membuktikan pendaftaran resmi, penerimaan dana, penerbitan nomor porsi, atau kepastian keberangkatan. Setiap status harus mengikuti bukti dan prosedur yang berlaku, termasuk Sistem Informasi Kementerian bila diwajibkan.</p>
                <p class="pks-p">(5) Jika Perusahaan telah menerima pembayaran dan pendaftaran jamaah, ketiadaan nama dalam manifest tidak dengan sendirinya menghapus hubungan transaksi atau hak jamaah. Status, kewajiban, dan penyelesaian dinilai menurut perjanjian layanan, bukti, serta hukum.</p>
                <p class="pks-p">(6) Konfirmasi pembayaran, sisa tagihan, perubahan layanan, pengaduan, dan instruksi keberangkatan disampaikan langsung oleh Pusat. Bila kontak tidak valid atau jamaah tidak dapat dihubungi, Pusat dan Mitra wajib menindaklanjuti sebelum menyatakan proses selesai.</p>

                <div class="pasal-title">PASAL 6</div>
                <div class="pasal-subtitle">PEMBAYARAN DAN LARANGAN MENERIMA DANA</div>
                <p class="pks-p">(1) Dana Biaya Perjalanan Ibadah Umrah wajib disetorkan langsung ke Rekening Penampungan PPIU pada bank penerima setoran sesuai ketentuan. Setoran haji khusus mengikuti mekanisme BPS Bipih dan BPKH. Biaya jasa atau produk lain yang sah dirinci sesuai peruntukannya dalam Tagihan Resmi.</p>
                <p class="pks-p">(2) Perusahaan wajib memverifikasi kepemilikan, fungsi, dan peruntukan rekening sebelum menampilkannya dalam Tagihan Resmi. Rekening atas nama Perusahaan tidak otomatis digunakan untuk setiap jenis setoran. Mitra wajib merujuk tujuan pembayaran sesuai Tagihan Resmi yang diterima langsung dari Pusat.</p>
                <p class="pks-p">(3) Mitra dilarang menerima, menampung, menitipkan kepada pihak lain, meminjam, menggunakan, atau mengalihkan dana jamaah, termasuk DP, pelunasan, biaya tambahan, dan refund, dalam</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 3 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 4 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-4">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">bentuk tunai, rekening pribadi, keluarga, badan usaha, pihak lain, dompet elektronik, QRIS, atau sarana pembayaran lain.</p>
                <p class="pks-p">(4) Larangan pada ayat (3) berlaku meskipun jamaah meminta atau menyetujui penitipan uang. Mitra hanya boleh membantu menjelaskan cara pembayaran tanpa menguasai dana, rekening, kartu, PIN, kata sandi, atau kode OTP bank jamaah. Bantuan petugas internal PPIU atas penyetoran yang diwakilkan diatur terpisah sesuai hukum dan tidak diberikan melalui status Mitra.</p>
                <p class="pks-p">(5) Mitra dilarang memotong komisi, memperhitungkan utang pribadi, atau menutup pembayaran jamaah lain dari dana jamaah. Komisi dibayarkan Perusahaan secara terpisah. Mitra juga dilarang menawarkan rekening pengganti atau meminta jamaah mengabaikan Tagihan Resmi.</p>
                <p class="pks-p">(6) Pusat mencocokkan setoran dengan mutasi atau konfirmasi bank dan identitas pemesanan. Gambar transfer, kuitansi buatan Mitra, atau pernyataan lisan tidak dengan sendirinya mengesahkan pelunasan. Konfirmasi nominal diterima, sisa tagihan, dan jatuh tempo dikirim Pusat kepada jamaah paling lambat 1 Hari Kerja setelah rekonsiliasi selesai.</p>
                <p class="pks-p">(7) Perusahaan melakukan rekonsiliasi secara rutin dan memenuhi tenggat pelaporan menurut peraturan. Hak membuat tagihan, mengesahkan pembayaran, mengeluarkan perlengkapan, dan menetapkan daftar keberangkatan dipisahkan sesuai kewenangan petugas serta dicatat.</p>
                <p class="pks-p">(8) Jika dana terlanjur diterima Mitra tanpa kewenangan, Mitra wajib segera melapor sesuai Pasal 4 ayat (4), tidak memakai atau memindahkan dana untuk kepentingan apa pun, dan memulihkannya melalui instruksi Pusat yang sah serta dapat ditelusuri. Pemulihan tidak menghapus pelanggaran.</p>
                <p class="pks-p">(9) Pelanggaran Pasal ini merupakan pelanggaran berat. Ketentuan pembayaran ini mengatur hubungan Para Pihak dan tidak menghapus hak jamaah atau tanggung jawab Perusahaan yang diwajibkan hukum.</p>

                <div class="pasal-title">PASAL 7</div>
                <div class="pasal-subtitle">HARGA PROMOSI DAN LARANGAN JANJI</div>
                <p class="pks-p">(1) Mitra hanya menggunakan harga, fasilitas, syarat, dan materi promosi resmi yang masih berlaku. Perusahaan menyimpan versi penawaran yang menjadi dasar transaksi. Perubahan untuk transaksi yang sudah disepakati mengikuti perjanjian layanan dan persetujuan yang diperlukan.</p>
                <p class="pks-p">(2) Mitra tidak boleh membuat diskon, hadiah, tambahan biaya, jaminan refund, atau pungutan atas nama Perusahaan di luar penawaran resmi. Biaya tambahan yang sah harus ditagihkan serta dijelaskan oleh Pusat, bukan dipungut Mitra.</p>
                <p class="pks-p">(3) Mitra dilarang menjamin tahun keberangkatan haji, percepatan, nomor porsi, kuota, penerbitan visa, seat pesawat, hotel, kurs, harga tetap, atau kelulusan pembiayaan di luar informasi dan syarat resmi yang telah disetujui Pusat. Estimasi tidak boleh dipresentasikan sebagai kepastian.</p>
                <p class="pks-p">(4) Mitra dilarang memalsukan dokumen atau keadaan transaksi, termasuk data untuk pembiayaan. Informasi harga, uang muka, angsuran, biaya, risiko, dan syarat persetujuan harus sesuai transaksi serta ketentuan penyedia pembiayaan yang sah.</p>
                <p class="pks-p">(5) Mitra tidak boleh mengaku sebagai penyelenggara berizin atas dasar izin Perusahaan, menerbitkan dokumen seolah-olah dari Pusat, atau memakai testimoni dan data jamaah secara tidak sah. Koreksi atas promosi yang keliru wajib dilakukan segera setelah diketahui atau diberitahukan.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 4 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 5 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-5">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <div class="pasal-title">PASAL 8</div>
                <div class="pasal-subtitle">MEREK IDENTITAS DAN PERLENGKAPAN</div>
                <p class="pks-p">(1) Perusahaan memberikan izin penggunaan merek dan materi resmi secara terbatas untuk pelaksanaan Perjanjian. Izin tersebut tidak dapat dialihkan, tidak memberikan hak kepemilikan, dan berakhir ketika dicabut atau kerja sama berakhir.</p>
                <p class="pks-p">(2) Mitra wajib memperoleh persetujuan sebelum membuat atau mengelola akun, domain, aplikasi, papan nama, atau media yang memakai identitas Perusahaan. Mitra dilarang membuat cap, kop surat, kuitansi, ID, surat tugas, atau tampilan yang menimbulkan kesan sebagai Perusahaan atau kantor cabang di luar kewenangan serta ketentuan perizinan.</p>
                <p class="pks-p">(3) Koper, seragam, dan perlengkapan resmi hanya dikeluarkan Pusat per ID serta nama jamaah setelah pelunasan terverifikasi dan alokasi layanan diperiksa. Barang tidak boleh dikeluarkan hanya berdasarkan DP, janji agen, atau bukti transfer yang belum diverifikasi.</p>
                <p class="pks-p">(4) Mitra tidak boleh memproduksi, membeli untuk disalurkan sebagai barang resmi, menyimpan stok bebas, menjual, atau membagikan atribut kepada jamaah di luar catatan Pusat. Jika membantu distribusi, tugas harus ditetapkan per nama penerima dan jumlah barang, tanpa stok bebas, disertai serah terima serta konfirmasi langsung oleh Pusat.</p>
                <p class="pks-p">(5) Penyerahan perlengkapan tidak menggantikan kuitansi atau konfirmasi kesiapan keberangkatan. Pembayaran, tiket, visa, asuransi, dan kesiapan layanan diperiksa sesuai tahapan yang berlaku. Mitra dilarang menggunakan atribut sebagai bukti bahwa jamaah pasti berangkat.</p>
                <p class="pks-p">(6) Saat izin dicabut atau kerja sama berakhir, Mitra wajib menghentikan penggunaan merek dan materi, mengembalikan barang milik Perusahaan, serta menyerahkan atau menonaktifkan sarana promosi terkait sesuai hak kepemilikan dan instruksi yang sah.</p>

                <div class="pasal-title">PASAL 9</div>
                <div class="pasal-subtitle">KOMISI INSENTIF DAN BIAYA</div>
                <p class="pks-p">(1) Besaran atau rumus komisi ditentukan dalam Konfirmasi Komisi untuk produk atau program terkait. Konfirmasi disampaikan dan disetujui secara tercatat sebelum produk dipasarkan, memuat tarif, pemicu hak, syarat transaksi, pembatalan, pajak, dan tenggat pembayaran sesuai Lampiran 1. Perusahaan tidak boleh meminta pemasaran berkomisi tanpa kejelasan ketentuan tersebut.</p>
                <p class="pks-p">(2) Referral diakui berdasarkan pendaftaran yang tercatat, identitas jamaah yang dapat diverifikasi, dan bukti kontribusi Mitra. Duplikasi atau sengketa referral diperiksa berdasarkan bukti; Perusahaan menyampaikan alasan penetapan dan memberi kesempatan klarifikasi.</p>
                <p class="pks-p">(3) Hak komisi atas paket keberangkatan timbul setelah transaksi sah, dana yang dipersyaratkan diterima dan diverifikasi, serta jamaah berangkat. Pembayarannya paling lambat 2 hari setelah keberangkatan. Identitas perpajakan dan rekening penerima komisi wajib dilengkapi sebelum keberangkatan agar pembayaran dapat dilakukan tepat waktu.</p>
                <p class="pks-p">(4) Untuk komisi yang disepakati timbul sebelum keberangkatan, termasuk program referral pendaftaran haji atau layanan tersendiri, pemicu dan tanggal pencairan wajib dinyatakan dalam Konfirmasi Komisi sebelum pemasaran. Apabila tidak ditetapkan pemicu yang berbeda, ketentuan ayat (3) berlaku.</p>
                <p class="pks-p">(5) Komisi dibayar ke rekening atas nama Mitra yang terverifikasi setelah pemotongan pajak yang diwajibkan. Perusahaan menyediakan rincian serta bukti pemotongan sesuai ketentuan. Perubahan tarif tidak boleh mengurangi hak atas referral yang telah diterima berdasarkan ketentuan sebelumnya.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 5 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 6 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-6">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">(6) Pembatalan atau perubahan transaksi memengaruhi komisi sesuai Konfirmasi Komisi dan sebab pembatalan. Komisi yang telah dibayar hanya dapat diminta kembali bila memang tidak berhak menurut kesepakatan dan bukti. Pembatalan akibat kesalahan Perusahaan tidak otomatis dibebankan kepada Mitra.</p>
                <p class="pks-p">(7) Atas indikasi pelanggaran yang berdasar, Perusahaan dapat menahan sementara bagian komisi yang terkait secara proporsional paling lama 30 Hari Kerja dengan alasan tertulis dan kesempatan klarifikasi. Perpanjangan harus mempunyai dasar kesepakatan tertulis atau penetapan pihak berwenang. Bagian yang tidak disengketakan tetap dibayar.</p>
                <p class="pks-p">(8) Perjumpaan utang atau pengurangan komisi untuk ganti rugi hanya dilakukan atas jumlah yang telah diakui tertulis oleh Mitra, disepakati dalam penyelesaian yang mengikat, atau ditetapkan dengan putusan yang berkekuatan hukum tetap. Dana jamaah tidak boleh dipakai untuk penyelesaian tersebut.</p>
                <p class="pks-p">(9) Perjanjian ini tidak menjanjikan penghasilan tetap atau hasil investasi. Biaya keanggotaan, penjaminan, atau program berbayar tidak timbul semata-mata karena PKS ini dan memerlukan kesepakatan terpisah yang sah. Perekrutan orang lain tidak dengan sendirinya menimbulkan komisi.</p>

                <div class="pasal-title">PASAL 10</div>
                <div class="pasal-subtitle">KERAHASIAAN DATA DAN DOKUMEN</div>
                <p class="pks-p">(1) Para Pihak menjaga informasi nonpublik yang diperoleh melalui kerja sama, termasuk data jamaah, data transaksi, akses sistem, dan informasi bisnis. Kewajiban ini tetap berlaku setelah kerja sama berakhir sepanjang informasi masih bersifat rahasia.</p>
                <p class="pks-p">(2) Mitra hanya memperoleh dan memproses data sesuai tugas, instruksi, tujuan, dan dasar pemrosesan yang sah. Mitra dilarang menjual, mengalihkan, memakai untuk kepentingan pribadi, atau mengungkapkan data kepada pihak yang tidak berhak. Perusahaan tetap memenuhi kewajiban sebagai pengendali data sesuai kegiatannya.</p>
                <p class="pks-p">(3) Akses dibatasi pada kebutuhan tugas dan tidak boleh dibagikan. Dokumen identitas, kesehatan, keuangan, serta data anak hanya diproses melalui kanal aman sesuai ketentuan. Penggunaan foto atau testimoni untuk promosi memerlukan dasar serta persetujuan yang sesuai, terpisah dari persetujuan layanan jika diwajibkan.</p>
                <p class="pks-p">(4) Paspor dan dokumen asli dicatat dalam serah terima, diamankan, serta dikembalikan ketika tidak lagi diperlukan. Mitra tidak boleh menahan dokumen sebagai jaminan utang, komisi, atau perselisihan. Penyerahan melalui Mitra hanya untuk tugas yang dicatat Pusat dan tidak memberikan hak menahan dokumen.</p>
                <p class="pks-p">(5) Mitra melaporkan dugaan kegagalan pelindungan data paling lambat 6 jam sejak diketahui, menjaga bukti, dan membantu penanganan. Perusahaan memenuhi kewajiban pemberitahuan serta pemulihan sesuai undang-undang. Batas 6 jam adalah kewajiban internal dan tidak menggantikan tenggat hukum.</p>
                <p class="pks-p">(6) Ketika tujuan pemrosesan berakhir atau akses dicabut, data diserahkan dengan aman dan salinan tanpa dasar retensi dihapus dengan bukti. Dokumen yang wajib disimpan atau diperlukan untuk sengketa diamankan sesuai hukum dan tidak dipakai untuk tujuan lain.</p>
                <p class="pks-p">(7) Data Mitra diproses seperlunya untuk verifikasi, pelaksanaan PKS, pembayaran, pajak, pengawasan, dan pembuktian sesuai pemberitahuan privasi serta dasar yang sah. Perusahaan tidak memperoleh hak tanpa batas atas data pribadi Mitra karena PKS ini.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 6 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 7 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-7">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">(8) Kerahasiaan tidak menghalangi pengaduan yang sah, pembelaan hak, atau pemenuhan permintaan aparat dan regulator yang berwenang. Pengungkapan dilakukan sebatas yang diperlukan sesuai hukum.</p>

                <div class="pasal-title">PASAL 11</div>
                <div class="pasal-subtitle">PENGADUAN PERUBAHAN LAYANAN DAN REFUND</div>
                <p class="pks-p">(1) Mitra wajib segera meneruskan pengaduan, permintaan perubahan, atau pembatalan kepada Pusat dan tidak boleh menyembunyikan, menunda demi komisi, mengancam pelapor, atau menjanjikan penyelesaian tanpa kewenangan.</p>
                <p class="pks-p">(2) Pusat memverifikasi langsung dengan jamaah dan pembayar, menjaga bukti, serta memberikan penanganan sesuai perjanjian layanan dan hukum. Hak jamaah yang telah jelas tidak boleh ditunda semata-mata karena penagihan kepada Mitra belum berhasil.</p>
                <p class="pks-p">(3) Refund dilakukan langsung oleh pihak yang berkewajiban kepada penerima yang sah, dengan mengutamakan rekening asal pembayaran setelah verifikasi. Perubahan tujuan refund harus diperiksa langsung dan disetujui oleh petugas berwenang; Mitra tidak boleh menerima atau menguasainya.</p>
                <p class="pks-p">(4) Biaya pembatalan atau pengembalian mengacu pada perjanjian layanan, biaya yang dapat dipertanggungjawabkan, dan ketentuan hukum. Mitra dilarang menetapkan sendiri pemotongan, pernyataan tidak dapat dikembalikan, perpindahan paket, atau pengalihan jamaah ke penyelenggara lain.</p>
                <p class="pks-p">(5) Komunikasi resmi mengenai sengketa atau insiden dilakukan oleh pejabat yang ditunjuk Perusahaan dengan informasi yang telah diverifikasi dan tetap menjaga privasi. Ketentuan ini tidak melarang laporan yang sah kepada pihak berwenang.</p>

                <div class="pasal-title">PASAL 12</div>
                <div class="pasal-subtitle">AUDIT DAN TINDAKAN PENGAMANAN</div>
                <p class="pks-p">(1) Perusahaan berhak memeriksa kegiatan, materi promosi, pendaftaran, dokumen, dan transaksi yang berkaitan dengan kerja sama melalui cara yang sah. Mitra wajib memberikan bukti yang relevan dan tidak boleh mengubah atau menghilangkannya.</p>
                <p class="pks-p">(2) Audit dibatasi pada tujuan yang sah serta data yang diperlukan. Hak audit tidak memberikan kewenangan mengambil alih akun pribadi, meminta PIN atau kata sandi bank, menyita barang, atau mengakses informasi yang tidak terkait secara tidak sah.</p>
                <p class="pks-p">(3) Jika terdapat indikasi berdasar atas penyalahgunaan dana, pemalsuan, penyalahgunaan identitas, pengaduan serius, atau kebocoran data, Perusahaan dapat segera membekukan akses dan aktivitas pemasaran Mitra serta menghentikan pengeluaran atribut. Tindakan ini tidak harus menunggu teguran berulang atau putusan pidana.</p>
                <p class="pks-p">(4) Pembekuan diberitahukan secara tercatat beserta alasan pokok sejauh tidak mengganggu pengamanan bukti. Mitra diberi kesempatan klarifikasi dalam 2 Hari Kerja sejak pemberitahuan diterima atau waktu lain yang wajar bila ada alasan yang dapat dibuktikan.</p>
                <p class="pks-p">(5) Perusahaan menilai bukti secara wajar, mencatat hasil pemeriksaan, dan memberitahukan apakah akses dipulihkan, dibatasi, atau kerja sama diakhiri. Penahanan komisi mengikuti Pasal 9, tidak otomatis berlaku atas seluruh hak Mitra.</p>
                <p class="pks-p">(6) Pengamanan internal tidak memberikan hak memblokir rekening bank milik Mitra atau mengambil asetnya tanpa dasar hukum. Permintaan tindakan terhadap bank, aparat, atau pihak lain dilakukan melalui prosedur yang berlaku.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 7 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 8 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-8">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <div class="pasal-title">PASAL 13</div>
                <div class="pasal-subtitle">PELANGGARAN SANKSI DAN GANTI RUGI</div>
                <p class="pks-p">(1) Pelanggaran dapat dikenai teguran, kewajiban koreksi, pembatasan akses, pembekuan, pengakhiran, atau tuntutan pemulihan sesuai sifat, bukti, dan akibatnya. Pelanggaran berat meliputi penerimaan atau penyalahgunaan dana, pemalsuan, pinjam izin, penyembunyian transaksi, penyalahgunaan data, serta penggunaan identitas tanpa hak.</p>
                <p class="pks-p">(2) Mitra wajib mengembalikan dana atau barang yang dikuasai tanpa hak dan mengganti kerugian nyata, langsung, wajar, serta terbukti mempunyai hubungan sebab akibat dengan kesalahan atau pelanggarannya. Kerugian dapat mencakup dana yang disalahgunakan dan biaya penanganan atau bantuan hukum yang dapat dipertanggungjawabkan.</p>
                <p class="pks-p">(3) Perusahaan menyampaikan rincian kerugian dan dasar tuntutan serta memberi kesempatan tanggapan. Nilai tuntutan tidak otomatis ditetapkan sepihak sebagai utang yang boleh dipotong. Jika tidak disepakati, jumlah dan pelaksanaannya diselesaikan menurut mekanisme sengketa.</p>
                <p class="pks-p">(4) Untuk penipuan atau penyalahgunaan dana oleh Mitra, nilai komisi bukan batas maksimum ganti rugi. Kerugian yang sama tidak boleh ditagih dua kali. Pemulihan dari Mitra, pihak lain, atau asuransi diperhitungkan secara benar.</p>
                <p class="pks-p">(5) Bagian kerugian yang berasal dari kesalahan Perusahaan atau kewajiban Perusahaan menurut hukum tidak otomatis dialihkan kepada Mitra. PKS ini tidak membebaskan Perusahaan dari tanggung jawab kepada jamaah, regulator, atau pihak lain yang ditentukan hukum.</p>
                <p class="pks-p">(6) Para Pihak dapat menempuh laporan kepada aparat atau regulator berdasarkan bukti dan unsur yang berlaku. Pengembalian uang, perdamaian, atau pemutusan kerja sama tidak dengan sendirinya menghapus pertanggungjawaban pidana menurut hukum.</p>

                <div class="pasal-title">PASAL 14</div>
                <div class="pasal-subtitle">MASA BERLAKU DAN PENGAKHIRAN</div>
                <p class="pks-p">(1) Perjanjian berlaku selama 12 bulan sejak tanggal efektif ketika persetujuan kedua pihak yang berwenang telah tercatat. Perpanjangan dilakukan berdasarkan evaluasi dan persetujuan baru. Tanggal mulai dan berakhir dicantumkan dalam Ringkasan Kemitraan.</p>
                <p class="pks-p">(2) Masing-masing pihak dapat mengakhiri kerja sama biasa dengan pemberitahuan tertulis paling lambat 30 hari sebelumnya. Pengakhiran tidak boleh digunakan untuk mengambil komisi yang telah sah menjadi hak Mitra.</p>
                <p class="pks-p">(3) Pelanggaran berat yang telah diperiksa dapat menjadi dasar pengakhiran melalui pemberitahuan tertulis tanpa menunggu masa pemberitahuan biasa, dengan tetap memberi kesempatan klarifikasi. Pembekuan pengamanan dapat dilakukan lebih dahulu menurut Pasal 12.</p>
                <p class="pks-p">(4) Jika perizinan yang diperlukan tidak lagi berlaku atau kegiatan menjadi dilarang oleh peraturan, pemasaran terkait dihentikan segera. Para Pihak menyelesaikan dampaknya terhadap transaksi berjalan dan hak jamaah sesuai hukum.</p>
                <p class="pks-p">(5) Sejak berakhir atau dicabut kewenangannya, Mitra wajib menghentikan penawaran dan penggunaan identitas. Paling lambat 3 Hari Kerja, Mitra menyerahkan daftar transaksi berjalan, dokumen, data dan barang milik Perusahaan serta membantu transisi yang aman; penanganan mendesak dilakukan segera.</p>
                <p class="pks-p">(6) Perusahaan mencabut akses, memperbarui status Mitra, dan memberi pemberitahuan faktual kepada jamaah atau pihak terkait yang sebelumnya berhubungan dengan Mitra. Pencabutan yang hanya diberitahukan kepada Mitra tidak dijadikan alasan mengabaikan perlindungan pihak ketiga yang beritikad baik.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 8 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 9 DARI 14                          -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-9">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">(7) Pengakhiran tidak menghapus komisi yang telah sah timbul, kewajiban pemulihan, kerahasiaan, pelindungan data, penyelesaian sengketa, atau kewajiban terhadap transaksi dan jamaah yang masih berjalan.</p>

                <div class="pasal-title">PASAL 15</div>
                <div class="pasal-subtitle">KEADAAN KAHAR</div>
                <p class="pks-p">(1) Keadaan kahar adalah peristiwa di luar kendali wajar pihak terdampak yang tidak dapat dicegah secara patut dan secara langsung menghalangi kewajibannya, dengan memperhatikan hukum serta perjanjian layanan yang relevan.</p>
                <p class="pks-p">(2) Pihak terdampak memberi pemberitahuan dan bukti paling lambat 3 Hari Kerja sejak dapat berkomunikasi secara wajar, menjelaskan dampak dan perkiraan penanganan, serta melakukan mitigasi. Keterlambatan dinilai berdasarkan keadaan yang dapat dibuktikan.</p>
                <p class="pks-p">(3) Kekurangan dana, pemakaian uang jamaah, pemalsuan, perselisihan komisi, atau kegagalan pengendalian internal yang sepatutnya dapat dicegah tidak menjadi alasan keadaan kahar.</p>
                <p class="pks-p">(4) Keadaan kahar tidak otomatis menghapus kewajiban menjaga dana dan bukti, pelindungan data, pelaporan, pengembalian yang tetap diwajibkan hukum, atau hak jamaah. Solusi atas perubahan layanan, penjadwalan, dan refund dibahas sesuai ketentuan yang berlaku.</p>

                <div class="pasal-title">PASAL 16</div>
                <div class="pasal-subtitle">PEMBERITAHUAN SOP DAN PERUBAHAN PERJANJIAN</div>
                <p class="pks-p">(1) Pemberitahuan dilakukan melalui Kanal Resmi kepada kontak terverifikasi yang dicatat dalam Ringkasan Kemitraan. Pengirim menyimpan bukti pengiriman dan menindaklanjuti kegagalan penyampaian. Perubahan kontak wajib diverifikasi; informasi dari pihak tidak berwenang tidak mengubah perjanjian.</p>
                <p class="pks-p">(2) PKS ini, Ringkasan Kemitraan, Lampiran 1 dan 2, serta Konfirmasi Komisi yang disepakati menjadi satu kesatuan. Dokumen tambahan harus dapat diidentifikasi, diakses, dan disimpan dalam versi yang telah disetujui.</p>
                <p class="pks-p">(3) SOP teknis dapat diperbarui untuk kepatuhan dan pengamanan dengan nomor versi serta pemberitahuan. Perubahan yang mengurangi hak komisi yang telah timbul, memperluas kewenangan, menambah beban pokok, atau mengubah ketentuan material memerlukan persetujuan Para Pihak dan tidak berlaku surut.</p>
                <p class="pks-p">(4) Hukum yang bersifat wajib berlaku terlebih dahulu. Untuk hal material, naskah PKS berlaku kecuali diubah dengan addendum yang secara tegas disepakati. Konfirmasi Komisi mengatur rincian komersial dalam ruang yang diperbolehkan Pasal 9; SOP atau chat tidak boleh mengurangi larangan menerima dana dalam Pasal 6.</p>
                <p class="pks-p">(5) Tidak adanya jawaban tidak dianggap sebagai persetujuan perubahan material. Pengamanan mendesak yang tidak menghapus hak yang telah timbul dapat diberlakukan segera dengan pemberitahuan tercatat.</p>

                <div class="pasal-title">PASAL 17</div>
                <div class="pasal-subtitle">HUKUM DAN PENYELESAIAN PERSELISIHAN</div>
                <p class="pks-p">(1) Perjanjian dibuat dalam Bahasa Indonesia dan tunduk pada hukum Republik Indonesia, termasuk ketentuan perikatan, penyelenggaraan haji dan umrah, perlindungan konsumen, pelindungan data, serta transaksi dan tanda tangan elektronik yang berlaku.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 9 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 10 DARI 14                         -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-10">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">(2) Perselisihan Para Pihak diupayakan selesai melalui musyawarah paling lama 14 Hari Kerja sejak pemberitahuan sengketa. Para Pihak menyampaikan pokok persoalan serta bukti yang relevan dengan itikad baik.</p>
                <p class="pks-p">(3) Jika musyawarah gagal, Para Pihak memilih Pengadilan Negeri Bogor sepanjang sesuai kewenangan menurut hukum. Jika kompetensi menurut hukum menentukan forum lain, penyelesaian mengikuti forum yang berwenang.</p>
                <p class="pks-p">(4) Ketentuan ini tidak menunda tindakan pengamanan yang sah, membatasi laporan kepada aparat atau regulator, atau mengikat hak dan pilihan forum jamaah yang bukan pihak dalam PKS. Hak yang tidak disengketakan tetap dilaksanakan.</p>

                <div class="pasal-title">PASAL 18</div>
                <div class="pasal-subtitle">PERSETUJUAN ELEKTRONIK DAN PEMBUKTIAN</div>
                <p class="pks-p">(1) Sebelum persetujuan, Para Pihak memperoleh kesempatan memeriksa naskah utuh, Ringkasan Kemitraan, lampiran dan ketentuan komisi yang relevan. Naskah dan versi yang disetujui harus dapat diunduh atau diterima sebagai salinan yang utuh.</p>
                <p class="pks-p">(2) Persetujuan dilakukan oleh penandatangan sendiri dengan mekanisme yang dapat mengidentifikasi dirinya, menunjukkan kehendak untuk terikat, mencatat waktu, dan menjaga integritas dokumen sesuai ketentuan. Tiga pernyataan pada akhir naskah ditampilkan sebagai centang terpisah yang awalnya kosong dan dipilih aktif oleh Mitra.</p>
                <p class="pks-p">(3) Persetujuan Perusahaan diberikan oleh direktur atau pihak yang berwenang melalui mekanisme yang sah. Label terverifikasi, gambar cap, atau nama otomatis pada PDF tidak dengan sendirinya menggantikan bukti kewenangan dan persetujuan.</p>
                <p class="pks-p">(4) Sistem menyimpan rekam persetujuan, identitas, hasil verifikasi, waktu beserta zona waktunya, versi naskah serta lampiran, dan bukti integritas yang memadai. Penggunaan OTP bukan pernyataan bahwa tanda tangan otomatis tersertifikasi. Metode yang dipilih tetap harus memenuhi ketentuan hukum.</p>
                <p class="pks-p">(5) Salinan final dan bukti persetujuan tersedia bagi Para Pihak melalui kanal aman. Perubahan terhadap isi yang telah disetujui harus dapat ditelusuri; perubahan material memerlukan persetujuan baru dan tidak boleh mengganti diam-diam naskah sebelumnya.</p>
                <p class="pks-p">(6) Para Pihak menjaga keamanan akun dan sarana autentikasi, tidak memberikan persetujuan melalui orang yang tidak berwenang, serta segera melaporkan dugaan penyalahgunaan. Rekam elektronik digunakan sebagai bukti sesuai hukum dan bukan bukti yang tidak dapat dibantah.</p>

                <div class="pasal-title">PASAL 19</div>
                <div class="pasal-subtitle">PENUTUP DAN KETENTUAN PERALIHAN</div>
                <p class="pks-p">(1) Para Pihak menyatakan telah memahami hak, kewajiban, batas kewenangan, serta akibat kerja sama dan mengikatkan diri secara bebas tanpa paksaan. Lampiran 1 dan 2 merupakan bagian yang tidak terpisahkan dari Perjanjian.</p>
                <p class="pks-p">(2) Jika suatu ketentuan dinyatakan tidak berlaku, bagian lain tetap berlaku sepanjang dapat dilaksanakan secara sah. Para Pihak menyesuaikan bagian yang bermasalah tanpa menghapus hak yang dilindungi hukum.</p>
                <p class="pks-p">(3) Bagi Mitra yang sebelumnya telah mempunyai PKS, naskah ini menggantikan pengaturan kerja sama untuk kegiatan setelah tanggal efektif ketika disetujui kedua pihak. Hak, kewajiban, dan komisi atas transaksi sebelumnya tetap mengikuti dasar yang berlaku saat timbul, kecuali diubah secara sah dengan persetujuan yang jelas.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 10 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 11 DARI 14                         -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-11">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">(4) Kewenangan menerima dana yang pernah diberikan kepada Mitra ditarik untuk kegiatan setelah tanggal efektif naskah ini. Perusahaan menyampaikan pencabutan kepada pihak terkait sesuai hukum. Bukti perjanjian serta transaksi lama dipertahankan dan tidak diberi tanggal mundur.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 11 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 12 DARI 14                         -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-12">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <div class="lampiran-title">LAMPIRAN 1</div>
                <div class="lampiran-subtitle">RINGKASAN KEMITRAAN DAN KETENTUAN KOMISI</div>

                <p class="pks-p">1. Ringkasan Kemitraan dan Konfirmasi Komisi merupakan catatan kesepakatan yang dapat ditampilkan di aplikasi atau disampaikan melalui Kanal Resmi. Catatan tersebut harus dapat diunduh atau disimpan bersama PKS dalam versi yang disetujui.</p>
                <p class="pks-p">2. Ringkasan Kemitraan memuat nomor perjanjian, versi naskah, nama dan jabatan penandatangan Perusahaan beserta dasar kewenangannya, nama serta NIK Mitra, alamat lengkap, nomor kontak terverifikasi, alamat surat elektronik bila digunakan, Kanal Resmi Pusat, tanggal efektif, dan masa berlaku. Identitas harus cocok dengan bukti verifikasi yang sah.</p>
                <p class="pks-p">3. Informasi legalitas produk Perusahaan dapat diperiksa melalui Kanal Resmi dan sumber perizinan yang berwenang. Nomor izin atau status yang belum diverifikasi tidak boleh ditampilkan seolah-olah sah atau berlaku.</p>
                <p class="pks-p">4. Konfirmasi Komisi setiap produk memuat nama atau kode produk, periode penawaran, nominal atau rumus komisi, pemicu hak, syarat penerimaan dana dan dokumen, tenggat pencairan, pajak, ketentuan pembatalan, serta cara penanganan referral ganda. Tarif dan syarat harus tampil atau disampaikan sebelum Mitra menyetujuinya dan memasarkan produk.</p>
                <p class="pks-p">5. Persetujuan Konfirmasi Komisi dicatat dengan identitas, tanggal, versi, dan isi yang disetujui. Tidak ada tarif yang dianggap disetujui hanya karena tersimpan di halaman yang dapat diubah sepihak. Perusahaan menyimpan riwayat untuk referral yang telah diterima.</p>
                <p class="pks-p">6. Tagihan Resmi kepada jamaah memuat ID pemesanan, nama jamaah, produk, total harga, setoran yang ditagihkan, sisa kewajiban, jatuh tempo, bank dan nomor tujuan, nama pemilik tujuan, serta peruntukan setoran. Dana umrah dan setoran haji khusus mengikuti Pasal 6; perubahan tujuan diverifikasi Pusat dan diberitahukan langsung kepada jamaah.</p>
                <p class="pks-p">7. Tanpa identitas dan persetujuan Para Pihak yang dapat dibuktikan, dokumen tidak boleh diberi status telah disepakati. Tanpa kejelasan komisi produk, Perusahaan tidak boleh meminta Mitra memasarkan produk tersebut dengan janji komisi yang belum ditetapkan.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 12 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 13 DARI 14                         -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-13">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <div class="lampiran-title">LAMPIRAN 2</div>
                <div class="lampiran-subtitle">KEWENANGAN DAN SOP MINIMUM</div>

                <div class="section-label">Matriks kewenangan</div>

                <table class="matriks-table">
                    <thead>
                        <tr>
                            <th style="width: 22%;">Kegiatan</th>
                            <th style="width: 39%;">Mitra</th>
                            <th style="width: 39%;">Perusahaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-activity">Promosi dan pengenalan</td>
                            <td class="col-mitra">Menyampaikan materi resmi dan menghubungkan calon jamaah ke Pusat.</td>
                            <td class="col-perusahaan">Menetapkan produk, harga, syarat, serta materi yang berlaku.</td>
                        </tr>
                        <tr>
                            <td class="col-activity">Pendaftaran dan kontrak</td>
                            <td class="col-mitra">Membantu pengisian data yang benar sesuai instruksi.</td>
                            <td class="col-perusahaan">Memverifikasi identitas dan kontak, menerbitkan kontrak serta ID pemesanan, dan melakukan pencatatan yang diwajibkan.</td>
                        </tr>
                        <tr>
                            <td class="col-activity">Pembayaran</td>
                            <td class="col-mitra">Menjelaskan cara membayar langsung tanpa menguasai dana atau kode rahasia.</td>
                            <td class="col-perusahaan">Menerbitkan tagihan, memastikan tujuan resmi, mencocokkan setoran, serta mengirim konfirmasi saldo kepada jamaah.</td>
                        </tr>
                        <tr>
                            <td class="col-activity">Koper seragam dan atribut</td>
                            <td class="col-mitra">Hanya membantu distribusi per nama bila ditugaskan dan dicatat.</td>
                            <td class="col-perusahaan">Mengeluarkan barang setelah syarat Pasal 8 terpenuhi, mencatat serah terima, dan mengonfirmasi langsung kepada penerima.</td>
                        </tr>
                        <tr>
                            <td class="col-activity">Status keberangkatan</td>
                            <td class="col-mitra">Meneruskan informasi resmi tanpa menjanjikan kepastian sendiri.</td>
                            <td class="col-perusahaan">Memeriksa dana, dokumen, tiket, visa, asuransi dan layanan; menetapkan serta mengomunikasikan status keberangkatan.</td>
                        </tr>
                        <tr>
                            <td class="col-activity">Perubahan dan refund</td>
                            <td class="col-mitra">Meneruskan permintaan serta bukti; tidak mengambil keputusan atau menerima uang.</td>
                            <td class="col-perusahaan">Memverifikasi permintaan langsung, menetapkan penanganan yang sah, dan membayar penerima yang berhak.</td>
                        </tr>
                        <tr>
                            <td class="col-activity">Insiden dan pengakhiran</td>
                            <td class="col-mitra">Melapor segera, menjaga bukti, menghentikan kegiatan terlarang dan membantu serah terima.</td>
                            <td class="col-perusahaan">Mengamankan akses, memeriksa, menangani jamaah, memperbarui status, dan memberi pemberitahuan faktual yang diperlukan.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="section-label">Alur minimum pelaksanaan</div>

                <p class="pks-p">1. Sebelum pemasaran aktif, Pusat menyelesaikan verifikasi identitas, persetujuan PKS, penjelasan batas kewenangan, serta Konfirmasi Komisi. Akun dan sarana promosi terkait dicatat.</p>
                <p class="pks-p">2. Sebelum transaksi, Pusat sudah memiliki data serta kontak terverifikasi jamaah atau wakil keluarga yang sah. Kontrak dan tagihan disampaikan langsung; komunikasi tidak hanya melalui Mitra.</p>
                <p class="pks-p">3. Setiap setoran diperiksa dengan bank. Status pembayaran hanya diubah petugas berwenang dan dicatat. Konfirmasi penerimaan beserta sisa tagihan dikirim langsung sesuai Pasal 6.</p>
                <p class="pks-p">4. Pengeluaran perlengkapan dicocokkan dengan ID, pelunasan, dan pemeriksaan alokasi. Tidak ada stok bebas pada Mitra. Daftar penerima barang dicocokkan dengan seluruh pendaftar, termasuk yang belum masuk manifest.</p>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 13 dari 14</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- HALAMAN 14 DARI 14                         -->
        <!-- ========================================== -->
        <div class="pks-page" id="page-14">
            <div class="pks-running-header">
                <span class="header-left">Perjanjian Kerja Sama Mitra Amanah</span>
                <span class="header-right">PT Amanah Tangguh Mandiri</span>
            </div>
            <div class="pks-page-body">
                <p class="pks-p">5. Pusat menghubungi jamaah menjelang jatuh tempo dan memeriksa kesesuaian data serta layanan pada H-14 dan H-7. Pada H-3 dan sebelum berkumpul, status kesiapan diverifikasi serta disampaikan langsung. Untuk pendaftaran yang lebih dekat, seluruh kontrol yang relevan diselesaikan sebelum keberangkatan. Jadwal ini adalah kontrol internal dan tidak menggantikan tenggat hukum.</p>
                <p class="pks-p">6. Pemeriksaan mencakup seluruh pendaftar dan penerima perlengkapan, bukan hanya manifest akhir. Jika jamaah menyatakan telah membayar kepada Mitra, status langsung ditandai sebagai insiden untuk ditelusuri sebelum hari keberangkatan.</p>
                <p class="pks-p">7. Atas insiden, Pusat mengamankan akses serta bukti dan menghubungi jamaah terkait secara langsung. Dana diterima Perusahaan, dana yang disengketakan, dan janji Mitra dicocokkan berdasarkan bukti. Pemenuhan kewajiban yang telah jelas tidak menunggu hasil penagihan kepada Mitra.</p>
                <p class="pks-p">8. Penyelesaian tidak menggunakan dana jamaah lain untuk menutup kekurangan. Kerugian, pengembalian, komisi dan biaya penanganan dicatat terpisah. Laporan kepada regulator atau aparat serta pemberitahuan data dilakukan sesuai ketentuan yang berlaku.</p>
                <p class="pks-p">9. Saat kerja sama berakhir, akses dan status diperbarui, atribut serta data diserahterimakan, dan jamaah terkait menerima pemberitahuan faktual. Catatan tidak boleh dihapus untuk menyembunyikan peristiwa.</p>

                <p class="pks-p">Nomor perjanjian, identitas penandatangan, dasar kewenangan, tanggal dan waktu, metode verifikasi, serta rekam persetujuan kedua pihak tercatat dalam Ringkasan Persetujuan yang disatukan dengan naskah final. Perjanjian efektif setelah persetujuan kedua pihak yang berwenang tercatat, sesuai Pasal 14 dan Pasal 18.</p>

                <p class="pks-p">Naskah, Ringkasan Kemitraan dan Persetujuan, kedua lampiran, serta Konfirmasi Komisi yang disepakati disimpan sebagai satu paket dokumen yang dapat ditelusuri. Salinan diberikan kepada Para Pihak melalui kanal aman.</p>

                <div class="signatures-wrapper">
                    <div class="signatures-heading">Pengesahan Para Pihak</div>
                    <div class="signatures-grid">
                        <div class="sig-col">
                            <div class="sig-party-role">PIHAK PERTAMA</div>
                            <div class="sig-party-name">PT AMANAH TANGGUH MANDIRI</div>
                            <div class="sig-box">
                                <div class="sig-seal">
                                    <i class="fas fa-certificate text-emerald-600"></i><br>
                                    TERVERIFIKASI DIGITAL<br>
                                    <span style="font-size: 7pt; font-weight: normal; color: #047857;">PT Amanah Tangguh Mandiri</span>
                                </div>
                            </div>
                            <div class="sig-desc">
                                M. Erwin Saputra selaku Direktur Utama atau pejabat yang berwenang sebagaimana Ringkasan Kemitraan.
                            </div>
                        </div>

                        <div class="sig-col">
                            <div class="sig-party-role">PIHAK KEDUA</div>
                            <div class="sig-party-name">MITRA AMANAH</div>
                            <div class="sig-box">
                                <?php if (!empty($user['agreement_accepted_at'])): ?>
                                <div class="sig-seal">
                                    <i class="fas fa-check-circle text-emerald-600"></i><br>
                                    DISETUJUI ELEKTRONIK<br>
                                    <span style="font-size: 7pt; font-weight: normal; color: #047857;">
                                        <?= esc($user['nama_lengkap'] ?? 'Mitra') ?><br>
                                        <?= esc(date('d/m/Y H:i', strtotime($user['agreement_accepted_at']))) ?> WIB
                                    </span>
                                </div>
                                <?php else: ?>
                                <div class="sig-seal pending">
                                    <i class="fas fa-clock text-slate-400"></i><br>
                                    MENUNGGU PERSETUJUAN<br>
                                    <span style="font-size: 7pt; font-weight: normal; color: #64748b;">Persetujuan Digital Mitra</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="sig-desc">
                                Nama dan identitas Mitra sesuai Ringkasan Kemitraan yang telah diverifikasi serta disetujui.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pks-running-footer">
                <span class="footer-left">PKS Mitra Amanah</span>
                <span class="footer-right">Halaman 14 dari 14</span>
            </div>
        </div>

    </div>

</body>
</html>
