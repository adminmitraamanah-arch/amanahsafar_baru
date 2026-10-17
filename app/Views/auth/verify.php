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
    .input-pill {
        width: 100%;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.625rem 0.875rem 0.625rem 2.65rem;
        font-size: 0.8125rem;
        color: #0f172a;
        transition: all 0.2s ease;
    }
    .input-pill:focus {
        background-color: #ffffff;
        border-color: #6b008a;
        outline: none;
        box-shadow: 0 0 0 3px rgba(107, 0, 138, 0.15);
    }
    .input-pill[readonly],
    .input-pill.input-locked {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        color: #334155 !important;
        cursor: not-allowed !important;
        user-select: none !important;
        box-shadow: none !important;
    }
    #namaBankManual {
        text-align: left;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    #namaBankManual:placeholder-shown {
        text-align: center !important;
    }
    #namaBankManual:focus {
        text-align: left !important;
    }
    #namaBankManual::placeholder,
    #namaBankManual::-webkit-input-placeholder,
    #namaBankManual::-moz-placeholder,
    #namaBankManual:-ms-input-placeholder {
        text-align: center !important;
    }
    #selectBank {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: none !important;
        padding-right: 2.25rem !important;
    }
    #selectBank::-ms-expand {
        display: none !important;
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
    .btn-brand-primary:disabled,
    .btn-brand-primary[disabled] {
        background-color: #a8a29e !important;
        color: #f5f5f4 !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
        opacity: 0.65 !important;
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
    .upload-dropzone {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        transition: all 0.2s ease;
    }
    .upload-dropzone:hover {
        background: #faf5ff;
        border-color: #6b008a;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="auth-glow-bg p-4 sm:p-6 lg:p-8 relative selection:bg-[#6b008a] selection:text-white">

    <!-- Top Navigation Header (Persis Referensi: Tombol Kiri Pill & Badge Status Kanan) -->
    <header class="w-full max-w-5xl mx-auto flex items-center justify-between py-2 z-10">
        <a href="<?= base_url('/logout') ?>" class="nav-pill-btn group">
            <i class="fas fa-arrow-left icon-arrow"></i>
            <span>Keluar Sesi Sementara</span>
        </a>
        <div class="nav-pill-badge">
            <span class="nav-badge-dot"></span>
            <span>Tahap 1 dari 2: Verifikasi Data</span>
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
                            <span class="text-[#ca82e6]">Langkah 1/2</span>
                        </div>
                        <div class="w-full bg-black/20 rounded-full h-1.5 flex overflow-hidden">
                            <div class="bg-gradient-to-r from-[#ca82e6] to-amber-400 h-1.5 rounded-full w-1/2"></div>
                        </div>
                        <div class="flex items-center justify-between mt-3 text-[11px] text-purple-muted">
                            <span class="font-bold text-amber-300 flex items-center gap-1.5">
                                <i class="fas fa-circle-dot text-[8px]"></i> 1. Verifikasi Data
                            </span>
                            <span class="opacity-60 flex items-center gap-1.5">
                                <i class="fas fa-circle text-[8px]"></i> 2. PKS Online
                            </span>
                        </div>
                    </div>

                    <!-- Headline Sambutan -->
                    <h1 class="text-2xl sm:text-[26px] font-extrabold leading-snug text-white mb-4">
                        Lengkapi Berkas Verifikasi Mitra.
                    </h1>
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 panel-feature-text" style="color: #dfb2f1 !important;">
                        Data identitas dan rekening diperlukan untuk validasi legalitas, penerbitan PKS, serta penyaluran ujroh bagi hasil.
                    </p>

                    <!-- Info Ringkas Akun Terdaftar -->
                    <div class="frosted-box rounded-2xl p-4 mb-6">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-[#ca82e6] mb-2.5">Akun Mitra Terdaftar:</div>
                        <div class="space-y-2">
                            <div class="font-bold text-white text-xs flex items-center gap-2.5">
                                <span class="w-4 h-4 inline-flex items-center justify-center shrink-0 text-[#ca82e6]">
                                    <i class="fas fa-user-circle text-xs"></i>
                                </span>
                                <span class="truncate"><?= esc($user['nama_lengkap'] ?? 'Mitra') ?></span>
                            </div>
                            <div class="text-[11px] flex items-center gap-2.5 panel-feature-text" style="color: #dfb2f1 !important;">
                                <span class="w-4 h-4 inline-flex items-center justify-center shrink-0 text-[#ca82e6]">
                                    <i class="fas fa-envelope text-xs"></i>
                                </span>
                                <span class="truncate"><?= esc($user['email'] ?? '-') ?></span>
                            </div>
                            <div class="text-[11px] flex items-center gap-2.5 panel-feature-text" style="color: #dfb2f1 !important;">
                                <span class="w-4 h-4 inline-flex items-center justify-center shrink-0 text-[#ca82e6]">
                                    <i class="fab fa-whatsapp text-xs"></i>
                                </span>
                                <span class="truncate"><?= esc($user['no_wa'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Poin Panduan Dokumen (Ikon Bulat) -->
                    <ul class="space-y-3.5 text-xs">
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <span class="text-xs font-medium panel-feature-text" style="color: #dfb2f1 !important;">Foto KTP asli terbaca jelas & tidak buram</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-building-columns"></i>
                            </div>
                            <span class="text-xs font-medium panel-feature-text" style="color: #dfb2f1 !important;">Rekening bank valid atas nama pribadi</span>
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
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Verifikasi Data Diri</h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">Lengkapi data diri Anda untuk melanjutkan proses aktivasi akun</p>
                        </div>
                        <div class="auth-tab-switcher">
                            <span class="auth-tab-btn active">
                                <i class="fas fa-id-card text-[11px] mr-1"></i> Tahap 1: Verifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Flash Message Notifications -->
                    <?php if (!empty($verification) && $verification['status'] === 'ditolak'): ?>
                        <div class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 text-xs">
                            <div class="flex items-center gap-2 font-bold text-rose-700 mb-1">
                                <i class="fas fa-circle-xmark text-sm"></i> Verifikasi Sebelumnya Ditolak Admin
                            </div>
                            <p class="text-rose-800 leading-relaxed">
                                <strong>Catatan Perbaikan:</strong> <?= esc($verification['catatan'] ?: 'Mohon periksa kembali kejelasan foto KTP dan nomor rekening Anda.') ?>
                            </p>
                            <p class="text-[11px] text-rose-600 mt-1">Silakan unggah berkas yang benar melalui formulir di bawah ini.</p>
                        </div>
                    <?php endif; ?>

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

                    <!-- Form Verifikasi Data -->
                    <form id="verificationForm" method="POST" action="<?= base_url('/verify') ?>" enctype="multipart/form-data" class="mt-5">
                        <?= csrf_field() ?>

                        <!-- 1. BAGIAN UPLOAD KTP (POSISI PALING ATAS FORM) -->
                        <div class="form-group-spaced">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Dokumen Identitas KTP <span class="text-rose-500">*</span>
                                </label>
                                <?php if (!empty($verification['ktp_path'])): ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <i class="fas fa-check-circle text-[10px]"></i> KTP Tersimpan
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div id="ktpDropzone" class="upload-dropzone rounded-2xl p-4 sm:p-5 text-center cursor-pointer group relative border-2 border-dashed border-slate-300 hover:border-[#6b008a] transition-all bg-slate-50/60"
                                 onclick="document.getElementById('ktpInput').click()">
                                <input type="file" id="ktpInput" name="ktp" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" <?= empty($verification['ktp_path']) ? 'required' : '' ?> class="hidden">
                                
                                <div id="ktpPreview" class="<?= empty($verification['ktp_path']) ? 'hidden' : '' ?> mb-2 relative inline-block">
                                    <img id="ktpImageElement" src="<?= !empty($verification['ktp_path']) ? base_url($verification['ktp_path']) : '' ?>" alt="KTP Preview" class="max-h-36 max-w-full mx-auto rounded-xl object-contain shadow-sm border border-slate-200 bg-white p-1">
                                    <span class="inline-block mt-2 text-[11px] font-semibold text-purple-700 hover:text-purple-900 underline">Klik untuk ganti dokumen KTP</span>
                                </div>
                                <div id="ktpDefaultIcon" class="<?= !empty($verification['ktp_path']) ? 'hidden' : '' ?> w-12 h-12 rounded-2xl bg-purple-50 text-[#6b008a] flex items-center justify-center mx-auto mb-2.5 group-hover:scale-105 transition-transform shadow-sm">
                                    <i class="fas fa-id-card text-lg"></i>
                                </div>
                                <div id="ktpTextPrompt" class="<?= !empty($verification['ktp_path']) ? 'hidden' : '' ?>">
                                    <span id="ktpFileName" class="block text-xs font-bold text-slate-800">Pilih atau Tarik Foto / Scan KTP ke Sini</span>
                                    <span class="block text-[11px] text-slate-400 mt-1">Format JPG, PNG, atau WEBP • Maksimal 5MB • Foto asli penuh & jelas</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. HASIL PEMERIKSAAN DOKUMEN & STATUS VALIDASI (STATUS BOX) -->
                        <div id="ktpStatusContainer" class="form-group-spaced">
                            <div id="ktpStatusBox" class="p-3.5 rounded-2xl border transition-all duration-200 <?= !empty($verification['ktp_path']) ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-700' ?>">
                                <div class="flex items-start gap-3">
                                    <div id="ktpStatusIcon" class="mt-0.5 text-base shrink-0 <?= !empty($verification['ktp_path']) ? 'text-emerald-600' : 'text-purple-600' ?>">
                                        <i class="fas <?= !empty($verification['ktp_path']) ? 'fa-circle-check' : 'fa-circle-info' ?>"></i>
                                    </div>
                                    <div class="flex-grow text-xs space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span id="ktpStatusTitle" class="font-bold text-xs <?= !empty($verification['ktp_path']) ? 'text-emerald-900' : 'text-slate-800' ?>">
                                                <?= !empty($verification['ktp_path']) ? 'Dokumen KTP Sebelumnya Telah Terunggah' : 'Pemeriksaan Identitas Otomatis' ?>
                                            </span>
                                            <span id="ktpStatusBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= !empty($verification['ktp_path']) ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-purple-100 text-[#6b008a] border border-purple-200' ?>">
                                                <?= !empty($verification['ktp_path']) ? 'Siap Lanjut' : 'Menunggu Berkas' ?>
                                            </span>
                                        </div>
                                        <p id="ktpStatusDesc" class="text-[11px] leading-relaxed text-slate-600">
                                            <?= !empty($verification['ktp_path']) ? 'KTP Anda telah tersimpan di sistem. Unggah berkas baru apabila Anda ingin mengganti dokumen KTP.' : 'Unggah foto KTP asli yang jelas dan terbaca. Sistem akan memverifikasi NIK dan nama secara otomatis.' ?>
                                        </p>
                                        
                                        <!-- Ringkasan Ekstraksi Dokumen Terbaca -->
                                        <div id="ktpExtractedSummary" class="hidden mt-2 p-2.5 rounded-xl bg-white/80 border border-slate-200/80 text-[11px] space-y-1">
                                            <div class="flex items-center justify-between font-mono">
                                                <span class="text-slate-500 font-sans">NIK Terdeteksi:</span>
                                                <span id="extractedNikText" class="font-bold text-slate-800 tracking-wider">-</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500">Nama Terdeteksi:</span>
                                                <span id="extractedNamaText" class="font-bold text-slate-800 uppercase">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. BAGIAN IDENTITAS: NAMA SESUAI KTP -->
                        <div class="form-group-spaced">
                            <label for="namaKtp" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap (KTP) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="namaKtp" name="nama_ktp" required
                                    value="<?= esc(old('nama_ktp', $verification['nama_ktp'] ?? ($user['nama_lengkap'] ?? session('nama') ?? ''))) ?>"
                                    class="input-pill"
                                    placeholder="Contoh: Ahmad Dahlan">
                            </div>
                        </div>

                        <!-- 4. BAGIAN IDENTITAS: NOMOR INDUK KEPENDUDUKAN (NIK) -->
                        <div class="form-group-spaced">
                            <label for="nikInput" class="block text-xs font-bold text-slate-700 mb-1.5">
                                NIK (Nomor Induk Kependudukan) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" id="nikInput" name="nik" required pattern="[0-9]{16}" minlength="16" maxlength="16" inputmode="numeric"
                                    value="<?= esc($verification['nik'] ?? '') ?>"
                                    class="input-pill font-mono tracking-wider"
                                    placeholder="16 digit angka KTP"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)">
                            </div>
                            <span class="block text-[10px] text-slate-400 mt-1">1 NIK hanya berlaku untuk 1 akun kemitraan.</span>
                        </div>

                        <!-- 5. BAGIAN REKENING: NAMA BANK & NOMOR REKENING -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 form-group-spaced">
                            <!-- Nama Bank -->
                            <div>
                                <label for="selectBank" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama Bank <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-building-columns"></i>
                                    </span>
                                    <select id="selectBank" required
                                        class="input-pill cursor-pointer bg-white pr-4"
                                        onchange="handleBankSelection(this.value)">
                                        <option value="" disabled selected>-- Pilih Nama Bank --</option>
                                        <option value="BCA (Bank Central Asia)">BCA (Bank Central Asia)</option>
                                        <option value="BSI (Bank Syariah Indonesia)">BSI (Bank Syariah Indonesia)</option>
                                        <option value="Bank Mandiri">Bank Mandiri</option>
                                        <option value="BRI (Bank Rakyat Indonesia)">BRI (Bank Rakyat Indonesia)</option>
                                        <option value="BNI (Bank Negara Indonesia)">BNI (Bank Negara Indonesia)</option>
                                        <option value="Bank Muamalat">Bank Muamalat</option>
                                        <option value="CIMB Niaga">CIMB Niaga</option>
                                        <option value="Bank Permata">Bank Permata</option>
                                        <option value="Bank Danamon">Bank Danamon</option>
                                        <option value="Bank Tabungan Negara (BTN)">Bank Tabungan Negara (BTN)</option>
                                        <option value="Bank BJB">Bank BJB</option>
                                        <option value="Bank Jatim">Bank Jatim</option>
                                        <option value="Bank Jateng">Bank Jateng</option>
                                        <option value="Bank Sumut">Bank Sumut</option>
                                        <option value="Bank Nagari">Bank Nagari</option>
                                        <option value="Bank DKI">Bank DKI</option>
                                        <option value="Bank BTPN / Jenius">Bank BTPN / Jenius</option>
                                        <option value="Bank Jago">Bank Jago</option>
                                        <option value="SeaBank">SeaBank</option>
                                        <option value="Lainnya">Lainnya (Ketik Manual)</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-700 text-xs">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>

                                <!-- Kolom Input Ketik Manual (Hanya Aktif & Muncul Ketika Memilih Opsi 'Lainnya') -->
                                <div id="bankManualContainer" class="hidden mt-2">
                                    <input type="text" id="namaBankManual" name="nama_bank_manual"
                                        class="input-pill bg-purple-50/50 border-purple-200 focus:bg-white focus:border-[#6b008a]"
                                        style="padding-left: 1rem !important; padding-right: 1rem !important;"
                                        placeholder="Ketik nama bank Anda di sini..."
                                        onfocus="this.style.textAlign='left'"
                                        onblur="if(!this.value.trim()) this.style.textAlign='center'"
                                        oninput="syncBankValue(this.value); this.style.textAlign=this.value.trim() ? 'left' : 'center'">
                                </div>

                                <!-- Input Hidden untuk memastikan nama_bank terkirim bersih ke backend -->
                                <input type="hidden" id="namaBankFinal" name="nama_bank" value="<?= esc($verification['nama_bank'] ?? '') ?>" required>
                            </div>

                            <!-- 6. Nomor Rekening -->
                            <div>
                                <label for="noRek" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor Rekening <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-credit-card"></i>
                                    </span>
                                    <input type="text" id="noRek" name="nomor_rekening" required pattern="[0-9]{5,25}"
                                        value="<?= esc($verification['nomor_rekening'] ?? '') ?>"
                                        class="input-pill font-mono"
                                        placeholder="Contoh: 1234567890">
                                </div>
                            </div>
                        </div>

                        <!-- 7. Nama Pemilik Rekening -->
                        <div class="form-group-spaced">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="namaPemilikRekening" class="block text-xs font-bold text-slate-700">
                                    Nama Pemilik Rekening <span class="text-rose-500">*</span>
                                </label>
                                <button type="button" id="btnCopyNamaKtp" onclick="copyKtpName()" class="hidden text-xs font-bold text-[#6b008a] hover:text-[#55006e] hover:underline items-center gap-1 focus:outline-none" disabled style="display: none;">
                                    <i class="fas fa-clone text-[10px]"></i> Salin dari Nama KTP
                                </button>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <input type="text" id="namaPemilikRekening" name="nama_pemilik_rekening" required readonly tabindex="-1"
                                    value="<?= esc(old('nama_pemilik_rekening', $verification['nama_pemilik_rekening'] ?? ($user['nama_lengkap'] ?? session('nama') ?? ''))) ?>"
                                    class="input-pill input-locked"
                                    placeholder="Nama lengkap sesuai KTP">
                            </div>
                            <span id="rekeningOwnerError" class="hidden text-[10px] text-rose-600 font-semibold mt-1 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation"></i> Nama pemilik rekening harus sama persis dengan nama pada KTP.
                            </span>
                            <span id="rekeningOwnerHint" class="block text-[10px] text-slate-400 mt-1">Nama pemilik rekening otomatis mengikuti Nama Lengkap (KTP) dan terkunci.</span>
                        </div>

                        <!-- 8. Checkbox Persetujuan Kebenaran Data -->
                        <div class="pt-1 mb-5">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" id="agreeVerification" name="agree_verification" value="1" required class="mt-1 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-600 leading-relaxed">
                                    Saya menyatakan data identitas dan informasi rekening di atas adalah benar, sah, dan milik saya pribadi.
                                </span>
                            </label>
                        </div>

                        <!-- 9. Tombol Utama Verifikasi (Disabled/Terkunci Sampai KTP & Data Valid) -->
                        <div class="auth-submit-wrap">
                            <button type="submit" id="submitBtn" class="btn-brand-primary" <?= empty($verification['ktp_path']) ? 'disabled' : '' ?>>
                                <span id="submitBtnText">Lanjutkan ke Perjanjian</span>
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

    <!-- Bottom Page Footer -->
    <footer class="auth-bottom-footer">
        <a href="<?= base_url('/') ?>">Alur Pendaftaran</a>
        <span>•</span>
        <a href="<?= base_url('/register') ?>">Persyaratan</a>
        <span>•</span>
        <a href="https://wa.me/6281234567890" target="_blank">Bantuan & Kontak</a>
    </footer>
</div>

<!-- Tesseract.js via CDN untuk Pemindaian OCR KTP Sisi Klien -->
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

<script>
// State Identitas & Status Dokumen
const systemState = {
    hasExistingKtp: <?= !empty($verification['ktp_path']) ? 'true' : 'false' ?>,
    fileSelected: false,
    isProcessing: false,
    isKtpValid: <?= !empty($verification['ktp_path']) ? 'true' : 'false' ?>,
    extractedNik: null,
    extractedNama: null,
    currentScanId: 0
};

// Fungsi Helper Penguncian Field Identitas & Rekening
function lockField(el) {
    if (!el || !el.value || !el.value.trim()) return;
    el.readOnly = true;
    el.classList.add('input-locked');
    el.setAttribute('tabindex', '-1');
}

function unlockField(el) {
    if (!el) return;
    el.readOnly = false;
    el.classList.remove('input-locked');
    el.removeAttribute('tabindex');
}

// Pemeriksaan Kesesuaian Nama Pemilik Rekening dengan Nama KTP
function checkRekeningOwnerMatch() {
    const namaKtpEl = document.getElementById('namaKtp');
    const namaPemilikEl = document.getElementById('namaPemilikRekening');
    const errorEl = document.getElementById('rekeningOwnerError');
    if (!namaKtpEl || !namaPemilikEl) return true;

    const valKtp = namaKtpEl.value.trim().toUpperCase().replace(/\s+/g, ' ');
    const valPemilik = namaPemilikEl.value.trim().toUpperCase().replace(/\s+/g, ' ');

    if (!valPemilik || !valKtp) {
        if (errorEl) errorEl.classList.add('hidden');
        namaPemilikEl.classList.remove('border-rose-500', 'bg-rose-50/40');
        return false;
    }

    if (valKtp !== valPemilik) {
        if (errorEl) errorEl.classList.remove('hidden');
        namaPemilikEl.classList.add('border-rose-500', 'bg-rose-50/40');
        return false;
    } else {
        if (errorEl) errorEl.classList.add('hidden');
        namaPemilikEl.classList.remove('border-rose-500', 'bg-rose-50/40');
        return true;
    }
}

// Salin / Sinkronisasi Nama KTP ke Nama Pemilik Rekening
function syncNamaRekeningFromKtp() {
    const namaKtpInput = document.getElementById('namaKtp');
    const namaPemilikInput = document.getElementById('namaPemilikRekening');
    if (!namaPemilikInput) return;

    if (namaKtpInput) {
        namaPemilikInput.value = namaKtpInput.value;
    } else {
        namaPemilikInput.value = '';
    }
    namaPemilikInput.readOnly = true;
    namaPemilikInput.classList.add('input-locked');
    namaPemilikInput.setAttribute('tabindex', '-1');
}

function copyKtpName() {
    syncNamaRekeningFromKtp();
    checkRekeningOwnerMatch();
    refreshSubmitButton();
}

// Logika Bank & Opsi Lainnya
function handleBankSelection(value) {
    const manualContainer = document.getElementById('bankManualContainer');
    const manualInput = document.getElementById('namaBankManual');
    const finalInput = document.getElementById('namaBankFinal');

    if (value === 'Lainnya') {
        manualContainer.classList.remove('hidden');
        manualInput.required = true;
        setTimeout(() => manualInput.focus(), 50);
        finalInput.value = manualInput.value.trim();
    } else {
        manualContainer.classList.add('hidden');
        manualInput.required = false;
        manualInput.value = '';
        finalInput.value = value;
    }
    refreshSubmitButton();
}

function syncBankValue(value) {
    document.getElementById('namaBankFinal').value = value.trim();
    refreshSubmitButton();
}

// Update Tampilan Status Box
function updateStatusBox(type, title, desc, badgeText) {
    const box = document.getElementById('ktpStatusBox');
    const icon = document.getElementById('ktpStatusIcon');
    const titleEl = document.getElementById('ktpStatusTitle');
    const descEl = document.getElementById('ktpStatusDesc');
    const badgeEl = document.getElementById('ktpStatusBadge');

    // Reset styles
    box.className = 'p-3.5 rounded-2xl border transition-all duration-200';
    badgeEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full border';

    if (type === 'loading') {
        box.classList.add('bg-purple-50/80', 'border-purple-200', 'text-purple-900');
        icon.className = 'mt-0.5 text-base shrink-0 text-[#6b008a]';
        icon.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
        titleEl.className = 'font-bold text-xs text-purple-950';
        badgeEl.classList.add('bg-purple-100', 'text-[#6b008a]', 'border-purple-300');
    } else if (type === 'error') {
        box.classList.add('bg-rose-50', 'border-rose-300', 'text-rose-900');
        icon.className = 'mt-0.5 text-base shrink-0 text-rose-600';
        icon.innerHTML = '<i class="fas fa-circle-xmark"></i>';
        titleEl.className = 'font-bold text-xs text-rose-800';
        badgeEl.classList.add('bg-rose-100', 'text-rose-800', 'border-rose-300');
    } else if (type === 'warning') {
        box.classList.add('bg-amber-50', 'border-amber-300', 'text-amber-900');
        icon.className = 'mt-0.5 text-base shrink-0 text-amber-600';
        icon.innerHTML = '<i class="fas fa-triangle-exclamation"></i>';
        titleEl.className = 'font-bold text-xs text-amber-800';
        badgeEl.classList.add('bg-amber-100', 'text-amber-800', 'border-amber-300');
    } else if (type === 'success') {
        box.classList.add('bg-emerald-50', 'border-emerald-300', 'text-emerald-900');
        icon.className = 'mt-0.5 text-base shrink-0 text-emerald-600';
        icon.innerHTML = '<i class="fas fa-circle-check"></i>';
        titleEl.className = 'font-bold text-xs text-emerald-800';
        badgeEl.classList.add('bg-emerald-100', 'text-emerald-800', 'border-emerald-300');
    } else {
        box.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-700');
        icon.className = 'mt-0.5 text-base shrink-0 text-purple-600';
        icon.innerHTML = '<i class="fas fa-circle-info"></i>';
        titleEl.className = 'font-bold text-xs text-slate-800';
        badgeEl.classList.add('bg-purple-100', 'text-[#6b008a]', 'border-purple-200');
    }

    titleEl.textContent = title;
    descEl.textContent = desc;
    badgeEl.textContent = badgeText;
}

// Update Status Tombol Submit
function refreshSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const agreeCheck = document.getElementById('agreeVerification');
    const isAgree = agreeCheck ? agreeCheck.checked : false;
    const isRekeningMatch = checkRekeningOwnerMatch();
    const nikInput = document.getElementById('nikInput');
    const namaInput = document.getElementById('namaKtp');
    const valNik = nikInput ? nikInput.value.trim() : '';
    const valNama = namaInput ? namaInput.value.trim() : '';

    // Periksa kelengkapan data rekening bank
    const noRekInput = document.getElementById('noRek');
    const finalBankInput = document.getElementById('namaBankFinal');
    const valNoRek = noRekInput ? noRekInput.value.trim() : '';
    const valBank = finalBankInput ? finalBankInput.value.trim() : '';
    const isBankComplete = valNoRek.length >= 5 && valBank.length > 0;

    const isFormComplete = systemState.isKtpValid && 
                           isAgree && 
                           isRekeningMatch && 
                           isBankComplete &&
                           valNik.length === 16 && 
                           valNama.length >= 3;

    if (submitBtn) {
        if (isFormComplete) {
            submitBtn.removeAttribute('disabled');
            submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        } else {
            submitBtn.setAttribute('disabled', 'disabled');
            submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        }
    }
}

// Analisis Kualitas & Citra Gambar di Canvas
function analyzeImageQuality(img) {
    return new Promise((resolve) => {
        const width = img.naturalWidth || img.width;
        const height = img.naturalHeight || img.height;

        // 1. Validasi Dimensi Minimum (toleransi resolusi 320x200 untuk KTP)
        if (width < 320 || height < 200) {
            return resolve({
                valid: false,
                reason: 'Resolusi gambar KTP terlalu kecil atau tidak jelas. Mohon unggah foto KTP dengan resolusi lebih tinggi.'
            });
        }

        // 2. Validasi Aspek Rasio (Toleransi rasio 0.45 - 3.2 untuk foto portrait smartphone maupun landscape)
        const ratio = width / height;
        if (ratio < 0.45 || ratio > 3.2) {
            return resolve({
                valid: false,
                reason: 'Proporsi dokumen tidak proporsional untuk kartu identitas KTP. Mohon pastikan foto kartu terlihat penuh.'
            });
        }

        // 3. Analisis Kecerahan & Kontras Citra di In-Memory Canvas
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const sampleW = 200;
        const sampleH = Math.round(200 / (ratio || 1.58));
        canvas.width = sampleW;
        canvas.height = sampleH;

        try {
            ctx.drawImage(img, 0, 0, sampleW, sampleH);
            const imgData = ctx.getImageData(0, 0, sampleW, sampleH);
            const d = imgData.data;
            let totalLum = 0;
            const step = 4;
            const pixelCount = d.length / 4;

            for (let i = 0; i < d.length; i += step * 4) {
                const r = d[i];
                const g = d[i + 1];
                const b = d[i + 2];
                totalLum += (0.299 * r + 0.587 * g + 0.114 * b);
            }

            const sampledCount = Math.floor(pixelCount / step);
            const avgLum = totalLum / sampledCount;

            // Foto terlalu gelap (underexposed) - toleransi dilonggarkan ke < 20
            if (avgLum < 20) {
                return resolve({
                    valid: false,
                    reason: 'Foto KTP terlalu gelap atau buram. Pastikan foto diambil dalam pencahayaan yang cukup.'
                });
            }

            // Foto silau / putih total (overexposed) - toleransi dilonggarkan ke > 252
            if (avgLum > 252) {
                return resolve({
                    valid: false,
                    reason: 'Foto KTP terlalu silau atau terdapat pantulan cahaya berlebih. Mohon ambil foto tanpa pantulan cahaya.'
                });
            }

            // 4. Analisis Ketajaman / Blur Parah (Laplacian Edge Variance)
            // Menolak foto yang buram total/out-of-focus sehingga tidak dapat diverifikasi visual
            const grays = new Float32Array(sampleW * sampleH);
            for (let i = 0; i < pixelCount; i++) {
                grays[i] = 0.299 * d[i * 4] + 0.587 * d[i * 4 + 1] + 0.114 * d[i * 4 + 2];
            }
            let sumLap = 0, sumLapSq = 0, lapCount = 0;
            for (let y = 1; y < sampleH - 1; y++) {
                for (let x = 1; x < sampleW - 1; x++) {
                    const idx = y * sampleW + x;
                    const lap = grays[idx + 1] + grays[idx - 1] + grays[idx + sampleW] + grays[idx - sampleW] - 4 * grays[idx];
                    sumLap += lap;
                    sumLapSq += lap * lap;
                    lapCount++;
                }
            }
            if (lapCount > 0) {
                const meanLap = sumLap / lapCount;
                const lapVariance = (sumLapSq / lapCount) - (meanLap * meanLap);
                if (lapVariance < 2.0) {
                    return resolve({
                        valid: false,
                        reason: 'Foto KTP terlalu buram sehingga bagian utama dokumen tidak dapat diperiksa. Mohon unggah foto KTP yang lebih fokus dan jelas.'
                    });
                }
            }

            resolve({ valid: true, canvas: canvas });
        } catch (e) {
            // Jika canvas ter-taint (CORS), lanjutkan tanpa menghentikan proses
            resolve({ valid: true });
        }
    });
}

// Preprocessing citra KTP di Canvas sebelum OCR (Scale Normalization & Kontras Teks vs Latar KTP Adaptif)
function preprocessKtpCanvas(img) {
    const origW = img.naturalWidth || img.width;
    const origH = img.naturalHeight || img.height;
    if (!origW || !origH) return null;

    // Normalisasi resolusi optimal untuk engine Tesseract (1100 - 1600px)
    let targetW = origW;
    let targetH = origH;
    if (origW > 1600) {
        targetW = 1500;
        targetH = Math.round((origH / origW) * targetW);
    } else if (origW < 1000) {
        targetW = 1200;
        targetH = Math.round((origH / origW) * targetW);
    }

    const canvas = document.createElement('canvas');
    canvas.width = targetW;
    canvas.height = targetH;
    const ctx = canvas.getContext('2d');
    if (!ctx) return null;

    ctx.drawImage(img, 0, 0, targetW, targetH);

    try {
        const imgData = ctx.getImageData(0, 0, targetW, targetH);
        const d = imgData.data;
        const totalPixels = d.length / 4;
        const hist = new Uint32Array(256);

        // Hitung grayscale & kumpulkan histogram tanpa merusak citra redup
        for (let i = 0; i < d.length; i += 4) {
            // Bobot perceptually balanced untuk menonjolkan teks hitam di atas latar cyan/biru
            const gray = Math.round(0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2]);
            d[i] = gray;
            hist[gray]++;
        }

        // Hitung batas dinamis (persentil 2% dan 98%) agar kontras adaptif di semua pencahayaan
        let low = 0, high = 255;
        let count = 0;
        const lowCut = totalPixels * 0.02;
        const highCut = totalPixels * 0.02;

        for (let i = 0; i < 256; i++) {
            count += hist[i];
            if (count >= lowCut) {
                low = i;
                break;
            }
        }
        count = 0;
        for (let i = 255; i >= 0; i--) {
            count += hist[i];
            if (count >= highCut) {
                high = i;
                break;
            }
        }

        const range = (high > low + 15) ? (high - low) : 255;
        const lut = new Uint8Array(256);
        for (let i = 0; i < 256; i++) {
            if (i <= low) lut[i] = 0;
            else if (i >= high) lut[i] = 255;
            else lut[i] = Math.round(((i - low) / range) * 255);
        }

        // Terapkan penajaman kontras adaptif
        for (let i = 0; i < d.length; i += 4) {
            const val = lut[d[i]];
            d[i] = val;
            d[i + 1] = val;
            d[i + 2] = val;
        }

        ctx.putImageData(imgData, 0, 0);
        return canvas;
    } catch (e) {
        return null;
    }
}

// Pembersihan & Normalisasi String untuk Komparasi
function normalizeText(str) {
    if (!str) return '';
    return str.toString()
        .toUpperCase()
        .replace(/[^A-Z0-9\s]/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
}

// Daftar Kode Provinsi Indonesia Sah untuk Validasi Format NIK
const VALID_PROV_CODES = new Set([
    11, 12, 13, 14, 15, 16, 17, 18, 19, 21,
    31, 32, 33, 34, 35, 36,
    51, 52, 53,
    61, 62, 63, 64, 65,
    71, 72, 73, 74, 75, 76,
    81, 82,
    91, 92, 93, 94, 95, 96
]);

function isValidNikFormat(candidate) {
    if (!candidate || typeof candidate !== 'string') return false;
    const clean = candidate.trim();
    if (!/^[1-9][0-9]{15}$/.test(clean)) return false;
    const prov = parseInt(clean.substring(0, 2), 10);
    return VALID_PROV_CODES.has(prov);
}

// Ekstraksi Kata Kunci KTP Indonesia (dengan toleransi variasi kata & deteksi dokumen lain)
function checkIndonesianKtpKeywords(rawText) {
    if (!rawText) return { isKtp: false, isOtherDoc: false, matchCount: 0, foundKeywords: [] };
    const text = normalizeText(rawText);
    const upper = (rawText || '').toUpperCase();

    // 1. Tolak langsung dokumen selain KTP (Kartu Keluarga, SIM, Paspor, dll.)
    const otherDocs = [
        'KARTU KELUARGA', 'SURAT IZIN MENGEMUDI', 'PASPOR', 'PASSPORT',
        'IJAZAH', 'AKTA KELAHIRAN', 'BUKU NIKAH', 'SERTIFIKAT'
    ];
    for (const other of otherDocs) {
        if (upper.includes(other)) {
            return {
                isKtp: false,
                isOtherDoc: true,
                rejectedDocName: other,
                matchCount: 0,
                foundKeywords: []
            };
        }
    }

    const keywords = [
        'REPUBLIK', 'INDONESIA', 'KARTU', 'TANDA', 'PENDUDUK',
        'NIK', 'PROVINSI', 'KABUPATEN', 'KOTA', 'AGAMA',
        'STATUS', 'PERKAWINAN', 'PEKERJAAN', 'KEWARGANEGARAAN',
        'BERLAKU', 'DARAH', 'ALAMAT', 'TEMPAT', 'LAHIR', 'KELURAHAN', 'KECAMATAN',
        'KELAMIN', 'SEUMUR', 'HIDUP', 'DESA', 'WARGANEGARA', 'WNI', 'GOLONGAN', 'KAWIN'
    ];

    let matchCount = 0;
    const foundKeywords = [];

    keywords.forEach(kw => {
        if (text.includes(kw)) {
            matchCount++;
            foundKeywords.push(kw);
        }
    });

    const hasNik = /\bN[\s\.\-_]*[I1l|!]?[\s\.\-_]*[KkRr]\b/i.test(rawText) || text.includes('NIK');
    const hasCoreAnchor = hasNik || (text.includes('PENDUDUK') && text.includes('INDONESIA')) || (text.includes('PROVINSI') && text.includes('KABUPATEN'));
    const isIndonesianKtp = matchCount >= 2 || (hasCoreAnchor && matchCount >= 1) || hasNik;

    return {
        isKtp: isIndonesianKtp,
        isOtherDoc: false,
        matchCount: matchCount,
        foundKeywords: foundKeywords
    };
}

// Ekstraksi 16 Digit NIK dengan Toleransi Spasi & OCR Typos
function extractNikFromText(rawText) {
    if (!rawText) return null;

    const fixOcrDigits = (str) => {
        return str
            .replace(/[oODQ]/g, '0')
            .replace(/[Il|!\]\[]/g, '1')
            .replace(/[Zz]/g, '2')
            .replace(/[A]/g, '4')
            .replace(/[Ss]/g, '5')
            .replace(/[bG]/g, '6')
            .replace(/[T]/g, '7')
            .replace(/[B]/g, '8')
            .replace(/[gq]/g, '9');
    };

    // 1. Coba pola 16 digit langsung (tanpa spasi)
    const exactMatches = rawText.match(/\b([1-9][0-9]{15})\b/g);
    if (exactMatches) {
        for (const m of exactMatches) {
            if (isValidNikFormat(m)) return m;
        }
    }

    const lines = rawText.split('\n').map(l => l.trim()).filter(Boolean);

    // 2. Coba periksa baris yang mengandung indikasi kata NIK (dengan toleransi typo N1K, NlK, NIR, dll)
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i];
        if (/\bN[\s\.\-_]*[I1l|!]?[\s\.\-_]*[KkRr]\b/i.test(line) || /NIK/i.test(line)) {
            let candidateLine = line.replace(/.*?\bN[\s\.\-_]*[I1l|!]?[\s\.\-_]*[KkRr]\b\s*[:;\-=_~!\.]*\s*/i, '');
            if (!candidateLine.trim() && i + 1 < lines.length) {
                candidateLine = lines[i + 1];
            }
            
            let cleaned = fixOcrDigits(candidateLine).replace(/[^0-9]/g, '');
            if (cleaned.length >= 16) {
                for (let j = 0; j <= cleaned.length - 16; j++) {
                    const sub = cleaned.substring(j, j + 16);
                    if (isValidNikFormat(sub)) return sub;
                }
            }
        }
    }

    // 3. Coba cari baris mana saja yang memiliki kombinasi angka (minimal 8 digit asli)
    for (const line of lines) {
        let candidateStr = line.replace(/.*?\b(NIK|N1K|NOMOR|NO|KTP)\b\s*[:;\-=_~!\.]*\s*/i, '');
        const rawDigitsCount = (candidateStr.match(/[0-9]/g) || []).length;
        if (rawDigitsCount >= 8) {
            let cleaned = fixOcrDigits(candidateStr).replace(/[^0-9]/g, '');
            if (cleaned.length >= 16) {
                for (let j = 0; j <= cleaned.length - 16; j++) {
                    const sub = cleaned.substring(j, j + 16);
                    if (isValidNikFormat(sub)) return sub;
                }
            }
        }
    }

    return null;
}

// Ekstraksi Nama Sesuai KTP dengan Toleransi Variasi Spasi & Separator
function extractNamaFromText(rawText, foundNik) {
    if (!rawText) return null;

    const lines = rawText.split('\n').map(l => l.trim()).filter(Boolean);

    const standaloneExcludes = new Set([
        'ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KONGHUCU',
        'WNI', 'WNA', 'KAWIN', 'BELUM KAWIN', 'CERAI HIDUP', 'CERAI MATI',
        'SEUMUR HIDUP', 'LAKI LAKI', 'LAKI-LAKI', 'PEREMPUAN', 'KARYAWAN SWASTA',
        'WIRASWASTA', 'PELAJAR MAHASISWA', 'PELAJAR/MAHASISWA', 'BELUM TIDAK BEKERJA', 'TIDAK BEKERJA',
        'BURUH', 'PNS', 'PEGAWAI NEGERI'
    ]);

    const headerPrefixes = [
        'PROVINSI', 'KABUPATEN', 'KOTA', 'KARTU TANDA', 'TANDA PENDUDUK',
        'REPUBLIK INDONESIA', 'TEMPAT', 'TGL LAHIR', 'GOL DARAH', 'ALAMAT',
        'RT RW', 'KEL DESA', 'KECAMATAN', 'AGAMA', 'STATUS PERKAWINAN',
        'PEKERJAAN', 'KEWARGANEGARAAN', 'BERLAKU HINGGA'
    ];

    const isBlacklisted = (str) => {
        const upper = str.toUpperCase().trim();
        if (standaloneExcludes.has(upper)) return true;
        for (const h of headerPrefixes) {
            if (upper === h || upper.startsWith(h + ' ') || upper.startsWith(h + ':')) return true;
        }
        return false;
    };

    const cleanCandidateName = (str) => {
        if (!str) return '';
        let cleaned = str.replace(/.*?\bN[\s\.\-_]*A[\s\.\-_]*M[\s\.\-_]*A\b\s*[:;\-=_~!\.]*\s*/i, '');
        cleaned = cleaned.replace(/\b(TEMPAT|LAHIR|GOL|DARAH|ALAMAT|JENIS|KELAMIN|AGAMA|STATUS|PEKERJAAN|KEWARGANEGARAAN)\b.*/i, '');
        cleaned = cleaned.replace(/[^A-Za-z\s'\.]/g, ' ');
        cleaned = cleaned.replace(/\s+/g, ' ').trim();
        if (cleaned.length >= 3 && /[AEIOUaeiou]/.test(cleaned) && !isBlacklisted(cleaned)) {
            return cleaned.toUpperCase();
        }
        return '';
    };

    // 1. Coba cari baris yang memuat kata 'NAMA' (dengan toleransi spasi atau titik: N A M A, NAM A, NA.MA, HAM A)
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i];
        if (/\bN[\s\.\-_]*A[\s\.\-_]*M[\s\.\-_]*A\b/i.test(line) || /\b(HAM\s*A|MAMA)\b/i.test(line)) {
            const cleaned = cleanCandidateName(line);
            if (cleaned) {
                return cleaned;
            }
            if (i + 1 < lines.length) {
                const nextLineClean = cleanCandidateName(lines[i + 1]);
                if (nextLineClean) {
                    return nextLineClean;
                }
            }
        }
    }

    // 2. Posisi struktural: jika kata 'NAMA' tidak terbaca, periksa baris setelah NIK
    let nikLineIndex = -1;
    if (foundNik) {
        for (let i = 0; i < lines.length; i++) {
            const digitsOnly = lines[i].replace(/[oODQ]/g, '0').replace(/[Il|!\]\[]/g, '1').replace(/[^0-9]/g, '');
            if (digitsOnly.includes(foundNik)) {
                nikLineIndex = i;
                break;
            }
        }
    }

    if (nikLineIndex !== -1 && nikLineIndex + 1 < lines.length) {
        for (let offset = 1; offset <= 2 && (nikLineIndex + offset) < lines.length; offset++) {
            const candidateLine = lines[nikLineIndex + offset];
            const cleaned = cleanCandidateName(candidateLine);
            if (cleaned && !cleaned.includes(foundNik)) {
                return cleaned;
            }
        }
    }

    return null;
}

// Ekstraksi 16 Digit NIK dan Nama dari Teks OCR
function extractKtpData(rawText) {
    if (!rawText) return { nik: null, nama: null };
    const upper = rawText.toUpperCase();
    if (upper.includes('KARTU KELUARGA') || upper.includes('SURAT IZIN MENGEMUDI') || upper.includes('PASPOR')) {
        return { nik: null, nama: null };
    }
    const nik = extractNikFromText(rawText);
    const nama = extractNamaFromText(rawText, nik);
    return { nik, nama };
}

// Validasi Kesesuaian Identitas (Input Pengguna vs Status Dokumen KTP)
function validateIdentityMatch() {
    // Jika belum ada file atau masih loading, serahkan ke handler terkait
    if (!systemState.fileSelected && !systemState.hasExistingKtp) {
        refreshSubmitButton();
        return;
    }

    // Jika KTP lama masih berlaku dan tidak ada file baru dipilih
    if (!systemState.fileSelected && systemState.hasExistingKtp) {
        systemState.isKtpValid = true;
        refreshSubmitButton();
        return;
    }

    const nikInput = document.getElementById('nikInput');
    const namaInput = document.getElementById('namaKtp');
    const valNik = nikInput ? nikInput.value.trim() : '';
    const valNama = namaInput ? namaInput.value.trim() : '';

    // Jika KTP belum valid (dokumen ditolak atau proses belum selesai)
    if (!systemState.isKtpValid) {
        // Status penanganan variasi dokumen:
        // 'KTP Terdeteksi Namun Teks Kurang Jelas' ('Teks Kurang Jelas')
        // 'NIK Terbaca, Nama Belum Terbaca Jelas' ('Nama Belum Terbaca')
        // 'Nama Terbaca, NIK Belum Terbaca' ('NIK Belum Terbaca')
        refreshSubmitButton();
        return;
    }

    // Jika NIK belum lengkap 16 digit
    if (valNik.length !== 16) {
        updateStatusBox('warning', 'KTP Valid - Lengkapi NIK',
            'Silakan masukkan 16 digit NIK lengkap sesuai KTP Anda.',
            'KTP Valid'
        );
        refreshSubmitButton();
        return;
    }

    // Jika Nama belum lengkap
    if (!valNama || valNama.length < 3) {
        updateStatusBox('warning', 'KTP Valid - Lengkapi Nama',
            'Silakan masukkan Nama Lengkap Anda sesuai yang tertera pada KTP.',
            'KTP Valid'
        );
        refreshSubmitButton();
        return;
    }

    // Semua Verifikasi Sukses (KTP valid & input manual terisi lengkap)
    updateStatusBox('success', 'Dokumen KTP Terverifikasi & Sesuai',
        'Dokumen KTP valid dan data identitas telah lengkap.',
        'KTP Sesuai'
    );
    // Status label pelengkap: 'Dokumen KTP Terunggah & Data Lengkap' ('Data Lengkap')
    refreshSubmitButton();
}

// Algoritma Kemiripan Teks Sederhana (Jaccard similarity on character bigrams)
function calculateSimilarity(str1, str2) {
    if (!str1 || !str2) return 0;
    if (str1 === str2) return 1.0;
    const getBigrams = str => {
        const s = new Set();
        for (let i = 0; i < str.length - 1; i++) s.add(str.slice(i, i + 2));
        return s;
    };
    const b1 = getBigrams(str1);
    const b2 = getBigrams(str2);
    let intersection = 0;
    b1.forEach(bg => { if (b2.has(bg)) intersection++; });
    const union = b1.size + b2.size - intersection;
    return union === 0 ? 0 : intersection / union;
}

// Eksekusi Pemindaian KTP Lengkap (File Input Handler)
async function handleKtpFile(file) {
    if (!file) return;

    systemState.fileSelected = true;
    systemState.isKtpValid = false;
    systemState.extractedNik = null;
    systemState.extractedNama = null;
    const scanId = ++systemState.currentScanId;

    // Reset tombol submit
    refreshSubmitButton();

    // 1. Validasi Tipe & Ukuran File
    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        updateStatusBox('error', 'Format File Tidak Didukung',
            'Format dokumen tidak didukung atau bukan format gambar resmi. Gunakan JPG, PNG, atau WEBP.',
            'Format Ditolak'
        );
        return;
    }

    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        updateStatusBox('error', 'Ukuran Berkas Melebihi Batas',
            'Format dokumen tidak didukung atau ukuran melebihi batas 5MB.',
            'Ukuran Terlalu Besar'
        );
        return;
    }

    // Tampilkan Nama File pada Dropzone
    const fileNameEl = document.getElementById('ktpFileName');
    if (fileNameEl) fileNameEl.textContent = file.name;

    // Preview Gambar
    const imgEl = document.getElementById('ktpImageElement');
    const previewContainer = document.getElementById('ktpPreview');
    const defaultIcon = document.getElementById('ktpDefaultIcon');
    const textPrompt = document.getElementById('ktpTextPrompt');

    const fileReader = new FileReader();
    fileReader.onload = async function(e) {
        if (scanId !== systemState.currentScanId) return;

        imgEl.src = e.target.result;
        previewContainer.classList.remove('hidden');
        if (defaultIcon) defaultIcon.classList.add('hidden');
        if (textPrompt) textPrompt.classList.add('hidden');

        // Mulai Pemeriksaan Kualitas
        updateStatusBox('loading', 'Memeriksa Dokumen...',
            'Tahap 1/2: Menganalisis resolusi, kualitas foto, dan format citra...',
            'Memeriksa Citra'
        );

        // Buat objek Image untuk analisis dimensi dan kualitas citra
        const tempImg = new Image();
        tempImg.onload = async function() {
            if (scanId !== systemState.currentScanId) return;

            const quality = await analyzeImageQuality(tempImg);
            if (!quality.valid) {
                updateStatusBox('error', 'Kualitas Dokumen Belum Memenuhi Syarat',
                    quality.reason,
                    'Kualitas Ditolak'
                );
                systemState.isKtpValid = false;
                refreshSubmitButton();
                return;
            }

            // Tahap 2: Deteksi KTP Indonesia & Ekstraksi OCR
            updateStatusBox('loading', 'Memindai Teks KTP...',
                'Tahap 2/2: Memverifikasi dokumen KTP Indonesia serta membaca NIK & Nama...',
                'Membaca KTP'
            );

            try {
                // Periksa kesiapan Tesseract
                if (typeof Tesseract === 'undefined') {
                    throw new Error('Modul OCR belum selesai dimuat. Silakan periksa koneksi internet Anda.');
                }

                // Preprocessing citra KTP ke Canvas (normalisasi resolusi & penajaman kontras teks vs latar batik biru)
                const preprocessedCanvas = preprocessKtpCanvas(tempImg);
                const ocrSource = preprocessedCanvas || tempImg;

                const result = await Tesseract.recognize(ocrSource, 'eng', {
                    logger: m => {
                        if (m.status === 'recognizing text' && scanId === systemState.currentScanId) {
                            const pct = Math.round(m.progress * 100);
                            const badge = document.getElementById('ktpStatusBadge');
                            if (badge) badge.textContent = `OCR ${pct}%`;
                        }
                    }
                });

                if (scanId !== systemState.currentScanId) return;

                let ocrText = result.data.text || '';

                // Ekstraksi NIK & Nama (Pass 1)
                let extracted = extractKtpData(ocrText);

                // Dual-pass fallback: Jika NIK atau Nama belum lengkap dan menggunakan preprocessed canvas,
                // coba pass kedua dengan citra asli tempImg untuk melengkapi ekstraksi
                if ((!extracted.nik || !extracted.nama) && preprocessedCanvas) {
                    try {
                        const fallbackResult = await Tesseract.recognize(tempImg, 'eng');
                        if (scanId === systemState.currentScanId) {
                            const fallbackText = fallbackResult.data.text || '';
                            const fallbackExtracted = extractKtpData(fallbackText);
                            if (!extracted.nik && fallbackExtracted.nik) {
                                extracted.nik = fallbackExtracted.nik;
                            }
                            if (!extracted.nama && fallbackExtracted.nama) {
                                extracted.nama = fallbackExtracted.nama;
                            }
                            if (fallbackExtracted.nik || fallbackExtracted.nama) {
                                ocrText = ocrText + '\n' + fallbackText;
                            }
                        }
                    } catch (e) {
                        // Silent fallback jika pass kedua tidak berhasil
                    }
                }

                systemState.extractedNik = extracted.nik;
                systemState.extractedNama = extracted.nama;

                // Validasi Kata Kunci KTP Indonesia (atau jika NIK 16 digit Indonesia yang sah berhasil terdeteksi)
                const ktpCheck = checkIndonesianKtpKeywords(ocrText);
                if (ktpCheck.isOtherDoc) {
                    updateStatusBox('error', 'Dokumen Bukan KTP',
                        'Dokumen yang diunggah terdeteksi sebagai ' + (ktpCheck.rejectedDocName || 'dokumen lain') + '. Silakan unggah foto KTP Indonesia yang sah.',
                        'Bukan KTP'
                    );
                    systemState.isKtpValid = false;
                    refreshSubmitButton();
                    return;
                }

                if (!ktpCheck.isKtp && !extracted.nik) {
                    updateStatusBox('error', 'Dokumen Bukan KTP Indonesia',
                        'Dokumen yang diunggah tidak terdeteksi sebagai KTP Indonesia. Pastikan foto KTP terlihat jelas dan bukan dokumen lain.',
                        'Bukan KTP'
                    );
                    systemState.isKtpValid = false;
                    refreshSubmitButton();
                    return;
                }

                // Dokumen KTP Dinyatakan Sah & Valid
                systemState.isKtpValid = true;

                // Tampilkan summary ekstraksi secara informatif (tidak memblokir jika tidak terbaca)
                const summaryBox = document.getElementById('ktpExtractedSummary');
                const nikText = document.getElementById('extractedNikText');
                const namaText = document.getElementById('extractedNamaText');

                if (summaryBox) summaryBox.classList.remove('hidden');
                if (nikText) nikText.textContent = extracted.nik || '(Diisi manual)';
                if (namaText) namaText.textContent = extracted.nama || '(Diisi manual)';

                // Auto-fill nilai awal jika teks terbaca, TETAPI JANGAN KUNCI field NIK & Nama KTP
                const nikInput = document.getElementById('nikInput');
                const namaInput = document.getElementById('namaKtp');

                if (extracted.nik && nikInput && !nikInput.value.trim()) {
                    nikInput.value = extracted.nik;
                }
                if (nikInput) {
                    unlockField(nikInput);
                }

                if (extracted.nama && namaInput && !namaInput.value.trim()) {
                    namaInput.value = extracted.nama;
                }
                if (namaInput) {
                    unlockField(namaInput);
                }

                // Selalu sinkronkan Nama Pemilik Rekening dari Nama KTP & pastikan terkunci permanen
                // (namaPemilikInput.value = extracted.nama / lockField(namaPemilikInput) disempurnakan via syncNamaRekeningFromKtp)
                syncNamaRekeningFromKtp();

                // Evaluasi status dan berikan arahan pengisian ke pendaftar
                validateIdentityMatch();
                checkRekeningOwnerMatch();
                refreshSubmitButton();

            } catch (err) {
                if (scanId !== systemState.currentScanId) return;
                console.error('OCR Error:', err);
                updateStatusBox('warning', 'Dokumen KTP Terunggah',
                    'Dokumen KTP berhasil diunggah. Silakan lengkapi Nama Lengkap dan 16 digit NIK Anda pada formulir di bawah ini.',
                    'KTP Terunggah'
                );
                // Berikan toleransi jika OCR engine gagal termuat di browser
                systemState.isKtpValid = true;
                const nikInput = document.getElementById('nikInput');
                const namaInput = document.getElementById('namaKtp');
                if (nikInput) unlockField(nikInput);
                if (namaInput) unlockField(namaInput);
                syncNamaRekeningFromKtp();
                validateIdentityMatch();
                checkRekeningOwnerMatch();
                refreshSubmitButton();
            }
        };

        tempImg.onerror = function() {
            if (scanId !== systemState.currentScanId) return;
            updateStatusBox('error', 'File Rusak',
                'File gambar tidak dapat dibaca oleh browser. Pastikan file tidak rusak.',
                'Gagal Membaca'
            );
        };

        tempImg.src = e.target.result;
    };

    fileReader.readAsDataURL(file);
}

