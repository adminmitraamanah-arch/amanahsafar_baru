<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKS - <?= esc($pksNumber) ?> - <?= esc($user['nama_lengkap']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif, 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #000000;
            font-size: 13.5px;
            line-height: 1.65;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .action-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #ffffff;
            border-bottom: 1px solid #cbd5e1;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .action-bar .title {
            font-size: 14px;
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
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
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
        .page-container {
            max-width: 820px;
            margin: 24px auto;
            background: #ffffff;
            padding: 56px 64px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 4px;
        }
        .pks-header {
            text-align: center;
            margin-bottom: 18px;
        }
        .pks-header h2 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.02em;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .pks-header p {
            font-size: 12px;
            line-height: 1.4;
            max-width: 680px;
            margin: 0 auto;
        }
        .header-line {
            border-top: 3px double #000000;
            margin: 16px 0 22px 0;
        }
        .doc-title-container {
            text-align: center;
            margin-bottom: 22px;
        }
        .doc-title {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 4px;
        }
        .doc-subtitle {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 6px;
        }
        .doc-body {
            font-size: 13.5px;
            line-height: 1.65;
            text-align: justify;
            color: #000000;
        }
        .doc-body p {
            margin-bottom: 12px;
            text-indent: 0;
        }
        .parties-list {
            margin-bottom: 14px;
        }
        .parties-item {
            margin-bottom: 10px;
            text-align: justify;
        }
        .bullet-list {
            list-style-type: none;
            padding-left: 18px;
            margin-bottom: 14px;
        }
        .bullet-list li {
            position: relative;
            padding-left: 14px;
            margin-bottom: 6px;
            text-align: justify;
        }
        .bullet-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        .numbered-list {
            list-style-type: decimal;
            padding-left: 24px;
            margin-bottom: 14px;
        }
        .numbered-list li {
            margin-bottom: 6px;
            text-align: justify;
        }
        .article-title {
            font-size: 13.5px;
            font-weight: 800;
            text-align: center;
            margin-top: 22px;
            margin-bottom: 2px;
            text-transform: uppercase;
            page-break-after: avoid;
        }
        .article-subtitle {
            font-size: 13px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            page-break-after: avoid;
        }
        .signatures-container {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            page-break-inside: avoid;
        }
        .sig-block {
            width: 44%;
            text-align: center;
            font-size: 13.5px;
        }
        .sig-block .role {
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .sig-block .company {
            font-weight: 700;
            margin-bottom: 8px;
        }
        .sig-space {
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 6px 0;
        }
        .digital-seal {
            border: 1.5px dashed #059669;
            background: #ecfdf5;
            color: #047857;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 10.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            text-align: center;
            line-height: 1.35;
        }
        .sig-pending {
            font-style: italic;
            color: #64748b;
            font-size: 11px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .sig-name {
            font-weight: 800;
            text-decoration: underline;
            margin-top: 6px;
        }
        .sig-title {
            font-size: 12.5px;
            margin-top: 2px;
        }
        .footer-note {
            margin-top: 32px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #94a3b8;
            text-align: center;
            line-height: 1.4;
        }

        @media print {
            body {
                background: #ffffff;
                font-size: 12.5px;
                line-height: 1.55;
            }
            .action-bar {
                display: none !important;
            }
            .page-container {
                max-width: 100%;
                margin: 0;
                padding: 10px 15px;
                box-shadow: none;
                border-radius: 0;
            }
            @page {
                size: A4;
                margin: 1.8cm 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (Hidden during print) -->
    <div class="action-bar">
        <div class="title">
            <i class="fas fa-file-contract text-purple-700"></i>
            <span>Dokumen Resmi PKS Digital &bull; <?= esc($pksNumber) ?></span>
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

    <?php
    $rawDate = $user['agreement_accepted_at'] ?? $verification['created_at'] ?? date('Y-m-d H:i:s');
    $pksDateFormatted = format_tanggal_indonesia($rawDate, true);

    $pihakKeduaNama = !empty($user['nama_lengkap']) ? strtoupper(trim($user['nama_lengkap'])) : '[Nama Mitra Belum Terdaftar]';
    $pihakKeduaNik = !empty($verification['nik']) ? trim($verification['nik']) : '-';
    $pihakKeduaAlamat = !empty($user['domisili']) ? strtoupper(trim($user['domisili'])) : 'INDONESIA';
    ?>

    <!-- Printable Sheet -->
    <div class="page-container">
        
        <!-- Kop Surat Persis Sesuai PDF Referensi -->
        <div class="pks-header">
            <h2>PT AMANAH TANGGUH MANDIRI (AMANAH SAFAR)</h2>
            <p>Gedung Graha Pool Lt. 3 Unit 303-304, Jl. Merdeka No. 110, Ciwaringin, Bogor Tengah, Kota Bogor, Jawa Barat</p>
        </div>
        <div class="header-line"></div>

        <!-- Judul Surat Persis Sesuai PDF Referensi -->
        <div class="doc-title-container">
            <h1 class="doc-title">PERJANJIAN KERJA SAMA (PKS)</h1>
            <div class="doc-subtitle">PT AMANAH TANGGUH MANDIRI DAN MITRA AMANAH</div>
        </div>

        <!-- Isi Surat -->
        <div class="doc-body">
            <p>
                Pada hari ini, <strong><?= esc($pksDateFormatted) ?></strong> bertempat di <strong>Bogor</strong>, telah dibuat dan disepakati Perjanjian Kerja Sama (PKS) antara:
            </p>

            <div class="parties-list">
                <!-- Pihak Pertama (Statis Sesuai PDF Referensi) -->
                <div class="parties-item">
                    1. <strong>PT AMANAH TANGGUH MANDIRI (AMANAH SAFAR)</strong>, suatu perseroan terbatas yang didirikan berdasarkan hukum Negara Republik Indonesia, berkedudukan di Gedung Graha Pool Lt. 3 Unit 303-304, Jl. Merdeka No. 110, Ciwaringin, Bogor Tengah, Kota Bogor, Jawa Barat, dalam hal ini diwakili oleh <strong>M. Erwin Saputra</strong> selaku Direktur Utama, bertindak untuk dan atas nama PT Amanah Tangguh Mandiri, selanjutnya disebut <strong>"Pihak Pertama"</strong> atau <strong>"Pusat"</strong>.
                </div>

                <!-- Pihak Kedua (Dinamis Sesuai Data Mitra) -->
                <div class="parties-item">
                    2. <strong><?= esc($pihakKeduaNama) ?></strong>, NIK: <strong><?= esc($pihakKeduaNik) ?></strong>, berkedudukan dan beralamat di <strong><?= esc($pihakKeduaAlamat) ?></strong> dalam hal ini disebut <strong>MITRA AMANAH</strong>, untuk selanjutnya disebut <strong>"Pihak Kedua"</strong>.
                </div>
            </div>

            <p>
                PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama disebut <strong>Para Pihak</strong>. Para Pihak terlebih dahulu menerangkan hal-hal sebagai berikut bahwa:
            </p>

            <ul class="bullet-list">
                <li>Pihak Pertama adalah suatu Perusahaan yang bergerak di bidang Biro Perjalanan Wisata yang telah memiliki izin resmi sebagai penyelenggara perjalanan Umroh dan Haji.</li>
                <li>Para Pihak setuju dan sepakat untuk melaksanakan kerjasama dalam pemasaran Produk Pihak Pertama dan dengan ini Pihak Kedua mendaftarkan diri sebagai mitra Pihak Pertama.</li>
            </ul>

            <p>
                Berdasarkan hal-hal tersebut di atas, maka Para Pihak dengan ini setuju dan sepakat untuk mengadakan Perjanjian ini dengan syarat dan ketentuan sebagai berikut:
            </p>

            <!-- PASAL 1 -->
            <div class="article-title">PASAL 1</div>
            <div class="article-subtitle">MAKSUD DAN TUJUAN</div>
            <p>
                Perjanjian ini dibuat sebagai dasar kerja sama pemasaran produk dan layanan yang diselenggarakan oleh PT Amanah Safar melalui Program Mitra Amanah.
            </p>

            <!-- PASAL 2 -->
            <div class="article-title">PASAL 2</div>
            <div class="article-subtitle">RUANG LINGKUP KERJA SAMA</div>
            <p>PIHAK KEDUA bertugas untuk:</p>
            <ol class="numbered-list">
                <li>Memasarkan produk yang disediakan oleh PIHAK PERTAMA. Pihak Kedua memiliki hak dan wewenang untuk menjual dan/atau memasarkan Produk Pihak Pertama secara langsung kepada calon Jemaah atau masyarakat baik secara offline maupun online.</li>
                <li>Memberikan informasi yang benar kepada calon jamaah.</li>
                <li>Mengarahkan calon jamaah melakukan pendaftaran melalui sistem resmi Amanah Safar.</li>
                <li>Mendampingi jamaah selama proses administrasi apabila diperlukan.</li>
            </ol>

            <!-- PASAL 3 -->
            <div class="article-title">PASAL 3</div>
            <div class="article-subtitle">STATUS MITRA</div>
            <ol class="numbered-list">
                <li>MITRA bukan merupakan karyawan PT Amanah Safar.</li>
                <li>Hubungan kerja sama ini bersifat kemitraan.</li>
                <li>MITRA tidak memiliki kewenangan mewakili perusahaan untuk membuat keputusan hukum tanpa persetujuan tertulis dari PIHAK PERTAMA.</li>
            </ol>

            <!-- PASAL 4 -->
            <div class="article-title">PASAL 4</div>
            <div class="article-subtitle">HAK PIHAK PERTAMA</div>
            <p>PIHAK PERTAMA berhak:</p>
            <ul class="bullet-list">
                <li>Menentukan kebijakan kemitraan.</li>
                <li>Melakukan verifikasi identitas mitra.</li>
                <li>Mengubah SOP operasional.</li>
                <li>Memberikan pembinaan kepada mitra.</li>
                <li>Menunda atau menghentikan kemitraan apabila terjadi pelanggaran.</li>
            </ul>

            <!-- PASAL 5 -->
            <div class="article-title">PASAL 5</div>
            <div class="article-subtitle">HAK MITRA</div>
            <p>MITRA berhak:</p>
            <ul class="bullet-list">
                <li>Memasarkan produk resmi Amanah Safar.</li>
                <li>Mendapatkan materi promosi.</li>
                <li>Mengakses dashboard mitra.</li>
                <li>Mendapatkan komisi atau insentif sesuai ketentuan program yang berlaku.</li>
                <li>Mengikuti pelatihan dan pembinaan.</li>
            </ul>

            <!-- PASAL 6 -->
            <div class="article-title">PASAL 6</div>
            <div class="article-subtitle">KEWAJIBAN MITRA</div>
            <p>MITRA wajib:</p>
            <ol class="numbered-list">
                <li>Menjaga nama baik Amanah Safar.</li>
                <li>Memberikan informasi yang benar kepada calon jamaah.</li>
                <li>Mematuhi seluruh SOP dan kebijakan perusahaan.</li>
                <li>Menjaga kerahasiaan data jamaah.</li>
                <li>Tidak menggunakan logo, merek, atau identitas Amanah Safar di luar ketentuan yang ditetapkan.</li>
                <li>Tidak membuat janji, promo, atau penawaran di luar kebijakan perusahaan.</li>
            </ol>

            <!-- PASAL 7 -->
            <div class="article-title">PASAL 7</div>
            <div class="article-subtitle">PEMBAYARAN JAMAAH</div>
            <ol class="numbered-list">
                <li>Seluruh pembayaran jamaah dilakukan langsung ke rekening resmi Amanah Safar, Rekening Bank BSI, No. Rek. 7275610601 an. PT AMANAH TANGGUH MANDIRI.</li>
                <li>MITRA tidak diperkenankan menerima, menampung, atau mengalihkan dana jamaah ke rekening pribadi, kecuali terdapat penugasan tertulis dari PIHAK PERTAMA.</li>
                <li>Pelanggaran terhadap ketentuan ini merupakan pelanggaran berat.</li>
            </ol>

            <!-- PASAL 8 -->
            <div class="article-title">PASAL 8</div>
            <div class="article-subtitle">KOMISI DAN INSENTIF</div>
            <ol class="numbered-list">
                <li>Besaran komisi mengikuti ketentuan program yang berlaku.</li>
                <li>Pembayaran komisi dilakukan setelah syarat pencairan terpenuhi dan maksimal pembayaran fee/komisi 2 hari setelah keberangkatan jamaah.</li>
                <li>Perubahan skema komisi akan diberitahukan kepada mitra.</li>
            </ol>

            <!-- PASAL 9 -->
            <div class="article-title">PASAL 9</div>
            <div class="article-subtitle">KERAHASIAAN</div>
            <p>
                MITRA wajib menjaga kerahasiaan seluruh data jamaah, data perusahaan, informasi bisnis, strategi pemasaran, dan dokumen internal yang diperoleh selama masa kerja sama.
            </p>

            <!-- PASAL 10 -->
            <div class="article-title">PASAL 10</div>
            <div class="article-subtitle">LARANGAN</div>
            <p>MITRA dilarang:</p>
            <ol class="numbered-list">
                <li>Memalsukan data.</li>
                <li>Menyalahgunakan identitas Amanah Safar.</li>
                <li>Menipu calon jamaah.</li>
                <li>Menggunakan rekening pribadi untuk menerima pembayaran jamaah.</li>
                <li>Menyebarkan informasi yang tidak benar.</li>
                <li>Melakukan tindakan yang merugikan perusahaan atau jamaah.</li>
            </ol>

            <!-- PASAL 11 -->
            <div class="article-title">PASAL 11</div>
            <div class="article-subtitle">SANKSI</div>
            <p>Pelanggaran dapat dikenakan:</p>
            <ol class="numbered-list">
                <li>Teguran tertulis.</li>
                <li>Penangguhan status mitra.</li>
                <li>Penghentian kerja sama.</li>
                <li>Pencabutan status mitra.</li>
                <li>Tuntutan ganti rugi apabila menimbulkan kerugian.</li>
                <li>Proses hukum sesuai peraturan perundang-undangan apabila terdapat unsur pidana atau perdata.</li>
            </ol>

            <!-- PASAL 12 -->
            <div class="article-title">PASAL 12</div>
            <div class="article-subtitle">JANGKA WAKTU</div>
            <p>
                Perjanjian ini berlaku sejak tanggal ditandatangani dan tetap berlaku sampai diakhiri oleh salah satu pihak sesuai ketentuan dalam perjanjian ini.
            </p>

            <!-- PASAL 13 -->
            <div class="article-title">PASAL 13</div>
            <div class="article-subtitle">PENYELESAIAN PERSELISIHAN</div>
            <p>
                Setiap perselisihan akan diselesaikan terlebih dahulu melalui musyawarah untuk mufakat. Apabila tidak tercapai kesepakatan, penyelesaian dilakukan sesuai ketentuan hukum Republik Indonesia.
            </p>

            <!-- PASAL 14 -->
            <div class="article-title">PASAL 14</div>
            <div class="article-subtitle">PENUTUP</div>
            <p>
                Dengan menandatangani perjanjian ini, Para Pihak menyatakan telah membaca, memahami, dan menyetujui seluruh isi Perjanjian Kerja Sama tanpa adanya paksaan dari pihak mana pun.
            </p>

            <!-- Tanda Tangan Para Pihak Persis Sesuai PDF Referensi -->
            <div class="signatures-container">
                <div class="sig-block">
                    <div class="role">PIHAK PERTAMA</div>
                    <div class="company">PT Amanah Safar</div>
                    <div class="sig-space">
                        <div class="digital-seal">
                            <i class="fas fa-certificate text-emerald-600"></i><br>
                            TERVERIFIKASI DIGITAL<br>
                            <span style="font-size: 9px; font-weight: 500; color: #065f46;">Amanah Safar System</span>
                        </div>
                    </div>
                    <div class="sig-name">M. Erwin Saputra</div>
                    <div class="sig-title">Direktur Utama</div>
                </div>

                <div class="sig-block">
                    <div class="role">PIHAK KEDUA</div>
                    <div class="company">Mitra Amanah</div>
                    <div class="sig-space">
                        <?php if (!empty($user['agreement_accepted_at'])): ?>
                            <div class="digital-seal">
                                <i class="fas fa-signature text-emerald-600"></i><br>
                                DISETUJUI SECARA DIGITAL<br>
                                <span style="font-size: 9px; font-weight: 500; color: #065f46;">
                                    <?= date('d/m/Y H:i:s \W\I\B', strtotime($user['agreement_accepted_at'])) ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="sig-pending">
                                (Menunggu Persetujuan Elektronik)
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?= esc($pihakKeduaNama) ?></div>
                    <div class="sig-title">NIK: <?= esc($pihakKeduaNik) ?></div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="footer-note">
                Dokumen ini merupakan salinan resmi elektronik yang diterbitkan secara otomatis oleh sistem Amanah Safar Digital Platform.<br>
                &copy; <?= date('Y') ?> PT Amanah Tangguh Mandiri. All Rights Reserved. | PKS Digital Kemitraan
            </div>

        </div>

    </div>

</body>
</html>
