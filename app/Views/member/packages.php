<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Paket</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            <?php if (in_array($userRole, ['mitra', 'manager_mitra', 'cabang'])): ?>
                                Pilih dan pesan paket perjalanan umroh & haji resmi untuk jamaah Anda.
                            <?php else: ?>
                                Katalog paket perjalanan umroh & haji resmi Amanah Safar.
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Role Badge Indicator -->
                    <div class="flex items-center gap-2">
                        <?php if (in_array($userRole, ['mitra', 'manager_mitra', 'cabang'])): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] border border-purple-200 text-xs font-bold">
                                <i class="fas fa-shopping-bag text-xs"></i> Mode Pemesanan Paket
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold">
                                <i class="fas fa-book-open text-xs"></i> Mode Referensi (Read-Only)
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Toolbar (Search, Filter, Sort, Per-Page) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col md:flex-row items-center justify-between gap-3">
                    <div class="relative w-full md:w-80">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="memberPackagesSearchInput" placeholder="Cari nama paket, kategori..." 
                               class="w-full pl-9 pr-12 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] focus:bg-white transition">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                        <!-- View Mode Toggle (Card / List) -->
                        <div class="inline-flex items-center p-1 bg-slate-100 border border-slate-200/90 rounded-xl" id="memberPackageViewToggleGroup" role="group" aria-label="Tampilan">
                            <button type="button" id="memberCardViewBtn" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition bg-white text-[#5e0078] shadow-xs" title="Tampilan Kartu">
                                <i class="fas fa-th-large text-xs"></i>
                                <span class="hidden sm:inline">Grid</span>
                            </button>
                            <button type="button" id="memberListViewBtn" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition text-slate-500 hover:text-slate-800" title="Tampilan Daftar">
                                <i class="fas fa-list text-xs"></i>
                                <span class="hidden sm:inline">List</span>
                            </button>
                        </div>

                        <!-- Sort By -->
                        <select id="memberPackagesSortSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="nama_asc">Urutkan: Nama (A - Z)</option>
                            <option value="nama_desc">Urutkan: Nama (Z - A)</option>
                            <option value="harga_asc">Urutkan: Harga (Terendah)</option>
                            <option value="harga_desc">Urutkan: Harga (Tertinggi)</option>
                            <option value="kategori_asc">Urutkan: Kategori (A - Z)</option>
                            <option value="kategori_desc">Urutkan: Kategori (Z - A)</option>
                        </select>

                        <!-- Filter Kategori -->
                        <select id="memberPackagesCategoryFilter" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="all">Semua Kategori</option>
                            <option value="umroh">Umroh</option>
                            <option value="haji">Haji</option>
                        </select>

                        <!-- Per Page Size -->
                        <select id="memberPackagesPageSizeSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="10">10 / halaman</option>
                            <option value="25">25 / halaman</option>
                            <option value="50">50 / halaman</option>
                            <option value="100">100 / halaman</option>
                        </select>

                        <!-- Reset Button -->
                        <button type="button" id="resetMemberPackagesFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition text-xs" title="Reset Filter">
                            <i class="fas fa-redo-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Package Grid (Card View) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="memberPackagesGridContainer">
                    <div id="emptyMemberPackageCard" class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs <?= empty($packages) ? '' : 'hidden' ?>">
                        <i class="fas fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                        <p>Tidak ada paket umroh yang sesuai dengan filter atau kata kunci.</p>
                    </div>

                    <?php if (!empty($packages)): ?>
                        <?php foreach ($packages as $pkg): ?>
                            <?php
                            $pkgDetailJson = htmlspecialchars(json_encode([
                                'id' => $pkg['id'],
                                'nama' => $pkg['nama'],
                                'kategori' => $pkg['kategori'] ?? 'Umroh',
                                'program_durasi' => $pkg['program_durasi'] ?? '',
                                'tanggal_keberangkatan' => !empty($pkg['tanggal_keberangkatan']) ? date('d M Y', strtotime($pkg['tanggal_keberangkatan'])) : '',
                                'maskapai' => $pkg['maskapai'] ?? '',
                                'hotel_mekkah' => $pkg['hotel_mekkah'] ?? '',
                                'hotel_madinah' => $pkg['hotel_madinah'] ?? '',
                                'harga' => (float)$pkg['harga'],
                                'harga_fmt' => 'Rp ' . number_format($pkg['harga'], 0, ',', '.'),
                                'harga_quad' => !empty($pkg['harga_quad']) ? 'Rp ' . number_format($pkg['harga_quad'], 0, ',', '.') : ('Rp ' . number_format($pkg['harga'], 0, ',', '.')),
                                'harga_triple' => !empty($pkg['harga_triple']) ? 'Rp ' . number_format($pkg['harga_triple'], 0, ',', '.') : '-',
                                'harga_double' => !empty($pkg['harga_double']) ? 'Rp ' . number_format($pkg['harga_double'], 0, ',', '.') : '-',
                                'seat_tersedia' => (int)($pkg['seat_tersedia'] ?? 0),
                                'includes' => !empty($pkg['includes']) ? (json_decode($pkg['includes'], true) ?: []) : [],
                                'excludes' => !empty($pkg['excludes']) ? (json_decode($pkg['excludes'], true) ?: []) : [],
                                'deskripsi' => $pkg['deskripsi'] ?? '',
                                'gambar' => !empty($pkg['gambar']) ? base_url('uploads/packages/' . esc($pkg['gambar'])) : null
                            ]), ENT_QUOTES, 'UTF-8');
                            ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition duration-200 group package-card-item"
                                 data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                                 data-harga="<?= (int)$pkg['harga'] ?>"
                                 data-kategori="<?= strtolower(esc($pkg['kategori'] ?? 'umroh')) ?>"
                                 data-search="<?= strtolower(esc($pkg['nama'] . ' ' . ($pkg['kategori'] ?? '') . ' ' . ($pkg['program_durasi'] ?? '') . ' ' . ($pkg['maskapai'] ?? '') . ' ' . ($pkg['hotel_mekkah'] ?? '') . ' ' . ($pkg['deskripsi'] ?? '') . ' ' . $pkg['harga'])) ?>">
                                <?php if (!empty($pkg['gambar'])): ?>
                                    <div class="h-48 w-full overflow-hidden bg-slate-100 relative">
                                        <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute top-3 left-3 flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2.5 py-1 rounded-lg bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                                <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                            </span>
                                            <?php if (!empty($pkg['program_durasi'])): ?>
                                                <span class="px-2.5 py-1 rounded-lg bg-[#5e0078]/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                                    <?= esc($pkg['program_durasi']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="h-48 w-full bg-[#5e0078] flex flex-col items-center justify-center text-white relative">
                                        <i class="fas fa-kaaba text-4xl opacity-80 mb-1"></i>
                                        <span class="text-xs font-semibold opacity-90 tracking-wider uppercase"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                        <?php if (!empty($pkg['program_durasi'])): ?>
                                            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full mt-1 font-bold"><?= esc($pkg['program_durasi']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                            <?php if (!empty($pkg['program_durasi'])): ?>
                                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md"><?= esc($pkg['program_durasi']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 mb-1.5 leading-snug group-hover:text-[#5e0078] transition-colors"><?= esc($pkg['nama']) ?></h3>
                                        
                                        <!-- Key Highlights -->
                                        <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-500 my-2">
                                            <?php if (!empty($pkg['tanggal_keberangkatan'])): ?>
                                                <span class="inline-flex items-center gap-1" title="Tanggal Keberangkatan">
                                                    <i class="fas fa-calendar-day text-[#5e0078] text-[10px]"></i> <?= date('d M Y', strtotime($pkg['tanggal_keberangkatan'])) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($pkg['maskapai'])): ?>
                                                <span class="inline-flex items-center gap-1" title="Maskapai Penerbangan">
                                                    <i class="fas fa-plane text-[#5e0078] text-[10px]"></i> <?= esc($pkg['maskapai']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed"><?= esc($pkg['deskripsi'] ?? 'Paket perjalanan resmi amanah safar dengan akomodasi dan bimbingan ibadah lengkap.') ?></p>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                        <div>
                                            <span class="text-[10px] text-slate-400 block font-medium">Mulai dari (Quad)</span>
                                            <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format($pkg['harga_quad'] ?? $pkg['harga'], 0, ',', '.') ?></span>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <?= !empty($pkg['seat_tersedia']) ? ((int)$pkg['seat_tersedia'] . ' Seat') : 'Tersedia' ?>
                                        </span>
                                    </div>

                                    <div class="flex gap-2 pt-1">
                                        <!-- Detail Button (All Roles) -->
                                        <button type="button" 
                                                onclick="openDetailModal(<?= $pkgDetailJson ?>)"
                                                class="btn-interactive flex-1 text-center bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-3 py-2.5 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                        </button>

                                        <!-- Order Button (For Mitra, Manager Mitra, and Cabang) -->
                                        <?php if (in_array($userRole, ['mitra', 'manager_mitra', 'cabang'])): ?>
                                            <button type="button" 
                                                    onclick="openOrderModal(<?= htmlspecialchars(json_encode([
                                                        'id' => $pkg['id'],
                                                        'nama' => $pkg['nama'],
                                                        'harga' => (float)$pkg['harga'],
                                                        'harga_fmt' => 'Rp ' . number_format($pkg['harga'], 0, ',', '.')
                                                    ]), ENT_QUOTES, 'UTF-8') ?>)"
                                                    class="btn-interactive flex-1 text-center bg-[#5e0078] hover:bg-[#4c0062] text-white px-3 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                                                <i class="fas fa-shopping-cart mr-1"></i> Pesan
                                            </button>
                                        <?php else: ?>
                                            <span class="flex-1 inline-flex items-center justify-center text-center bg-slate-100 text-slate-400 px-3 py-2.5 rounded-xl text-xs font-semibold cursor-not-allowed border border-slate-200" title="Pemesanan paket dibuka untuk akun Mitra, Manager Mitra, dan Cabang">
                                                <i class="fas fa-lock mr-1 text-[10px]"></i> Mode Referensi
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Package List View Container -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden hidden" id="memberPackagesListContainer">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-4 sm:px-6">Paket</th>
                                    <th class="py-3.5 px-4">Kategori & Durasi</th>
                                    <th class="py-3.5 px-4">Keberangkatan</th>
                                    <th class="py-3.5 px-4">Biaya Mulai</th>
                                    <th class="py-3.5 px-4">Ketersediaan</th>
                                    <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs" id="memberPackagesListTableBody">
                                <tr id="emptyMemberPackageListRow" class="<?= empty($packages) ? '' : 'hidden' ?>">
                                    <td colspan="6" class="p-12 text-center text-slate-400">
                                        <i class="fas fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                                        <p>Tidak ada paket umroh yang sesuai dengan filter atau kata kunci.</p>
                                    </td>
                                </tr>

                                <?php if (!empty($packages)): ?>
                                    <?php foreach ($packages as $pkg): ?>
                                        <?php
                                        $pkgDetailJson = htmlspecialchars(json_encode([
                                            'id' => $pkg['id'],
                                            'nama' => $pkg['nama'],
                                            'kategori' => $pkg['kategori'] ?? 'Umroh',
                                            'program_durasi' => $pkg['program_durasi'] ?? '',
                                            'tanggal_keberangkatan' => !empty($pkg['tanggal_keberangkatan']) ? date('d M Y', strtotime($pkg['tanggal_keberangkatan'])) : '',
                                            'maskapai' => $pkg['maskapai'] ?? '',
                                            'hotel_mekkah' => $pkg['hotel_mekkah'] ?? '',
                                            'hotel_madinah' => $pkg['hotel_madinah'] ?? '',
                                            'harga' => (float)$pkg['harga'],
                                            'harga_fmt' => 'Rp ' . number_format($pkg['harga'], 0, ',', '.'),
                                            'harga_quad' => !empty($pkg['harga_quad']) ? 'Rp ' . number_format($pkg['harga_quad'], 0, ',', '.') : ('Rp ' . number_format($pkg['harga'], 0, ',', '.')),
                                            'harga_triple' => !empty($pkg['harga_triple']) ? 'Rp ' . number_format($pkg['harga_triple'], 0, ',', '.') : '-',
                                            'harga_double' => !empty($pkg['harga_double']) ? 'Rp ' . number_format($pkg['harga_double'], 0, ',', '.') : '-',
                                            'seat_tersedia' => (int)($pkg['seat_tersedia'] ?? 0),
                                            'includes' => !empty($pkg['includes']) ? (json_decode($pkg['includes'], true) ?: []) : [],
                                            'excludes' => !empty($pkg['excludes']) ? (json_decode($pkg['excludes'], true) ?: []) : [],
                                            'deskripsi' => $pkg['deskripsi'] ?? '',
                                            'gambar' => !empty($pkg['gambar']) ? base_url('uploads/packages/' . esc($pkg['gambar'])) : null
                                        ]), ENT_QUOTES, 'UTF-8');
                                        ?>
                                        <tr class="hover:bg-slate-50/70 transition duration-150 package-list-row"
                                            data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                                            data-harga="<?= (int)$pkg['harga'] ?>"
                                            data-kategori="<?= strtolower(esc($pkg['kategori'] ?? 'umroh')) ?>"
                                            data-search="<?= strtolower(esc($pkg['nama'] . ' ' . ($pkg['kategori'] ?? '') . ' ' . ($pkg['program_durasi'] ?? '') . ' ' . ($pkg['maskapai'] ?? '') . ' ' . ($pkg['hotel_mekkah'] ?? '') . ' ' . ($pkg['deskripsi'] ?? '') . ' ' . $pkg['harga'])) ?>">
                                            <td class="py-3.5 px-4 sm:px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 bg-slate-100 border border-slate-100">
                                                        <?php if (!empty($pkg['gambar'])): ?>
                                                            <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <div class="w-full h-full bg-[#5e0078] flex items-center justify-center text-white">
                                                                <i class="fas fa-kaaba text-sm opacity-80"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="min-w-0 max-w-xs sm:max-w-md">
                                                        <div class="font-bold text-slate-900 truncate"><?= esc($pkg['nama']) ?></div>
                                                        <div class="text-[11px] text-slate-500 truncate mt-0.5 flex items-center gap-2">
                                                            <?php if (!empty($pkg['maskapai'])): ?>
                                                                <span><i class="fas fa-plane text-slate-400 text-[10px]"></i> <?= esc($pkg['maskapai']) ?></span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($pkg['hotel_mekkah'])): ?>
                                                                <span><i class="fas fa-hotel text-slate-400 text-[10px]"></i> <?= esc($pkg['hotel_mekkah']) ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <div class="flex flex-col gap-1 items-start">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-[#5e0078] border border-purple-200/60 uppercase tracking-wide">
                                                        <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                                    </span>
                                                    <?php if (!empty($pkg['program_durasi'])): ?>
                                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">
                                                            <?= esc($pkg['program_durasi']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                                <div class="flex items-center gap-1.5 text-[11px]">
                                                    <i class="fas fa-calendar-day text-slate-400 text-[10px]"></i>
                                                    <span><?= !empty($pkg['tanggal_keberangkatan']) ? date('d M Y', strtotime($pkg['tanggal_keberangkatan'])) : (!empty($pkg['created_at']) ? date('d M Y', strtotime($pkg['created_at'])) : '-') ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="font-bold text-[#5e0078] text-sm">Rp <?= number_format($pkg['harga_quad'] ?? $pkg['harga'], 0, ',', '.') ?></span>
                                                <span class="text-[10px] text-slate-400 block font-normal">Quad / org</span>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <?= !empty($pkg['seat_tersedia']) ? ((int)$pkg['seat_tersedia'] . ' Seat') : 'Tersedia' ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button type="button" 
                                                            onclick="openDetailModal(<?= $pkgDetailJson ?>)"
                                                            class="btn-interactive inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition"
                                                            title="Detail Paket">
                                                        <i class="fas fa-info-circle text-xs"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                    <?php if (in_array($userRole, ['mitra', 'manager_mitra', 'cabang'])): ?>
                                                        <button type="button" 
                                                                onclick="openOrderModal(<?= htmlspecialchars(json_encode([
                                                                    'id' => $pkg['id'],
                                                                    'nama' => $pkg['nama'],
                                                                    'harga' => (float)$pkg['harga'],
                                                                    'harga_fmt' => 'Rp ' . number_format($pkg['harga'], 0, ',', '.')
                                                                ]), ENT_QUOTES, 'UTF-8') ?>)"
                                                                class="btn-interactive inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-xs transition"
                                                                title="Pesan Paket">
                                                            <i class="fas fa-shopping-cart text-xs"></i>
                                                            <span>Pesan</span>
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-400 text-xs font-semibold cursor-not-allowed border border-slate-200" title="Mode Referensi">
                                                            <i class="fas fa-lock text-[10px]"></i>
                                                            <span>Referensi</span>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Footer Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <span id="memberPackageCountSummary">Menampilkan <?= count($packages ?? []) ?> paket</span>
                    <div class="flex items-center gap-1 select-none" id="memberPackagePaginationControls">
                        <button type="button" id="prevMemberPackageBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fas fa-arrow-left text-[10px]"></i> Previous
                        </button>
                        <div id="memberPackagePaginationNumbers" class="flex items-center gap-1">
                            <!-- Generated by JS -->
                        </div>
                        <button type="button" id="nextMemberPackageBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Next <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Detail Paket (Untuk Semua Role) -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all my-8 max-h-[90vh] flex flex-col">
            <!-- Header Image -->
            <div id="detailImageContainer" class="h-44 sm:h-52 w-full bg-[#5e0078] flex items-center justify-center text-white relative shrink-0 overflow-hidden">
                <i class="fas fa-kaaba text-5xl opacity-80"></i>
                <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white hover:bg-black/60 flex items-center justify-center transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            
            <!-- Modal Body (Scrollable) -->
            <div class="p-5 sm:p-6 space-y-5 overflow-y-auto flex-1">
                <div>
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span id="detailKategori" class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider bg-purple-50 px-2.5 py-0.5 rounded-md border border-purple-100"></span>
                        <span id="detailProgram" class="text-[11px] font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md hidden"></span>
                    </div>
                    <h3 id="detailNama" class="text-lg sm:text-xl font-extrabold text-slate-900"></h3>
                </div>

                <!-- Info Grid: Tanggal, Maskapai, Hotel, Seat -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                        <span class="text-[10px] text-slate-400 font-semibold block uppercase">Keberangkatan</span>
                        <span id="detailTanggal" class="font-bold text-slate-800 mt-0.5 block truncate">-</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                        <span class="text-[10px] text-slate-400 font-semibold block uppercase">Maskapai</span>
                        <span id="detailMaskapai" class="font-bold text-slate-800 mt-0.5 block truncate">-</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                        <span class="text-[10px] text-slate-400 font-semibold block uppercase">Hotel Mekkah</span>
                        <span id="detailHotelMekkah" class="font-bold text-slate-800 mt-0.5 block truncate">-</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                        <span class="text-[10px] text-slate-400 font-semibold block uppercase">Hotel Madinah</span>
                        <span id="detailHotelMadinah" class="font-bold text-slate-800 mt-0.5 block truncate">-</span>
                    </div>
                </div>

                <!-- Harga Kamar: Quad, Triple, Double -->
                <div class="p-4 bg-purple-50/60 rounded-2xl border border-purple-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-[#5e0078] uppercase tracking-wide">Pilihan Tipe Kamar & Biaya</span>
                        <span id="detailSeat" class="text-xs font-bold text-slate-700"></span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                        <div class="p-2.5 bg-white rounded-xl border border-purple-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-medium">Quad (4 Orang)</span>
                            <span id="detailHargaQuad" class="font-extrabold text-[#5e0078] text-sm mt-0.5">-</span>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border border-purple-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-medium">Triple (3 Orang)</span>
                            <span id="detailHargaTriple" class="font-extrabold text-[#5e0078] text-sm mt-0.5">-</span>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border border-purple-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-medium">Double (2 Orang)</span>
                            <span id="detailHargaDouble" class="font-extrabold text-[#5e0078] text-sm mt-0.5">-</span>
                        </div>
                    </div>
                </div>

                <!-- Include & Exclude Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70">
                        <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-1.5 text-xs text-emerald-700">
                            <i class="fas fa-check-circle"></i> Termasuk (Include)
                        </h4>
                        <ul id="detailIncludeList" class="space-y-1.5 text-slate-600 pl-1">
                            <!-- JS populated -->
                        </ul>
                    </div>

                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70">
                        <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-1.5 text-xs text-rose-700">
                            <i class="fas fa-times-circle"></i> Tidak Termasuk (Exclude)
                        </h4>
                        <ul id="detailExcludeList" class="space-y-1.5 text-slate-600 pl-1">
                            <!-- JS populated -->
                        </ul>
                    </div>
                </div>

                <!-- Deskripsi / Catatan Tambahan -->
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">Catatan & Deskripsi:</h4>
                    <div id="detailDeskripsi" class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-3.5 rounded-xl border border-slate-200/80"></div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-4 bg-slate-50/75 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                <button type="button" onclick="closeDetailModal()" class="btn-interactive px-5 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Pemesanan Paket (Untuk Mitra, Manager Mitra, dan Cabang) -->
