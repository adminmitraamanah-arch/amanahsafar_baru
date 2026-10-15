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
                                            <?= !empty($verification['ktp_path']) ? 'KTP Anda telah tersimpan di sistem. Unggah berkas baru apabila Anda ingin mengganti dokumen KTP.' : 'Unggah foto KTP asli Anda pada kotak di atas. Sistem akan memeriksa format citra, memastikan dokumen KTP Indonesia yang sah, serta mencocokkan NIK dan Nama Anda secara otomatis.' ?>
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
                                            <div id="quickSyncButtons" class="pt-1.5 flex flex-wrap gap-1.5 border-t border-slate-200/60 mt-1">
                                                <button type="button" id="btnApplyNik" class="text-[10px] font-bold px-2 py-1 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition-colors">
                                                    <i class="fas fa-arrow-down mr-1"></i> Gunakan NIK dari KTP
                                                </button>
                                                <button type="button" id="btnApplyNama" class="text-[10px] font-bold px-2 py-1 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition-colors">
                                                    <i class="fas fa-arrow-down mr-1"></i> Gunakan Nama dari KTP
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. BAGIAN IDENTITAS: NAMA SESUAI KTP -->
                        <div class="form-group-spaced">
                            <label for="namaKtp" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Sesuai KTP <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="namaKtp" name="nama_ktp" required
                                    value="<?= esc(old('nama_ktp', '')) ?>"
                                    class="input-pill"
                                    placeholder="Contoh: AHMAD DAHLAN (Sesuai KTP)">
                            </div>
                            <span class="block text-[10px] text-slate-400 mt-1">Pastikan nama lengkap persis seperti yang tertera pada kartu identitas KTP Anda.</span>
                        </div>

                        <!-- 4. BAGIAN IDENTITAS: NOMOR INDUK KEPENDUDUKAN (NIK) -->
                        <div class="form-group-spaced">
                            <label for="nikInput" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nomor Induk Kependudukan (NIK KTP - 16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" id="nikInput" name="nik" required pattern="[0-9]{16}" minlength="16" maxlength="16" inputmode="numeric"
                                    value="<?= esc($verification['nik'] ?? '') ?>"
                                    class="input-pill font-mono tracking-wider"
                                    placeholder="Contoh: 3201012345670001"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)">
                            </div>
                            <span class="block text-[10px] text-slate-400 mt-1">Wajib 16 digit angka sesuai KTP resmi. 1 NIK hanya berlaku untuk 1 akun kemitraan.</span>
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
                                <button type="button" onclick="copyKtpName()" class="text-xs font-bold text-[#6b008a] hover:text-[#55006e] hover:underline inline-flex items-center gap-1 focus:outline-none">
                                    <i class="fas fa-clone text-[10px]"></i> Salin dari Nama KTP
                                </button>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <input type="text" id="namaPemilikRekening" name="nama_pemilik_rekening" required
                                    value="<?= esc($verification['nama_pemilik_rekening'] ?? '') ?>"
                                    class="input-pill"
                                    placeholder="Nama lengkap sesuai KTP">
                            </div>
                            <span id="rekeningOwnerError" class="hidden text-[10px] text-rose-600 font-semibold mt-1 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation"></i> Nama pemilik rekening harus sama persis dengan nama pada KTP.
                            </span>
                            <span class="block text-[10px] text-slate-400 mt-1">Nama pemilik rekening bank wajib sama persis dengan nama identitas KTP Anda.</span>
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
                    &copy; <?= date('Y') ?> PT Amanah Tangguh Mandiri. All Rights Reserved. | Amanah Safar Digital Platform.
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

// Pemeriksaan Kesesuaian Nama Pemilik Rekening dengan Nama KTP
function checkRekeningOwnerMatch() {
    const namaKtpEl = document.getElementById('namaKtp');
    const namaPemilikEl = document.getElementById('namaPemilikRekening');
    const errorEl = document.getElementById('rekeningOwnerError');
    if (!namaKtpEl || !namaPemilikEl) return true;

    const valKtp = namaKtpEl.value.trim().toUpperCase().replace(/\s+/g, ' ');
    const valPemilik = namaPemilikEl.value.trim().toUpperCase().replace(/\s+/g, ' ');

    if (!valPemilik) {
        if (errorEl) errorEl.classList.add('hidden');
        namaPemilikEl.classList.remove('border-rose-500', 'bg-rose-50/40');
        return false;
    }

    if (valKtp && valPemilik && valKtp !== valPemilik) {
        if (errorEl) errorEl.classList.remove('hidden');
        namaPemilikEl.classList.add('border-rose-500', 'bg-rose-50/40');
        return false;
    } else {
        if (errorEl) errorEl.classList.add('hidden');
        namaPemilikEl.classList.remove('border-rose-500', 'bg-rose-50/40');
        return true;
    }
}

