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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Verifikasi Pengguna</h1>
                    </div>
                    <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs">
                        Total Pengajuan: <strong class="text-slate-900"><?= count($verifications ?? []) ?></strong>
                    </span>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
                        <i class="fas fa-circle-check text-emerald-500 text-base shrink-0"></i>
                        <span><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center gap-3">
                        <i class="fas fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                        <span><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 bg-white">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Data Verifikasi Bank Pengguna</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Informasi rekening bank untuk pencairan komisi mitra.</p>
                        </div>
                    </div>

                    <!-- Toolbar (Search, Filter, Sort, Per-Page) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="verificationsSearchInput" placeholder="Cari nama bank, rekening, user ID..." 
                                   class="w-full pl-9 pr-12 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <!-- Sort By -->
                            <select id="verificationsSortSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="created_desc">Urutkan: Tanggal (Terbaru)</option>
                                <option value="created_asc">Urutkan: Tanggal (Terlama)</option>
                                <option value="bank_asc">Urutkan: Bank (A - Z)</option>
                                <option value="bank_desc">Urutkan: Bank (Z - A)</option>
                                <option value="rekening_asc">Urutkan: No. Rekening (A - Z)</option>
                                <option value="rekening_desc">Urutkan: No. Rekening (Z - A)</option>
                                <option value="user_asc">Urutkan: User ID (Terkecil)</option>
                                <option value="user_desc">Urutkan: User ID (Terbesar)</option>
                                <option value="status_asc">Urutkan: Status (A - Z)</option>
                                <option value="status_desc">Urutkan: Status (Z - A)</option>
                            </select>

                            <!-- Filter Status -->
                            <select id="verificationsStatusFilter" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status</option>
                                <option value="menunggu_review">Status: Menunggu Review</option>
                                <option value="terverifikasi">Status: Terverifikasi</option>
                                <option value="ditolak">Status: Ditolak</option>
                                <option value="belum_verifikasi">Status: Belum Verif</option>
                            </select>

                            <!-- Per Page Size -->
                            <select id="verificationsPageSizeSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="10">10 / halaman</option>
                                <option value="25">25 / halaman</option>
                                <option value="50">50 / halaman</option>
                                <option value="100">100 / halaman</option>
                            </select>

                            <!-- Reset Button -->
                            <button type="button" id="resetVerificationsFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition text-xs" title="Reset Filter">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="verificationsTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5" data-sort-asc="user_asc" data-sort-desc="user_desc">Pengguna</th>
                                    <th class="px-5 py-3.5" data-sort-asc="bank_asc" data-sort-desc="bank_desc">Nama Bank</th>
                                    <th class="px-5 py-3.5" data-sort-asc="rekening_asc" data-sort-desc="rekening_desc">Nomor Rekening</th>
                                    <th class="px-5 py-3.5" data-sort-asc="status_asc" data-sort-desc="status_desc">Status</th>
                                    <th class="px-5 py-3.5" data-sort-asc="created_asc" data-sort-desc="created_desc">Dibuat Pada</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($verifications)): ?>
                                    <tr id="emptyVerificationRow">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Belum ada pengajuan verifikasi rekening.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr id="emptyVerificationRow" style="display: none;">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Tidak ada verifikasi rekening yang cocok dengan filter atau kata kunci.
                                        </td>
                                    </tr>
                                    <?php foreach ($verifications as $v): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors verification-table-row"
                                            data-user="<?= esc($v['user_id']) ?>"
                                            data-bank="<?= strtolower(esc($v['nama_bank'] ?? '')) ?>"
                                            data-rekening="<?= esc($v['nomor_rekening'] ?? '') ?>"
                                            data-status="<?= esc($v['status']) ?>"
                                            data-created="<?= strtotime($v['created_at']) ?>"
                                            data-search="<?= strtolower(esc($v['user_id'] . ' ' . ($v['nama_lengkap'] ?? '') . ' ' . ($v['email'] ?? '') . ' ' . ($v['kode_mitra'] ?? '') . ' ' . ($v['nama_bank'] ?? '') . ' ' . ($v['nomor_rekening'] ?? '') . ' ' . $v['status'])) ?>">
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5 flex-wrap">
                                                    <span><?= esc($v['nama_lengkap'] ?? ('User #' . $v['user_id'])) ?></span>
                                                    <?php if (!empty($v['kode_mitra'])): ?>
                                                        <span class="px-1.5 py-0.5 rounded bg-purple-100 text-[#5e0078] text-[10px] font-mono font-bold"><?= esc($v['kode_mitra']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="font-mono text-[11px] text-slate-400 mt-0.5">ID: #<?= esc($v['user_id']) ?><?= !empty($v['email']) ? ' &bull; ' . esc($v['email']) : '' ?></div>
                                            </td>
                                            <td class="px-5 py-4 font-semibold text-slate-800">
                                                <?= esc($v['nama_bank'] ?: '-') ?>
                                            </td>
                                            <td class="px-5 py-4 font-mono text-slate-700">
                                                <?= esc($v['nomor_rekening'] ?: '-') ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php 
                                                    $statusBadge = match($v['status']) {
                                                        'belum_verifikasi' => ['class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400', 'label' => 'Belum Verif'],
                                                        'menunggu_review' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Menunggu Review'],
                                                        'terverifikasi' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Terverifikasi'],
                                                        'ditolak' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                                                        default => ['class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($v['status'])]
                                                    };
                                                ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $statusBadge['class'] ?> border">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $statusBadge['dot'] ?>"></span>
                                                    <?= $statusBadge['label'] ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-500">
                                                <?= date('d M Y, H:i', strtotime($v['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                                <a href="<?= base_url('/superadmin/verifications/' . $v['id']) ?>" 
                                                   class="w-8 h-8 rounded-xl inline-flex items-center justify-center bg-purple-50 text-[#5e0078] hover:bg-[#5e0078] hover:text-white border border-purple-200 transition shadow-xs group" 
                                                   title="Detail Verifikasi">
                                                    <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Pagination Footer -->
                    <div class="px-5 py-3.5 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                        <span id="verificationCountSummary">Menampilkan <?= count($verifications ?? []) ?> pengajuan</span>
                        <div class="flex items-center gap-1 select-none" id="verificationPaginationControls">
                            <button type="button" id="prevVerificationBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </button>
                            <div id="verificationPaginationNumbers" class="flex items-center gap-1">
                                <!-- Generated by JS -->
                            </div>
                            <button type="button" id="nextVerificationBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
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
        containerId: 'verificationsTable',
        rowSelector: 'tbody tr.verification-table-row',
        entityName: 'pengajuan',
        defaultPageSize: 10,
        searchInputId: 'verificationsSearchInput',
        sortSelectId: 'verificationsSortSelect',
        pageSizeSelectId: 'verificationsPageSizeSelect',
        summaryId: 'verificationCountSummary',
        prevBtnId: 'prevVerificationBtn',
        nextBtnId: 'nextVerificationBtn',
        numbersId: 'verificationPaginationNumbers',
        emptyStateId: 'emptyVerificationRow',
        resetBtnId: 'resetVerificationsFiltersBtn',
        defaultSort: 'created_desc',
        filters: [
            { selectId: 'verificationsStatusFilter', key: 'status', defaultValue: 'all' }
        ],
        sortDefinitions: {
            created_desc: (a, b) => (parseInt(b.getAttribute('data-created'), 10) || 0) - (parseInt(a.getAttribute('data-created'), 10) || 0),
            created_asc: (a, b) => (parseInt(a.getAttribute('data-created'), 10) || 0) - (parseInt(b.getAttribute('data-created'), 10) || 0),
            bank_asc: (a, b) => (a.getAttribute('data-bank') || '').localeCompare(b.getAttribute('data-bank') || '', 'id', { numeric: true, sensitivity: 'base' }),
            bank_desc: (a, b) => (b.getAttribute('data-bank') || '').localeCompare(a.getAttribute('data-bank') || '', 'id', { numeric: true, sensitivity: 'base' }),
            rekening_asc: (a, b) => (a.getAttribute('data-rekening') || '').localeCompare(b.getAttribute('data-rekening') || '', 'id', { numeric: true, sensitivity: 'base' }),
            rekening_desc: (a, b) => (b.getAttribute('data-rekening') || '').localeCompare(a.getAttribute('data-rekening') || '', 'id', { numeric: true, sensitivity: 'base' }),
            user_desc: (a, b) => (parseInt(b.getAttribute('data-user'), 10) || 0) - (parseInt(a.getAttribute('data-user'), 10) || 0),
            user_asc: (a, b) => (parseInt(a.getAttribute('data-user'), 10) || 0) - (parseInt(b.getAttribute('data-user'), 10) || 0),
            status_asc: (a, b) => (a.getAttribute('data-status') || '').localeCompare(b.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }),
            status_desc: (a, b) => (b.getAttribute('data-status') || '').localeCompare(a.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' })
        }
    });
});
</script>
<?= $this->endSection() ?>
