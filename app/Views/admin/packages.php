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
                        <?php foreach ($packages as $pkg): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition duration-200 package-card-item"
                                 data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                                 data-harga="<?= (int)$pkg['harga'] ?>"
                                 data-kategori="<?= strtolower(esc($pkg['kategori'] ?? 'umroh')) ?>"
                                 data-status="<?= esc($pkg['status'] ?? 'aktif') ?>"
                                 data-search="<?= strtolower(esc($pkg['nama'] . ' ' . ($pkg['kategori'] ?? '') . ' ' . ($pkg['deskripsi'] ?? '') . ' ' . $pkg['harga'])) ?>">
                                <?php if (!empty($pkg['gambar'])): ?>
                                    <div class="h-44 w-full overflow-hidden bg-slate-100">
                                        <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="h-44 w-full bg-[#5e0078] flex items-center justify-center text-white">
                                        <i class="fas fa-kaaba text-4xl opacity-80"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                    <div>
                                        <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                        <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug"><?= esc($pkg['nama']) ?></h3>
                                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed"><?= esc($pkg['deskripsi'] ?? '') ?></p>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                        <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                            <?= ucfirst($pkg['status'] ?? 'aktif') ?>
                                        </span>
                                    </div>

                                    <div class="flex gap-2 pt-1">
                                        <a href="<?= base_url('/admin/packages/' . $pkg['id'] . '/edit') ?>" 
                                           class="btn-interactive flex-1 text-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 px-3 py-2 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form method="POST" action="<?= base_url('/admin/packages/' . $pkg['id'] . '/delete') ?>" style="flex:1;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket <?= esc($pkg['nama']) ?>?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn-interactive w-full bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/80 px-3 py-2 rounded-xl text-xs font-bold transition">
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
                                    <th class="py-3.5 px-4">Kategori</th>
                                    <th class="py-3.5 px-4">Dibuat</th>
                                    <th class="py-3.5 px-4">Harga</th>
                                    <th class="py-3.5 px-4">Status</th>
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
                                            data-search="<?= strtolower(esc($pkg['nama'] . ' ' . ($pkg['kategori'] ?? '') . ' ' . ($pkg['deskripsi'] ?? '') . ' ' . $pkg['harga'])) ?>">
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
                                                        <div class="text-[11px] text-slate-500 truncate mt-0.5"><?= esc($pkg['deskripsi'] ?? '-') ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-[#5e0078] border border-purple-200/60 uppercase tracking-wide">
                                                    <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                                <div class="flex items-center gap-1.5 text-[11px]">
                                                    <i class="fas fa-calendar-day text-slate-400 text-[10px]"></i>
                                                    <span><?= !empty($pkg['created_at']) ? date('d M Y', strtotime($pkg['created_at'])) : '-' ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="font-bold text-slate-900 text-sm">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= ($pkg['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                                    <?= ucfirst($pkg['status'] ?? 'aktif') ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1.5">
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

<script>
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
