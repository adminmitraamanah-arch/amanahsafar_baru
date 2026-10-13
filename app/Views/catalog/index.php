<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50">
    <nav class="bg-white border-b border-slate-200/80 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#5e0078] rounded-xl flex items-center justify-center font-bold text-sm text-white shadow-sm">
                        AS
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-base tracking-tight text-slate-900 leading-tight">Amanah Safar</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Katalog Paket</span>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('/login') ?>" class="text-xs sm:text-sm font-semibold text-slate-700 hover:text-[#5e0078] px-3 py-2 rounded-lg transition">Masuk</a>
                    <a href="<?= base_url('/register') ?>" class="bg-[#5e0078] text-white px-4 py-2 rounded-xl hover:bg-[#4c0062] text-xs sm:text-sm font-bold shadow-sm transition">Daftar Mitra</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">Paket Umroh & Haji</h1>
            <p class="text-sm text-slate-500">Pilih paket perjalanan resmi dan terbaik untuk kenyamanan ibadah Anda</p>
        </div>

        <!-- Toolbar (Search, Sort, Toggle) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="relative w-full md:w-80">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="publicCatalogSearchInput" placeholder="Cari nama paket, kategori..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] focus:bg-white transition">
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                <!-- View Mode Toggle (Card / List) -->
                <div class="inline-flex items-center p-1 bg-slate-100 border border-slate-200/90 rounded-xl" id="publicCatalogViewToggleGroup" role="group" aria-label="Tampilan">
                    <button type="button" id="publicCardViewBtn" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition bg-white text-[#5e0078] shadow-xs" title="Tampilan Kartu">
                        <i class="fas fa-th-large text-xs"></i>
                        <span class="hidden sm:inline">Grid</span>
                    </button>
                    <button type="button" id="publicListViewBtn" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition text-slate-500 hover:text-slate-800" title="Tampilan Daftar">
                        <i class="fas fa-list text-xs"></i>
                        <span class="hidden sm:inline">List</span>
                    </button>
                </div>

                <!-- Sort By -->
                <select id="publicCatalogSortSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                    <option value="nama_asc">Urutkan: Nama (A - Z)</option>
                    <option value="nama_desc">Urutkan: Nama (Z - A)</option>
                    <option value="harga_asc">Urutkan: Harga (Terendah)</option>
                    <option value="harga_desc">Urutkan: Harga (Tertinggi)</option>
                </select>
            </div>
        </div>

        <!-- Package Grid (Card View) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="publicCatalogGridContainer">
            <div id="emptyPublicCatalogCard" class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs <?= empty($packages) ? '' : 'hidden' ?>">
                <i class="fas fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                <p>Tidak ada paket umroh yang sesuai dengan pencarian.</p>
            </div>

            <?php foreach ($packages ?? [] as $pkg): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition duration-200 group public-package-card"
                     data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                     data-harga="<?= (int)$pkg['harga'] ?>"
                     data-search="<?= strtolower(esc($pkg['nama'] . ' ' . ($pkg['kategori'] ?? '') . ' ' . ($pkg['deskripsi'] ?? '') . ' ' . $pkg['harga'])) ?>">
                    <?php if (!empty($pkg['gambar'])): ?>
                        <div class="h-48 overflow-hidden bg-slate-100 relative">
                            <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <?php if (!empty($pkg['kategori'])): ?>
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                    <?= esc($pkg['kategori']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="h-48 bg-[#5e0078] flex items-center justify-center text-white relative">
                            <i class="fas fa-kaaba text-5xl opacity-80"></i>
                            <?php if (!empty($pkg['kategori'])): ?>
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-black/40 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                    <?= esc($pkg['kategori']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 leading-snug group-hover:text-[#5e0078] transition-colors"><?= esc($pkg['nama']) ?></h3>
                            <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed"><?= esc($pkg['deskripsi'] ?? 'Paket perjalanan resmi amanah safar dengan akomodasi dan bimbingan ibadah lengkap.') ?></p>
                        </div>
                        
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Mulai dari</span>
                                <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                            </div>
                            <a href="<?= base_url('/catalog/' . $pkg['id']) ?>" class="btn-interactive inline-flex items-center gap-1.5 bg-[#5e0078] text-white px-4 py-2 rounded-xl hover:bg-[#4c0062] text-xs font-bold shadow-xs transition">
                                <span>Lihat Detail</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Package List View Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden hidden" id="publicCatalogListContainer">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6">Paket</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Biaya Paket</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs" id="publicCatalogListTableBody">
                        <tr id="emptyPublicCatalogListRow" class="<?= empty($packages) ? '' : 'hidden' ?>">
                            <td colspan="5" class="p-12 text-center text-slate-400">
                                <i class="fas fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                                <p>Tidak ada paket umroh yang sesuai dengan pencarian.</p>
                            </td>
                        </tr>

                        <?php foreach ($packages ?? [] as $pkg): ?>
                            <tr class="hover:bg-slate-50/70 transition duration-150 public-package-row"
                                data-nama="<?= strtolower(esc($pkg['nama'])) ?>"
                                data-harga="<?= (int)$pkg['harga'] ?>"
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
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-[#5e0078] text-sm">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tersedia
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap text-right">
                                    <a href="<?= base_url('/catalog/' . $pkg['id']) ?>" 
                                       class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#5e0078] text-white hover:bg-[#4c0062] text-xs font-bold shadow-xs transition">
                                        <span>Lihat Detail</span>
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridContainer = document.getElementById('publicCatalogGridContainer');
    const listContainer = document.getElementById('publicCatalogListContainer');
    const cardViewBtn = document.getElementById('publicCardViewBtn');
    const listViewBtn = document.getElementById('publicListViewBtn');
    const searchInput = document.getElementById('publicCatalogSearchInput');
    const sortSelect = document.getElementById('publicCatalogSortSelect');
    const emptyCard = document.getElementById('emptyPublicCatalogCard');
    const emptyListRow = document.getElementById('emptyPublicCatalogListRow');
    const listTableBody = document.getElementById('publicCatalogListTableBody');

    const cards = Array.from(gridContainer.querySelectorAll('.public-package-card'));
    const rows = Array.from(listContainer.querySelectorAll('.public-package-row'));

    const allItems = cards.map((card, idx) => ({
        card: card,
        row: rows[idx],
        nama: card.getAttribute('data-nama') || '',
        harga: parseFloat(card.getAttribute('data-harga')) || 0,
        search: card.getAttribute('data-search') || ''
    }));

    let currentMode = localStorage.getItem('amanah_public_catalog_view_mode') || 'card';

    function setViewMode(mode) {
        currentMode = mode;
        localStorage.setItem('amanah_public_catalog_view_mode', mode);
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

    const sortFns = {
        nama_asc: (a, b) => a.nama.localeCompare(b.nama, 'id', { numeric: true, sensitivity: 'base' }),
        nama_desc: (a, b) => b.nama.localeCompare(a.nama, 'id', { numeric: true, sensitivity: 'base' }),
        harga_asc: (a, b) => a.harga - b.harga,
        harga_desc: (a, b) => b.harga - a.harga
    };

    function render() {
        const query = searchInput.value.trim().toLowerCase();
        const sortKey = sortSelect.value;

        let filtered = allItems.filter(item => {
            if (query && !item.search.includes(query)) return false;
            return true;
        });

        if (sortFns[sortKey]) {
            filtered.sort(sortFns[sortKey]);
        }

        allItems.forEach(item => {
            item.card.style.display = 'none';
            if (item.row) item.row.style.display = 'none';
        });

        if (filtered.length === 0) {
            emptyCard.classList.remove('hidden');
            emptyListRow.classList.remove('hidden');
            return;
        }

        emptyCard.classList.add('hidden');
        emptyListRow.classList.add('hidden');

        filtered.forEach(item => {
            item.card.style.display = '';
            gridContainer.appendChild(item.card);
            if (item.row) {
                item.row.style.display = '';
                listTableBody.appendChild(item.row);
            }
        });
    }

    searchInput.addEventListener('input', render);
    sortSelect.addEventListener('change', render);

    setViewMode(currentMode);
    render();
});
</script>
<?= $this->endSection() ?>
