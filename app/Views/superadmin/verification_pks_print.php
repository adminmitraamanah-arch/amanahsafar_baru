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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #e2e8f0;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.6;
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
            padding: 48px 56px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 16px;
            border-bottom: 3px double #0f172a;
            margin-bottom: 24px;
        }
        .kop-surat .logo-container img {
            height: 60px;
            width: auto;
            object-contain: contain;
        }
        .kop-surat .info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            line-height: 1.4;
        }
        .kop-surat .info .company-name {
            font-size: 16px;
            font-weight: 800;
            color: #5e0078;
            letter-spacing: -0.01em;
            margin-bottom: 2px;
        }
        .doc-title-container {
            text-align: center;
            margin-bottom: 24px;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-decoration: underline;
        }
        .doc-number {
            font-size: 12px;
            color: #475569;
            font-weight: 600;
            margin-top: 4px;
        }
        .doc-body {
            font-size: 12.5px;
            line-height: 1.7;
            text-align: justify;
            color: #1e293b;
        }
        .doc-body p {
            margin-bottom: 12px;
        }
        .parties-table {
            width: 100%;
            margin: 12px 0 18px 0;
            border-collapse: collapse;
        }
        .parties-table td {
            vertical-align: top;
            padding: 3px 0;
            font-size: 12px;
        }
        .parties-table .label {
            width: 160px;
            color: #475569;
        }
        .parties-table .colon {
            width: 15px;
        }
        .parties-table .val {
            font-weight: 600;
            color: #0f172a;
        }
        .article-title {
            font-size: 13px;
            font-weight: 800;
            text-align: center;
            color: #0f172a;
            margin: 18px 0 6px 0;
            text-transform: uppercase;
        }
        .article-subtitle {
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            color: #5e0078;
            margin-bottom: 8px;
        }
        .article-list {
            padding-left: 20px;
            margin-bottom: 12px;
        }
        .article-list li {
            margin-bottom: 4px;
        }
        .signatures-container {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            page-break-inside: avoid;
        }
        .sig-block {
            width: 45%;
            text-align: center;
            font-size: 12px;
        }
        .sig-block .role {
            font-weight: 700;
            color: #475569;
            margin-bottom: 4px;
        }
        .sig-block .company {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .sig-box {
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 6px 0;
        }
        .sig-box .digital-seal {
            border: 2px dashed #059669;
            background: #ecfdf5;
            color: #047857;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            line-height: 1.3;
        }
        .sig-name {
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
            margin-top: 8px;
        }
        .sig-title {
            font-size: 11px;
            color: #64748b;
        }
        .footer-note {
            margin-top: 36px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.4;
        }

        @media print {
            body {
                background: #ffffff;
                font-size: 11.5px;
            }
            .action-bar {
                display: none !important;
            }
            .page-container {
                max-width: 100%;
                margin: 0;
                padding: 20px 24px;
                box-shadow: none;
                border-radius: 0;
            }
            @page {
                size: A4;
                margin: 1.5cm;
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

    <!-- Printable Sheet -->
    <div class="page-container">
        
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="logo-container">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Amanah Safar" onerror="this.style.display='none'">
            </div>
            <div class="info">
                <div class="company-name">PT AMANAH TANGGUH MANDIRI</div>
                <div>Amanah Safar Digital Platform &bull; Biro Perjalanan Umrah &amp; Wisata Halal</div>
                <div>Gedung Pusat Amanah Safar, Jakarta &bull; www.amanahsafar.com</div>
                <div>Layanan Mitra: support@amanahsafar.com</div>
            </div>
        </div>

        <!-- Judul Surat -->
        <div class="doc-title-container">
            <h1 class="doc-title">SURAT PERJANJIAN KERJA SAMA KEMITRAAN (PKS)</h1>
            <div class="doc-number">Nomor: <?= esc($pksNumber) ?></div>
        </div>

        <!-- Isi Surat -->
        <div class="doc-body">
            <p>
                Pada hari ini, tanggal <strong><?= date('d F Y', strtotime($user['agreement_accepted_at'] ?? $verification['created_at'])) ?></strong>, bertempat di kantor pusat PT Amanah Tangguh Mandiri, telah dibuat, disepakati, dan ditandatangani secara digital Perjanjian Kerja Sama Kemitraan (selanjutnya disebut "Perjanjian") oleh dan antara para pihak:
            </p>

            <!-- Pihak Pertama -->
            <table class="parties-table">
                <tr>
                    <td class="label">1. Nama Perusahaan</td>
                    <td class="colon">:</td>
                    <td class="val">PT Amanah Tangguh Mandiri</td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Platform</td>
                    <td class="colon">:</td>
                    <td class="val">Amanah Safar Digital Platform</td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Kedudukan</td>
                    <td class="colon">:</td>
                    <td class="val">Penyelenggara Sistem Kemitraan Digital</td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Sebutan</td>
                    <td class="colon">:</td>
                    <td class="val">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</td>
                </tr>
            </table>

            <!-- Pihak Kedua -->
            <table class="parties-table">
                <tr>
                    <td class="label">2. Nama Lengkap</td>
                    <td class="colon">:</td>
                    <td class="val"><?= esc($user['nama_lengkap']) ?></td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Kode Mitra</td>
                    <td class="colon">:</td>
                    <td class="val"><?= esc($voucher['kode'] ?? ('MITRA-' . $user['id'])) ?></td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Nomor WhatsApp</td>
                    <td class="colon">:</td>
                    <td class="val"><?= esc($user['no_wa'] ?: '-') ?></td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Domisili / Alamat</td>
                    <td class="colon">:</td>
                    <td class="val"><?= esc($user['domisili'] ?: 'Indonesia') ?></td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Rekening Bank</td>
                    <td class="colon">:</td>
                    <td class="val"><?= esc($verification['nama_bank'] ?: '-') ?> &bull; No: <?= esc($verification['nomor_rekening'] ?: '-') ?> (a.n. <?= esc($verification['nama_pemilik_rekening'] ?: $user['nama_lengkap']) ?>)</td>
                </tr>
                <tr>
                    <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Sebutan</td>
                    <td class="colon">:</td>
                    <td class="val">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong> (Mitra).</td>
                </tr>
            </table>

            <p>
                PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama selanjutnya disebut sebagai "PARA PIHAK", dan masing-masing disebut "PIHAK". PARA PIHAK menerangkan terlebih dahulu bahwa PIHAK PERTAMA adalah perusahaan penyelenggara layanan perjalanan ibadah Umrah dan Wisata Halal, dan PIHAK KEDUA adalah individu/badan yang bermaksud menjalin kerja sama syiar, pemasaran, serta referensi pendaftaran jamaah.
            </p>

            <p>
                Berdasarkan pertimbangan tersebut di atas, PARA PIHAK sepakat untuk saling mengikatkan diri dalam Perjanjian ini dengan syarat dan ketentuan sebagai berikut:
            </p>

            <!-- PASAL 1 -->
            <div class="article-title">PASAL 1</div>
            <div class="article-subtitle">KETENTUAN UMUM DAN RUANG LINGKUP</div>
            <p>
                Perjanjian ini mengatur kerja sama kemitraan syiar, pemasaran, dan pendaftaran calon jamaah Umrah dan Wisata Halal melalui sistem digital Amanah Safar. PIHAK KEDUA bertindak sebagai mitra perujuk resmi yang berhak memanfaatkan materi promosi, tautan pendaftaran, dan fasilitas pendukung yang disediakan oleh PIHAK PERTAMA.
            </p>

            <!-- PASAL 2 -->
            <div class="article-title">PASAL 2</div>
            <div class="article-subtitle">HAK DAN KEWAJIBAN PIHAK PERTAMA</div>
            <p>
                1. PIHAK PERTAMA berkewajiban menyediakan fasilitas portal digital, materi pemasaran resmi, kepastian kuota paket ibadah, dan bimbingan kemitraan berkala.<br>
                2. PIHAK PERTAMA berkewajiban membayarkan hak ujroh / bagi hasil kepada PIHAK KEDUA atas setiap pendaftaran jamaah yang telah lunas dan terverifikasi sesuai skema komisi resmi.<br>
                3. PIHAK PERTAMA berhak mengevaluasi kinerja dan kepatuhan kode etik kemitraan PIHAK KEDUA sewaktu-waktu.
            </p>

            <!-- PASAL 3 -->
            <div class="article-title">PASAL 3</div>
            <div class="article-subtitle">HAK DAN KEWAJIBAN PIHAK KEDUA (MITRA)</div>
            <p>
                1. PIHAK KEDUA berhak menerima pembayaran ujroh kemitraan secara tepat waktu melalui rekening bank terdaftar.<br>
                2. PIHAK KEDUA berkewajiban menyampaikan informasi paket secara jujur, akurat, dan transparan sesuai ketentuan resmi perusahaan.<br>
                3. PIHAK KEDUA wajib menjaga nama baik, reputasi, dan integritas brand Amanah Safar dalam setiap aktivitas syiar.<br>
                4. PIHAK KEDUA dilarang memungut biaya pendaftaran di luar harga resmi yang ditetapkan oleh PIHAK PERTAMA.
            </p>

            <!-- PASAL 4 -->
            <div class="article-title">PASAL 4</div>
            <div class="article-subtitle">PENETAPAN HARGA DAN PENCAIRAN UJROH</div>
            <p>
                Harga resmi paket ibadah sepenuhnya ditentukan oleh PIHAK PERTAMA sebagaimana tercantum dalam portal sistem. Pencairan ujroh kemitraan dilakukan melalui transfer langsung ke rekening bank terverifikasi milik PIHAK KEDUA setelah data transaksi dinyatakan valid dan sah oleh manajemen PIHAK PERTAMA.
            </p>

            <!-- PASAL 5 -->
            <div class="article-title">PASAL 5</div>
            <div class="article-subtitle">JANGKA WAKTU PERJANJIAN</div>
            <p>
                Perjanjian ini berlaku efektif terhitung sejak tanggal persetujuan elektronik diberikan oleh PIHAK KEDUA untuk jangka waktu 1 (satu) tahun, dan dapat diperpanjang secara otomatis berdasarkan hasil evaluasi bersama oleh PIHAK PERTAMA.
            </p>

            <!-- PASAL 6 -->
            <div class="article-title">PASAL 6</div>
            <div class="article-subtitle">KERAHASIAAN DAN PERLINDUNGAN DATA</div>
            <p>
                PARA PIHAK sepakat untuk menjaga kerahasiaan seluruh data pribadi calon jamaah dan informasi operasional perusahaan, serta tidak membocorkannya kepada pihak ketiga manapun tanpa persetujuan tertulis terlebih dahulu, sesuai Undang-Undang Perlindungan Data Pribadi yang berlaku di Republik Indonesia.
            </p>

            <!-- PASAL 7 -->
            <div class="article-title">PASAL 7</div>
            <div class="article-subtitle">SYARAT DAN KETENTUAN ANGGOTA JARINGAN</div>
            <ul class="article-list">
                <li>Setiap mitra kemitraan harus terdaftar resmi dan memiliki akun aktif pada platform Amanah Safar.</li>
                <li>Komisi dan insentif dihitung dan dibayarkan sesuai struktur skema resmi yang disetujui perusahaan.</li>
                <li>Mitra dilarang keras menerima setoran tunai pembayaran paket atas nama pribadi calon jamaah. Seluruh transaksi wajib disalurkan melalui rekening resmi perusahaan.</li>
                <li>PIHAK PERTAMA berhak menonaktifkan akun mitra secara sepihak apabila terbukti melakukan wanprestasi, manipulasi sistem, atau pelanggaran hukum.</li>
            </ul>

            <!-- PASAL 8 -->
            <div class="article-title">PASAL 8</div>
            <div class="article-subtitle">PENYELESAIAN SENGKETA DAN PENUTUP</div>
            <p>
                Segala perselisihan yang timbul dari pelaksanaan Perjanjian ini akan diselesaikan secara musyawarah untuk mencapai mufakat. Apabila musyawarah tidak mencapai mufakat, PARA PIHAK sepakat menyelesaikannya melalui Pengadilan Negeri yang berwenang di wilayah hukum domisili PIHAK PERTAMA. Perjanjian ini sah, memiliki kekuatan hukum yang mengikat, dan diakui secara legal berdasarkan UU ITE No. 11 Tahun 2008 Pasal 5 &amp; 6.
            </p>

            <!-- Tanda Tangan Digital -->
            <div class="signatures-container">
                <div class="sig-block">
                    <div class="role">PIHAK PERTAMA</div>
                    <div class="company">PT AMANAH TANGGUH MANDIRI</div>
                    <div class="sig-box">
                        <div class="digital-seal">
                            <i class="fas fa-certificate text-emerald-600"></i><br>
                            TERVERIFIKASI DIGITAL<br>
                            <span style="font-size: 9px; font-weight: 500; color: #065f46;">Amanah Safar System</span>
                        </div>
                    </div>
                    <div class="sig-name">DIREKSI UTAMA</div>
                    <div class="sig-title">PT Amanah Tangguh Mandiri</div>
                </div>

                <div class="sig-block">
                    <div class="role">PIHAK KEDUA (MITRA)</div>
                    <div class="company">&nbsp;</div>
                    <div class="sig-box">
                        <?php if (!empty($user['agreement_accepted_at'])): ?>
                            <div class="digital-seal">
                                <i class="fas fa-signature text-emerald-600"></i><br>
                                DISETUJUI SECARA DIGITAL<br>
                                <span style="font-size: 9px; font-weight: 500; color: #065f46;">
                                    <?= date('d/m/Y H:i:s \W\I\B', strtotime($user['agreement_accepted_at'])) ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div style="font-style: italic; color: #94a3b8; font-size: 11px;">
                                (Menunggu Persetujuan Elektronik)
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?= strtoupper(esc($user['nama_lengkap'])) ?></div>
                    <div class="sig-title">Mitra Terdaftar &bull; <?= esc($voucher['kode'] ?? ('ID #' . $user['id'])) ?></div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="footer-note">
                Dokumen ini merupakan salinan resmi elektronik yang diterbitkan secara otomatis oleh sistem Amanah Safar Digital Platform.<br>
                &copy; 2026 PT Amanah Tangguh Mandiri. All Rights Reserved. | Amanah Safar Digital Platform.
            </div>

        </div>

    </div>

</body>
</html>
