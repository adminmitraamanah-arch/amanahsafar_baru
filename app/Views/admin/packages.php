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
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar paket perjalanan umroh dan haji yang dikelola oleh Admin Pusat.</p>
                    </div>
                    <a href="<?= base_url('/admin/packages/create') ?>" 
                       class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Paket Baru</span>
                    </a>
                </div>

                <!-- Toolbar (Search, Filter, Sort, Per-Page) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col md:flex-row items-center justify-between gap-3">
                    <div class="relative w-full md:w-80">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="adminPackagesSearchInput" placeholder="Cari nama paket, kategori..." 
                               class="w-full pl-9 pr-12 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] focus:bg-white transition">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                        <!-- View Mode Toggle (Card / List) -->
                        <div class="inline-flex items-center p-1 bg-slate-100 border border-slate-200/90 rounded-xl" id="adminPackageViewToggleGroup" role="group" aria-label="Tampilan">
                            <button type="button" id="adminCardViewBtn" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition bg-white text-[#5e0078] shadow-xs" title="Tampilan Kartu">
                                <i class="fas fa-th-large text-xs"></i>
                                <span class="hidden sm:inline">Grid</span>
                            </button>
                            <button type="button" id="adminListViewBtn" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition text-slate-500 hover:text-slate-800" title="Tampilan Daftar">
                                <i class="fas fa-list text-xs"></i>
                                <span class="hidden sm:inline">List</span>
                            </button>
                        </div>

                        <!-- Sort By -->
                        <select id="adminPackagesSortSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="nama_asc">Urutkan: Nama (A - Z)</option>
                            <option value="nama_desc">Urutkan: Nama (Z - A)</option>
                            <option value="harga_asc">Urutkan: Harga (Terendah)</option>
                            <option value="harga_desc">Urutkan: Harga (Tertinggi)</option>
                            <option value="kategori_asc">Urutkan: Kategori (A - Z)</option>
                            <option value="kategori_desc">Urutkan: Kategori (Z - A)</option>
                            <option value="status_asc">Urutkan: Status (Aktif Dahulu)</option>
                            <option value="status_desc">Urutkan: Status (Nonaktif Dahulu)</option>
                        </select>

                        <!-- Filter Status -->
                        <select id="adminPackagesStatusFilter" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="all">Semua Status</option>
                            <option value="aktif">Status: Aktif</option>
                            <option value="nonaktif">Status: Nonaktif</option>
                        </select>

                        <!-- Per Page Size -->
                        <select id="adminPackagesPageSizeSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="10">10 / halaman</option>
                            <option value="25">25 / halaman</option>
                            <option value="50">50 / halaman</option>
                            <option value="100">100 / halaman</option>
                        </select>

                        <!-- Reset Button -->
                        <button type="button" id="resetAdminPackagesFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition text-xs" title="Reset Filter">
                            <i class="fas fa-redo-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Package Grid (Card View) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="adminPackagesGridContainer">
                    <div id="emptyAdminPackageCard" class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs <?= empty($packages) ? '' : 'hidden' ?>">
                        <i class="fas fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                        <p>Tidak ada paket umroh yang sesuai dengan filter atau kata kunci.</p>
                    </div>

                    <?php if (!empty($packages)): ?>
                        <?php
                        $packagesJsRegistry = [];
                        foreach ($packages as $pItem) {
                            $packagesJsRegistry[] = [
                                'id' => (int)$pItem['id'],
                                'nama' => $pItem['nama'],
                                'kategori' => $pItem['kategori'] ?? 'Umroh',
                                'program_durasi' => $pItem['program_durasi'] ?? '',
                                'tanggal_keberangkatan' => !empty($pItem['tanggal_keberangkatan']) ? date('d M Y', strtotime($pItem['tanggal_keberangkatan'])) : '',
                                'maskapai' => $pItem['maskapai'] ?? '',
                                'hotel_mekkah' => $pItem['hotel_mekkah'] ?? '',
                                'hotel_madinah' => $pItem['hotel_madinah'] ?? '',
                                'harga' => (float)$pItem['harga'],
                                'harga_fmt' => 'Rp ' . number_format($pItem['harga'], 0, ',', '.'),
                                'harga_quad' => !empty($pItem['harga_quad']) ? 'Rp ' . number_format($pItem['harga_quad'], 0, ',', '.') : ('Rp ' . number_format($pItem['harga'], 0, ',', '.')),
                                'harga_triple' => !empty($pItem['harga_triple']) ? 'Rp ' . number_format($pItem['harga_triple'], 0, ',', '.') : '-',
                                'harga_double' => !empty($pItem['harga_double']) ? 'Rp ' . number_format($pItem['harga_double'], 0, ',', '.') : '-',
                                'seat_tersedia' => (int)($pItem['seat_tersedia'] ?? 0),
                                'includes' => !empty($pItem['includes']) ? (json_decode($pItem['includes'], true) ?: []) : [],
                                'excludes' => !empty($pItem['excludes']) ? (json_decode($pItem['excludes'], true) ?: []) : [],
                                'deskripsi' => $pItem['deskripsi'] ?? '',
                                'gambar' => !empty($pItem['gambar']) ? base_url('uploads/packages/' . esc($pItem['gambar'])) : null
                            ];
                        }
                        ?>
                        <?php foreach ($packages as $pkg): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition duration-200 package-card-item"
                                 data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                                 data-harga="<?= (int)$pkg['harga'] ?>"
                                 data-kategori="<?= strtolower(esc($pkg['kategori'] ?? 'umroh')) ?>"
                                 data-status="<?= esc($pkg['status'] ?? 'aktif') ?>"
                                 data-search="<?= strtolower(esc($pkg['nama'] . ' ' . ($pkg['kategori'] ?? '') . ' ' . ($pkg['program_durasi'] ?? '') . ' ' . ($pkg['maskapai'] ?? '') . ' ' . ($pkg['hotel_mekkah'] ?? '') . ' ' . ($pkg['deskripsi'] ?? '') . ' ' . $pkg['harga'])) ?>">
                                <?php if (!empty($pkg['gambar'])): ?>
                                    <div class="h-44 w-full overflow-hidden bg-slate-100 relative">
                                        <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover">
                                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                                <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                            </span>
                                            <?php if (!empty($pkg['program_durasi'])): ?>
                                                <span class="px-2 py-0.5 rounded-lg bg-[#5e0078]/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                                    <?= esc($pkg['program_durasi']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="h-44 w-full bg-[#5e0078] flex flex-col items-center justify-center text-white relative">
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
                                            <?php if (isset($pkg['seat_tersedia']) && $pkg['seat_tersedia'] !== null): ?>
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md ml-auto">
                                                    <i class="fas fa-chair text-[9px] mr-0.5"></i> <?= (int)$pkg['seat_tersedia'] ?> Seat
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug"><?= esc($pkg['nama']) ?></h3>
                                        
                                        <!-- Key Highlights -->
                                        <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-500 my-2">
                                            <?php if (!empty($pkg['tanggal_keberangkatan'])): ?>
                                                <span class="inline-flex items-center gap-1" title="Tanggal Keberangkatan">
                                                    <i class="fas fa-calendar-day text-[#5e0078] text-[10px]"></i> <?= date('d M Y', strtotime($pkg['tanggal_keberangkatan'])) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($pkg['maskapai'])): ?>
                                                <span class="inline-flex items-center gap-1" title="Maskapai">
                                                    <i class="fas fa-plane text-[#5e0078] text-[10px]"></i> <?= esc($pkg['maskapai']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed"><?= esc($pkg['deskripsi'] ?? '') ?></p>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                        <div>
                                            <span class="text-xs text-slate-400 block font-medium">Mulai dari</span>
                                            <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                            <?= ucfirst($pkg['status'] ?? 'aktif') ?>
                                        </span>
                                    </div>

                                    <div class="flex gap-2 pt-1">
                                        <button type="button" 
                                                onclick="showPackageDetail(<?= $pkg['id'] ?>)"
                                                class="btn-interactive flex-1 text-center bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200 px-2.5 py-2 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                        </button>
                                        <a href="<?= base_url('/admin/packages/' . $pkg['id'] . '/edit') ?>" 
                                            class="btn-interactive flex-1 text-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 px-2.5 py-2 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form method="POST" action="<?= base_url('/admin/packages/' . $pkg['id'] . '/delete') ?>" style="flex:1;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket <?= esc($pkg['nama']) ?>?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn-interactive w-full bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/80 px-2.5 py-2 rounded-xl text-xs font-bold transition">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Package List View Container -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden hidden" id="adminPackagesListContainer">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-4 sm:px-6">Paket</th>
                                    <th class="py-3.5 px-4">Kategori & Program</th>
                                    <th class="py-3.5 px-4">Keberangkatan</th>
                                    <th class="py-3.5 px-4">Harga Quad</th>
                                    <th class="py-3.5 px-4">Seat & Status</th>
                                    <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs" id="adminPackagesListTableBody">
                                <tr id="emptyAdminPackageListRow" class="<?= empty($packages) ? '' : 'hidden' ?>">
                                    <td colspan="6" class="p-12 text-center text-slate-400">
                                        <i class="fas fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                                        <p>Tidak ada paket umroh yang sesuai dengan filter atau kata kunci.</p>
                                    </td>
                                </tr>

                                <?php if (!empty($packages)): ?>
                                    <?php foreach ($packages as $pkg): ?>
                                        <tr class="hover:bg-slate-50/70 transition duration-150 package-list-row"
                                            data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                                            data-harga="<?= (int)$pkg['harga'] ?>"
                                            data-kategori="<?= strtolower(esc($pkg['kategori'] ?? 'umroh')) ?>"
                                            data-status="<?= esc($pkg['status'] ?? 'aktif') ?>"
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
                                                            <span><?= esc($pkg['deskripsi'] ?? '-') ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-[#5e0078] border border-purple-200/60 uppercase tracking-wide">
                                                        <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                                    </span>
                                                    <?php if (!empty($pkg['program_durasi'])): ?>
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                                            <?= esc($pkg['program_durasi']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                                <div class="flex items-center gap-1.5 text-[11px]">
                                                    <i class="fas fa-calendar-day text-[#5e0078] text-[10px]"></i>
                                                    <span><?= !empty($pkg['tanggal_keberangkatan']) ? date('d M Y', strtotime($pkg['tanggal_keberangkatan'])) : (!empty($pkg['created_at']) ? date('d M Y', strtotime($pkg['created_at'])) : '-') ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="font-bold text-[#5e0078] text-sm">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                                <?php if (!empty($pkg['harga_triple']) || !empty($pkg['harga_double'])): ?>
                                                    <div class="text-[10px] text-slate-400">Quad room</div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <div class="flex flex-col gap-1">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                        <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                                        <span class="w-1.5 h-1.5 rounded-full <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                                        <?= ucfirst($pkg['status'] ?? 'aktif') ?>
                                                    </span>
                                                    <?php if (isset($pkg['seat_tersedia']) && $pkg['seat_tersedia'] !== null): ?>
                                                        <span class="text-[10px] text-slate-500 font-medium">
                                                            <i class="fas fa-chair text-slate-400 mr-1"></i><?= (int)$pkg['seat_tersedia'] ?> kursi
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button type="button" 
                                                            onclick="showPackageDetail(<?= $pkg['id'] ?>)"
                                                            class="btn-interactive inline-flex items-center justify-center p-2 rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200 text-xs font-bold transition"
                                                            title="Detail Paket">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <a href="<?= base_url('/admin/packages/' . $pkg['id'] . '/edit') ?>" 
                                                       class="btn-interactive inline-flex items-center justify-center p-2 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 text-xs font-bold transition"
                                                       title="Edit Paket">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="<?= base_url('/admin/packages/' . $pkg['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket <?= esc($pkg['nama']) ?>?')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" 
                                                                class="btn-interactive inline-flex items-center justify-center p-2 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/80 text-xs font-bold transition"
                                                                title="Hapus Paket">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
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
                    <span id="adminPackageCountSummary">Menampilkan <?= count($packages ?? []) ?> paket</span>
                    <div class="flex items-center gap-1 select-none" id="adminPackagePaginationControls">
                        <button type="button" id="prevAdminPackageBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fas fa-arrow-left text-[10px]"></i> Previous
                        </button>
                        <div id="adminPackagePaginationNumbers" class="flex items-center gap-1">
                            <!-- Generated by JS -->
                        </div>
                        <button type="button" id="nextAdminPackageBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Next <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Detail Paket (Admin) -->
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

<script>
const packagesRegistry = <?= json_encode($packagesJsRegistry ?? [], JSON_UNESCAPED_UNICODE) ?>;

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showPackageDetail(packageId) {
    const pkg = packagesRegistry.find(p => String(p.id) === String(packageId));
    if (!pkg) {
        console.error('Package not found:', packageId);
        return;
    }
    openDetailModal(pkg);
}

function openDetailModal(pkg) {
    if (!pkg) return;
    const modal = document.getElementById('detailModal');
    if (!modal) return;
    const imgContainer = document.getElementById('detailImageContainer');
    
    if (imgContainer) {
        if (pkg.gambar) {
            imgContainer.innerHTML = `
                <img src="${pkg.gambar}" alt="${escapeHtml(pkg.nama)}" class="w-full h-full object-cover">
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
    }

    const katEl = document.getElementById('detailKategori');
    if (katEl) katEl.textContent = pkg.kategori || 'Umroh';

    const progEl = document.getElementById('detailProgram');
    if (progEl) {
        if (pkg.program_durasi) {
            progEl.textContent = pkg.program_durasi;
            progEl.classList.remove('hidden');
        } else {
            progEl.classList.add('hidden');
        }
    }

    const namaEl = document.getElementById('detailNama');
    if (namaEl) namaEl.textContent = pkg.nama || '';

    const tglEl = document.getElementById('detailTanggal');
    if (tglEl) tglEl.textContent = pkg.tanggal_keberangkatan || '-';

    const airEl = document.getElementById('detailMaskapai');
    if (airEl) airEl.textContent = pkg.maskapai || '-';

    const hmekEl = document.getElementById('detailHotelMekkah');
    if (hmekEl) hmekEl.textContent = pkg.hotel_mekkah || 'Sesuai Standar Bintang';

    const hmadEl = document.getElementById('detailHotelMadinah');
    if (hmadEl) hmadEl.textContent = pkg.hotel_madinah || 'Sesuai Standar Bintang';

    const seatEl = document.getElementById('detailSeat');
    if (seatEl) {
        seatEl.textContent = pkg.seat_tersedia ? (pkg.seat_tersedia + ' Seat Tersedia') : 'Seat Tersedia';
    }

    const hQuadEl = document.getElementById('detailHargaQuad');
    if (hQuadEl) hQuadEl.textContent = pkg.harga_quad || pkg.harga_fmt || '-';

    const hTripEl = document.getElementById('detailHargaTriple');
    if (hTripEl) hTripEl.textContent = pkg.harga_triple || '-';

    const hDblEl = document.getElementById('detailHargaDouble');
    if (hDblEl) hDblEl.textContent = pkg.harga_double || '-';

    // Includes
    const incListEl = document.getElementById('detailIncludeList');
    if (incListEl) {
        incListEl.innerHTML = '';
        if (Array.isArray(pkg.includes) && pkg.includes.length > 0) {
            pkg.includes.forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5';
                li.innerHTML = `<i class="fas fa-check text-emerald-600 mt-0.5 text-[10px] shrink-0"></i><span>${escapeHtml(item)}</span>`;
                incListEl.appendChild(li);
            });
        } else {
            incListEl.innerHTML = '<li class="text-slate-400 italic">Termasuk fasilitas standar paket.</li>';
        }
    }

    // Excludes
    const excListEl = document.getElementById('detailExcludeList');
    if (excListEl) {
        excListEl.innerHTML = '';
        if (Array.isArray(pkg.excludes) && pkg.excludes.length > 0) {
            pkg.excludes.forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5';
                li.innerHTML = `<i class="fas fa-times text-rose-500 mt-0.5 text-[10px] shrink-0"></i><span>${escapeHtml(item)}</span>`;
                excListEl.appendChild(li);
            });
        } else {
            excListEl.innerHTML = '<li class="text-slate-400 italic">Tidak ada rincian exclude khusus.</li>';
        }
    }

    const deskEl = document.getElementById('detailDeskripsi');
    if (deskEl) deskEl.textContent = pkg.deskripsi || 'Tidak ada deskripsi detail tambahan untuk paket ini.';

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const gridContainer = document.getElementById('adminPackagesGridContainer');
    const listContainer = document.getElementById('adminPackagesListContainer');
    const cardViewBtn = document.getElementById('adminCardViewBtn');
    const listViewBtn = document.getElementById('adminListViewBtn');
    const searchInput = document.getElementById('adminPackagesSearchInput');
    const sortSelect = document.getElementById('adminPackagesSortSelect');
    const statusFilter = document.getElementById('adminPackagesStatusFilter');
    const pageSizeSelect = document.getElementById('adminPackagesPageSizeSelect');
    const resetBtn = document.getElementById('resetAdminPackagesFiltersBtn');
    const summaryEl = document.getElementById('adminPackageCountSummary');
    const prevBtn = document.getElementById('prevAdminPackageBtn');
    const nextBtn = document.getElementById('nextAdminPackageBtn');
    const paginationNumbers = document.getElementById('adminPackagePaginationNumbers');
    const emptyCard = document.getElementById('emptyAdminPackageCard');
    const emptyListRow = document.getElementById('emptyAdminPackageListRow');
    const listTableBody = document.getElementById('adminPackagesListTableBody');

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
        status: card.getAttribute('data-status') || '',
        search: card.getAttribute('data-search') || ''
    }));

    let currentPage = 1;
    let pageSize = parseInt(pageSizeSelect.value, 10) || 10;
    let currentMode = localStorage.getItem('amanah_admin_packages_view_mode') || 'card';

    // View toggle handler
    function setViewMode(mode) {
        currentMode = mode;
        localStorage.setItem('amanah_admin_packages_view_mode', mode);
        
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
        kategori_desc: (a, b) => b.kategori.localeCompare(a.kategori, 'id', { numeric: true, sensitivity: 'base' }),
        status_asc: (a, b) => a.status.localeCompare(b.status, 'id', { numeric: true, sensitivity: 'base' }),
        status_desc: (a, b) => b.status.localeCompare(a.status, 'id', { numeric: true, sensitivity: 'base' })
    };

    function render() {
        const query = searchInput.value.trim().toLowerCase();
        const selectedStatus = statusFilter.value;
        const sortKey = sortSelect.value;

        // Filter
        let filtered = allItems.filter(item => {
            if (query && !item.search.includes(query)) return false;
            if (selectedStatus !== 'all' && item.status !== selectedStatus) return false;
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
    statusFilter.addEventListener('change', () => { currentPage = 1; render(); });
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
            const selectedStatus = statusFilter.value;
            if (query && !item.search.includes(query)) return false;
            if (selectedStatus !== 'all' && item.status !== selectedStatus) return false;
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
        statusFilter.value = 'all';
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
