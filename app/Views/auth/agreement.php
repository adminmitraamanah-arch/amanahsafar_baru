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
                    <!-- Squircle Logo & Nama Perusahaan -->
                    <div class="flex items-center gap-3.5 mb-7">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center font-black text-lg text-[#6b008a] shadow-md shrink-0">
                            AS
                        </div>
                        <div>
                            <span class="block font-black text-lg sm:text-xl tracking-tight leading-tight text-white uppercase">Amanah Safar</span>
                            <span class="block text-[11px] font-semibold text-[#ca82e6] tracking-wider uppercase">Onboarding Mitra</span>
                        </div>
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
                                <i class="fas fa-signature"></i>
                            </div>
                            <span class="panel-feature-text" style="color: #dfb2f1 !important;">Tanda tangan elektronik sah & tersimpan permanen</span>
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

                    <!-- Ringkasan Para Pihak -->
                    <div class="mt-4 bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Para Pihak yang Bersepakat:</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700">
                            <div class="border-l-2 border-[#6b008a] pl-2.5">
                                <span class="font-bold text-slate-900 block text-[11px]">PIHAK PERTAMA</span>
                                <span class="text-xs">PT Amanah Tangguh Mandiri</span>
                            </div>
                            <div class="border-l-2 border-amber-500 pl-2.5">
                                <span class="font-bold text-slate-900 block text-[11px]">PIHAK KEDUA (MITRA)</span>
                                <span class="text-xs font-semibold text-slate-800"><?= esc($user['nama_lengkap'] ?? 'Mitra Terdaftar') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Scrollable Konten Perjanjian (Mempertahankan Seluruh Pasal Asli Sistem) -->
                    <div class="mt-4 bg-slate-50/70 border border-slate-200 rounded-xl p-4 max-h-48 overflow-y-auto text-xs leading-relaxed text-slate-700 space-y-3.5 shadow-inner">
                        <div class="text-center pb-2 border-b border-slate-200">
                            <strong class="text-xs text-slate-900 block font-black uppercase">PERJANJIAN KERJA SAMA KEMITRAAN (PKS)</strong>
                            <span class="text-[10px] text-slate-500">Nomor: PKS/AS/<?= date('Ym') ?>/<?= str_pad((string)($user['id'] ?? 1), 4, '0', STR_PAD_LEFT) ?></span>
                        </div>

                        <p>
                            Pada hari ini, <strong><?= date('d F Y') ?></strong>, telah dibuat dan disepakati Perjanjian Kerja Sama antara <strong>PT Amanah Tangguh Mandiri</strong> (selanjutnya disebut "Pihak Pertama") dan <strong><?= esc($user['nama_lengkap'] ?? 'Mitra') ?></strong> (selanjutnya disebut "Pihak Kedua"), dengan kesepakatan sebagai berikut:
                        </p>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 1: KETENTUAN UMUM DAN RUANG LINGKUP</strong>
                            <p class="mt-0.5">Perjanjian ini mengatur kerja sama kemitraan syiar, pemasaran, dan pendaftaran jamaah Umrah dan Wisata Halal melalui sistem portal digital Amanah Safar.</p>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 2: HAK DAN KEWAJIBAN PIHAK PERTAMA</strong>
                            <p class="mt-0.5">Pihak Pertama berkewajiban menyediakan fasilitas sistem booking, bimbingan berkala, materi promosi, kepastian kuota paket ibadah, serta membayarkan hak ujroh bagi hasil sesuai skema komisi yang berlaku.</p>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 3: HAK DAN KEWAJIBAN PIHAK KEDUA (MITRA)</strong>
                            <p class="mt-0.5">Pihak Kedua berhak atas ujroh transaksi dan materi pemasaran resmi, serta berkewajiban menyampaikan informasi paket secara jujur, menjaga nama baik Amanah Safar, dan mematuhi kode etik kemitraan.</p>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 4: PENETAPAN HARGA DAN PENCAIRAN UJROH</strong>
                            <p class="mt-0.5">Harga paket ibadah merujuk pada katalog resmi perusahaan. Ujroh kemitraan dibayarkan melalui transfer ke rekening bank terverifikasi milik Mitra setelah transaksi terkonfirmasi sah.</p>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 5: JANGKA WAKTU PERJANJIAN</strong>
                            <p class="mt-0.5">Perjanjian ini berlaku efektif terhitung sejak tanggal persetujuan elektronik ini diberikan hingga 1 (satu) tahun ke depan dan dapat diperpanjang secara otomatis atas evaluasi kinerja bersama.</p>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 6: KERAHASIAAN DAN PERLINDUNGAN DATA</strong>
                            <p class="mt-0.5">Pihak Kedua wajib menjaga kerahasiaan data pribadi calon jamaah dan informasi operasional internal perusahaan dari pihak ketiga yang tidak berwenang.</p>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 7: SYARAT DAN KETENTUAN ANGGOTA JARINGAN</strong>
                            <ul class="list-disc list-inside space-y-1 mt-1 pl-1">
                                <li>Setiap mitra kemitraan harus terdaftar resmi dan memiliki akun aktif.</li>
                                <li>Komisi dan insentif dibayarkan sesuai skema resmi yang disetujui perusahaan.</li>
                                <li>Mitra dilarang memungut biaya di luar harga paket yang telah ditetapkan resmi oleh perusahaan.</li>
                                <li>Perusahaan berhak menonaktifkan akun mitra apabila terbukti melakukan pelanggaran hukum atau penipuan.</li>
                            </ul>
                        </div>

                        <div>
                            <strong class="text-slate-900 block font-bold">PASAL 8: PENYELESAIAN SENGKETA DAN PENUTUP</strong>
                            <p class="mt-0.5">Segala perselisihan yang timbul akan diselesaikan secara musyawarah mufakat. Perjanjian ini dinyatakan sah dan mengikat secara hukum sejak disetujui secara digital.</p>
                        </div>
                    </div>

                    <!-- Form Persetujuan & Tanda Tangan -->
                    <form method="POST" action="<?= base_url('/agreement') ?>" class="mt-5">
                        <?= csrf_field() ?>

                        <!-- Mekanisme Tanda Tangan Digital (Canvas Asli Sistem) -->
                        <div class="form-group-spaced">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Tanda Tangan Digital <span class="text-rose-500">*</span>
                                </label>
                                <button type="button" onclick="clearSignature()" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline inline-flex items-center gap-1 focus:outline-none">
                                    <i class="fas fa-rotate-left text-[10px]"></i> Hapus / Ulangi
                                </button>
                            </div>
                            
                            <div class="relative bg-slate-50 border border-slate-200 rounded-xl p-2.5 group hover:border-[#6b008a] transition">
                                <canvas id="signaturePad" class="w-full h-28 rounded-lg bg-white border border-dashed border-slate-200 cursor-crosshair touch-none"></canvas>
                                
                                <div id="signaturePlaceholder" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-slate-400 text-xs gap-1">
                                    <i class="fas fa-pen-nib text-sm text-slate-300"></i>
                                    <span>Tanda tangani di area ini (Gunakan mouse atau layar sentuh)</span>
                                </div>

                                <div class="flex items-center justify-between mt-2 px-1 text-[11px] text-slate-500">
                                    <span class="flex items-center gap-1 font-semibold text-slate-700">
                                        <i class="fas fa-user-check text-[#6b008a]"></i> <?= esc($user['nama_lengkap'] ?? 'Mitra') ?>
                                    </span>
                                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                                        <i class="fas fa-shield-halved text-[10px]"></i> Tanda Tangan Sah Terverifikasi
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox Persetujuan (Kalimat Asli Sistem) -->
                        <div class="pt-1 mb-5">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="agree" required class="mt-1 w-4 h-4 rounded border-slate-300 text-[#6b008a] focus:ring-[#6b008a]">
                                <span class="text-xs text-slate-700 leading-relaxed">
                                    Saya telah membaca dan menyetujui semua Syarat dan Ketentuan yang berlaku.
                                </span>
                            </label>
                        </div>

                        <!-- Tombol Submit (Kalimat Asli Sistem: Setuju dan Lanjutkan) -->
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
// Interactive Signature Pad
const canvas = document.getElementById('signaturePad');
const ctx = canvas.getContext('2d');
const placeholder = document.getElementById('signaturePlaceholder');
let isDrawing = false;
let hasSigned = false;

function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    ctx.scale(ratio, ratio);
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#1e1b4b';
}

window.addEventListener('resize', resizeCanvas);
setTimeout(resizeCanvas, 50);

function startDrawing(e) {
    isDrawing = true;
    placeholder.classList.add('hidden');
    draw(e);
}

function stopDrawing() {
    isDrawing = false;
    ctx.beginPath();
}

function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return {
        x: clientX - rect.left,
        y: clientY - rect.top
    };
}

function draw(e) {
    if (!isDrawing) return;
    hasSigned = true;
    const pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
}

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
window.addEventListener('mouseup', stopDrawing);

canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    startDrawing(e);
}, { passive: false });
canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    draw(e);
}, { passive: false });
window.addEventListener('touchend', stopDrawing);

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.beginPath();
    hasSigned = false;
    placeholder.classList.remove('hidden');
}
</script>
<?= $this->endSection() ?>
