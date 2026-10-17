<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
<style>
    /* Styling Presisi Sesuai Gambar Referensi */
    body {
        background-color: #030105 !important;
        background-image: radial-gradient(circle at 10% 20%, rgba(94, 0, 120, 0.45) 0%, transparent 45%),
                          radial-gradient(circle at 90% 80%, rgba(143, 32, 173, 0.35) 0%, transparent 45%),
                          radial-gradient(circle at 50% 50%, rgba(45, 0, 59, 0.6) 0%, transparent 60%),
                          radial-gradient(rgba(223, 178, 241, 0.15) 1px, transparent 1px) !important;
        background-size: 100% 100%, 100% 100%, 100% 100%, 24px 24px !important;
        background-attachment: fixed !important;
        color: #ffffff;
        min-height: 100vh;
    }
    .auth-glow-bg {
        background: transparent;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .auth-card-container {
        width: 100%;
        max-width: 980px;
        margin: 1rem auto 2.5rem;
        border-radius: 36px;
        overflow: hidden;
        border: 1.5px solid #ffffff;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.9), 0 0 40px rgba(94, 0, 120, 0.25);
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }
    @media (min-width: 1024px) {
        .auth-card-container {
            flex-direction: row;
            min-height: 600px;
        }
        .auth-left-panel {
            width: 44%;
            flex-shrink: 0;
        }
        .auth-right-panel {
            width: 56%;
            flex-grow: 1;
        }
    }
    .auth-left-panel {
        background: linear-gradient(145deg, #180020 0%, #2e003c 45%, #420054 100%);
        color: #ffffff;
        padding: 2.5rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .auth-left-panel,
    .auth-left-panel * {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .auth-left-panel *::selection {
        background: transparent !important;
        color: inherit !important;
    }
    .auth-left-panel img {
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
        pointer-events: none;
    }
    .auth-left-panel a,
    .auth-left-panel button {
        pointer-events: auto;
        cursor: pointer;
    }
    .auth-right-panel {
        background: #ffffff;
        padding: 2.5rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #0f172a;
    }
    .form-group-spaced {
        margin-bottom: 1rem;
    }
    .btn-brand-primary {
        width: 100%;
        background-color: #6b008a;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.875rem;
        letter-spacing: 0.015em;
        padding: 0.6875rem 1.25rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 6px 18px -3px rgba(107, 0, 138, 0.45), 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .btn-brand-primary:hover {
        background-color: #55006e;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px -3px rgba(107, 0, 138, 0.55), 0 3px 6px rgba(0, 0, 0, 0.14);
    }
    .btn-brand-primary:active {
        background-color: #430057;
        transform: scale(0.99);
    }
    .nav-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.15rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.28);
        color: #ffffff !important;
        font-size: 0.8125rem;
        font-weight: 600;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }
    .nav-pill-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        border-color: rgba(255, 255, 255, 0.5);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3), 0 0 15px rgba(223, 178, 241, 0.25);
    }
    .nav-pill-btn .icon-arrow {
        color: #dfb2f1;
        font-size: 0.75rem;
        transition: transform 0.2s ease, color 0.2s ease;
    }
    .nav-pill-btn:hover .icon-arrow {
        transform: translateX(-3px);
        color: #ffffff;
    }
    .nav-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.375rem 0.95rem;
        border-radius: 9999px;
        background: rgba(107, 0, 138, 0.32);
        border: 1px solid rgba(202, 130, 230, 0.38);
        color: #e9d5ff !important;
        font-size: 0.8125rem;
        font-weight: 600;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        letter-spacing: 0.01em;
    }
    .nav-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 9999px;
        background-color: #dfb2f1;
        box-shadow: 0 0 8px #dfb2f1;
        display: inline-block;
        flex-shrink: 0;
        animation: pulseBadgeDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulseBadgeDot {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
            box-shadow: 0 0 10px rgba(223, 178, 241, 0.9);
        }
        50% {
            opacity: 0.35;
            transform: scale(0.85);
            box-shadow: 0 0 2px rgba(223, 178, 241, 0.3);
        }
    }
    .frosted-tile {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .circle-icon-badge {
        width: 2rem;
        height: 2rem;
        min-width: 2rem;
        min-height: 2rem;
        border-radius: 9999px;
        background: rgba(202, 130, 230, 0.14);
        border: 1.5px solid rgba(202, 130, 230, 0.45);
        color: #dfb2f1 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 0 10px rgba(202, 130, 230, 0.12);
        transition: all 0.25s ease;
    }
    .circle-icon-badge:hover {
        background: rgba(202, 130, 230, 0.22);
        border-color: rgba(223, 178, 241, 0.65);
        box-shadow: 0 0 14px rgba(223, 178, 241, 0.25);
    }
    .circle-icon-badge i {
        color: #dfb2f1 !important;
        font-size: 0.75rem;
    }
    .frosted-box {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.16);
    }
    .text-purple-muted,
    .panel-feature-text,
    .auth-left-panel p,
    .auth-left-panel ul li span,
    .left-panel-footer,
    .left-panel-footer span {
        color: #dfb2f1 !important;
    }
    .border-white-subtle {
        border-color: rgba(255, 255, 255, 0.14);
    }
    .auth-bottom-footer {
        width: 100%;
        max-w-5xl;
        margin: 0 auto;
        padding: 0.85rem 1rem;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.45);
    }
    .auth-bottom-footer a {
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .auth-bottom-footer a:hover {
        color: #ffffff;
    }
    .left-panel-footer {
        position: relative;
        z-index: 10;
        margin-top: 2rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #dfb2f1 !important;
    }
    @media (max-width: 640px) {
        .auth-left-panel {
            padding: 2.25rem 1.35rem;
        }
        .auth-right-panel {
            padding: 2.25rem 1.35rem;
        }
        .auth-card-container {
            border-radius: 24px;
        }
    }
    .auth-submit-wrap {
        margin-top: 1.5rem;
    }
    .auth-card-footer {
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f1f5f9;
        text-align: center;
        font-size: 0.6875rem;
        color: #94a3b8;
    }
    .auth-tab-switcher {
        display: inline-flex;
        align-items: center;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 4px;
        gap: 4px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    .auth-tab-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 72px;
        text-align: center;
        gap: 0.35rem;
        padding: 0.45rem 1.15rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        line-height: 1;
        cursor: pointer;
        user-select: none;
    }
    .auth-tab-btn.active {
        background-color: #6b008a;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 4px 12px -1px rgba(107, 0, 138, 0.4), 0 2px 4px rgba(0, 0, 0, 0.06);
    }
    /* Scrollable Container Dokumen PKS */
    .pks-document-scroll {
        max-height: min(48vh, 420px);
        min-height: 220px;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        overscroll-behavior-y: contain;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }
    .pks-document-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .pks-document-scroll::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 9999px;
    }
    .pks-document-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .pks-document-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    @media (max-width: 640px) {
        .pks-document-scroll {
            max-height: 320px;
            padding: 0.875rem !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="auth-glow-bg p-4 sm:p-6 lg:p-8 relative selection:bg-[#6b008a] selection:text-white">

    <!-- Top Navigation Header (Persis Referensi: Tombol Kiri Pill & Badge Status Kanan) -->
    <header class="w-full max-w-5xl mx-auto flex items-center justify-between py-2 z-10">
        <a href="<?= base_url('/verify') ?>" class="nav-pill-btn group">
            <i class="fas fa-arrow-left icon-arrow"></i>
            <span>Kembali ke Verifikasi Data</span>
        </a>
        <div class="nav-pill-badge">
            <span class="nav-badge-dot"></span>
            <span>Tahap 2 dari 2: PKS Online</span>
        </div>
    </header>

    <!-- Main Card Container (2 Kolom Presisi Sesuai Referensi) -->
    <main class="w-full max-w-5xl mx-auto z-10 flex-grow flex items-start justify-center pt-2 sm:pt-4">
        <div class="auth-card-container">
            
            <!-- Panel Kiri: Identitas Ungu Amanah Safar & Stepper -->
            <div class="auth-left-panel">
                <div class="relative z-10">
                    <!-- Logo Resmi Amanah Safar Putih & Identitas -->
                    <div class="mb-8" style="margin-bottom: 2rem;">
                        <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Amanah Safar" class="block h-11 sm:h-12 w-auto object-contain mb-1.5 drop-shadow-sm">
                        <span class="block text-[11px] font-semibold text-[#ca82e6] tracking-wider uppercase">Onboarding Mitra</span>
                    </div>

                    <!-- Step Progress Indicator -->
                    <div class="mb-6 frosted-box rounded-2xl p-4">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-white">Proses Onboarding</span>
                            <span class="text-emerald-300 font-bold">Langkah 2/2 Selesai</span>
                        </div>
                        <div class="w-full bg-black/20 rounded-full h-1.5 flex overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-400 to-emerald-400 h-1.5 rounded-full w-full"></div>
                        </div>
                        <div class="flex items-center justify-between mt-3 text-[11px] text-purple-muted">
                            <span class="text-emerald-300 font-bold flex items-center gap-1.5">
                                <i class="fas fa-circle-check text-xs"></i> 1. Terverifikasi
                            </span>
                            <span class="text-amber-300 font-bold flex items-center gap-1.5">
                                <i class="fas fa-circle-dot text-[8px]"></i> 2. PKS Online
                            </span>
                        </div>
                    </div>

                    <!-- Headline Sambutan -->
                    <h1 class="text-2xl sm:text-[26px] font-extrabold leading-snug text-white mb-4">
                        Perjanjian Kerja Sama (PKS) Digital.
                    </h1>
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 panel-feature-text" style="color: #dfb2f1 !important;">
                        Dokumen legalitas ini mengikat kesepakatan kemitraan syiar antara PT Amanah Tangguh Mandiri dengan Mitra, menjamin transparansi ujroh dan kode etik layanan.
                    </p>

                    <!-- Poin Keunggulan Legalitas (Ikon Bulat) -->
                    <ul class="space-y-3.5 text-xs">
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-scale-balanced"></i>
                            </div>
                            <span class="panel-feature-text" style="color: #dfb2f1 !important;">Landasan hukum resmi sesuai regulasi Kemenag & UU ITE</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-file-shield"></i>
                            </div>
                            <span class="panel-feature-text" style="color: #dfb2f1 !important;">Persetujuan elektronik sah & tersimpan permanen</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <span class="panel-feature-text" style="color: #dfb2f1 !important;">Hak ujroh, perlindungan data, dan transparansi terjamin</span>
                        </li>
                    </ul>
                </div>

                <!-- Footer Panel Kiri -->
                <div class="left-panel-footer" style="color: #dfb2f1 !important;">
                    <span class="panel-feature-text" style="color: #dfb2f1 !important;">Butuh Bantuan?</span>
                </div>
            </div>

            <!-- Panel Kanan: Area Formulir Putih Bersih -->
            <div class="auth-right-panel">
                <div>
                    <!-- Form Header (Judul & Subjudul Asli Sistem) -->
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Perjanjian Pengguna</h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">Harap baca dan setujui perjanjian berikut sebelum melanjutkan</p>
                        </div>
                        <div class="auth-tab-switcher">
                            <span class="auth-tab-btn active">
                                <i class="fas fa-file-signature text-[11px] mr-1"></i> Tahap 2: PKS
                            </span>
                        </div>
                    </div>

                    <!-- Flash Message Notifications -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="mt-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
                            <i class="fas fa-circle-exclamation text-rose-500 text-sm shrink-0"></i>
                            <div class="font-medium"><?= esc(session()->getFlashdata('error')) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('warning')): ?>
                        <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3">
                            <i class="fas fa-triangle-exclamation text-amber-500 text-sm shrink-0"></i>
                            <div class="font-medium"><?= esc(session()->getFlashdata('warning')) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="mt-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-3">
                            <i class="fas fa-circle-check text-emerald-500 text-sm shrink-0"></i>
                            <div class="font-medium"><?= esc(session()->getFlashdata('success')) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $rawDate = $user['agreement_accepted_at'] ?? $verification['created_at'] ?? date('Y-m-d H:i:s');
                    $pksDateFormatted = format_tanggal_indonesia($rawDate, true);
                    $pihakKeduaNama = !empty($user['nama_lengkap']) ? strtoupper(trim($user['nama_lengkap'])) : 'MITRA TERDAFTAR';
                    $pihakKeduaNik = !empty($verification['nik']) ? trim($verification['nik']) : '-';
                    $pihakKeduaAlamat = !empty($user['domisili']) ? strtoupper(trim($user['domisili'])) : 'INDONESIA';
                    ?>

                    <!-- Ringkasan Para Pihak -->
                    <div class="mt-4 bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Para Pihak yang Bersepakat:</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700">
                            <div class="border-l-2 border-[#6b008a] pl-2.5">
                                <span class="font-bold text-slate-900 block text-[11px]">PIHAK PERTAMA</span>
                                <span class="text-xs font-semibold block text-slate-800">PT AMANAH TANGGUH MANDIRI (AMANAH SAFAR)</span>
                                <span class="text-[11px] text-slate-500">M. Erwin Saputra &bull; Direktur Utama</span>
                            </div>
                            <div class="border-l-2 border-amber-500 pl-2.5">
                                <span class="font-bold text-slate-900 block text-[11px]">PIHAK KEDUA (MITRA AMANAH)</span>
                                <span class="text-xs font-semibold block text-slate-800"><?= esc($pihakKeduaNama) ?></span>
                                <span class="text-[11px] text-slate-500">NIK: <?= esc($pihakKeduaNik) ?> &bull; <?= esc($pihakKeduaAlamat) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Scrollable Konten Perjanjian (100% Verbatim Sesuai PDF Referensi) -->
                    <div class="pks-document-scroll mt-4 bg-slate-50/70 border border-slate-200 rounded-xl p-4 text-xs leading-relaxed text-slate-700 space-y-3.5 shadow-inner" style="max-height: 380px; overflow-y: auto;">
                        <div class="text-center pb-2.5 border-b border-slate-200">
                            <strong class="text-xs text-slate-900 block font-black uppercase tracking-wide">PERJANJIAN KERJA SAMA MITRA AMANAH</strong>
                            <strong class="text-[11px] text-[#6b008a] block font-bold">PT Amanah Tangguh Mandiri dengan merek Amanah Safar</strong>
                            <span class="text-[10px] text-slate-500">Nomor: PKS/AS/<?= date('Ym') ?>/<?= str_pad((string)($user['id'] ?? 1), 4, '0', STR_PAD_LEFT) ?></span>
                        </div>

                        <p class="text-slate-800">
                            Perjanjian ini dibuat oleh dan antara para pihak berikut.
                        </p>

                        <div class="space-y-2 text-[11.5px] text-slate-800">
                            <p>
                                <strong>1. PT AMANAH TANGGUH MANDIRI</strong>, dengan merek <strong>AMANAH SAFAR</strong>, berkedudukan di Gedung Graha Pool Lt. 3 Unit 303-304, Jl. Merdeka No. 110, Ciwaringin, Bogor Tengah, Kota Bogor, Jawa Barat, diwakili oleh <strong>M. Erwin Saputra</strong> selaku Direktur Utama atau pejabat yang memperoleh kewenangan sah darinya, selanjutnya disebut <strong>Perusahaan</strong> atau <strong>Pusat</strong>.
                            </p>
                            <p>
                                <strong>2. Orang perseorangan</strong> yang nama, NIK, alamat lengkap, dan kontaknya telah diverifikasi serta ditampilkan dalam Ringkasan Kemitraan yang disetujui bersamaan dengan Perjanjian ini, bertindak untuk diri sendiri, selanjutnya disebut <strong>Mitra</strong>.
                            </p>
                        </div>

                        <p class="text-[11.5px] text-slate-800">
                            Perusahaan dan Mitra selanjutnya disebut <strong>Para Pihak</strong>. Perusahaan menyediakan produk sesuai perizinan berusaha yang berlaku. Mitra bermaksud membantu pemasaran melalui Program Mitra Amanah. Para Pihak sepakat mengikatkan diri pada ketentuan berikut.
                        </p>

                        <p class="text-[11.5px] text-slate-800">
                            Ringkasan Kemitraan memuat nomor perjanjian, versi naskah, identitas Para Pihak, dan kontak resmi yang dapat diperiksa sebelum persetujuan. Catatan waktu dilengkapi saat masing-masing pihak menyetujui. Ringkasan disimpan bersama naskah ini dan menjadi bagian yang tidak terpisahkan dari Perjanjian. Tanggal efektif mengikuti Pasal 14 dan Pasal 18, bukan tanggal versi naskah.
                        </p>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 1</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PENGERTIAN DAN RUANG LINGKUP</strong>
                            <p class="mt-1">(1) Produk adalah layanan umrah, haji khusus, perjalanan wisata, atau layanan lain yang secara sah disediakan Perusahaan dan dibuka untuk pemasaran oleh Mitra sesuai perizinan masing-masing.</p>
                            <p class="mt-1">(2) Jamaah mencakup calon jamaah atau pelanggan yang diperkenalkan Mitra. Tagihan Resmi adalah tagihan yang diterbitkan Pusat, memuat identitas pemesanan, produk, nominal, peruntukan, dan tujuan pembayaran yang telah diperiksa Perusahaan.</p>
                            <p class="mt-1">(3) Kanal Resmi adalah aplikasi, situs, alamat surat elektronik, atau nomor kontak Pusat yang identitasnya ditampilkan dalam Ringkasan Kemitraan. Mitra tidak berwenang menetapkan atau mengubah Kanal Resmi.</p>
                            <p class="mt-1">(4) Konfirmasi Komisi adalah kesepakatan tertulis atau elektronik mengenai tarif dan syarat komisi produk sebagaimana diatur dalam Pasal 9 dan Lampiran 1. SOP dalam Lampiran 2 mengatur alur minimum kerja sama.</p>
                            <p class="mt-1">(5) Kerja sama meliputi pengenalan calon jamaah, penyampaian informasi resmi, dan pendampingan administratif terbatas. Penyelenggaraan layanan serta hubungan kontraktual dengan jamaah tetap dikelola Perusahaan.</p>
                            <p class="mt-1">(6) Hari Kerja adalah Senin sampai Jumat selain hari libur nasional. Tenggat yang hanya menyebut hari tanpa kata kerja dihitung sebagai hari kalender.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 2</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">STATUS MITRA DAN BATAS KEWENANGAN</strong>
                            <p class="mt-1">(1) Mitra merupakan pihak independen dalam hubungan kemitraan. Perjanjian ini tidak memberikan kedudukan sebagai direksi, pegawai, pemegang izin, kantor cabang, atau penerima kuasa umum Perusahaan. Penilaian hubungan kerja dan kewajiban perizinan tetap mengikuti pelaksanaan sebenarnya serta hukum yang berlaku.</p>
                            <p class="mt-1">(2) Mitra hanya berwenang menyampaikan materi resmi yang berlaku, memperkenalkan calon jamaah, dan membantu administrasi sesuai SOP. Mitra tidak berwenang menandatangani kontrak atas nama Perusahaan, membuat atau mengubah harga, tagihan, kuitansi, paket, jadwal, jaminan layanan, atau keputusan keberangkatan.</p>
                            <p class="mt-1">(3) Surat tugas, ID mitra, akses aplikasi, pelatihan, dan penggunaan merek tidak memberikan kuasa menerima uang. Larangan menerima dana dalam Pasal 6 tidak dapat dikecualikan melalui surat tugas, chat, persetujuan jamaah, atau penugasan Mitra berdasarkan Perjanjian ini.</p>
                            <p class="mt-1">(4) Mitra tidak boleh menunjuk subagen, meminjamkan akun, atau mengalihkan kerja sama. Orang yang direkomendasikan menjadi mitra wajib diverifikasi dan membuat perjanjian langsung dengan Perusahaan sebelum bertindak.</p>
                            <p class="mt-1">(5) Mitra dilarang menyelenggarakan paket sendiri atas nama Perusahaan atau menggunakan kerja sama ini sebagai pinjam izin. Tindakan yang memerlukan kewenangan atau izin lain harus diproses tersendiri sesuai hukum dan tidak dianggap diberikan oleh Perjanjian ini.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 3</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">HAK DAN KEWAJIBAN PERUSAHAAN</strong>
                            <p class="mt-1">(1) Perusahaan berhak menentukan produk yang dipasarkan, memeriksa identitas dan kepatuhan Mitra, mengawasi kegiatan yang terkait dengan kerja sama, serta melakukan pengamanan dan pengakhiran sesuai Perjanjian ini.</p>
                            <p class="mt-1">(2) Perusahaan menyediakan informasi produk, materi promosi, ketentuan komisi, SOP, tujuan pembayaran, dan Kanal Resmi yang jelas. Perusahaan memastikan produk yang dipasarkan sesuai dengan izin serta kapasitas layanan yang dimilikinya.</p>
                            <p class="mt-1">(3) Perusahaan menerbitkan perjanjian layanan dengan jamaah, mengelola pendaftaran, memverifikasi penerimaan dana, dan menyampaikan konfirmasi langsung mengenai pembayaran serta status layanan.</p>
                            <p class="mt-1">(4) Perusahaan menjalankan kewajiban penyelenggaraan, pelaporan, pelindungan jamaah, penanganan pengaduan, dan pelindungan data sesuai peraturan. Perusahaan tetap wajib mengawasi orang yang diberi akses terhadap proses dan data di bawah kendalinya.</p>
                            <p class="mt-1">(5) Perusahaan membayar komisi yang telah menjadi hak Mitra sesuai Pasal 9, menjaga bukti transaksi, dan menyediakan kesempatan klarifikasi atas pembatasan hak atau dugaan pelanggaran.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 4</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">HAK DAN KEWAJIBAN MITRA</strong>
                            <p class="mt-1">(1) Mitra berhak memperoleh materi resmi, pembinaan, informasi referral yang relevan, serta komisi sesuai kesepakatan. Akses data dibatasi pada kebutuhan tugas dan tidak memberikan hak mengubah status pembayaran atau keberangkatan.</p>
                            <p class="mt-1">(2) Mitra wajib memberikan data dan informasi yang benar, memperbarui kontak atau alamat paling lambat 3 Hari Kerja setelah berubah, mengikuti SOP, dan menjaga kerahasiaan serta dokumen jamaah.</p>
                            <p class="mt-1">(3) Mitra wajib memperkenalkan diri sebagai Mitra Pemasaran Amanah Safar dan menjelaskan bahwa transaksi, pembayaran, serta keputusan layanan dilakukan Pusat. Mitra tidak boleh memberi kesan bahwa dirinya memiliki kewenangan lebih luas.</p>
                            <p class="mt-1">(4) Mitra wajib melaporkan dugaan penyalahgunaan uang, identitas, dokumen, atau pengaduan yang berisiko segera setelah diketahui, paling lambat 6 jam, melalui Kanal Resmi. Laporan dilengkapi bukti yang tersedia tanpa menunggu bukti lengkap.</p>
                            <p class="mt-1">(5) Mitra wajib membantu penelusuran transaksi, penanganan pengaduan, dan serah terima yang relevan. Mitra tidak boleh menyembunyikan jamaah, menghapus bukti, memberikan data palsu, meminta kode rahasia bank jamaah, atau menggunakan keadaan darurat sebagai alasan menerima uang.</p>
                            <p class="mt-1">(6) Biaya pemasaran yang dikeluarkan Mitra menjadi tanggungannya sendiri kecuali telah disetujui tertulis untuk ditanggung Perusahaan. Mitra dilarang mengatasnamakan Perusahaan untuk berutang, memungut sumbangan, atau meminta pembayaran lain tanpa dasar yang sah.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 5</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PENDAFTARAN DAN KOMUNIKASI DENGAN JAMAAH</strong>
                            <p class="mt-1">(1) Sebelum meminta pembayaran atau pemberian perlengkapan, Mitra wajib mengarahkan calon jamaah ke Pusat dan menyerahkan data pendaftaran melalui kanal yang ditentukan dengan dasar pemrosesan data yang sah.</p>
                            <p class="mt-1">(2) Pusat memverifikasi identitas serta nomor kontak jamaah atau wakil keluarga yang sah. Nomor Mitra tidak boleh menjadi satu-satunya kontak. Mitra dilarang menguasai akses jamaah atau menghalangi komunikasi langsung dengan Pusat.</p>
                            <p class="mt-1">(3) Pusat menyampaikan penawaran dan perjanjian layanan langsung kepada jamaah, termasuk harga, fasilitas, jadwal atau estimasi yang relevan, pembayaran, perubahan, pembatalan, dan pengembalian dana. Jamaah harus mempunyai kesempatan memeriksa serta menyetujuinya.</p>
                            <p class="mt-1">(4) Pencatatan lead di aplikasi tidak dengan sendirinya membuktikan pendaftaran resmi, penerimaan dana, penerbitan nomor porsi, atau kepastian keberangkatan. Setiap status harus mengikuti bukti dan prosedur yang berlaku, termasuk Sistem Informasi Kementerian bila diwajibkan.</p>
                            <p class="mt-1">(5) Jika Perusahaan telah menerima pembayaran dan pendaftaran jamaah, ketiadaan nama dalam manifest tidak dengan sendirinya menghapus hubungan transaksi atau hak jamaah. Status, kewajiban, dan penyelesaian dinilai menurut perjanjian layanan, bukti, serta hukum.</p>
                            <p class="mt-1">(6) Konfirmasi pembayaran, sisa tagihan, perubahan layanan, pengaduan, dan instruksi keberangkatan disampaikan langsung oleh Pusat. Bila kontak tidak valid atau jamaah tidak dapat dihubungi, Pusat dan Mitra wajib menindaklanjuti sebelum menyatakan proses selesai.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 6</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PEMBAYARAN DAN LARANGAN MENERIMA DANA</strong>
                            <p class="mt-1">(1) Dana Biaya Perjalanan Ibadah Umrah wajib disetorkan langsung ke Rekening Penampungan PPIU pada bank penerima setoran sesuai ketentuan. Setoran haji khusus mengikuti mekanisme BPS Bipih dan BPKH. Biaya jasa atau produk lain yang sah dirinci sesuai peruntukannya dalam Tagihan Resmi.</p>
                            <p class="mt-1">(2) Perusahaan wajib memverifikasi kepemilikan, fungsi, dan peruntukan rekening sebelum menampilkannya dalam Tagihan Resmi. Rekening atas nama Perusahaan tidak otomatis digunakan untuk setiap jenis setoran. Mitra wajib merujuk tujuan pembayaran sesuai Tagihan Resmi yang diterima langsung dari Pusat.</p>
                            <p class="mt-1">(3) Mitra dilarang menerima, menampung, menitipkan kepada pihak lain, meminjam, menggunakan, atau mengalihkan dana jamaah, termasuk DP, pelunasan, biaya tambahan, dan refund, dalam bentuk tunai, rekening pribadi, keluarga, badan usaha, pihak lain, dompet elektronik, QRIS, atau sarana pembayaran lain.</p>
                            <p class="mt-1">(4) Larangan pada ayat (3) berlaku meskipun jamaah meminta atau menyetujui penitipan uang. Mitra hanya boleh membantu menjelaskan cara pembayaran tanpa menguasai dana, rekening, kartu, PIN, kata sandi, atau kode OTP bank jamaah. Bantuan petugas internal PPIU atas penyetoran yang diwakilkan diatur terpisah sesuai hukum dan tidak diberikan melalui status Mitra.</p>
                            <p class="mt-1">(5) Mitra dilarang memotong komisi, memperhitungkan utang pribadi, atau menutup pembayaran jamaah lain dari dana jamaah. Komisi dibayarkan Perusahaan secara terpisah. Mitra juga dilarang menawarkan rekening pengganti atau meminta jamaah mengabaikan Tagihan Resmi.</p>
                            <p class="mt-1">(6) Pusat mencocokkan setoran dengan mutasi atau konfirmasi bank dan identitas pemesanan. Gambar transfer, kuitansi buatan Mitra, atau pernyataan lisan tidak dengan sendirinya mengesahkan pelunasan. Konfirmasi nominal diterima, sisa tagihan, dan jatuh tempo dikirim Pusat kepada jamaah paling lambat 1 Hari Kerja setelah rekonsiliasi selesai.</p>
                            <p class="mt-1">(7) Perusahaan melakukan rekonsiliasi secara rutin dan memenuhi tenggat pelaporan menurut peraturan. Hak membuat tagihan, mengesahkan pembayaran, mengeluarkan perlengkapan, dan menetapkan daftar keberangkatan dipisahkan sesuai kewenangan petugas serta dicatat.</p>
                            <p class="mt-1">(8) Jika dana terlanjur diterima Mitra tanpa kewenangan, Mitra wajib segera melapor sesuai Pasal 4 ayat (4), tidak memakai atau memindahkan dana untuk kepentingan apa pun, dan memulihkannya melalui instruksi Pusat yang sah serta dapat ditelusuri. Pemulihan tidak menghapus pelanggaran.</p>
                            <p class="mt-1">(9) Pelanggaran Pasal ini merupakan pelanggaran berat. Ketentuan pembayaran ini mengatur hubungan Para Pihak dan tidak menghapus hak jamaah atau tanggung jawab Perusahaan yang diwajibkan hukum.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 7</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">HARGA PROMOSI DAN LARANGAN JANJI</strong>
                            <p class="mt-1">(1) Mitra hanya menggunakan harga, fasilitas, syarat, dan materi promosi resmi yang masih berlaku. Perusahaan menyimpan versi penawaran yang menjadi dasar transaksi. Perubahan untuk transaksi yang sudah disepakati mengikuti perjanjian layanan dan persetujuan yang diperlukan.</p>
                            <p class="mt-1">(2) Mitra tidak boleh membuat diskon, hadiah, tambahan biaya, jaminan refund, atau pungutan atas nama Perusahaan di luar penawaran resmi. Biaya tambahan yang sah harus ditagihkan serta dijelaskan oleh Pusat, bukan dipungut Mitra.</p>
                            <p class="mt-1">(3) Mitra dilarang menjamin tahun keberangkatan haji, percepatan, nomor porsi, kuota, penerbitan visa, seat pesawat, hotel, kurs, harga tetap, atau kelulusan pembiayaan di luar informasi dan syarat resmi yang telah disetujui Pusat. Estimasi tidak boleh dipresentasikan sebagai kepastian.</p>
                            <p class="mt-1">(4) Mitra dilarang memalsukan dokumen atau keadaan transaksi, termasuk data untuk pembiayaan. Informasi harga, uang muka, angsuran, biaya, risiko, dan syarat persetujuan harus sesuai transaksi serta ketentuan penyedia pembiayaan yang sah.</p>
                            <p class="mt-1">(5) Mitra tidak boleh mengaku sebagai penyelenggara berizin atas dasar izin Perusahaan, menerbitkan dokumen seolah-olah dari Pusat, atau memakai testimoni dan data jamaah secara tidak sah. Koreksi atas promosi yang keliru wajib dilakukan segera setelah diketahui atau diberitahukan.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 8</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">MEREK IDENTITAS DAN PERLENGKAPAN</strong>
                            <p class="mt-1">(1) Perusahaan memberikan izin penggunaan merek dan materi resmi secara terbatas untuk pelaksanaan Perjanjian. Izin tersebut tidak dapat dialihkan, tidak memberikan hak kepemilikan, dan berakhir ketika dicabut atau kerja sama berakhir.</p>
                            <p class="mt-1">(2) Mitra wajib memperoleh persetujuan sebelum membuat atau mengelola akun, domain, aplikasi, papan nama, atau media yang memakai identitas Perusahaan. Mitra dilarang membuat cap, kop surat, kuitansi, ID, surat tugas, atau tampilan yang menimbulkan kesan sebagai Perusahaan atau kantor cabang di luar kewenangan serta ketentuan perizinan.</p>
                            <p class="mt-1">(3) Koper, seragam, dan perlengkapan resmi hanya dikeluarkan Pusat per ID serta nama jamaah setelah pelunasan terverifikasi dan alokasi layanan diperiksa. Barang tidak boleh dikeluarkan hanya berdasarkan DP, janji agen, atau bukti transfer yang belum diverifikasi.</p>
                            <p class="mt-1">(4) Mitra tidak boleh memproduksi, membeli untuk disalurkan sebagai barang resmi, menyimpan stok bebas, menjual, atau membagikan atribut kepada jamaah di luar catatan Pusat. Jika membantu distribusi, tugas harus ditetapkan per nama penerima dan jumlah barang, tanpa stok bebas, disertai serah terima serta konfirmasi langsung oleh Pusat.</p>
                            <p class="mt-1">(5) Penyerahan perlengkapan tidak menggantikan kuitansi atau konfirmasi kesiapan keberangkatan. Pembayaran, tiket, visa, asuransi, dan kesiapan layanan diperiksa sesuai tahapan yang berlaku. Mitra dilarang menggunakan atribut sebagai bukti bahwa jamaah pasti berangkat.</p>
                            <p class="mt-1">(6) Saat izin dicabut atau kerja sama berakhir, Mitra wajib menghentikan penggunaan merek dan materi, mengembalikan barang milik Perusahaan, serta menyerahkan atau menonaktifkan sarana promosi terkait sesuai hak kepemilikan dan instruksi yang sah.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 9</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">KOMISI INSENTIF DAN BIAYA</strong>
                            <p class="mt-1">(1) Besaran atau rumus komisi ditentukan dalam Konfirmasi Komisi untuk produk atau program terkait. Konfirmasi disampaikan dan disetujui secara tercatat sebelum produk dipasarkan, memuat tarif, pemicu hak, syarat transaksi, pembatalan, pajak, dan tenggat pembayaran sesuai Lampiran 1. Perusahaan tidak boleh meminta pemasaran berkomisi tanpa kejelasan ketentuan tersebut.</p>
                            <p class="mt-1">(2) Referral diakui berdasarkan pendaftaran yang tercatat, identitas jamaah yang dapat diverifikasi, dan bukti kontribusi Mitra. Duplikasi atau sengketa referral diperiksa berdasarkan bukti; Perusahaan menyampaikan alasan penetapan dan memberi kesempatan klarifikasi.</p>
                            <p class="mt-1">(3) Hak komisi atas paket keberangkatan timbul setelah transaksi sah, dana yang dipersyaratkan diterima dan diverifikasi, serta jamaah berangkat. Pembayarannya paling lambat 2 hari setelah keberangkatan. Identitas perpajakan dan rekening penerima komisi wajib dilengkapi sebelum keberangkatan agar pembayaran dapat dilakukan tepat waktu.</p>
                            <p class="mt-1">(4) Untuk komisi yang disepakati timbul sebelum keberangkatan, termasuk program referral pendaftaran haji atau layanan tersendiri, pemicu dan tanggal pencairan wajib dinyatakan dalam Konfirmasi Komisi sebelum pemasaran. Apabila tidak ditetapkan pemicu yang berbeda, ketentuan ayat (3) berlaku.</p>
                            <p class="mt-1">(5) Komisi dibayar ke rekening atas nama Mitra yang terverifikasi setelah pemotongan pajak yang diwajibkan. Perusahaan menyediakan rincian serta bukti pemotongan sesuai ketentuan. Perubahan tarif tidak boleh mengurangi hak atas referral yang telah diterima berdasarkan ketentuan sebelumnya.</p>
                            <p class="mt-1">(6) Pembatalan atau perubahan transaksi memengaruhi komisi sesuai Konfirmasi Komisi dan sebab pembatalan. Komisi yang telah dibayar hanya dapat diminta kembali bila memang tidak berhak menurut kesepakatan dan bukti. Pembatalan akibat kesalahan Perusahaan tidak otomatis dibebankan kepada Mitra.</p>
                            <p class="mt-1">(7) Atas indikasi pelanggaran yang berdasar, Perusahaan dapat menahan sementara bagian komisi yang terkait secara proporsional paling lama 30 Hari Kerja dengan alasan tertulis dan kesempatan klarifikasi. Perpanjangan harus mempunyai dasar kesepakatan tertulis atau penetapan pihak berwenang. Bagian yang tidak disengketakan tetap dibayar.</p>
                            <p class="mt-1">(8) Perjumpaan utang atau pengurangan komisi untuk ganti rugi hanya dilakukan atas jumlah yang telah diakui tertulis oleh Mitra, disepakati dalam penyelesaian yang mengikat, atau ditetapkan dengan putusan yang berkekuatan hukum tetap. Dana jamaah tidak boleh dipakai untuk penyelesaian tersebut.</p>
                            <p class="mt-1">(9) Perjanjian ini tidak menjanjikan penghasilan tetap atau hasil investasi. Biaya keanggotaan, penjaminan, atau program berbayar tidak timbul semata-mata karena PKS ini dan memerlukan kesepakatan terpisah yang sah. Perekrutan orang lain tidak dengan sendirinya menimbulkan komisi.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 10</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">KERAHASIAAN DATA DAN DOKUMEN</strong>
                            <p class="mt-1">(1) Para Pihak menjaga informasi nonpublik yang diperoleh melalui kerja sama, termasuk data jamaah, data transaksi, akses sistem, dan informasi bisnis. Kewajiban ini tetap berlaku setelah kerja sama berakhir sepanjang informasi masih bersifat rahasia.</p>
                            <p class="mt-1">(2) Mitra hanya memperoleh dan memproses data sesuai tugas, instruksi, tujuan, dan dasar pemrosesan yang sah. Mitra dilarang menjual, mengalihkan, memakai untuk kepentingan pribadi, atau mengungkapkan data kepada pihak yang tidak berhak. Perusahaan tetap memenuhi kewajiban sebagai pengendali data sesuai kegiatannya.</p>
                            <p class="mt-1">(3) Akses dibatasi pada kebutuhan tugas dan tidak boleh dibagikan. Dokumen identitas, kesehatan, keuangan, serta data anak hanya diproses melalui kanal aman sesuai ketentuan. Penggunaan foto atau testimoni untuk promosi memerlukan dasar serta persetujuan yang sesuai, terpisah dari persetujuan layanan jika diwajibkan.</p>
                            <p class="mt-1">(4) Paspor dan dokumen asli dicatat dalam serah terima, diamankan, serta dikembalikan ketika tidak lagi diperlukan. Mitra tidak boleh menahan dokumen sebagai jaminan utang, komisi, atau perselisihan. Penyerahan melalui Mitra hanya untuk tugas yang dicatat Pusat dan tidak memberikan hak menahan dokumen.</p>
                            <p class="mt-1">(5) Mitra melaporkan dugaan kegagalan pelindungan data paling lambat 6 jam sejak diketahui, menjaga bukti, dan membantu penanganan. Perusahaan memenuhi kewajiban pemberitahuan serta pemulihan sesuai undang-undang. Batas 6 jam adalah kewajiban internal dan tidak menggantikan tenggat hukum.</p>
                            <p class="mt-1">(6) Ketika tujuan pemrosesan berakhir atau akses dicabut, data diserahkan dengan aman dan salinan tanpa dasar retensi dihapus dengan bukti. Dokumen yang wajib disimpan atau diperlukan untuk sengketa diamankan sesuai hukum dan tidak dipakai untuk tujuan lain.</p>
                            <p class="mt-1">(7) Data Mitra diproses seperlunya untuk verifikasi, pelaksanaan PKS, pembayaran, pajak, pengawasan, dan pembuktian sesuai pemberitahuan privasi serta dasar yang sah. Perusahaan tidak memperoleh hak tanpa batas atas data pribadi Mitra karena PKS ini.</p>
                            <p class="mt-1">(8) Kerahasiaan tidak menghalangi pengaduan yang sah, pembelaan hak, atau pemenuhan permintaan aparat dan regulator yang berwenang. Pengungkapan dilakukan sebatas yang diperlukan sesuai hukum.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 11</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PENGADUAN PERUBAHAN LAYANAN DAN REFUND</strong>
                            <p class="mt-1">(1) Mitra wajib segera meneruskan pengaduan, permintaan perubahan, atau pembatalan kepada Pusat dan tidak boleh menyembunyikan, menunda demi komisi, mengancam pelapor, atau menjanjikan penyelesaian tanpa kewenangan.</p>
                            <p class="mt-1">(2) Pusat memverifikasi langsung dengan jamaah dan pembayar, menjaga bukti, serta memberikan penanganan sesuai perjanjian layanan dan hukum. Hak jamaah yang telah jelas tidak boleh ditunda semata-mata karena penagihan kepada Mitra belum berhasil.</p>
                            <p class="mt-1">(3) Refund dilakukan langsung oleh pihak yang berkewajiban kepada penerima yang sah, dengan mengutamakan rekening asal pembayaran setelah verifikasi. Perubahan tujuan refund harus diperiksa langsung dan disetujui oleh petugas berwenang; Mitra tidak boleh menerima atau menguasainya.</p>
                            <p class="mt-1">(4) Biaya pembatalan atau pengembalian mengacu pada perjanjian layanan, biaya yang dapat dipertanggungjawabkan, dan ketentuan hukum. Mitra dilarang menetapkan sendiri pemotongan, pernyataan tidak dapat dikembalikan, perpindahan paket, atau pengalihan jamaah ke penyelenggara lain.</p>
                            <p class="mt-1">(5) Komunikasi resmi mengenai sengketa atau insiden dilakukan oleh pejabat yang ditunjuk Perusahaan dengan informasi yang telah diverifikasi dan tetap menjaga privasi. Ketentuan ini tidak melarang laporan yang sah kepada pihak berwenang.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 12</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">AUDIT DAN TINDAKAN PENGAMANAN</strong>
                            <p class="mt-1">(1) Perusahaan berhak memeriksa kegiatan, materi promosi, pendaftaran, dokumen, dan transaksi yang berkaitan dengan kerja sama melalui cara yang sah. Mitra wajib memberikan bukti yang relevan dan tidak boleh mengubah atau menghilangkannya.</p>
                            <p class="mt-1">(2) Audit dibatasi pada tujuan yang sah serta data yang diperlukan. Hak audit tidak memberikan kewenangan mengambil alih akun pribadi, meminta PIN atau kata sandi bank, menyita barang, atau mengakses informasi yang tidak terkait secara tidak sah.</p>
                            <p class="mt-1">(3) Jika terdapat indikasi berdasar atas penyalahgunaan dana, pemalsuan, penyalahgunaan identitas, pengaduan serius, atau kebocoran data, Perusahaan dapat segera membekukan akses dan aktivitas pemasaran Mitra serta menghentikan pengeluaran atribut. Tindakan ini tidak harus menunggu teguran berulang atau putusan pidana.</p>
                            <p class="mt-1">(4) Pembekuan diberitahukan secara tercatat beserta alasan pokok sejauh tidak mengganggu pengamanan bukti. Mitra diberi kesempatan klarifikasi dalam 2 Hari Kerja sejak pemberitahuan diterima atau waktu lain yang wajar bila ada alasan yang dapat dibuktikan.</p>
                            <p class="mt-1">(5) Perusahaan menilai bukti secara wajar, mencatat hasil pemeriksaan, dan memberitahukan apakah akses dipulihkan, dibatasi, atau kerja sama diakhiri. Penahanan komisi mengikuti Pasal 9, tidak otomatis berlaku atas seluruh hak Mitra.</p>
                            <p class="mt-1">(6) Pengamanan internal tidak memberikan hak memblokir rekening bank milik Mitra atau mengambil asetnya tanpa dasar hukum. Permintaan tindakan terhadap bank, aparat, atau pihak lain dilakukan melalui prosedur yang berlaku.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 13</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PELANGGARAN SANKSI DAN GANTI RUGI</strong>
                            <p class="mt-1">(1) Pelanggaran dapat dikenai teguran, kewajiban koreksi, pembatasan akses, pembekuan, pengakhiran, atau tuntutan pemulihan sesuai sifat, bukti, dan akibatnya. Pelanggaran berat meliputi penerimaan atau penyalahgunaan dana, pemalsuan, pinjam izin, penyembunyian transaksi, penyalahgunaan data, serta penggunaan identitas tanpa hak.</p>
                            <p class="mt-1">(2) Mitra wajib mengembalikan dana atau barang yang dikuasai tanpa hak dan mengganti kerugian nyata, langsung, wajar, serta terbukti mempunyai hubungan sebab akibat dengan kesalahan atau pelanggarannya. Kerugian dapat mencakup dana yang disalahgunakan dan biaya penanganan atau bantuan hukum yang dapat dipertanggungjawabkan.</p>
                            <p class="mt-1">(3) Perusahaan menyampaikan rincian kerugian dan dasar tuntutan serta memberi kesempatan tanggapan. Nilai tuntutan tidak otomatis ditetapkan sepihak sebagai utang yang boleh dipotong. Jika tidak disepakati, jumlah dan pelaksanaannya diselesaikan menurut mekanisme sengketa.</p>
                            <p class="mt-1">(4) Untuk penipuan atau penyalahgunaan dana oleh Mitra, nilai komisi bukan batas maksimum ganti rugi. Kerugian yang sama tidak boleh ditagih dua kali. Pemulihan dari Mitra, pihak lain, atau asuransi diperhitungkan secara benar.</p>
                            <p class="mt-1">(5) Bagian kerugian yang berasal dari kesalahan Perusahaan atau kewajiban Perusahaan menurut hukum tidak otomatis dialihkan kepada Mitra. PKS ini tidak membebaskan Perusahaan dari tanggung jawab kepada jamaah, regulator, atau pihak lain yang ditentukan hukum.</p>
                            <p class="mt-1">(6) Para Pihak dapat menempuh laporan kepada aparat atau regulator berdasarkan bukti dan unsur yang berlaku. Pengembalian uang, perdamaian, atau pemutusan kerja sama tidak dengan sendirinya menghapus pertanggungjawaban pidana menurut hukum.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 14</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">MASA BERLAKU DAN PENGAKHIRAN</strong>
                            <p class="mt-1">(1) Perjanjian berlaku selama 12 bulan sejak tanggal efektif ketika persetujuan kedua pihak yang berwenang telah tercatat. Perpanjangan dilakukan berdasarkan evaluasi dan persetujuan baru. Tanggal mulai dan berakhir dicantumkan dalam Ringkasan Kemitraan.</p>
                            <p class="mt-1">(2) Masing-masing pihak dapat mengakhiri kerja sama biasa dengan pemberitahuan tertulis paling lambat 30 hari sebelumnya. Pengakhiran tidak boleh digunakan untuk mengambil komisi yang telah sah menjadi hak Mitra.</p>
                            <p class="mt-1">(3) Pelanggaran berat yang telah diperiksa dapat menjadi dasar pengakhiran melalui pemberitahuan tertulis tanpa menunggu masa pemberitahuan biasa, dengan tetap memberi kesempatan klarifikasi. Pembekuan pengamanan dapat dilakukan lebih dahulu menurut Pasal 12.</p>
                            <p class="mt-1">(4) Jika perizinan yang diperlukan tidak lagi berlaku atau kegiatan menjadi dilarang oleh peraturan, pemasaran terkait dihentikan segera. Para Pihak menyelesaikan dampaknya terhadap transaksi berjalan dan hak jamaah sesuai hukum.</p>
                            <p class="mt-1">(5) Sejak berakhir atau dicabut kewenangannya, Mitra wajib menghentikan penawaran dan penggunaan identitas. Paling lambat 3 Hari Kerja, Mitra menyerahkan daftar transaksi berjalan, dokumen, data dan barang milik Perusahaan serta membantu transisi yang aman; penanganan mendesak dilakukan segera.</p>
                            <p class="mt-1">(6) Perusahaan mencabut akses, memperbarui status Mitra, dan memberi pemberitahuan faktual kepada jamaah atau pihak terkait yang sebelumnya berhubungan dengan Mitra. Pencabutan yang hanya diberitahukan kepada Mitra tidak dijadikan alasan mengabaikan perlindungan pihak ketiga yang beritikad baik.</p>
                            <p class="mt-1">(7) Pengakhiran tidak menghapus komisi yang telah sah timbul, kewajiban pemulihan, kerahasiaan, pelindungan data, penyelesaian sengketa, atau kewajiban terhadap transaksi dan jamaah yang masih berjalan.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 15</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">KEADAAN KAHAR</strong>
                            <p class="mt-1">(1) Keadaan kahar adalah peristiwa di luar kendali wajar pihak terdampak yang tidak dapat dicegah secara patut dan secara langsung menghalangi kewajibannya, dengan memperhatikan hukum serta perjanjian layanan yang relevan.</p>
                            <p class="mt-1">(2) Pihak terdampak memberi pemberitahuan dan bukti paling lambat 3 Hari Kerja sejak dapat berkomunikasi secara wajar, menjelaskan dampak dan perkiraan penanganan, serta melakukan mitigasi. Keterlambatan dinilai berdasarkan keadaan yang dapat dibuktikan.</p>
                            <p class="mt-1">(3) Kekurangan dana, pemakaian uang jamaah, pemalsuan, perselisihan komisi, atau kegagalan pengendalian internal yang sepatutnya dapat dicegah tidak menjadi alasan keadaan kahar.</p>
                            <p class="mt-1">(4) Keadaan kahar tidak otomatis menghapus kewajiban menjaga dana dan bukti, pelindungan data, pelaporan, pengembalian yang tetap diwajibkan hukum, atau hak jamaah. Solusi atas perubahan layanan, penjadwalan, dan refund dibahas sesuai ketentuan yang berlaku.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 16</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PEMBERITAHUAN SOP DAN PERUBAHAN PERJANJIAN</strong>
                            <p class="mt-1">(1) Pemberitahuan dilakukan melalui Kanal Resmi kepada kontak terverifikasi yang dicatat dalam Ringkasan Kemitraan. Pengirim menyimpan bukti pengiriman dan menindaklanjuti kegagalan penyampaian. Perubahan kontak wajib diverifikasi; informasi dari pihak tidak berwenang tidak mengubah perjanjian.</p>
                            <p class="mt-1">(2) PKS ini, Ringkasan Kemitraan, Lampiran 1 dan 2, serta Konfirmasi Komisi yang disepakati menjadi satu kesatuan. Dokumen tambahan harus dapat diidentifikasi, diakses, dan disimpan dalam versi yang telah disetujui.</p>
                            <p class="mt-1">(3) SOP teknis dapat diperbarui untuk kepatuhan dan pengamanan dengan nomor versi serta pemberitahuan. Perubahan yang mengurangi hak komisi yang telah timbul, memperluas kewenangan, menambah beban pokok, atau mengubah ketentuan material memerlukan persetujuan Para Pihak dan tidak berlaku surut.</p>
                            <p class="mt-1">(4) Hukum yang bersifat wajib berlaku terlebih dahulu. Untuk hal material, naskah PKS berlaku kecuali diubah dengan addendum yang secara tegas disepakati. Konfirmasi Komisi mengatur rincian komersial dalam ruang yang diperbolehkan Pasal 9; SOP atau chat tidak boleh mengurangi larangan menerima dana dalam Pasal 6.</p>
                            <p class="mt-1">(5) Tidak adanya jawaban tidak dianggap sebagai persetujuan perubahan material. Pengamanan mendesak yang tidak menghapus hak yang telah timbul dapat diberlakukan segera dengan pemberitahuan tercatat.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 17</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">HUKUM DAN PENYELESAIAN PERSELISIHAN</strong>
                            <p class="mt-1">(1) Perjanjian dibuat dalam Bahasa Indonesia dan tunduk pada hukum Republik Indonesia, termasuk ketentuan perikatan, penyelenggaraan haji dan umrah, perlindungan konsumen, pelindungan data, serta transaksi dan tanda tangan elektronik yang berlaku.</p>
                            <p class="mt-1">(2) Perselisihan Para Pihak diupayakan selesai melalui musyawarah paling lama 14 Hari Kerja sejak pemberitahuan sengketa. Para Pihak menyampaikan pokok persoalan serta bukti yang relevan dengan itikad baik.</p>
                            <p class="mt-1">(3) Jika musyawarah gagal, Para Pihak memilih Pengadilan Negeri Bogor sepanjang sesuai kewenangan menurut hukum. Jika kompetensi menurut hukum menentukan forum lain, penyelesaian mengikuti forum yang berwenang.</p>
                            <p class="mt-1">(4) Ketentuan ini tidak menunda tindakan pengamanan yang sah, membatasi laporan kepada aparat atau regulator, atau mengikat hak dan pilihan forum jamaah yang bukan pihak dalam PKS. Hak yang tidak disengketakan tetap dilaksanakan.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 18</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PERSETUJUAN ELEKTRONIK DAN PEMBUKTIAN</strong>
                            <p class="mt-1">(1) Sebelum persetujuan, Para Pihak memperoleh kesempatan memeriksa naskah utuh, Ringkasan Kemitraan, lampiran dan ketentuan komisi yang relevan. Naskah dan versi yang disetujui harus dapat diunduh atau diterima sebagai salinan yang utuh.</p>
                            <p class="mt-1">(2) Persetujuan dilakukan oleh penandatangan sendiri dengan mekanisme yang dapat mengidentifikasi dirinya, menunjukkan kehendak untuk terikat, mencatat waktu, dan menjaga integritas dokumen sesuai ketentuan. Tiga pernyataan pada akhir naskah ditampilkan sebagai centang terpisah yang awalnya kosong dan dipilih aktif oleh Mitra.</p>
                            <p class="mt-1">(3) Persetujuan Perusahaan diberikan oleh direktur atau pihak yang berwenang melalui mekanisme yang sah. Label terverifikasi, gambar cap, atau nama otomatis pada PDF tidak dengan sendirinya menggantikan bukti kewenangan dan persetujuan.</p>
                            <p class="mt-1">(4) Sistem menyimpan rekam persetujuan, identitas, hasil verifikasi, waktu beserta zona waktunya, versi naskah serta lampiran, dan bukti integritas yang memadai. Penggunaan OTP bukan pernyataan bahwa tanda tangan otomatis tersertifikasi. Metode yang dipilih tetap harus memenuhi ketentuan hukum.</p>
                            <p class="mt-1">(5) Salinan final dan bukti persetujuan tersedia bagi Para Pihak melalui kanal aman. Perubahan terhadap isi yang telah disetujui harus dapat ditelusuri; perubahan material memerlukan persetujuan baru dan tidak boleh mengganti diam-diam naskah sebelumnya.</p>
                            <p class="mt-1">(6) Para Pihak menjaga keamanan akun dan sarana autentikasi, tidak memberikan persetujuan melalui orang yang tidak berwenang, serta segera melaporkan dugaan penyalahgunaan. Rekam elektronik digunakan sebagai bukti sesuai hukum dan bukan bukti yang tidak dapat dibantah.</p>
                        </div>

                        <div class="pt-2">
                            <strong class="text-slate-900 block font-bold text-center text-xs">PASAL 19</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-1">PENUTUP DAN KETENTUAN PERALIHAN</strong>
                            <p class="mt-1">(1) Para Pihak menyatakan telah memahami hak, kewajiban, batas kewenangan, serta akibat kerja sama dan mengikatkan diri secara bebas tanpa paksaan. Lampiran 1 dan 2 merupakan bagian yang tidak terpisahkan dari Perjanjian.</p>
                            <p class="mt-1">(2) Jika suatu ketentuan dinyatakan tidak berlaku, bagian lain tetap berlaku sepanjang dapat dilaksanakan secara sah. Para Pihak menyesuaikan bagian yang bermasalah tanpa menghapus hak yang dilindungi hukum.</p>
                            <p class="mt-1">(3) Bagi Mitra yang sebelumnya telah mempunyai PKS, naskah ini menggantikan pengaturan kerja sama untuk kegiatan setelah tanggal efektif ketika disetujui kedua pihak. Hak, kewajiban, dan komisi atas transaksi sebelumnya tetap mengikuti dasar yang berlaku saat timbul, kecuali diubah secara sah dengan persetujuan yang jelas.</p>
                            <p class="mt-1">(4) Kewenangan menerima dana yang pernah diberikan kepada Mitra ditarik untuk kegiatan setelah tanggal efektif naskah ini. Perusahaan menyampaikan pencabutan kepada pihak terkait sesuai hukum. Bukti perjanjian serta transaksi lama dipertahankan dan tidak diberi tanggal mundur.</p>
                        </div>

                        <!-- LAMPIRAN 1 -->
                        <div class="pt-3 border-t border-slate-300">
                            <strong class="text-slate-900 block font-bold text-center text-xs">LAMPIRAN 1</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-2">RINGKASAN KEMITRAAN DAN KETENTUAN KOMISI</strong>
                            <p class="mt-1">1. Ringkasan Kemitraan dan Konfirmasi Komisi merupakan catatan kesepakatan yang dapat ditampilkan di aplikasi atau disampaikan melalui Kanal Resmi. Catatan tersebut harus dapat diunduh atau disimpan bersama PKS dalam versi yang disetujui.</p>
                            <p class="mt-1">2. Ringkasan Kemitraan memuat nomor perjanjian, versi naskah, nama dan jabatan penandatangan Perusahaan beserta dasar kewenangannya, nama serta NIK Mitra, alamat lengkap, nomor kontak terverifikasi, alamat surat elektronik bila digunakan, Kanal Resmi Pusat, tanggal efektif, dan masa berlaku. Identitas harus cocok dengan bukti verifikasi yang sah.</p>
                            <p class="mt-1">3. Informasi legalitas produk Perusahaan dapat diperiksa melalui Kanal Resmi dan sumber perizinan yang berwenang. Nomor izin atau status yang belum diverifikasi tidak boleh ditampilkan seolah-olah sah atau berlaku.</p>
                            <p class="mt-1">4. Konfirmasi Komisi setiap produk memuat nama atau kode produk, periode penawaran, nominal atau rumus komisi, pemicu hak, syarat penerimaan dana dan dokumen, tenggat pencairan, pajak, ketentuan pembatalan, serta cara penanganan referral ganda. Tarif dan syarat harus tampil atau disampaikan sebelum Mitra menyetujuinya dan memasarkan produk.</p>
                            <p class="mt-1">5. Persetujuan Konfirmasi Komisi dicatat dengan identitas, tanggal, versi, dan isi yang disetujui. Tidak ada tarif yang dianggap disetujui hanya karena tersimpan di halaman yang dapat diubah sepihak. Perusahaan menyimpan riwayat untuk referral yang telah diterima.</p>
                            <p class="mt-1">6. Tagihan Resmi kepada jamaah memuat ID pemesanan, nama jamaah, produk, total harga, setoran yang ditagihkan, sisa kewajiban, jatuh tempo, bank dan nomor tujuan, nama pemilik tujuan, serta peruntukan setoran. Dana umrah dan setoran haji khusus mengikuti Pasal 6; perubahan tujuan diverifikasi Pusat dan diberitahukan langsung kepada jamaah.</p>
                            <p class="mt-1">7. Tanpa identitas dan persetujuan Para Pihak yang dapat dibuktikan, dokumen tidak boleh diberi status telah disepakati. Tanpa kejelasan komisi produk, Perusahaan tidak boleh meminta Mitra memasarkan produk tersebut dengan janji komisi yang belum ditetapkan.</p>
                        </div>

                        <!-- LAMPIRAN 2 -->
                        <div class="pt-3 border-t border-slate-300">
                            <strong class="text-slate-900 block font-bold text-center text-xs">LAMPIRAN 2</strong>
                            <strong class="text-slate-900 block font-bold text-center text-[11px] mb-2">KEWENANGAN DAN SOP MINIMUM</strong>
                            
                            <strong class="text-slate-900 block font-bold text-[11px] mb-1.5">Matriks kewenangan</strong>
                            <div class="overflow-x-auto my-2">
                                <table class="w-full border-collapse border border-slate-300 text-[10.5px]">
                                    <thead>
                                        <tr class="bg-slate-800 text-white">
                                            <th class="p-1.5 border border-slate-300 text-left font-bold w-[22%]">Kegiatan</th>
                                            <th class="p-1.5 border border-slate-300 text-left font-bold w-[39%]">Mitra</th>
                                            <th class="p-1.5 border border-slate-300 text-left font-bold w-[39%]">Perusahaan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Promosi dan pengenalan</td>
                                            <td class="p-1.5 border border-slate-300">Menyampaikan materi resmi dan menghubungkan calon jamaah ke Pusat.</td>
                                            <td class="p-1.5 border border-slate-300">Menetapkan produk, harga, syarat, serta materi yang berlaku.</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Pendaftaran dan kontrak</td>
                                            <td class="p-1.5 border border-slate-300">Membantu pengisian data yang benar sesuai instruksi.</td>
                                            <td class="p-1.5 border border-slate-300">Memverifikasi identitas dan kontak, menerbitkan kontrak serta ID pemesanan, dan melakukan pencatatan yang diwajibkan.</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Pembayaran</td>
                                            <td class="p-1.5 border border-slate-300">Menjelaskan cara membayar langsung tanpa menguasai dana atau kode rahasia.</td>
                                            <td class="p-1.5 border border-slate-300">Menerbitkan tagihan, memastikan tujuan resmi, mencocokkan setoran, serta mengirim konfirmasi saldo kepada jamaah.</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Koper seragam dan atribut</td>
                                            <td class="p-1.5 border border-slate-300">Hanya membantu distribusi per nama bila ditugaskan dan dicatat.</td>
                                            <td class="p-1.5 border border-slate-300">Mengeluarkan barang setelah syarat Pasal 8 terpenuhi, mencatat serah terima, dan mengonfirmasi langsung kepada penerima.</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Status keberangkatan</td>
                                            <td class="p-1.5 border border-slate-300">Meneruskan informasi resmi tanpa menjanjikan kepastian sendiri.</td>
                                            <td class="p-1.5 border border-slate-300">Memeriksa dana, dokumen, tiket, visa, asuransi dan layanan; menetapkan serta mengomunikasikan status keberangkatan.</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Perubahan dan refund</td>
                                            <td class="p-1.5 border border-slate-300">Meneruskan permintaan serta bukti; tidak mengambil keputusan atau menerima uang.</td>
                                            <td class="p-1.5 border border-slate-300">Memverifikasi permintaan langsung, menetapkan penanganan yang sah, dan membayar penerima yang berhak.</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 border border-slate-300 font-semibold bg-slate-50">Insiden dan pengakhiran</td>
                                            <td class="p-1.5 border border-slate-300">Melapor segera, menjaga bukti, menghentikan kegiatan terlarang dan membantu serah terima.</td>
                                            <td class="p-1.5 border border-slate-300">Mengamankan akses, memeriksa, menangani jamaah, memperbarui status, dan memberi pemberitahuan faktual yang diperlukan.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <strong class="text-slate-900 block font-bold text-[11px] mt-2 mb-1">Alur minimum pelaksanaan</strong>
                            <p class="mt-1">1. Sebelum pemasaran aktif, Pusat menyelesaikan verifikasi identitas, persetujuan PKS, penjelasan batas kewenangan, serta Konfirmasi Komisi. Akun dan sarana promosi terkait dicatat.</p>
                            <p class="mt-1">2. Sebelum transaksi, Pusat sudah memiliki data serta kontak terverifikasi jamaah atau wakil keluarga yang sah. Kontrak dan tagihan disampaikan langsung; komunikasi tidak hanya melalui Mitra.</p>
                            <p class="mt-1">3. Setiap setoran diperiksa dengan bank. Status pembayaran hanya diubah petugas berwenang dan dicatat. Konfirmasi penerimaan beserta sisa tagihan dikirim langsung sesuai Pasal 6.</p>
                            <p class="mt-1">4. Pengeluaran perlengkapan dicocokkan dengan ID, pelunasan, dan pemeriksaan alokasi. Tidak ada stok bebas pada Mitra. Daftar penerima barang dicocokkan dengan seluruh pendaftar, termasuk yang belum masuk manifest.</p>
                            <p class="mt-1">5. Pusat menghubungi jamaah menjelang jatuh tempo dan memeriksa kesesuaian data serta layanan pada H-14 dan H-7. Pada H-3 dan sebelum berkumpul, status kesiapan diverifikasi serta disampaikan langsung. Untuk pendaftaran yang lebih dekat, seluruh kontrol yang relevan diselesaikan sebelum keberangkatan. Jadwal ini adalah kontrol internal dan tidak menggantikan tenggat hukum.</p>
                            <p class="mt-1">6. Pemeriksaan mencakup seluruh pendaftar dan penerima perlengkapan, bukan hanya manifest akhir. Jika jamaah menyatakan telah membayar kepada Mitra, status langsung ditandai sebagai insiden untuk ditelusuri sebelum hari keberangkatan.</p>
                            <p class="mt-1">7. Atas insiden, Pusat mengamankan akses serta bukti dan menghubungi jamaah terkait secara langsung. Dana diterima Perusahaan, dana yang disengketakan, dan janji Mitra dicocokkan berdasarkan bukti. Pemenuhan kewajiban yang telah jelas tidak menunggu hasil penagihan kepada Mitra.</p>
                            <p class="mt-1">8. Penyelesaian tidak menggunakan dana jamaah lain untuk menutup kekurangan. Kerugian, pengembalian, komisi dan biaya penanganan dicatat terpisah. Laporan kepada regulator atau aparat serta pemberitahuan data dilakukan sesuai ketentuan yang berlaku.</p>
                            <p class="mt-1">9. Saat kerja sama berakhir, akses dan status diperbarui, atribut serta data diserahterimakan, dan jamaah terkait menerima pemberitahuan faktual. Catatan tidak boleh dihapus untuk menyembunyikan peristiwa.</p>
                        </div>

                        <!-- PENUTUP & PENGESAHAN -->
                        <div class="pt-3 border-t border-slate-300">
                            <p class="text-[11.5px] text-slate-800">Nomor perjanjian, identitas penandatangan, dasar kewenangan, tanggal dan waktu, metode verifikasi, serta rekam persetujuan kedua pihak tercatat dalam Ringkasan Persetujuan yang disatukan dengan naskah final. Perjanjian efektif setelah persetujuan kedua pihak yang berwenang tercatat, sesuai Pasal 14 dan Pasal 18.</p>
                            <p class="text-[11.5px] text-slate-800 mt-2">Naskah, Ringkasan Kemitraan dan Persetujuan, kedua lampiran, serta Konfirmasi Komisi yang disepakati disimpan sebagai satu paket dokumen yang dapat ditelusuri. Salinan diberikan kepada Para Pihak melalui kanal aman.</p>
                            
                            <div class="mt-3 pt-2">
                                <strong class="text-slate-900 block font-bold text-xs uppercase mb-2">Pengesahan Para Pihak</strong>
                                <div class="grid grid-cols-2 gap-3 text-[11px]">
                                    <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50">
                                        <strong class="block text-slate-900">PIHAK PERTAMA</strong>
                                        <span class="block font-bold text-[#6b008a]">PT AMANAH TANGGUH MANDIRI</span>
                                        <span class="block text-slate-600 mt-1">M. Erwin Saputra selaku Direktur Utama atau pejabat yang berwenang sebagaimana Ringkasan Kemitraan.</span>
                                    </div>
                                    <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50">
                                        <strong class="block text-slate-900">PIHAK KEDUA</strong>
                                        <span class="block font-bold text-slate-900">MITRA AMANAH</span>
                                        <span class="block text-slate-600 mt-1">Nama dan identitas Mitra sesuai Ringkasan Kemitraan yang telah diverifikasi serta disetujui.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Persetujuan Elektronik Sesuai Pasal 18 Ayat (2) -->
                    <form method="POST" action="<?= base_url('/agreement') ?>" class="mt-4">
                        <?= csrf_field() ?>

                        <!-- 3 Pernyataan Centang Terpisah (Pasal 18 Ayat 2) -->
                        <div class="space-y-2.5 pt-1 mb-4">
                            <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer select-none">
                                <input type="checkbox" name="agree_klausul" value="1" required class="mt-0.5 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-700 leading-snug">
                                    <strong>Pernyataan 1:</strong> Saya telah membaca, memahami, dan menyetujui seluruh klausul Perjanjian Kerja Sama Mitra Amanah yang terdiri dari Pasal 1 sampai dengan Pasal 19 beserta Lampiran 1 dan Lampiran 2 secara utuh tanpa paksaan.
                                </span>
                            </label>

                            <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer select-none">
                                <input type="checkbox" name="agree_larangan_dana" value="1" required class="mt-0.5 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-700 leading-snug">
                                    <strong>Pernyataan 2:</strong> Saya memahami dan menyetujui larangan menerima, menampung, atau mengalihkan dana jamaah dalam bentuk apa pun, serta wajib mengarahkan seluruh pembayaran langsung ke Rekening Resmi PT Amanah Tangguh Mandiri sesuai Tagihan Resmi.
                                </span>
                            </label>

                            <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer select-none">
                                <input type="checkbox" name="agree" value="1" required class="mt-0.5 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-700 leading-snug">
                                    <strong>Pernyataan 3:</strong> Saya menyetujui bahwa persetujuan elektronik ini sah, berkekuatan hukum, dan mengikat diri saya secara hukum dengan PT Amanah Tangguh Mandiri sejak tanggal disetujui.
                                </span>
                            </label>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="auth-submit-wrap">
                            <button type="submit" class="btn-brand-primary">
                                <span>Setuju dan Lanjutkan</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Kartu -->
                <div class="auth-card-footer">
                    &copy; <?= date('Y') ?> Amanah Safar. Hak cipta dilindungi.
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Page Footer (Persis Referensi: Alur Pendaftaran • Persyaratan • Bantuan & Kontak) -->
    <footer class="auth-bottom-footer">
        <a href="<?= base_url('/') ?>">Alur Pendaftaran</a>
        <span>•</span>
        <a href="<?= base_url('/register') ?>">Persyaratan</a>
        <span>•</span>
        <a href="https://wa.me/6281234567890" target="_blank">Bantuan & Kontak</a>
    </footer>
</div>

<?= $this->endSection() ?>
