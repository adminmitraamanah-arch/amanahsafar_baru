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
                    <!-- Logo Resmi Amanah Safar Putih & Identitas -->
                    <div class="mb-8" style="margin-bottom: 2rem;">
                        <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Amanah Safar" class="block h-11 sm:h-12 w-auto object-contain mb-1.5 drop-shadow-sm">
                        <span class="block text-[11px] font-semibold text-[#ca82e6] tracking-wider uppercase">Onboarding Mitra</span>
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
                    <div class="frosted-box rounded-xl p-3.5 space-y-1.5 text-xs text-purple-100 mb-6">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-[#ca82e6]">Akun Mitra Terdaftar:</div>
                        <div class="font-bold text-white flex items-center gap-2">
                            <i class="fas fa-user-circle text-[#ca82e6]"></i> <?= esc($user['nama_lengkap'] ?? 'Mitra') ?>
                        </div>
                        <div class="text-[11px] flex items-center gap-2 panel-feature-text" style="color: #dfb2f1 !important;">
                            <i class="fas fa-envelope text-[#ca82e6]"></i> <?= esc($user['email'] ?? '-') ?>
                        </div>
                        <div class="text-[11px] flex items-center gap-2 panel-feature-text" style="color: #dfb2f1 !important;">
                            <i class="fab fa-whatsapp text-[#ca82e6]"></i> <?= esc($user['no_wa'] ?? '-') ?>
                        </div>
                    </div>

                    <!-- Poin Panduan Dokumen (Ikon Bulat) -->
                    <ul class="space-y-3.5 text-xs">
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <span class="panel-feature-text" style="color: #dfb2f1 !important;">Foto KTP asli terbaca jelas & tidak buram</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="circle-icon-badge">
                                <i class="fas fa-building-columns"></i>
                            </div>
                            <span class="panel-feature-text" style="color: #dfb2f1 !important;">Rekening bank valid atas nama pribadi</span>
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
                    <form method="POST" action="<?= base_url('/verify') ?>" enctype="multipart/form-data" class="mt-5">
                        <?= csrf_field() ?>

                        <!-- Bagian Upload: Upload KTP -->
                        <div class="form-group-spaced">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Dokumen Identitas KTP <span class="text-rose-500">*</span>
                            </label>
                            <div class="upload-dropzone rounded-xl p-4 text-center cursor-pointer group relative"
                                 onclick="document.getElementById('ktpInput').click()">
                                <input type="file" id="ktpInput" name="ktp" accept="image/*" required class="hidden" onchange="previewUpload(this, 'ktpPreview', 'ktpFileName', 'ktpDefaultIcon')">
                                
                                <div id="ktpPreview" class="hidden mb-2">
                                    <img src="" alt="KTP Preview" class="h-20 mx-auto rounded-lg object-cover shadow-sm border border-slate-200">
                                </div>
                                <div id="ktpDefaultIcon" class="w-10 h-10 rounded-xl bg-purple-50 text-[#6b008a] flex items-center justify-center mx-auto mb-2 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-id-card text-base"></i>
                                </div>
                                <span id="ktpFileName" class="block text-xs font-bold text-slate-800">Pilih Foto / Scan KTP</span>
                                <span class="block text-[10px] text-slate-400 mt-0.5">Format JPG, PNG, atau WEBP (Maksimal 5MB)</span>
                            </div>
                        </div>

                        <!-- Bagian Rekening: Nama Bank & Nomor Rekening -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 form-group-spaced">
                            <!-- Nama Bank -->
                            <div>
                                <label for="namaBank" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama Bank <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-building-columns"></i>
                                    </span>
                                    <input type="text" id="namaBank" name="nama_bank" list="bankList" required
                                        class="input-pill"
                                        placeholder="Contoh: BCA, BSI, Mandiri">
                                    <datalist id="bankList">
                                        <option value="BCA (Bank Central Asia)">
                                        <option value="BSI (Bank Syariah Indonesia)">
                                        <option value="Bank Mandiri">
                                        <option value="BRI (Bank Rakyat Indonesia)">
                                        <option value="BNI (Bank Negara Indonesia)">
                                        <option value="Bank Muamalat">
                                        <option value="CIMB Niaga">
                                        <option value="Bank Permata">
                                    </datalist>
                                </div>
                            </div>

                            <!-- Nomor Rekening -->
                            <div>
                                <label for="noRek" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor Rekening <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-credit-card"></i>
                                    </span>
                                    <input type="text" id="noRek" name="nomor_rekening" required pattern="[0-9]{5,25}"
                                        class="input-pill font-mono"
                                        placeholder="Contoh: 1234567890">
                                </div>
                            </div>
                        </div>

                        <!-- Nama Pemilik Rekening -->
                        <div class="form-group-spaced">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="namaPemilikRekening" class="block text-xs font-bold text-slate-700">
                                    Nama Pemilik Rekening <span class="text-rose-500">*</span>
                                </label>
                                <button type="button" onclick="copyUserName()" class="text-xs font-bold text-[#6b008a] hover:text-[#55006e] hover:underline inline-flex items-center gap-1 focus:outline-none">
                                    <i class="fas fa-clone text-[10px]"></i> Salin dari Nama Akun
                                </button>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <input type="text" id="namaPemilikRekening" name="nama_pemilik_rekening" required
                                    class="input-pill"
                                    placeholder="Nama sesuai buku tabungan">
                            </div>
                        </div>

                        <!-- Checkbox Persetujuan Kebenaran Data -->
                        <div class="pt-1 mb-5">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="agree_verification" required class="mt-1 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-600 leading-relaxed">
                                    Saya menyatakan data identitas dan informasi rekening di atas adalah benar, sah, dan milik saya pribadi.
                                </span>
                            </label>
                        </div>

                        <!-- Tombol Utama Verifikasi (Kalimat Asli Sistem: Lanjutkan ke Perjanjian) -->
                        <div class="auth-submit-wrap">
                            <button type="submit" class="btn-brand-primary">
                                <span>Lanjutkan ke Perjanjian</span>
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
function copyUserName() {
    const defaultName = <?= json_encode($user['nama_lengkap'] ?? '') ?>;
    document.getElementById('namaPemilikRekening').value = defaultName;
}

function previewUpload(input, previewContainerId, labelId, defaultIconId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        const container = document.getElementById(previewContainerId);
        const img = container.querySelector('img');
        const label = document.getElementById(labelId);
        const icon = document.getElementById(defaultIconId);

        reader.onload = function(e) {
            img.src = e.target.result;
            container.classList.remove('hidden');
            if (icon) icon.classList.add('hidden');
            label.textContent = file.name;
        };
        reader.readAsDataURL(file);
    }
}
</script>
<?= $this->endSection() ?>
