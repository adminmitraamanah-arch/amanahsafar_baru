<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50/40 to-slate-100 flex flex-col justify-between select-none">
    
    <!-- Top Header Navigation -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-3 group">
            <img src="<?= base_url('assets/images/logo-purple.png') ?>" alt="Amanah Safar" class="h-9 sm:h-10 w-auto object-contain transition group-hover:scale-105" onerror="this.onerror=null; this.classList.add('hidden'); document.getElementById('textLogo').classList.remove('hidden');">
            <div id="textLogo" class="hidden flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-[#5e0078] text-white flex items-center justify-center font-bold text-lg shadow-md">AS</div>
                <span class="font-extrabold text-xl tracking-tight text-slate-900">Amanah Safar</span>
            </div>
        </a>

        <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-[#5e0078] transition px-3 py-1.5 rounded-lg hover:bg-white/80">
            <i class="fas fa-home"></i> Beranda Utama
        </a>
    </header>

    <!-- Main Maintenance Content Card -->
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-xl bg-white/95 backdrop-blur-md rounded-2xl shadow-xl shadow-purple-900/5 border border-purple-100/80 p-6 sm:p-10 text-center relative overflow-hidden">
            
            <!-- Decorative Accent Top Bar -->
            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-[#5e0078] via-purple-500 to-amber-500"></div>

            <!-- Animated Badge & Icon -->
            <div class="relative mx-auto w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center text-[#5e0078] mb-6 shadow-inner border border-purple-200/60">
                <i class="fas fa-tools text-3xl sm:text-4xl animate-bounce"></i>
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                </span>
            </div>

            <!-- Title & Status -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-3">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Mode Pemeliharaan Portal</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                <?= esc($title ?? 'Portal Sedang Dalam Pemeliharaan') ?>
            </h1>

            <!-- Message Body -->
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                <?= nl2br(esc($message ?? 'Layanan pendaftaran baru dan dashboard kemitraan sedang ditingkatkan untuk kenyamanan Anda. Kami akan segera kembali beroperasi secara normal.')) ?>
            </p>

            <!-- Estimated Time Card if available -->
            <?php if (!empty($estimated_end)): ?>
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 mb-6 flex items-center justify-center gap-2.5 text-xs sm:text-sm text-slate-700">
                <i class="far fa-clock text-[#5e0078] text-base"></i>
                <span>Estimasi Selesai: <strong class="text-slate-900 font-semibold"><?= esc($estimated_end) ?></strong></span>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a href="<?= base_url('/') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>

                <?php 
                    $waNumber = $whatsapp ?? '6281234567890';
                    $waClean = preg_replace('/[^0-9]/', '', $waNumber);
                    $waUrl = !empty($whatsapp_cs_url) ? $whatsapp_cs_url : "https://wa.me/{$waClean}?text=" . urlencode("Halo Admin Amanah Safar, saya ingin bertanya terkait portal kemitraan.");
                ?>
                <a href="<?= $waUrl ?>" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition">
                    <i class="fab fa-whatsapp text-base"></i> Hubungi WhatsApp CS
                </a>
            </div>

            <!-- Discreet SuperAdmin Login Link -->
            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-center text-xs text-slate-400">
                <span>Pengelola Sistem? </span>
                <a href="<?= base_url('/login?admin=1') ?>" class="ml-1 text-[#5e0078] hover:text-purple-900 font-semibold hover:underline">
                    Masuk Super Admin
                </a>
            </div>

        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="w-full max-w-6xl mx-auto px-4 py-4 text-center text-xs text-slate-400">
        &copy; <?= date('Y') ?> Amanah Safar. Seluruh hak cipta dilindungi.
    </footer>

</div>
<?= $this->endSection() ?>