// Inisialisasi Event Listener saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    const ktpInput = document.getElementById('ktpInput');
    const ktpDropzone = document.getElementById('ktpDropzone');
    const nikInput = document.getElementById('nikInput');
    const namaInput = document.getElementById('namaKtp');
    const agreeCheck = document.getElementById('agreeVerification');
    const select = document.getElementById('selectBank');
    const manualContainer = document.getElementById('bankManualContainer');
    const manualInput = document.getElementById('namaBankManual');
    const finalInput = document.getElementById('namaBankFinal');
    const form = document.getElementById('verificationForm');

    // 1. File Input Change Listener
    if (ktpInput) {
        ktpInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleKtpFile(this.files[0]);
            }
        });
    }

    // 2. Drag & Drop Listener pada Dropzone
    if (ktpDropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            ktpDropzone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
                ktpDropzone.classList.add('border-[#6b008a]', 'bg-purple-50/50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            ktpDropzone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
                ktpDropzone.classList.remove('border-[#6b008a]', 'bg-purple-50/50');
            }, false);
        });

        ktpDropzone.addEventListener('drop', e => {
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                const file = e.dataTransfer.files[0];
                if (ktpInput) ktpInput.files = e.dataTransfer.files;
                handleKtpFile(file);
            }
        }, false);
    }

    // 3. Real-time Cross Check saat User Mengetik di NIK atau Nama
    if (nikInput) {
        nikInput.addEventListener('input', function() {
            if (systemState.fileSelected) {
                validateIdentityMatch();
            }
            refreshSubmitButton();
        });
    }

    if (namaInput) {
        namaInput.addEventListener('input', function() {
            // Sinkronkan langsung ke Nama Pemilik Rekening secara real-time (if (namaPemilikInput && !namaPemilikInput.readOnly) disempurnakan oleh syncNamaRekeningFromKtp)
            syncNamaRekeningFromKtp();
            if (systemState.fileSelected) {
                validateIdentityMatch();
            }
            checkRekeningOwnerMatch();
            refreshSubmitButton();
        });
    }

    const namaPemilikInput = document.getElementById('namaPemilikRekening');
    const noRekInput = document.getElementById('noRek');
    if (noRekInput) {
        noRekInput.addEventListener('input', refreshSubmitButton);
    }
    if (select) {
        select.addEventListener('change', refreshSubmitButton);
    }

    // 4. Inisialisasi status field identitas dan rekening
    // Field Nama KTP dan NIK selalu terbuka agar dapat diisi dan diedit manual oleh pendaftar
    if (namaInput) unlockField(namaInput);
    if (nikInput) unlockField(nikInput);

    // Field Nama Pemilik Rekening selalu sinkron mengikuti Nama KTP dan terkunci permanen
    syncNamaRekeningFromKtp();
    checkRekeningOwnerMatch();

    // Blokir mutlak pengeditan manual (ketik, hapus, paste, potong, drag-drop) pada Nama Pemilik Rekening
    if (namaPemilikInput) {
        ['keydown', 'paste', 'cut', 'drop'].forEach(evt => {
            namaPemilikInput.addEventListener(evt, function(e) {
                if (this.readOnly) {
                    e.preventDefault();
                } else {
                    e.preventDefault();
                }
            });
        });
    }

    // 5. Checkbox Persetujuan
    if (agreeCheck) {
        agreeCheck.addEventListener('change', refreshSubmitButton);
    }

    // 6. Inisialisasi Bank
    const savedBank = <?= json_encode($verification['nama_bank'] ?? '') ?>;
    if (savedBank && select) {
        let matched = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === savedBank) {
                select.selectedIndex = i;
                finalInput.value = savedBank;
                matched = true;
                break;
            }
        }

        if (!matched && select.options.length > 0) {
            select.value = 'Lainnya';
            manualContainer.classList.remove('hidden');
            manualInput.required = true;
            manualInput.value = savedBank;
            manualInput.style.textAlign = 'left';
            finalInput.value = savedBank;
        }
    }

    // 7. Gating pada Pengiriman Form (Submit Protection)
    if (form) {
        form.addEventListener('submit', function(e) {
            // Pastikan Nama Pemilik Rekening selalu tersinkronisasi persis sebelum form dikirim
            syncNamaRekeningFromKtp();

            // Cek apakah KTP valid
            if (!systemState.isKtpValid) {
                e.preventDefault();
                alert('Dokumen KTP belum terverifikasi atau data identitas belum sesuai. Mohon periksa kembali dokumen KTP dan formulir Anda.');
                const box = document.getElementById('ktpStatusBox');
                if (box) box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            // Validasi Kesesuaian Nama Pemilik Rekening dengan Nama KTP
            if (!checkRekeningOwnerMatch()) {
                e.preventDefault();
                alert('Nama pemilik rekening harus sama persis dengan Nama sesuai KTP.');
                if (namaPemilikInput) {
                    namaPemilikInput.focus();
                }
                return false;
            }

            // Validasi Bank
            if (select.value === 'Lainnya') {
                finalInput.value = manualInput.value.trim();
                if (!finalInput.value) {
                    e.preventDefault();
                    manualInput.focus();
                    alert('Silakan ketik nama bank Anda pada kolom yang disediakan.');
                    return false;
                }
            } else if (select.value) {
                finalInput.value = select.value;
            } else {
                e.preventDefault();
                select.focus();
                alert('Silakan pilih nama bank terlebih dahulu.');
                return false;
            }

            // Validasi Checkbox
            if (!agreeCheck.checked) {
                e.preventDefault();
                agreeCheck.focus();
                alert('Anda wajib menyetujui pernyataan kebenaran data identitas dan rekening.');
                return false;
            }
        });
    }

    // Refresh state awal tombol submit
    refreshSubmitButton();
});
</script>
<?= $this->endSection() ?>
