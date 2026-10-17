<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$isEdit = isset($package);
$includesList = [];
if (!empty($package['includes'])) {
    $decoded = json_decode($package['includes'], true);
    if (is_array($decoded)) {
        $includesList = $decoded;
    }
}
$excludesList = [];
if (!empty($package['excludes'])) {
    $decoded = json_decode($package['excludes'], true);
    if (is_array($decoded)) {
        $excludesList = $decoded;
    }
}
// Default suggestions for new package
if (!$isEdit) {
    $includesList = [
        'Tiket Pesawat PP Internasional',
        'Visa Umroh Resmi',
        'Hotel Mekkah & Madinah Sesuai Paket',
        'Makan 3x Sehari Fullboard Menu Indonesia',
        'Transportasi Bus Eksekutif Ber-AC',
        'Bimbingan Ibadah & Muthawwif Berpengalaman',
        'Handling Bandara & Asuransi Perjalanan',
    ];
    $excludesList = [
        'Pembuatan Paspor',
        'Vaksin Meningitis & Polio',
        'Kelebihan Bagasi (Excess Baggage)',
        'Pengeluaran Pribadi (Laundry, Telepon, dll)',
    ];
}
?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-5xl mx-auto space-y-6">
                
                <!-- Breadcrumb & Header -->
                <div>
                    <a href="<?= base_url('/admin/packages') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#5e0078] transition mb-3">
                        <i class="fas fa-arrow-left text-[11px]"></i> Kembali ke Katalog Paket
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        <?= $isEdit ? 'Edit Paket Perjalanan' : 'Tambah Paket Baru' ?>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi rincian paket, jadwal penerbangan, hotel, harga kamar, kuota seat, serta fasilitas.</p>
                </div>

                <!-- Form Container -->
                <form method="POST" action="<?= base_url($isEdit ? '/admin/packages/' . $package['id'] . '/update' : '/admin/packages/store') ?>" enctype="multipart/form-data" class="space-y-6">
                    <?= csrf_field() ?>

                    <!-- SECTION 1: INFORMASI UTAMA PAKET -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-box-open text-xs"></i>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Informasi Utama Paket</h2>
                                <p class="text-[11px] text-slate-400">Identitas dasar paket, kategori, dan visual flyer.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Nama Paket <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nama" required value="<?= esc($package['nama'] ?? '') ?>" placeholder="Paket Umroh VIP Plus Thaif 12 Hari"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Kategori <span class="text-rose-500">*</span>
                                </label>
                                <select name="kategori" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                    <option value="Umroh" <?= ($package['kategori'] ?? '') === 'Umroh' ? 'selected' : '' ?>>Umroh Reguler</option>
                                    <option value="Umroh Plus" <?= ($package['kategori'] ?? '') === 'Umroh Plus' ? 'selected' : '' ?>>Umroh Plus</option>
                                    <option value="Haji" <?= ($package['kategori'] ?? '') === 'Haji' ? 'selected' : '' ?>>Haji Khusus</option>
                                    <option value="Haji Furoda" <?= ($package['kategori'] ?? '') === 'Haji Furoda' ? 'selected' : '' ?>>Haji Furoda / Mujamalah</option>
                                    <option value="Wisata Halal" <?= ($package['kategori'] ?? '') === 'Wisata Halal' ? 'selected' : '' ?>>Wisata Halal</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Status Paket <span class="text-rose-500">*</span>
                                </label>
                                <select name="status" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                    <option value="aktif" <?= ($package['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif (Tampil di Katalog)</option>
                                    <option value="nonaktif" <?= ($package['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Flyer / Foto Paket
                                </label>
                                <?php if (!empty($package['gambar'])): ?>
                                    <div class="mb-3 flex items-center gap-3 p-3 bg-purple-50/50 border border-purple-100 rounded-xl">
                                        <img src="<?= base_url('uploads/packages/' . esc($package['gambar'])) ?>" alt="Flyer Paket" class="w-16 h-12 object-cover rounded-lg border border-slate-200 shadow-xs">
                                        <div class="min-w-0">
                                            <span class="text-[11px] text-slate-500 block">Flyer Saat Ini:</span>
                                            <span class="text-xs font-semibold text-slate-800 truncate block"><?= esc($package['gambar']) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="gambar" accept="image/*"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-xs sm:text-sm focus:outline-none focus:border-[#5e0078] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-[#5e0078] hover:file:bg-purple-200 cursor-pointer">
                                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maks 2MB). Rasio lanskap 4:3 atau 16:9 disarankan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: JADWAL, PROGRAM & PENERBANGAN -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-plane-departure text-xs"></i>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Jadwal & Penerbangan</h2>
                                <p class="text-[11px] text-slate-400">Waktu keberangkatan, durasi perjalanan hari, serta maskapai penerbangan.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Tanggal Keberangkatan
                                </label>
                                <input type="date" name="tanggal_keberangkatan" value="<?= esc($package['tanggal_keberangkatan'] ?? '') ?>"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Program / Durasi
                                </label>
                                <input type="text" name="program_durasi" value="<?= esc($package['program_durasi'] ?? '') ?>" placeholder="12 Hari (Free Thaif)"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Maskapai Penerbangan
                                </label>
                                <input type="text" name="maskapai" value="<?= esc($package['maskapai'] ?? '') ?>" placeholder="Saudia Airlines / Garuda Indonesia"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: AKOMODASI HOTEL -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-hotel text-xs"></i>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Akomodasi Hotel</h2>
                                <p class="text-[11px] text-slate-400">Hotel penginapan jamaah di Mekkah dan Madinah beserta kelas bintang.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Hotel Mekkah
                                </label>
                                <div class="relative">
                                    <i class="fas fa-kaaba absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" name="hotel_mekkah" value="<?= esc($package['hotel_mekkah'] ?? '') ?>" placeholder="Pullman Zamzam / Setaraf (5★)"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Hotel Madinah
                                </label>
                                <div class="relative">
                                    <i class="fas fa-mosque absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" name="hotel_madinah" value="<?= esc($package['hotel_madinah'] ?? '') ?>" placeholder="Rove Al Madinah / Setaraf (4★)"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: RINCIAN HARGA & SEAT TERSEDIA -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-tags text-xs"></i>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Harga Kamar & Ketersediaan Seat</h2>
                                <p class="text-[11px] text-slate-400">Harga per jamaah sesuai tipe kamar (Quad, Triple, Double) serta kuota kursi.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <!-- Harga Quad -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Harga Quad (4 Orang) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="harga_quad" id="adminHargaQuadInput" required step="1" value="<?= (int)($package['harga_quad'] ?? ($package['harga'] ?? '')) ?>" placeholder="28500000"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Kamar 4 orang (harga dasar)</span>
                            </div>

                            <!-- Harga Triple -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Harga Triple (3 Orang)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="harga_triple" step="1" value="<?= !empty($package['harga_triple']) ? (int)$package['harga_triple'] : '' ?>" placeholder="31000000"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Kamar 3 orang</span>
                            </div>

                            <!-- Harga Double -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Harga Double (2 Orang)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="harga_double" step="1" value="<?= !empty($package['harga_double']) ? (int)$package['harga_double'] : '' ?>" placeholder="34500000"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Kamar 2 orang</span>
                            </div>

                            <!-- Seat Tersedia -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Seat Tersedia
                                </label>
                                <div class="relative">
                                    <i class="fas fa-chair absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="number" name="seat_tersedia" min="0" value="<?= (int)($package['seat_tersedia'] ?? 45) ?>" placeholder="45"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Jumlah kursi yang dibuka</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4.1: ALOKASI KOMISI HIERARKI & DEFAULT UANG MUKA (DP) -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-handshake text-xs"></i>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Alokasi Komisi Hierarki & Default DP</h2>
                                <p class="text-[11px] text-slate-400">Atur jatah komisi per pax untuk setiap peran dalam jaringan dan minimal uang muka standar.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <!-- Komisi Mitra -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Komisi Mitra Langsung <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="komisi_mitra" id="komisiMitraInput" required min="0" step="1" oninput="calculateCommissionPool()"
                                        value="<?= (int)($package['komisi_mitra'] ?? 0) ?>" placeholder="0"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Komisi closing mitra per pax</span>
                            </div>

                            <!-- Komisi Manager -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Komisi Manager Mitra <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="komisi_manager" id="komisiManagerInput" required min="0" step="1" oninput="calculateCommissionPool()"
                                        value="<?= (int)($package['komisi_manager'] ?? 0) ?>" placeholder="0"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Override pembina manager per pax</span>
                            </div>

                            <!-- Komisi Cabang / Override Pusat -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Komisi Cabang / Pusat <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="komisi_cabang_pusat" id="komisiCabangInput" required min="0" step="1" oninput="calculateCommissionPool()"
                                        value="<?= (int)($package['komisi_cabang_pusat'] ?? 0) ?>" placeholder="0"
                                        class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Hak cabang / limpahan pusat</span>
                            </div>

                            <!-- Total Pool Komisi (Auto Calculated) -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Total Pool Komisi Paket
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="total_pool_komisi" id="totalPoolInput" readonly
                                        value="<?= (int)($package['total_pool_komisi'] ?? 0) ?>"
                                        class="w-full pl-10 pr-3 py-2.5 bg-purple-50/70 border border-purple-200 rounded-xl text-xs sm:text-sm font-extrabold text-[#5e0078] focus:outline-none cursor-not-allowed">
                                </div>
                                <span class="text-[10px] text-emerald-600 font-semibold mt-1 block flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Mitra + Manager + Cabang/Pusat
                                </span>
                            </div>
                        </div>

                        <?= $this->include('shared/commission_simulation') ?>

                        <!-- Minimal DP Default -->
                        <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Default Minimal DP Reguler</span>
                                <span class="text-[11px] text-slate-400">Berlaku otomatis jika waktu order di luar periode promo kalender di bawah.</span>
                            </div>
                            <div class="relative w-full sm:w-64">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" name="minimal_dp_default" step="1"
                                    value="<?= (int)($package['minimal_dp_default'] ?? 5000000) ?>" placeholder="5000000"
                                    class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4.2: ATURAN PROMO UANG MUKA BERDASARKAN TANGGAL KALENDER (MODEL A) -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                                    <i class="fas fa-calendar-alt text-xs"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Aturan Promo Uang Muka (DP) Kalender (Model A)</h2>
                                    <p class="text-[11px] text-slate-400">Atur nominal DP berbeda berdasarkan tanggal pemesanan (misal: Bulan 1-3 DP Rp 2 Juta, Bulan 4-5 DP Rp 5 Juta).</p>
                                </div>
                            </div>
                            <button type="button" onclick="addDpRuleRow()" 
                                    class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold border border-amber-200 transition">
                                <i class="fas fa-plus text-[10px]"></i>
                                <span>Tambah Periode Promo DP</span>
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs" id="dpRulesTable">
                                <thead>
                                    <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        <th class="px-3.5 py-2.5">Nama Promo / Keterangan</th>
                                        <th class="px-3.5 py-2.5">Tanggal Mulai</th>
                                        <th class="px-3.5 py-2.5">Tanggal Selesai</th>
                                        <th class="px-3.5 py-2.5">Nominal Min DP per Pax</th>
                                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="dpRulesContainer" class="divide-y divide-slate-100">
                                    <?php if (!empty($dpRules)): ?>
                                        <?php foreach ($dpRules as $rule): ?>
                                            <tr class="dp-rule-row">
                                                <td class="px-3.5 py-2.5">
                                                    <input type="text" name="dp_rule_keterangan[]" value="<?= esc($rule['keterangan'] ?? '') ?>" placeholder="Contoh: Promo Early Bird Jan-Mar"
                                                           class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
                                                </td>
                                                <td class="px-3.5 py-2.5">
                                                    <input type="date" name="dp_rule_tgl_mulai[]" value="<?= esc($rule['tanggal_mulai'] ?? '') ?>" required
                                                           class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
                                                </td>
                                                <td class="px-3.5 py-2.5">
                                                    <input type="date" name="dp_rule_tgl_selesai[]" value="<?= esc($rule['tanggal_selesai'] ?? '') ?>" required
                                                           class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
                                                </td>
                                                <td class="px-3.5 py-2.5">
                                                    <div class="relative">
                                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] font-bold text-slate-400">Rp</span>
                                                        <input type="number" name="dp_rule_nominal[]" step="1" value="<?= (int)($rule['nominal_dp'] ?? '') ?>" required placeholder="2000000"
                                                               class="w-full pl-8 pr-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
                                                    </div>
                                                </td>
                                                <td class="px-3.5 py-2.5 text-right">
                                                    <button type="button" onclick="this.closest('.dp-rule-row').remove()" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus Periode">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div id="dpRulesEmptyHint" class="text-center py-4 text-slate-400 text-xs <?= !empty($dpRules) ? 'hidden' : '' ?>">
                                Belum ada aturan tanggal khusus. Klik "Tambah Periode Promo DP" untuk menambahkan rentang tanggal promo DP.
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: FASILITAS INCLUDE & EXCLUDE (INPUT DINAMIS) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Include -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">Fasilitas Termasuk (Include)</h3>
                                            <p class="text-[10px] text-slate-400">Layanan & fasilitas yang didapatkan jamaah.</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="addIncludeItem()" class="btn-interactive inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition">
                                        <i class="fas fa-plus text-[10px]"></i> Tambah
                                    </button>
                                </div>

                                <div id="includeItemsContainer" class="space-y-2.5 mt-4">
                                    <?php foreach ($includesList as $inc): ?>
                                        <div class="flex items-center gap-2 group transition-all">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-xs">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <input type="text" name="includes[]" value="<?= esc($inc) ?>" placeholder="Contoh: Tiket Pesawat PP"
                                                class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="button" onclick="addIncludeItem()" class="w-full py-2 border-2 border-dashed border-emerald-200 hover:border-emerald-400 text-emerald-700 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 mt-3">
                                <i class="fas fa-plus-circle text-xs"></i> Tambah Item Include
                            </button>
                        </div>

                        <!-- Kolom Exclude -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">Tidak Termasuk (Exclude)</h3>
                                            <p class="text-[10px] text-slate-400">Biaya atau keperluan yang tidak ditanggung.</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="addExcludeItem()" class="btn-interactive inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition">
                                        <i class="fas fa-plus text-[10px]"></i> Tambah
                                    </button>
                                </div>

                                <div id="excludeItemsContainer" class="space-y-2.5 mt-4">
                                    <?php foreach ($excludesList as $exc): ?>
                                        <div class="flex items-center gap-2 group transition-all">
                                            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 text-xs">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <input type="text" name="excludes[]" value="<?= esc($exc) ?>" placeholder="Contoh: Pembuatan Paspor"
                                                class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="button" onclick="addExcludeItem()" class="w-full py-2 border-2 border-dashed border-rose-200 hover:border-rose-400 text-rose-700 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 mt-3">
                                <i class="fas fa-plus-circle text-xs"></i> Tambah Item Exclude
                            </button>
                        </div>
                    </div>

                    <!-- SECTION 6: CATATAN & DESKRIPSI TAMBAHAN -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-align-left text-xs"></i>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Deskripsi & Syarat Ketentuan</h2>
                                <p class="text-[11px] text-slate-400">Informasi tambahan, syarat pendaftaran, atau catatan khusus untuk jamaah dan mitra.</p>
                            </div>
                        </div>

                        <div>
                            <textarea name="deskripsi" rows="3" placeholder="Tuliskan catatan tambahan mengenai persyaratan jamaah, ketentuan pembatalan, atau keunggulan paket lainnya..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"><?= esc($package['deskripsi'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                        <a href="<?= base_url('/admin/packages') ?>" class="btn-interactive w-full sm:w-auto text-center px-6 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs sm:text-sm font-bold transition">
                            Batal
                        </a>
                        <button type="submit" class="btn-interactive w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                            <i class="fas fa-save text-xs"></i>
                            <span><?= $isEdit ? 'Simpan Perubahan Paket' : 'Simpan & Publikasikan Paket' ?></span>
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</div>

<script>
function addIncludeItem(value = '') {
    const container = document.getElementById('includeItemsContainer');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 group transition-all';
    row.innerHTML = `
        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-xs">
            <i class="fas fa-check"></i>
        </div>
        <input type="text" name="includes[]" value="${value.replace(/"/g, '&quot;')}" placeholder="Contoh: Tiket Pesawat PP" 
               class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
        <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    `;
    container.appendChild(row);
    const input = row.querySelector('input');
    if (!value) input.focus();
}

function addExcludeItem(value = '') {
    const container = document.getElementById('excludeItemsContainer');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 group transition-all';
    row.innerHTML = `
        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 text-xs">
            <i class="fas fa-times"></i>
        </div>
        <input type="text" name="excludes[]" value="${value.replace(/"/g, '&quot;')}" placeholder="Contoh: Pembuatan Paspor" 
               class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
        <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    `;
    container.appendChild(row);
    const input = row.querySelector('input');
    if (!value) input.focus();
}

function calculateCommissionPool() {
    const mitraEl = document.getElementById('komisiMitraInput');
    const managerEl = document.getElementById('komisiManagerInput');
    const cabangEl = document.getElementById('komisiCabangInput');
    const poolEl = document.getElementById('totalPoolInput');
    if (!poolEl) return;
    const mitra = mitraEl && mitraEl.value !== '' ? (parseFloat(mitraEl.value) || 0) : 0;
    const manager = managerEl && managerEl.value !== '' ? (parseFloat(managerEl.value) || 0) : 0;
    const cabang = cabangEl && cabangEl.value !== '' ? (parseFloat(cabangEl.value) || 0) : 0;
    const pool = mitra + manager + cabang;
    poolEl.value = pool;
}

document.addEventListener('DOMContentLoaded', function() {
    ['komisiMitraInput', 'komisiManagerInput', 'komisiCabangInput'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.value = '0';
                calculateCommissionPool();
                this.dispatchEvent(new Event('input'));
            }
        });
    });
});

function addDpRuleRow() {
    const container = document.getElementById('dpRulesContainer');
    const emptyHint = document.getElementById('dpRulesEmptyHint');
    if (emptyHint) emptyHint.classList.add('hidden');

    const tr = document.createElement('tr');
    tr.className = 'dp-rule-row';
    tr.innerHTML = `
        <td class="px-3.5 py-2.5">
            <input type="text" name="dp_rule_keterangan[]" placeholder="Contoh: Promo Early Bird Jan-Mar"
                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
        </td>
        <td class="px-3.5 py-2.5">
            <input type="date" name="dp_rule_tgl_mulai[]" required
                   class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
        </td>
        <td class="px-3.5 py-2.5">
            <input type="date" name="dp_rule_tgl_selesai[]" required
                   class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
        </td>
        <td class="px-3.5 py-2.5">
            <div class="relative">
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] font-bold text-slate-400">Rp</span>
                <input type="number" name="dp_rule_nominal[]" step="1" required placeholder="2000000"
                       class="w-full pl-8 pr-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5e0078]">
            </div>
        </td>
        <td class="px-3.5 py-2.5 text-right">
            <button type="button" onclick="this.closest('.dp-rule-row').remove()" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus Periode">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        </td>
    `;
    container.appendChild(tr);
}
</script>
<?= $this->endSection() ?>
