<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$includes = [];
if (!empty($package['includes'])) {
    $decodedInc = json_decode($package['includes'], true);
    $includes = is_array($decodedInc) ? $decodedInc : array_filter(array_map('trim', explode("\n", $package['includes'])));
}

$excludes = [];
if (!empty($package['excludes'])) {
    $decodedExc = json_decode($package['excludes'], true);
    $excludes = is_array($decodedExc) ? $decodedExc : array_filter(array_map('trim', explode("\n", $package['excludes'])));
}

$quadPrice = !empty($package['harga_quad']) ? (float)$package['harga_quad'] : (float)$package['harga'];
$triplePrice = !empty($package['harga_triple']) ? (float)$package['harga_triple'] : null;
$doublePrice = !empty($package['harga_double']) ? (float)$package['harga_double'] : null;
?>

<div class="min-h-screen bg-slate-50 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200/80 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="<?= base_url('/catalog') ?>" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#5e0078] rounded-xl flex items-center justify-center font-bold text-sm text-white shadow-sm">
                        AS
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-base tracking-tight text-slate-900 leading-tight">Amanah Safar</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Detail Paket</span>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('/catalog') ?>" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-[#5e0078] flex items-center gap-1.5 transition">
                        <i class="fas fa-arrow-left text-xs"></i>
                        <span>Kembali ke Katalog</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 flex-1 w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            <a href="<?= base_url('/') ?>" class="hover:text-[#5e0078] transition">Beranda</a>
            <span>/</span>
            <a href="<?= base_url('/catalog') ?>" class="hover:text-[#5e0078] transition">Katalog</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs"><?= esc($package['nama']) ?></span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <!-- Header Image / Banner -->
            <?php if (!empty($package['gambar'])): ?>
                <div class="h-64 sm:h-96 w-full overflow-hidden bg-slate-100 relative">
                    <img src="<?= base_url('uploads/packages/' . esc($package['gambar'])) ?>" alt="<?= esc($package['nama']) ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider">
                                <?= esc($package['kategori'] ?? 'Umroh') ?>
                            </span>
                            <?php if (!empty($package['program_durasi'])): ?>
                                <span class="px-3 py-1 rounded-full bg-[#5e0078] text-white text-xs font-bold uppercase tracking-wider shadow-sm">
                                    <i class="fas fa-clock mr-1"></i> <?= esc($package['program_durasi']) ?>
                                </span>
                            <?php endif; ?>
                            <?php if (isset($package['seat_tersedia']) && $package['seat_tersedia'] !== null): ?>
                                <span class="px-3 py-1 rounded-full bg-emerald-600/90 backdrop-blur-md text-white text-xs font-bold">
                                    <i class="fas fa-chair mr-1"></i> <?= (int)$package['seat_tersedia'] ?> Kursi Tersedia
                                </span>
                            <?php endif; ?>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white"><?= esc($package['nama']) ?></h1>
                    </div>
                </div>
            <?php else: ?>
                <div class="h-56 sm:h-72 w-full bg-gradient-to-br from-[#5e0078] to-[#3a004a] flex flex-col items-center justify-center text-white relative p-6 text-center">
                    <i class="fas fa-kaaba text-5xl sm:text-6xl text-white/80 mb-3"></i>
                    <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                        <span class="px-3 py-0.5 rounded-full bg-white/20 text-white text-xs font-bold uppercase tracking-wider">
                            <?= esc($package['kategori'] ?? 'Umroh') ?>
                        </span>
                        <?php if (!empty($package['program_durasi'])): ?>
                            <span class="px-3 py-0.5 rounded-full bg-white text-[#5e0078] text-xs font-bold uppercase tracking-wider">
                                <?= esc($package['program_durasi']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-1"><?= esc($package['nama']) ?></h1>
                </div>
            <?php endif; ?>

            <div class="p-6 sm:p-8 space-y-8">
                <!-- Flight & Schedule Quick Summary -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-purple-50/50 border border-purple-100">
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keberangkatan</div>
                        <div class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-calendar-alt text-[#5e0078]"></i>
                            <span><?= !empty($package['tanggal_keberangkatan']) ? date('d M Y', strtotime($package['tanggal_keberangkatan'])) : '-' ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Durasi / Program</div>
                        <div class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-hourglass-half text-[#5e0078]"></i>
                            <span><?= esc($package['program_durasi'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Maskapai</div>
                        <div class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-plane text-[#5e0078]"></i>
                            <span><?= esc($package['maskapai'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ketersediaan Kursi</div>
                        <div class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-chair text-emerald-600"></i>
                            <span><?= isset($package['seat_tersedia']) ? (int)$package['seat_tersedia'] . ' Seat' : '-' ?></span>
                        </div>
                    </div>
                </div>

                <!-- Hotel Accommodation -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Akomodasi Hotel</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center shrink-0">
                                <i class="fas fa-hotel text-base"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Hotel Mekkah</span>
                                <span class="font-bold text-slate-900 text-sm"><?= esc($package['hotel_mekkah'] ?? 'Sesuai Standar Bintang Paket') ?></span>
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center shrink-0">
                                <i class="fas fa-hotel text-base"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Hotel Madinah</span>
                                <span class="font-bold text-slate-900 text-sm"><?= esc($package['hotel_madinah'] ?? 'Sesuai Standar Bintang Paket') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Options (Quad, Triple, Double) -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Pilihan Kamar & Biaya Paket</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Quad -->
                        <div class="p-5 rounded-2xl border-2 border-[#5e0078]/20 bg-purple-50/30 flex flex-col justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1">Quad (Sekamar Ber-4)</span>
                                <div class="text-xl sm:text-2xl font-extrabold text-[#5e0078]">
                                    Rp <?= number_format($quadPrice, 0, ',', '.') ?>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-500 mt-2 block font-medium">Harga dasar per jamaah</span>
                        </div>

                        <!-- Triple -->
                        <div class="p-5 rounded-2xl border border-slate-200/80 bg-white flex flex-col justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Triple (Sekamar Ber-3)</span>
                                <div class="text-xl sm:text-2xl font-extrabold text-slate-900">
                                    <?= $triplePrice ? 'Rp ' . number_format($triplePrice, 0, ',', '.') : '-' ?>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 mt-2 block">Upgrade kamar ber-3</span>
                        </div>

                        <!-- Double -->
                        <div class="p-5 rounded-2xl border border-slate-200/80 bg-white flex flex-col justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Double (Sekamar Ber-2)</span>
                                <div class="text-xl sm:text-2xl font-extrabold text-slate-900">
                                    <?= $doublePrice ? 'Rp ' . number_format($doublePrice, 0, ',', '.') : '-' ?>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 mt-2 block">Upgrade kamar ber-2</span>
                        </div>
                    </div>
                </div>

                <!-- Includes & Excludes -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    <!-- Include -->
                    <div class="p-5 rounded-2xl border border-emerald-100 bg-emerald-50/30">
                        <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <span>Sudah Termasuk (Include)</span>
                        </h4>
                        <?php if (!empty($includes)): ?>
                            <ul class="space-y-2 text-xs text-slate-700">
                                <?php foreach ($includes as $inc): ?>
                                    <li class="flex items-start gap-2">
                                        <i class="fas fa-check text-emerald-600 mt-0.5 shrink-0 text-[11px]"></i>
                                        <span><?= esc($inc) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-xs text-slate-400 italic">Informasi fasilitas include belum diatur.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Exclude -->
                    <div class="p-5 rounded-2xl border border-rose-100 bg-rose-50/30">
                        <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-times-circle text-rose-600"></i>
                            <span>Belum Termasuk (Exclude)</span>
                        </h4>
                        <?php if (!empty($excludes)): ?>
                            <ul class="space-y-2 text-xs text-slate-700">
                                <?php foreach ($excludes as $exc): ?>
                                    <li class="flex items-start gap-2">
                                        <i class="fas fa-times text-rose-500 mt-0.5 shrink-0 text-[11px]"></i>
                                        <span><?= esc($exc) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-xs text-slate-400 italic">Informasi biaya exclude belum diatur.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <?php if (!empty($package['deskripsi'])): ?>
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi & Rincian Paket</h3>
                        <div class="text-slate-600 text-xs sm:text-sm leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <?= esc($package['deskripsi']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action CTA -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3 items-center">
                    <?php if (session('logged_in')): ?>
                        <a href="<?= base_url('/orders/create?package_id=' . $package['id']) ?>" 
                           class="btn-interactive w-full sm:flex-1 text-center bg-[#5e0078] hover:bg-[#4c0062] text-white py-3.5 px-6 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Pesan Paket Sekarang</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" 
                           class="btn-interactive w-full sm:flex-1 text-center bg-[#5e0078] hover:bg-[#4c0062] text-white py-3.5 px-6 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login untuk Memesan</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('/catalog') ?>" 
                       class="btn-interactive w-full sm:w-auto text-center px-6 py-3.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs sm:text-sm font-bold transition">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
