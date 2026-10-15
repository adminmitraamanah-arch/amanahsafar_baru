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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Binaan</h1>
                        <p class="text-xs text-slate-500 mt-1">Daftar mitra atau pengguna yang berada dalam binaan langsung Anda.</p>
                    </div>
                    <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs">
                        Total Binaan: <strong class="text-slate-900"><?= count($binaan ?? []) ?></strong>
                    </span>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <!-- Toolbar (Search, Filter, Sort, Per-Page) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="binaanSearchInput" placeholder="Cari nama, email, whatsapp..." 
                                   class="w-full pl-9 pr-12 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <!-- Sort By -->
                            <select id="binaanSortSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="nama_asc">Urutkan: Nama (A - Z)</option>
                                <option value="nama_desc">Urutkan: Nama (Z - A)</option>
                                <option value="role_asc">Urutkan: Role (A - Z)</option>
                                <option value="role_desc">Urutkan: Role (Z - A)</option>
                                <option value="email_asc">Urutkan: Email (A - Z)</option>
                                <option value="email_desc">Urutkan: Email (Z - A)</option>
                                <option value="wa_asc">Urutkan: WhatsApp (A - Z)</option>
                                <option value="wa_desc">Urutkan: WhatsApp (Z - A)</option>
                                <option value="status_asc">Urutkan: Status (Aktif Dahulu)</option>
                                <option value="status_desc">Urutkan: Status (Nonaktif Dahulu)</option>
                            </select>

                            <!-- Filter Status -->
                            <select id="binaanStatusFilter" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status</option>
                                <option value="aktif">Status: Aktif</option>
                                <option value="nonaktif">Status: Nonaktif</option>
                            </select>

                            <!-- Per Page Size -->
                            <select id="binaanPageSizeSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="10">10 / halaman</option>
                                <option value="25">25 / halaman</option>
                                <option value="50">50 / halaman</option>
                                <option value="100">100 / halaman</option>
                            </select>

                            <!-- Reset Button -->
                            <button type="button" id="resetBinaanFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition text-xs" title="Reset Filter">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="binaanTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5" data-sort-asc="nama_asc" data-sort-desc="nama_desc">Nama Binaan</th>
                                    <th class="px-5 py-3.5" data-sort-asc="email_asc" data-sort-desc="email_desc">Email</th>
                                    <th class="px-5 py-3.5" data-sort-asc="wa_asc" data-sort-desc="wa_desc">WhatsApp</th>
                                    <th class="px-5 py-3.5" data-sort-asc="role_asc" data-sort-desc="role_desc">Role</th>
                                    <th class="px-5 py-3.5" data-sort-asc="status_asc" data-sort-desc="status_desc">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($binaan)): ?>
                                    <tr id="emptyBinaanRow">
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-users text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada pengguna dalam binaan Anda.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr id="emptyBinaanRow" style="display: none;">
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-search text-3xl mb-2 text-slate-300 block"></i>
                                            Tidak ada binaan yang cocok dengan filter atau kata kunci.
                                        </td>
                                    </tr>
                                    <?php foreach ($binaan as $b): 
                                        $initial = strtoupper(substr($b['nama_lengkap'] ?? 'B', 0, 1));
                                    ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors binaan-table-row"
                                            data-nama="<?= strtolower(esc($b['nama_lengkap'])) ?>"
                                            data-email="<?= strtolower(esc($b['email'])) ?>"
                                            data-wa="<?= esc($b['no_wa']) ?>"
                                            data-role="<?= esc($b['role']) ?>"
                                            data-status="<?= esc($b['status']) ?>"
                                            data-search="<?= strtolower(esc($b['nama_lengkap'] . ' ' . $b['email'] . ' ' . $b['no_wa'] . ' ' . $b['role'])) ?>">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5e0078] font-bold text-xs flex items-center justify-center shrink-0">
                                                        <?= $initial ?>
                                                    </div>
                                                    <span class="font-bold text-slate-900"><?= esc($b['nama_lengkap']) ?></span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?= esc($b['email']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-700 font-medium">
                                                <i class="fab fa-whatsapp text-emerald-500 mr-1"></i>
                                                <?= esc($b['no_wa'] ?: '-') ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    <?= ucfirst(str_replace('_', ' ', $b['role'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?= $b['status'] === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $b['status'] === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                                    <?= ucfirst($b['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Pagination Footer -->
                    <div class="px-5 py-3.5 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                        <span id="binaanCountSummary">Menampilkan <?= count($binaan ?? []) ?> binaan</span>
                        <div class="flex items-center gap-1 select-none" id="binaanPaginationControls">
                            <button type="button" id="prevBinaanBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </button>
                            <div id="binaanPaginationNumbers" class="flex items-center gap-1">
                                <!-- Generated by JS -->
                            </div>
                            <button type="button" id="nextBinaanBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                Next <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    AmanahTable.init({
        containerId: 'binaanTable',
        rowSelector: 'tbody tr.binaan-table-row',
        entityName: 'binaan',
        defaultPageSize: 10,
        searchInputId: 'binaanSearchInput',
        sortSelectId: 'binaanSortSelect',
        pageSizeSelectId: 'binaanPageSizeSelect',
        summaryId: 'binaanCountSummary',
        prevBtnId: 'prevBinaanBtn',
        nextBtnId: 'nextBinaanBtn',
        numbersId: 'binaanPaginationNumbers',
        emptyStateId: 'emptyBinaanRow',
        resetBtnId: 'resetBinaanFiltersBtn',
        defaultSort: 'nama_asc',
        filters: [
            { selectId: 'binaanStatusFilter', key: 'status', defaultValue: 'all' }
        ],
        sortDefinitions: {
            nama_asc: (a, b) => (a.getAttribute('data-nama') || '').localeCompare(b.getAttribute('data-nama') || '', 'id', { numeric: true, sensitivity: 'base' }),
            nama_desc: (a, b) => (b.getAttribute('data-nama') || '').localeCompare(a.getAttribute('data-nama') || '', 'id', { numeric: true, sensitivity: 'base' }),
            email_asc: (a, b) => (a.getAttribute('data-email') || '').localeCompare(b.getAttribute('data-email') || '', 'id', { numeric: true, sensitivity: 'base' }),
            email_desc: (a, b) => (b.getAttribute('data-email') || '').localeCompare(a.getAttribute('data-email') || '', 'id', { numeric: true, sensitivity: 'base' }),
            wa_asc: (a, b) => (a.getAttribute('data-wa') || '').localeCompare(b.getAttribute('data-wa') || '', 'id', { numeric: true, sensitivity: 'base' }),
            wa_desc: (a, b) => (b.getAttribute('data-wa') || '').localeCompare(a.getAttribute('data-wa') || '', 'id', { numeric: true, sensitivity: 'base' }),
            role_asc: (a, b) => (a.getAttribute('data-role') || '').localeCompare(b.getAttribute('data-role') || '', 'id', { numeric: true, sensitivity: 'base' }),
            role_desc: (a, b) => (b.getAttribute('data-role') || '').localeCompare(a.getAttribute('data-role') || '', 'id', { numeric: true, sensitivity: 'base' }),
            status_asc: (a, b) => (a.getAttribute('data-status') || '').localeCompare(b.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }),
            status_desc: (a, b) => (b.getAttribute('data-status') || '').localeCompare(a.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' })
        }
    });
});
</script>
<?= $this->endSection() ?>
