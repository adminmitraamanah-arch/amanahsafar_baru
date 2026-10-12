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
        border-radius: 28px;
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
    }
    .auth-right-panel {
        background: #ffffff;
        padding: 2.5rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #0f172a;
    }
    .watermark-dome {
        position: absolute;
        right: -25px;
        bottom: -25px;
        width: 190px;
        height: 190px;
        opacity: 0.12;
        pointer-events-none;
        color: #ffffff;
    }
    .form-group-spaced {
        margin-bottom: 1rem;
    }
    .input-pill {
        width: 100%;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
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
        border-radius: 10px;
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
    .frosted-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #dfb2f1;
    }
    .text-purple-muted {
        color: #9b24c9;
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
        color: #9b24c9;
    }
    @media (max-width: 640px) {
        .auth-left-panel {
            padding: 2.25rem 1.35rem;
        }
        .auth-right-panel {
            padding: 2.25rem 1.35rem;
        }
        .auth-card-container {
            border-radius: 20px;
        }
    }
    .auth-submit-wrap {
        margin-top: 1.5rem;
    }
    .auth-form-bottom-link {
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .auth-card-footer {
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f1f5f9;
        text-align: center;
        font-size: 0.6875rem;
        color: #94a3b8;
    }
    .info-card-amber {
        margin-top: 0.875rem;
        margin-bottom: 0.875rem;
        padding: 0.625rem 0.875rem;
        border-radius: 10px;
        background-color: #fffbeb;
        border: 1px solid #fef08a;
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
    }
    .info-card-amber .info-card-icon {
        width: 1.375rem;
        height: 1.375rem;
        border-radius: 6px;
        background-color: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }
    .info-card-amber .info-card-content {
        flex: 1;
        min-width: 0;
    }
    .info-card-amber .info-card-title {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #92400e;
        line-height: 1.25;
        margin-bottom: 0.15rem;
    }
    .info-card-amber .info-card-desc {
        font-size: 0.6875rem;
        color: #78350f;
        line-height: 1.35;
        margin: 0;
    }
    .info-card-amber .info-card-desc strong {
        color: #451a03;
        font-weight: 700;
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
    .auth-tab-btn.inactive {
        background-color: transparent;
        color: #64748b;
    }
    .auth-tab-btn.inactive:hover {
        background-color: rgba(107, 0, 138, 0.08);
        color: #6b008a;
        transform: translateY(-0.5px);
    }
    .arabic-bismillah-gold {
        font-family: 'Amiri', 'Traditional Arabic', 'Scheherazade New', 'Noto Naskh Arabic', serif;
        font-size: 1.25rem;
        line-height: 1.5;
        font-weight: 700;
        display: inline-block;
        background: linear-gradient(
            120deg,
            #d4af37 0%,
            #f5d77f 25%,
            #fff8db 45%,
            #ffffff 50%,
            #fff8db 55%,
            #f5d77f 75%,
            #b8860b 100%
        );
        background-size: 250% 100%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: goldShimmerSlow 7s ease-in-out infinite;
        filter: drop-shadow(0 2px 10px rgba(212, 175, 55, 0.28));
        margin-bottom: 0.35rem;
    }
    @media (max-width: 640px) {
        .arabic-bismillah-gold {
            font-size: 1.1rem;
        }
    }
    @keyframes goldShimmerSlow {
        0% {
            background-position: 150% 0;
        }
        100% {
            background-position: -150% 0;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="auth-glow-bg p-4 sm:p-6 lg:p-8 relative selection:bg-[#6b008a] selection:text-white">

    <!-- Top Navigation Header (Persis Referensi: Tombol Kiri Pill & Badge Status Kanan) -->
    <header class="w-full max-w-5xl mx-auto flex items-center justify-between py-2 z-10">
        <a href="<?= base_url('/') ?>" class="nav-pill-btn group">
            <i class="fas fa-arrow-left icon-arrow"></i>
            <span>Kembali ke Beranda</span>
        </a>
        <div class="nav-pill-badge">
            <span class="nav-badge-dot"></span>
            <span>Sistem Mitra Amanah</span>
        </div>
    </header>

    <!-- Main Card Container (2 Kolom Presisi Sesuai Referensi) -->
    <main class="w-full max-w-5xl mx-auto z-10 flex-grow flex items-start justify-center pt-2 sm:pt-4">
        <div class="auth-card-container">
            
            <!-- Panel Kiri: Identitas Ungu Amanah Safar -->
            <div class="auth-left-panel">
                <!-- Watermark Ornamen Siluet Kubah -->
                <div class="watermark-dome">
                    <svg viewBox="0 0 200 200" fill="currentColor">
                        <path d="M100 20 C85 50 60 70 60 100 C60 145 78 180 100 200 C122 180 140 145 140 100 C140 70 115 50 100 20 Z" />
                        <path d="M40 120 C40 160 65 190 100 200 C35 190 20 150 20 120 Z" opacity="0.5" />
                        <path d="M160 120 C160 160 135 190 100 200 C165 190 180 150 180 120 Z" opacity="0.5" />
                    </svg>
                </div>
                
                <div class="relative z-10">
                    <!-- Squircle Logo & Nama Perusahaan (Persis Referensi) -->
                    <div class="flex items-center gap-3.5 mb-7">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center font-black text-lg text-[#6b008a] shadow-md shrink-0">
                            AS
                        </div>
                        <div>
                            <span class="block font-black text-lg sm:text-xl tracking-tight leading-tight text-white uppercase">Amanah Safar</span>
                            <span class="block text-[11px] font-semibold text-[#ca82e6] tracking-wider uppercase">Sistem Mitra Amanah</span>
                        </div>
                    </div>

                    <!-- Badge Pill Bintang Emas -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full frosted-badge text-xs font-semibold mb-6">
                        <i class="fas fa-star text-[10px] text-amber-400"></i>
                        <span>Kemitraan Amanah & Profesional</span>
                    </div>

                    <!-- Headline Sambutan (Dua Bagian: Kaligrafi Arab Gold Shimmer & Modern Bold Headline) -->
                    <div class="mb-4">
                        <div class="arabic-bismillah-gold" dir="rtl" lang="ar">
                            بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
                        </div>
                        <h1 class="text-2xl sm:text-[27px] font-black tracking-tight leading-snug text-white mt-1">
                            Bahagia sampai Baitullah.
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-[#9b24c9] leading-relaxed mb-8">
                        Daftarkan akun Mitra Anda dengan kode voucher resmi dari Pembina untuk membuka akses syiar dan bagi hasil yang barakah.
                    </p>

                    <!-- Poin Keunggulan (3 List Item Kotak Ikon Persis Referensi) -->
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg frosted-tile flex items-center justify-center shrink-0 text-[#ca82e6] text-xs">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <span class="text-xs text-[#9b24c9] font-medium">Aktivasi otomatis via Kode Voucher Pembina</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg frosted-tile flex items-center justify-center shrink-0 text-[#ca82e6] text-xs">
                                <i class="fas fa-coins"></i>
                            </div>
                            <span class="text-xs text-[#9b24c9] font-medium">Pencairan ujroh transparan & realtime</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg frosted-tile flex items-center justify-center shrink-0 text-[#ca82e6] text-xs">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <span class="text-xs text-[#9b24c9] font-medium">Verifikasi data & PKS digital berkekuatan hukum</span>
                        </li>
                    </ul>
                </div>

                <!-- Footer Panel Kiri -->
                <div class="left-panel-footer">
                    <span class="text-[#9b24c9]">Butuh Bantuan?</span>
                </div>
            </div>

            <!-- Panel Kanan: Area Formulir Putih Bersih -->
            <div class="auth-right-panel">
                <div>
                    <!-- Form Header & Pill Switcher Tab (Persis Gambar Referensi: Login / Daftar) -->
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar</h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">Bergabunglah dengan jaringan Amanah Safar</p>
                        </div>
                        <div class="auth-tab-switcher">
                            <a href="<?= base_url('/login') ?>" class="auth-tab-btn inactive">
                                Login
                            </a>
                            <span class="auth-tab-btn active">
                                Daftar
                            </span>
                        </div>
                    </div>

                    <!-- Callout Info: Pendaftaran Khusus Mitra (Warna Kuning & Ramping Tanpa Ruang Kosong Berlebih) -->
                    <div class="info-card-amber">
                        <div class="info-card-icon">
                            <i class="fas fa-circle-info"></i>
                        </div>
                        <div class="info-card-content">
                            <strong class="info-card-title">Pendaftaran Ditujukan Khusus untuk Mitra</strong>
                            <p class="info-card-desc">
                                Pastikan Anda memiliki <strong>Kode Voucher</strong> dari Pembina (Pusat / Cabang / Manager) untuk mengaktivasi akun ini.
                            </p>
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

                    <!-- Form Registrasi -->
                    <form method="POST" action="<?= base_url('/register') ?>" class="mt-5">
                        <?= csrf_field() ?>

                        <!-- Kode Voucher -->
                        <div class="form-group-spaced">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="kodeVoucher" class="block text-xs font-bold text-slate-700">
                                    Kode Voucher <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-[#6b008a] font-semibold bg-purple-50 px-2 py-0.5 rounded-md">Wajib Dimiliki</span>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                    <i class="fas fa-ticket-alt"></i>
                                </span>
                                <input type="text" id="kodeVoucher" name="kode_voucher" required autofocus
                                    value="<?= esc(old('kode_voucher') ?? '') ?>"
                                    class="input-pill font-mono font-bold uppercase text-[#6b008a]"
                                    placeholder="Contoh: MIT-PUS-001-0001">
                            </div>
                        </div>

                        <!-- Data Akun: Baris 1 (Nama Lengkap & No. WhatsApp) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 form-group-spaced">
                            <div>
                                <label for="namaLengkap" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                    <input type="text" id="namaLengkap" name="nama_lengkap" required
                                        value="<?= esc(old('nama_lengkap') ?? '') ?>"
                                        class="input-pill"
                                        placeholder="Nama sesuai KTP">
                                </div>
                            </div>
                            <div>
                                <label for="noWa" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    No. WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fab fa-whatsapp"></i>
                                    </span>
                                    <input type="tel" id="noWa" name="no_wa" required
                                        value="<?= esc(old('no_wa') ?? '') ?>"
                                        class="input-pill"
                                        placeholder="081234567890">
                                </div>
                            </div>
                        </div>

                        <!-- Data Akun: Baris 2 (Email & Domisili) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 form-group-spaced">
                            <div>
                                <label for="emailReg" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Email <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="emailReg" name="email" required
                                        value="<?= esc(old('email') ?? '') ?>"
                                        class="input-pill"
                                        placeholder="mitra@email.com">
                                </div>
                            </div>
                            <div>
                                <label for="domisili" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Domisili <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" id="domisili" name="domisili" required
                                        value="<?= esc(old('domisili') ?? '') ?>"
                                        class="input-pill"
                                        placeholder="Kota Domisili">
                                </div>
                            </div>
                        </div>

                        <!-- Kata Sandi & Konfirmasi Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 form-group-spaced">
                            <div>
                                <label for="passReg" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Password (min. 8 karakter) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="passReg" name="password" required minlength="8"
                                        class="input-pill pr-10"
                                        placeholder="Min. 8 karakter">
                                    <button type="button" onclick="togglePass('passReg', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="passConf" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Konfirmasi Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                        <i class="fas fa-shield-alt"></i>
                                    </span>
                                    <input type="password" id="passConf" name="confirm_password" required minlength="8"
                                        class="input-pill pr-10"
                                        placeholder="Ulangi password">
                                    <button type="button" onclick="togglePass('passConf', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox Ketentuan -->
                        <div class="pt-1 mb-5">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="agree_terms" required class="mt-1 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-600 leading-relaxed">
                                    Saya menyatakan data yang diisi adalah benar dan menyetujui seluruh ketentuan kemitraan Amanah Safar.
                                </span>
                            </label>
                        </div>

                        <!-- Tombol Utama Register (Kalimat Asli Sistem: Daftar) -->
                        <div class="auth-submit-wrap">
                            <button type="submit" class="btn-brand-primary">
                                <span>Daftar</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Link Teks Bawah (Kalimat Asli Sistem: Sudah punya akun? Login di sini) -->
                    <div class="auth-form-bottom-link">
                        <p class="text-xs text-slate-500">
                            Sudah punya akun?
                            <a href="<?= base_url('/login') ?>" class="text-[#6b008a] font-bold hover:text-[#55006e] hover:underline ml-1">
                                Login di sini
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer Kartu -->
                <div class="auth-card-footer">
                    © <?= date('Y') ?> PT Amanah Tangguh Mandiri • Dilindungi Sistem Keamanan Terpadu
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
function togglePass(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash text-xs text-[#6b008a]';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye text-xs text-slate-400';
    }
}
</script>
<?= $this->endSection() ?>