// Salin Nama KTP ke Nama Pemilik Rekening
function copyKtpName() {
    const namaKtpInput = document.getElementById('namaKtp');
    const targetInput = document.getElementById('namaPemilikRekening');
    if (namaKtpInput && targetInput) {
        targetInput.value = namaKtpInput.value.trim();
        checkRekeningOwnerMatch();
        refreshSubmitButton();
    }
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
}

function syncBankValue(value) {
    document.getElementById('namaBankFinal').value = value.trim();
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

    if (systemState.isKtpValid && isAgree && isRekeningMatch) {
        submitBtn.removeAttribute('disabled');
        submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
    } else {
        submitBtn.setAttribute('disabled', 'disabled');
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
    }
}

// Analisis Kualitas & Citra Gambar di Canvas
function analyzeImageQuality(img) {
    return new Promise((resolve) => {
        const width = img.naturalWidth || img.width;
        const height = img.naturalHeight || img.height;

        // 1. Validasi Dimensi Minimum
        if (width < 400 || height < 250) {
            return resolve({
                valid: false,
                reason: 'Resolusi gambar KTP terlalu kecil atau tidak jelas. Mohon unggah foto KTP dengan resolusi lebih tinggi.'
            });
        }

        // 2. Validasi Aspek Rasio (Standar KTP 85.6mm x 53.98mm = ~1.58, tolerir rotasi/crop 0.65 - 2.8)
        const ratio = width / height;
        if (ratio < 0.65 || ratio > 2.8) {
            return resolve({
                valid: false,
                reason: 'Proporsi dokumen tidak proporsional untuk kartu identitas KTP. Mohon pastikan foto berorientasi mendatar/landscape.'
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

            // Foto terlalu gelap (underexposed)
            if (avgLum < 30) {
                return resolve({
                    valid: false,
                    reason: 'Foto KTP terlalu gelap atau buram. Pastikan foto diambil dalam pencahayaan yang cukup.'
                });
            }

            // Foto silau / putih total (overexposed / flash glare)
            if (avgLum > 248) {
                return resolve({
                    valid: false,
                    reason: 'Foto KTP terlalu silau atau terdapat pantulan cahaya berlebih. Mohon ambil foto tanpa pantulan cahaya.'
                });
            }

            resolve({ valid: true, canvas: canvas });
        } catch (e) {
            // Jika canvas ter-taint (CORS), lanjutkan tanpa menghentikan proses
            resolve({ valid: true });
        }
    });
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

// Ekstraksi Kata Kunci KTP Indonesia
function checkIndonesianKtpKeywords(rawText) {
    const text = normalizeText(rawText);
    const keywords = [
        'REPUBLIK', 'INDONESIA', 'KARTU', 'TANDA', 'PENDUDUK',
        'NIK', 'PROVINSI', 'KABUPATEN', 'KOTA', 'AGAMA',
        'STATUS', 'PERKAWINAN', 'PEKERJAAN', 'KEWARGANEGARAAN',
        'BERLAKU', 'DARAH', 'ALAMAT', 'TEMPAT', 'LAHIR', 'KELURAHAN', 'KECAMATAN'
    ];

    let matchCount = 0;
    const foundKeywords = [];

    keywords.forEach(kw => {
        if (text.includes(kw)) {
            matchCount++;
            foundKeywords.push(kw);
        }
    });

    // Indikasi kuat KTP: Ada 'NIK' atau kombinasi nama negara/provinsi/tanda penduduk
    const hasCoreAnchor = text.includes('NIK') || (text.includes('PENDUDUK') && text.includes('INDONESIA')) || (text.includes('PROVINSI') && text.includes('KABUPATEN'));
    const isIndonesianKtp = matchCount >= 2 || (hasCoreAnchor && matchCount >= 1);

    return {
        isKtp: isIndonesianKtp,
        matchCount: matchCount,
        foundKeywords: foundKeywords
    };
}

// Ekstraksi 16 Digit NIK dan Nama dari Teks OCR
function extractKtpData(rawText) {
    let nik = null;
    let nama = null;

    // 1. Ekstraksi NIK (Pola 16 Digit Angka)
    // Coba regex langsung
    const rawMatches = rawText.match(/\b([1-9][0-9]{15})\b/g);
    if (rawMatches && rawMatches.length > 0) {
        nik = rawMatches[0];
    } else {
        // Coba cari di baris yang mengandung 'NIK' dan normalisasi OCR typos (misal O -> 0, I -> 1)
        const lines = rawText.split('\n');
        for (let line of lines) {
            if (/NIK/i.test(line)) {
                let candidate = line.replace(/NIK/gi, '')
                    .replace(/[:\-=_]/g, '')
                    .replace(/[oOD]/g, '0')
                    .replace(/[Il|!]/g, '1')
                    .replace(/[Ss]/g, '5')
                    .replace(/[B]/g, '8')
                    .replace(/[^0-9]/g, '');
                if (candidate.length >= 16) {
                    nik = candidate.slice(0, 16);
                    break;
                }
            }
        }
    }

    // 2. Ekstraksi Nama Sesuai KTP
    const lines = rawText.split('\n').map(l => l.trim()).filter(Boolean);
    for (let i = 0; i < lines.length; i++) {
        const lineUpper = lines[i].toUpperCase();
        if (lineUpper.includes('NAMA')) {
            // Ambil teks di sebelah kata NAMA atau di baris berikutnya
            let candidate = lineUpper.replace(/.*NAMA\s*[:\-=]?\s*/i, '').trim();
            // Bersihkan label KTP yang tidak sengaja terbawa
            candidate = candidate.replace(/\b(TEMPAT|LAHIR|GOL|DARAH|ALAMAT)\b.*/i, '').trim();
            if (candidate.length >= 3 && !/^(KTP|NIK|REPUBLIK|INDONESIA|PROVINSI)/i.test(candidate)) {
                nama = candidate;
                break;
            } else if (i + 1 < lines.length) {
                let nextLine = lines[i + 1].toUpperCase().replace(/.*[:\-=]?\s*/, '').trim();
                nextLine = nextLine.replace(/\b(TEMPAT|LAHIR|GOL|DARAH|ALAMAT)\b.*/i, '').trim();
                if (nextLine.length >= 3 && !/^(TEMPAT|LAHIR|GOL|ALAMAT|AGAMA|STATUS)/i.test(nextLine)) {
                    nama = nextLine;
                    break;
                }
            }
        }
    }

    return { nik, nama };
}

// Validasi Kesesuaian Identitas (Input Pengguna vs Ekstraksi KTP)
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

    // Jika tidak ada data yang berhasil diekstrak (KTP blur/terpotong)
    if (!systemState.extractedNik && !systemState.extractedNama) {
        updateStatusBox('warning', 'KTP Terdeteksi Namun Teks Kurang Jelas',
            'KTP terdeteksi namun NIK/Nama tidak dapat dibaca jelas. Pastikan seluruh bagian KTP terlihat penuh tanpa terpotong atau silau.',
            'Teks Kurang Jelas'
        );
        systemState.isKtpValid = false;
        refreshSubmitButton();
        return;
    }

    // 1. Validasi NIK
    let nikMismatch = false;
    if (systemState.extractedNik) {
        if (valNik && valNik !== systemState.extractedNik) {
            nikMismatch = true;
        }
    }

    if (nikMismatch) {
        updateStatusBox('error', 'Kesesuaian NIK Tidak Cocok',
            'NIK yang Anda masukkan tidak sesuai dengan NIK yang tertera pada dokumen KTP.',
            'NIK Tidak Sesuai'
        );
        systemState.isKtpValid = false;
        refreshSubmitButton();
        return;
    }

    // 2. Validasi Nama
    let namaMismatch = false;
    if (systemState.extractedNama && valNama) {
        const normInput = normalizeText(valNama);
        const normExtracted = normalizeText(systemState.extractedNama);

        // Periksa apakah nama input mengandung nama KTP atau sebaliknya
        const isMatch = normInput.includes(normExtracted) || 
                        normExtracted.includes(normInput) ||
                        calculateSimilarity(normInput, normExtracted) >= 0.65;

        if (!isMatch) {
            namaMismatch = true;
        }
    }

    if (namaMismatch) {
        updateStatusBox('warning', 'Nama Perlu Disesuaikan',
            'Nama yang dimasukkan berbeda dengan nama pada dokumen KTP. Mohon sesuaikan.',
            'Nama Tidak Cocok'
        );
        systemState.isKtpValid = false;
        refreshSubmitButton();
        return;
    }

    // Jika NIK belum lengkap 16 digit
    if (valNik.length !== 16) {
        updateStatusBox('warning', 'NIK Belum Lengkap',
            'Masukkan 16 digit NIK lengkap sesuai KTP Anda.',
            'Menunggu NIK'
        );
        systemState.isKtpValid = false;
        refreshSubmitButton();
        return;
    }

    // Semua Verifikasi Sukses
    updateStatusBox('success', 'Dokumen KTP Terverifikasi & Sesuai',
        'Dokumen KTP dan data identitas berhasil diverifikasi dan sesuai.',
        'KTP Sesuai'
    );
    systemState.isKtpValid = true;
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

                const result = await Tesseract.recognize(tempImg, 'eng', {
                    logger: m => {
                        if (m.status === 'recognizing text' && scanId === systemState.currentScanId) {
                            const pct = Math.round(m.progress * 100);
                            const badge = document.getElementById('ktpStatusBadge');
                            if (badge) badge.textContent = `OCR ${pct}%`;
                        }
                    }
                });

                if (scanId !== systemState.currentScanId) return;

                const ocrText = result.data.text || '';

                // Validasi Kata Kunci KTP Indonesia
                const ktpCheck = checkIndonesianKtpKeywords(ocrText);
                if (!ktpCheck.isKtp) {
                    updateStatusBox('error', 'Dokumen Bukan KTP Indonesia',
                        'Dokumen yang diunggah bukan KTP Indonesia. Silakan unggah foto KTP yang benar.',
                        'Bukan KTP'
                    );
                    systemState.isKtpValid = false;
                    refreshSubmitButton();
                    return;
                }

                // Ekstraksi NIK & Nama
                const extracted = extractKtpData(ocrText);
                systemState.extractedNik = extracted.nik;
                systemState.extractedNama = extracted.nama;

                // Tampilkan summary di status box
                const summaryBox = document.getElementById('ktpExtractedSummary');
                const nikText = document.getElementById('extractedNikText');
                const namaText = document.getElementById('extractedNamaText');

                if (summaryBox) summaryBox.classList.remove('hidden');
                if (nikText) nikText.textContent = extracted.nik || '(Tidak terbaca otomatis)';
                if (namaText) namaText.textContent = extracted.nama || '(Tidak terbaca otomatis)';

                // Auto-fill jika form masih kosong atau default
                const nikInput = document.getElementById('nikInput');
                const namaInput = document.getElementById('namaKtp');

                if (extracted.nik && nikInput && (!nikInput.value.trim() || nikInput.value.trim() === '')) {
                    nikInput.value = extracted.nik;
                }

                if (extracted.nama && namaInput && (!namaInput.value.trim() || namaInput.value.trim() === 'Mitra')) {
                    namaInput.value = extracted.nama;
                }

                // Cocokkan data input dengan KTP
                validateIdentityMatch();

            } catch (err) {
                if (scanId !== systemState.currentScanId) return;
                console.error('OCR Error:', err);
                updateStatusBox('warning', 'Pemeriksaan Otomatis Terkendala',
                    'Sistem tidak dapat memindai KTP secara otomatis (' + (err.message || 'Koneksi OCR terputus') + '). Pastikan data yang Anda isi di bawah telah sesuai dengan foto KTP.',
                    'Pemeriksaan Manual'
                );
                // Berikan toleransi jika OCR engine gagal termuat di browser
                systemState.isKtpValid = true;
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
    const btnApplyNik = document.getElementById('btnApplyNik');
    const btnApplyNama = document.getElementById('btnApplyNama');
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

    // 3. Tombol Terapkan NIK & Nama dari KTP
    if (btnApplyNik) {
        btnApplyNik.addEventListener('click', function() {
            if (systemState.extractedNik && nikInput) {
                nikInput.value = systemState.extractedNik;
                validateIdentityMatch();
            }
        });
    }

    if (btnApplyNama) {
        btnApplyNama.addEventListener('click', function() {
            if (systemState.extractedNama && namaInput) {
                namaInput.value = systemState.extractedNama;
                validateIdentityMatch();
            }
        });
    }

    // 4. Real-time Cross Check saat User Mengetik di NIK atau Nama
    if (nikInput) {
        nikInput.addEventListener('input', function() {
            if (systemState.fileSelected) {
                validateIdentityMatch();
            }
        });
    }

    if (namaInput) {
        namaInput.addEventListener('input', function() {
            if (systemState.fileSelected) {
                validateIdentityMatch();
            }
            checkRekeningOwnerMatch();
            refreshSubmitButton();
        });
    }

    const namaPemilikInput = document.getElementById('namaPemilikRekening');
    if (namaPemilikInput) {
        namaPemilikInput.addEventListener('input', function() {
            checkRekeningOwnerMatch();
            refreshSubmitButton();
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
