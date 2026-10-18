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

    /* Styling Pernyataan Persetujuan Checkbox Sederhana (Tanpa Card/Border/Background) */
    .agreement-check-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        cursor: pointer;
        user-select: none;
    }
    .agreement-check-input {
        width: 1.125rem;
        height: 1.125rem;
        margin-top: 0.125rem;
        flex-shrink: 0;
        border-radius: 0.25rem;
        border: 1.5px solid #cbd5e1;
        accent-color: #6b008a;
        cursor: pointer;
    }
    .agreement-check-text {
        font-size: 0.8125rem;
        line-height: 1.55;
        color: #334155;
    }
    .agreement-check-text .required-mark {
        color: #f43f5e;
        font-weight: 700;
        margin-right: 0.25rem;
    }
    .agreement-check-text .check-title {
        font-weight: 700;
        color: #0f172a;
    }

    /* Modal Action Button Styles */
    .btn-pdf-primary {
        background-color: #6b008a;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.8125rem;
        padding: 0.625rem 1rem;
        border-radius: 0.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px -2px rgba(107, 0, 138, 0.35);
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-pdf-primary:hover {
        background-color: #55006e;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px -2px rgba(107, 0, 138, 0.45);
        color: #ffffff !important;
    }
    .btn-pdf-secondary {
        background-color: #ffffff;
        color: #475569 !important;
        font-weight: 600;
        font-size: 0.8125rem;
        padding: 0.625rem 0.875rem;
        border-radius: 0.75rem;
        border: 1.5px solid #cbd5e1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-pdf-secondary:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a !important;
    }

    /* Modal PDF Viewer Styles */
    .pks-pdf-modal {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 0.5rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .pks-pdf-modal.is-active {
        opacity: 1;
        pointer-events: auto;
    }
    .pks-pdf-modal-container {
        width: 100%;
        max-width: 1200px;
        height: 96vh;
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        transform: scale(0.98);
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .pks-pdf-modal.is-active .pks-pdf-modal-container {
        transform: scale(1);
    }
    .pks-pdf-modal-header {
        padding: 0.625rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background-color: #ffffff;
        flex-shrink: 0;
    }
    .pks-pdf-modal-body {
        flex: 1 1 0%;
        min-height: 0;
        position: relative;
        background-color: #f8fafc;
    }
    .pks-pdf-modal-footer {
        padding: 0.625rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-shrink: 0;
    }
    @media (max-width: 640px) {
        .pks-pdf-modal {
            padding: 0;
        }
        .pks-pdf-modal-container {
            height: 100vh;
            border-radius: 0;
            border: none;
        }
        .pks-pdf-modal-header {
            padding: 0.625rem 1rem;
        }
        .pks-pdf-modal-footer {
            padding: 0.625rem 1rem;
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
                    <!-- Logo Resmi Amanah Safar Putih -->
                    <div class="mb-8" style="margin-bottom: 2rem;">
                        <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Amanah Safar" class="block h-11 sm:h-12 w-auto object-contain drop-shadow-sm">
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
                    $pihakKeduaNama = !empty($user['nama_lengkap']) ? strtoupper(trim($user['nama_lengkap'])) : 'Belum diisi';
                    $pihakKeduaNik = !empty($verification['nik']) ? trim($verification['nik']) : 'Belum diisi';
                    $pihakKeduaAlamat = !empty($user['domisili']) ? strtoupper(trim($user['domisili'])) : 'Belum diisi';
                    $pihakKeduaHp = !empty($user['no_wa']) ? trim($user['no_wa']) : 'Belum diisi';
                    ?>

                    <!-- Ringkasan Kemitraan (Para Pihak) -->
                    <div class="mt-4 bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Ringkasan Kemitraan (Para Pihak yang Bersepakat):</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700">
                            <div class="border-l-2 border-[#6b008a] pl-2.5">
                                <span class="font-bold text-slate-900 block text-[11px] mb-1">PIHAK PERTAMA</span>
                                <span class="text-xs font-semibold block text-slate-800">PT AMANAH TANGGUH MANDIRI (AMANAH SAFAR)</span>
                                <span class="text-[11px] text-slate-500">M. Erwin Saputra &bull; Direktur Utama</span>
                            </div>
                            <div class="border-l-2 border-amber-500 pl-2.5">
                                <span class="font-bold text-slate-900 block text-[11px] mb-1">PIHAK KEDUA (MITRA AMANAH)</span>
                                <div class="text-xs text-slate-700 space-y-0.5">
                                    <div>Nama: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaNama) ?></strong></div>
                                    <div>NIK: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaNik) ?></strong></div>
                                    <div>Alamat: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaAlamat) ?></strong></div>
                                    <div>Kontak: <strong class="text-slate-900 font-bold"><?= esc($pihakKeduaHp) ?></strong></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Tindakan Tunggal Pembuka Dokumen PKS PDF Lengkap -->
                    <div class="mt-4">
                        <button type="button" id="btnOpenPdfModal" class="btn-brand-primary">
                            <i class="fas fa-file-pdf"></i>
                            <span>Buka PKS Lengkap</span>
                        </button>
                    </div>

                    <!-- Form Persetujuan Elektronik Sesuai Pasal 18 Ayat (2) -->
                    <form method="POST" action="<?= base_url('/agreement') ?>" class="mt-4" id="agreementForm">
                        <?= csrf_field() ?>

                        <div id="agreementValidationAlert" class="hidden mb-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5">
                            <i class="fas fa-circle-exclamation text-rose-500 text-sm shrink-0"></i>
                            <span id="agreementValidationMessage">Seluruh pernyataan persetujuan (Pernyataan 1, 2, dan 3) wajib disetujui untuk melanjutkan.</span>
                        </div>

                        <!-- 3 Pernyataan Centang Terpisah (Pasal 18 Ayat 2) -->
                        <div class="space-y-4 pt-1 mb-5">
                            <label class="agreement-check-item">
                                <input type="checkbox" name="agree_klausul" id="agreeKlausul" value="1" required class="mt-0.5 agreement-check-input">
                                <span class="agreement-check-text">
                                    <span class="required-mark text-rose-500 font-bold mr-1">*</span><strong class="check-title">Identitas dan persetujuan PKS.</strong> Saya menyatakan identitas saya benar dan saya cakap membuat perjanjian. Saya telah membaca, memahami, dan menyetujui PKS Nomor <?= esc($pksNumber) ?>, versi <?= esc($pksVersion) ?>, beserta seluruh lampirannya, termasuk komisi dan SOP, serta secara sadar tanpa paksaan menyetujui penandatanganannya secara elektronik.
                                </span>
                            </label>

                            <label class="agreement-check-item">
                                <input type="checkbox" name="agree_larangan_dana" id="agreeLaranganDana" value="1" required class="mt-0.5 agreement-check-input">
                                <span class="agreement-check-text">
                                    <span class="required-mark text-rose-500 font-bold mr-1">*</span><strong class="check-title">Larangan menerima uang jamaah.</strong> Saya tidak akan menerima, menampung, menggunakan, atau mengalihkan dana jamaah dalam bentuk apa pun, termasuk melalui pihak lain, meskipun jamaah meminta menitipkan uang. Seluruh pembayaran harus langsung menuju tujuan resmi yang diverifikasi pusat. Saya tidak boleh memotong komisi dari dana jamaah.
                                </span>
                            </label>

                            <label class="agreement-check-item">
                                <input type="checkbox" name="agree" id="agreeKewajiban" value="1" required class="mt-0.5 agreement-check-input">
                                <span class="agreement-check-text">
                                    <span class="required-mark text-rose-500 font-bold mr-1">*</span><strong class="check-title">Kewajiban dan pertanggungjawaban mitra.</strong> Saya wajib mematuhi batas kewenangan, ketentuan promosi, penggunaan merek, dokumen dan perlengkapan, komunikasi langsung pusat dengan jamaah, serta pelindungan data. Saya menyetujui pengawasan, pembekuan akses, sanksi, dan penggantian kerugian yang terbukti akibat pelanggaran saya sesuai PKS dan hukum.
                                </span>
                            </label>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="auth-submit-wrap">
                            <button type="submit" id="btnSubmitAgreement" class="btn-brand-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
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

    <!-- Modal PDF Viewer (Tampilan Dokumen Luas & Bersih) -->
    <div id="pksPdfModal" class="pks-pdf-modal" role="dialog" aria-modal="true" aria-labelledby="pksModalTitle">
        <div id="pksModalContainer" class="pks-pdf-modal-container">
            <!-- Modal Header Ringkas -->
            <div class="pks-pdf-modal-header">
                <h4 id="pksModalTitle" class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight">
                    Dokumen PKS Mitra Amanah
                </h4>
                <button type="button" id="btnClosePdfModal" class="btn-pdf-primary text-xs px-3.5 py-1.5" title="Tutup dan kembali ke persetujuan">
                    <i class="fas fa-xmark mr-1.5"></i>
                    <span>Tutup</span>
                </button>
            </div>

            <!-- Modal Body (Area Baca Luas Maksimal) -->
            <div class="pks-pdf-modal-body flex flex-col relative flex-1 min-h-0 bg-slate-50">
                <!-- Loading Indicator -->
                <div id="pksPdfLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 text-slate-600 z-10">
                    <i class="fas fa-circle-notch fa-spin text-2xl text-[#6b008a] mb-2.5"></i>
                    <span class="text-xs font-semibold">Memuat Dokumen PKS Resmi...</span>
                </div>

                <!-- Error State Fallback -->
                <div id="pksPdfErrorState" class="hidden absolute inset-0 flex flex-col items-center justify-center p-6 bg-slate-50 text-center z-20">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl mb-3">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <h5 class="text-sm font-bold text-slate-800 mb-1">Gagal Memuat Dokumen PKS</h5>
                    <p class="text-xs text-slate-500 max-w-md mb-4">Terjadi kendala saat memuat berkas PDF. Silakan periksa koneksi internet Anda atau coba muat ulang dokumen.</p>
                    <button type="button" id="btnRetryPdf" class="btn-pdf-primary text-xs px-4 py-2">
                        <i class="fas fa-rotate-right mr-1.5"></i>
                        <span>Coba Muat Ulang</span>
                    </button>
                </div>

                <!-- Embedded PDF Viewer -->
                <iframe id="pksPdfIframe" data-src="<?= base_url('/agreement/pdf') ?>" class="w-full h-full border-0 flex-1 relative z-0" title="Dokumen PKS PDF Penuh"></iframe>
            </div>

            <!-- Modal Footer (Kembali ke Persetujuan) -->
            <div class="pks-pdf-modal-footer">
                <button type="button" id="btnDoneReadingPdf" class="btn-pdf-primary text-xs px-4 py-2 ml-auto w-full sm:w-auto">
                    <span>Selesai Membaca & Kembali ke Persetujuan</span>
                    <i class="fas fa-arrow-down text-[10px] ml-1.5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom Page Footer (Persis Referensi: Alur Pendaftaran • Persyaratan • Bantuan & Kontak) -->
    <footer class="auth-bottom-footer">
        <a href="<?= base_url('/') ?>">Alur Pendaftaran</a>
        <span>•</span>
        <a href="<?= base_url('/register') ?>">Persyaratan</a>
        <span>•</span>
        <a href="https://wa.me/6281234567890" target="_blank">Bantuan & Kontak</a>
    </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('agreementForm');
    const chk1 = document.getElementById('agreeKlausul');
    const chk2 = document.getElementById('agreeLaranganDana');
    const chk3 = document.getElementById('agreeKewajiban');
    const btn = document.getElementById('btnSubmitAgreement');
    const alertBox = document.getElementById('agreementValidationAlert');

    // 1. Pada awal proses, ketiga checkbox dalam keadaan belum dicentang
    if (chk1) chk1.checked = false;
    if (chk2) chk2.checked = false;
    if (chk3) chk3.checked = false;

    function updateSubmitState() {
        const allChecked = chk1 && chk2 && chk3 && chk1.checked && chk2.checked && chk3.checked;

        [chk1, chk2, chk3].forEach(function(c) {
            if (c) {
                const item = c.closest('.agreement-check-item') || c.closest('.agreement-check-card');
                if (item) {
                    item.classList.toggle('is-checked', c.checked);
                }
            }
        });

        if (btn) {
            btn.disabled = !allChecked;
            if (allChecked) {
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                if (alertBox) alertBox.classList.add('hidden');
            } else {
                btn.style.opacity = '0.6';
                btn.style.cursor = 'not-allowed';
            }
        }
    }

    [chk1, chk2, chk3].forEach(function(chk) {
        if (chk) {
            chk.addEventListener('change', updateSubmitState);
        }
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            const allChecked = chk1 && chk2 && chk3 && chk1.checked && chk2.checked && chk3.checked;
            if (!allChecked) {
                e.preventDefault();
                if (alertBox) {
                    alertBox.classList.remove('hidden');
                }
                alert('Seluruh pernyataan persetujuan (Pernyataan 1, 2, dan 3) wajib disetujui untuk melanjutkan.');
                return false;
            }
        });
    }

    updateSubmitState();

    // Modal PDF Viewer Handler
    const pdfModal = document.getElementById('pksPdfModal');
    const btnOpenModal = document.getElementById('btnOpenPdfModal');
    const btnCloseModal = document.getElementById('btnClosePdfModal');
    const btnDoneReading = document.getElementById('btnDoneReadingPdf');
    const btnRetryPdf = document.getElementById('btnRetryPdf');
    const pdfIframe = document.getElementById('pksPdfIframe');
    const pdfLoading = document.getElementById('pksPdfLoading');
    const pdfErrorState = document.getElementById('pksPdfErrorState');

    const pdfUrl = pdfIframe ? (pdfIframe.getAttribute('data-src') || '<?= base_url('/agreement/pdf') ?>') : '';
    let pdfLoadTimer = null;

    function loadPdf() {
        if (!pdfIframe || !pdfUrl) return;
        if (pdfLoading) pdfLoading.classList.remove('hidden');
        if (pdfErrorState) pdfErrorState.classList.add('hidden');

        if (pdfLoadTimer) clearTimeout(pdfLoadTimer);

        // Fallback timeout: Jika dalam 12 detik dokumen belum selesai ter-render atau terjadi error
        pdfLoadTimer = setTimeout(function() {
            if (pdfLoading && !pdfLoading.classList.contains('hidden')) {
                pdfLoading.classList.add('hidden');
                if (pdfErrorState) pdfErrorState.classList.remove('hidden');
            }
        }, 12000);

        pdfIframe.onload = function() {
            if (pdfLoadTimer) clearTimeout(pdfLoadTimer);
            if (pdfLoading) pdfLoading.classList.add('hidden');
            if (pdfErrorState) pdfErrorState.classList.add('hidden');
        };

        pdfIframe.onerror = function() {
            if (pdfLoadTimer) clearTimeout(pdfLoadTimer);
            if (pdfLoading) pdfLoading.classList.add('hidden');
            if (pdfErrorState) pdfErrorState.classList.remove('hidden');
        };

        pdfIframe.src = pdfUrl;
    }

    function openPdfModal() {
        if (!pdfModal) return;
        if (pdfIframe && (!pdfIframe.src || pdfIframe.src === 'about:blank' || !pdfIframe.src.includes('/agreement/pdf'))) {
            loadPdf();
        }
        pdfModal.classList.add('is-active');
        document.body.style.overflow = 'hidden';
    }

    function closePdfModal() {
        if (!pdfModal) return;
        pdfModal.classList.remove('is-active');
        document.body.style.overflow = '';
    }

    if (btnOpenModal) {
        btnOpenModal.addEventListener('click', openPdfModal);
    }
    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', closePdfModal);
    }
    if (btnDoneReading) {
        btnDoneReading.addEventListener('click', function() {
            closePdfModal();
            const formSection = document.getElementById('agreementForm');
            if (formSection) {
                formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
    if (btnRetryPdf) {
        btnRetryPdf.addEventListener('click', function() {
            loadPdf();
        });
    }

    if (pdfModal) {
        pdfModal.addEventListener('click', function(e) {
            if (e.target === pdfModal) {
                closePdfModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && pdfModal && pdfModal.classList.contains('is-active')) {
            closePdfModal();
        }
    });
});
</script>
<?= $this->endSection() ?>