<?php if (in_array($userRole, ['mitra', 'manager_mitra', 'cabang'])): ?>
<div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeOrderModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-xs"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Pemesanan Paket</h3>
                </div>
                <button type="button" onclick="closeOrderModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form method="POST" action="<?= base_url('/app/packages/order') ?>" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" id="orderPackageId" name="package_id" value="">

                <div>
                    <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Paket yang Dipilih</span>
                    <h4 id="orderPackageNama" class="text-sm font-bold text-slate-900"></h4>
                    <p id="orderPackageHargaFmt" class="text-xs font-semibold text-[#5e0078] mt-0.5"></p>
                </div>

                <div>
                    <label for="orderQty" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Jamaah / Paket <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="changeQty(-1)" class="w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center font-bold text-slate-600 transition">-</button>
                        <input type="number" id="orderQty" name="qty" value="1" min="1" max="100" required oninput="calcTotal()"
                               class="flex-1 text-center py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-[#5e0078] focus:bg-white transition">
                        <button type="button" onclick="changeQty(1)" class="w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center font-bold text-slate-600 transition">+</button>
                    </div>
                </div>

                <div>
                    <label for="orderCatatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan / Nama Jamaah</label>
                    <textarea id="orderCatatan" name="catatan" rows="3" placeholder="Tuliskan nama-nama calon jamaah atau catatan khusus lainnya..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"></textarea>
                </div>

                <div class="p-3.5 bg-purple-50/70 rounded-xl border border-purple-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Total Pembayaran:</span>
                    <span id="orderTotalFmt" class="text-base font-extrabold text-[#5e0078]">Rp 0</span>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeOrderModal()" class="btn-interactive flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-interactive flex-1 bg-[#5e0078] hover:bg-[#4c0062] text-white py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                        Konfirmasi Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
let currentPrice = 0;

function openDetailModal(pkg) {
    const modal = document.getElementById('detailModal');
    const imgContainer = document.getElementById('detailImageContainer');
    
    if (pkg.gambar) {
        imgContainer.innerHTML = `
            <img src="${pkg.gambar}" alt="${pkg.nama}" class="w-full h-full object-cover">
            <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white hover:bg-black/60 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
    } else {
        imgContainer.innerHTML = `
            <i class="fas fa-kaaba text-5xl opacity-80"></i>
            <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white hover:bg-black/60 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
    }

    document.getElementById('detailKategori').textContent = pkg.kategori || 'Umroh';
    document.getElementById('detailNama').textContent = pkg.nama || '';
    document.getElementById('detailHarga').textContent = pkg.harga_fmt || '';
    document.getElementById('detailDeskripsi').textContent = pkg.deskripsi || 'Tidak ada deskripsi detail tambahan untuk paket ini.';

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function openOrderModal(pkg) {
    const modal = document.getElementById('orderModal');
    if (!modal) return;

    currentPrice = pkg.harga || 0;
    document.getElementById('orderPackageId').value = pkg.id;
    document.getElementById('orderPackageNama').textContent = pkg.nama;
    document.getElementById('orderPackageHargaFmt').textContent = pkg.harga_fmt + ' / jamaah';
    document.getElementById('orderQty').value = 1;
    document.getElementById('orderCatatan').value = '';

    calcTotal();

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function changeQty(delta) {
    const qtyInput = document.getElementById('orderQty');
    let val = parseInt(qtyInput.value) || 1;
    val = Math.max(1, val + delta);
    qtyInput.value = val;
    calcTotal();
}

function calcTotal() {
    const qtyInput = document.getElementById('orderQty');
    const totalEl = document.getElementById('orderTotalFmt');
    let qty = parseInt(qtyInput.value) || 1;
    if (qty < 1) qty = 1;
    const total = qty * currentPrice;
    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Dual-View Coordinator (Search, Filtering, Sorting, Pagination & View Toggle)
document.addEventListener('DOMContentLoaded', function() {
    const gridContainer = document.getElementById('memberPackagesGridContainer');
    const listContainer = document.getElementById('memberPackagesListContainer');
    const cardViewBtn = document.getElementById('memberCardViewBtn');
    const listViewBtn = document.getElementById('memberListViewBtn');
    const searchInput = document.getElementById('memberPackagesSearchInput');
    const sortSelect = document.getElementById('memberPackagesSortSelect');
    const categoryFilter = document.getElementById('memberPackagesCategoryFilter');
    const pageSizeSelect = document.getElementById('memberPackagesPageSizeSelect');
    const resetBtn = document.getElementById('resetMemberPackagesFiltersBtn');
    const summaryEl = document.getElementById('memberPackageCountSummary');
    const prevBtn = document.getElementById('prevMemberPackageBtn');
    const nextBtn = document.getElementById('nextMemberPackageBtn');
    const paginationNumbers = document.getElementById('memberPackagePaginationNumbers');
    const emptyCard = document.getElementById('emptyMemberPackageCard');
    const emptyListRow = document.getElementById('emptyMemberPackageListRow');
    const listTableBody = document.getElementById('memberPackagesListTableBody');

    // Collect paired items
    const cards = Array.from(gridContainer.querySelectorAll('.package-card-item'));
    const rows = Array.from(listContainer.querySelectorAll('.package-list-row'));
    
    // Pair them up by index
    const allItems = cards.map((card, idx) => ({
        card: card,
        row: rows[idx],
        nama: card.getAttribute('data-nama') || '',
        harga: parseFloat(card.getAttribute('data-harga')) || 0,
        kategori: card.getAttribute('data-kategori') || '',
        search: card.getAttribute('data-search') || ''
    }));

    let currentPage = 1;
    let pageSize = parseInt(pageSizeSelect.value, 10) || 10;
    let currentMode = localStorage.getItem('amanah_member_packages_view_mode') || 'card';

    // View toggle handler
    function setViewMode(mode) {
        currentMode = mode;
        localStorage.setItem('amanah_member_packages_view_mode', mode);
        
        if (mode === 'card') {
            gridContainer.classList.remove('hidden');
            listContainer.classList.add('hidden');
            cardViewBtn.className = 'px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition bg-white text-[#5e0078] shadow-xs';
            listViewBtn.className = 'px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition text-slate-500 hover:text-slate-800';
        } else {
            gridContainer.classList.add('hidden');
            listContainer.classList.remove('hidden');
            listViewBtn.className = 'px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition bg-white text-[#5e0078] shadow-xs';
            cardViewBtn.className = 'px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition text-slate-500 hover:text-slate-800';
        }
    }

    cardViewBtn.addEventListener('click', () => setViewMode('card'));
    listViewBtn.addEventListener('click', () => setViewMode('list'));

    // Sort functions
    const sortFns = {
        nama_asc: (a, b) => a.nama.localeCompare(b.nama, 'id', { numeric: true, sensitivity: 'base' }),
        nama_desc: (a, b) => b.nama.localeCompare(a.nama, 'id', { numeric: true, sensitivity: 'base' }),
        harga_asc: (a, b) => a.harga - b.harga,
        harga_desc: (a, b) => b.harga - a.harga,
        kategori_asc: (a, b) => a.kategori.localeCompare(b.kategori, 'id', { numeric: true, sensitivity: 'base' }),
        kategori_desc: (a, b) => b.kategori.localeCompare(a.kategori, 'id', { numeric: true, sensitivity: 'base' })
    };

    function render() {
        const query = searchInput.value.trim().toLowerCase();
        const selectedCat = categoryFilter.value;
        const sortKey = sortSelect.value;

        // Filter
        let filtered = allItems.filter(item => {
            if (query && !item.search.includes(query)) return false;
            if (selectedCat !== 'all' && item.kategori !== selectedCat) return false;
            return true;
        });

        // Sort
        if (sortFns[sortKey]) {
            filtered.sort(sortFns[sortKey]);
        }

        const totalItems = filtered.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * pageSize;
        const endIdx = startIdx + pageSize;

        // Hide all first
        allItems.forEach(item => {
            item.card.style.display = 'none';
            if (item.row) item.row.style.display = 'none';
        });

        if (totalItems === 0) {
            emptyCard.classList.remove('hidden');
            emptyListRow.classList.remove('hidden');
            summaryEl.textContent = 'Menampilkan 0 dari 0 paket';
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            paginationNumbers.innerHTML = '';
            return;
        }

        emptyCard.classList.add('hidden');
        emptyListRow.classList.add('hidden');

        // Re-append and show visible items in DOM order
        const visibleSlice = filtered.slice(startIdx, endIdx);
        visibleSlice.forEach(item => {
            item.card.style.display = '';
            gridContainer.appendChild(item.card);
            if (item.row) {
                item.row.style.display = '';
                listTableBody.appendChild(item.row);
            }
        });

        // Update Summary
        const displayStart = startIdx + 1;
        const displayEnd = Math.min(endIdx, totalItems);
        summaryEl.textContent = `Menampilkan ${displayStart} - ${displayEnd} dari ${totalItems} paket`;

        // Update pagination buttons
        prevBtn.disabled = (currentPage <= 1);
        nextBtn.disabled = (currentPage >= totalPages);

        // Build page numbers
        paginationNumbers.innerHTML = '';
        const pages = getPageNumbers(currentPage, totalPages);
        pages.forEach(p => {
            if (p === '...') {
                const dots = document.createElement('span');
                dots.className = 'px-2 py-1 text-slate-400 text-xs';
                dots.textContent = '...';
                paginationNumbers.appendChild(dots);
            } else {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = p;
                if (p === currentPage) {
                    btn.className = 'w-7 h-7 flex items-center justify-center rounded-lg text-xs font-bold bg-[#5e0078] text-white shadow-xs';
                } else {
                    btn.className = 'w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition';
                    btn.addEventListener('click', () => {
                        currentPage = p;
                        render();
                    });
                }
                paginationNumbers.appendChild(btn);
            }
        });
    }

    function getPageNumbers(current, total) {
        if (total <= 5) {
            return Array.from({ length: total }, (_, i) => i + 1);
        }
        if (current <= 3) {
            return [1, 2, 3, 4, '...', total];
        }
        if (current >= total - 2) {
            return [1, '...', total - 3, total - 2, total - 1, total];
        }
        return [1, '...', current - 1, current, current + 1, '...', total];
    }

    // Event listeners
    searchInput.addEventListener('input', () => { currentPage = 1; render(); });
    sortSelect.addEventListener('change', () => { render(); });
    categoryFilter.addEventListener('change', () => { currentPage = 1; render(); });
    pageSizeSelect.addEventListener('change', () => {
        pageSize = parseInt(pageSizeSelect.value, 10) || 10;
        currentPage = 1;
        render();
    });

    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            render();
        }
    });

    nextBtn.addEventListener('click', () => {
        const totalItems = allItems.filter(item => {
            const query = searchInput.value.trim().toLowerCase();
            const selectedCat = categoryFilter.value;
            if (query && !item.search.includes(query)) return false;
            if (selectedCat !== 'all' && item.kategori !== selectedCat) return false;
            return true;
        }).length;
        const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));
        if (currentPage < totalPages) {
            currentPage++;
            render();
        }
    });

    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        sortSelect.value = 'nama_asc';
        categoryFilter.value = 'all';
        pageSizeSelect.value = '10';
        pageSize = 10;
        currentPage = 1;
        render();
    });

    // Keyboard shortcut (⌘K or Ctrl+K)
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
    });

    // Init initial mode and render
    setViewMode(currentMode);
    render();
});
</script>
<?= $this->endSection() ?>
