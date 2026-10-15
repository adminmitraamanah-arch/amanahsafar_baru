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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pesanan Saya</h1>
                        <p class="text-xs text-slate-500 mt-1">Daftar riwayat pesanan paket umrah dan status pemesanannya.</p>
                    </div>
                    <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs">
                        Total Pesanan: <strong class="text-slate-900"><?= count($orders ?? []) ?></strong>
                    </span>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <!-- Toolbar (Search, Filter, Sort, Per-Page) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="ordersSearchInput" placeholder="Cari nomor pesanan..." 
                                   class="w-full pl-9 pr-12 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <!-- Sort By -->
                            <select id="ordersSortSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="created_desc">Urutkan: Tanggal (Terbaru)</option>
                                <option value="created_asc">Urutkan: Tanggal (Terlama)</option>
                                <option value="total_desc">Urutkan: Total (Tertinggi)</option>
                                <option value="total_asc">Urutkan: Total (Terendah)</option>
                                <option value="order_desc">Urutkan: No. Pesanan (Z - A)</option>
                                <option value="order_asc">Urutkan: No. Pesanan (A - Z)</option>
                                <option value="status_asc">Urutkan: Status (A - Z)</option>
                                <option value="status_desc">Urutkan: Status (Z - A)</option>
                            </select>

                            <!-- Filter Status -->
                            <select id="ordersStatusFilter" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status</option>
                                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                                <option value="dibayar">Dibayar</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>

                            <!-- Per Page Size -->
                            <select id="ordersPageSizeSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="10">10 / halaman</option>
                                <option value="25">25 / halaman</option>
                                <option value="50">50 / halaman</option>
                                <option value="100">100 / halaman</option>
                            </select>

                            <!-- Reset Button -->
                            <button type="button" id="resetOrdersFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition text-xs" title="Reset Filter">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="ordersTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5" data-sort-asc="order_asc" data-sort-desc="order_desc">No. Pesanan</th>
                                    <th class="px-5 py-3.5" data-sort-asc="created_asc" data-sort-desc="created_desc">Tanggal</th>
                                    <th class="px-5 py-3.5" data-sort-asc="total_asc" data-sort-desc="total_desc">Total Tagihan</th>
                                    <th class="px-5 py-3.5">Terbayar / Sisa</th>
                                    <th class="px-5 py-3.5" data-sort-asc="status_asc" data-sort-desc="status_desc">Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($orders)): ?>
                                    <tr id="emptyOrderRow">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada pesanan dibuat.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr id="emptyOrderRow" style="display: none;">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-search text-3xl mb-2 text-slate-300 block"></i>
                                            Tidak ada pesanan yang cocok dengan filter atau kata kunci.
                                        </td>
                                    </tr>
                                    <?php foreach ($orders as $order): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors order-table-row"
                                            data-order="<?= strtolower(esc($order['no_order'])) ?>"
                                            data-total="<?= (int)$order['total'] ?>"
                                            data-status="<?= esc($order['status']) ?>"
                                            data-created="<?= strtotime($order['created_at']) ?>"
                                            data-search="<?= strtolower(esc($order['no_order'] . ' ' . $order['status'] . ' ' . ($order['status_pembayaran'] ?? '') . ' ' . $order['total'])) ?>">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-900 text-xs sm:text-sm">
                                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200/70 select-all"><?= esc($order['no_order']) ?></span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600 font-medium">
                                                <?= date('d M Y, H:i', strtotime($order['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4 font-bold text-[#5e0078]">
                                                Rp <?= number_format($order['total'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs">
                                                <div class="font-bold text-emerald-600">
                                                    Rp <?= number_format($order['total_dibayar'] ?? 0, 0, ',', '.') ?>
                                                </div>
                                                <div class="text-[11px] <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'text-rose-500' : 'text-slate-400' ?>">
                                                    Sisa: Rp <?= number_format($order['sisa_tagihan'] ?? $order['total'], 0, ',', '.') ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex flex-col gap-1 items-start">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                                                        <?php
                                                        $statusPay = $order['status_pembayaran'] ?? 'menunggu_pembayaran';
                                                        echo match($statusPay) {
                                                            'lunas' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                            'dp_diterima' => 'bg-sky-50 text-sky-700 border border-sky-200',
                                                            default => 'bg-amber-50 text-amber-700 border border-amber-200'
                                                        };
                                                        ?>">
                                                        <span class="w-1.5 h-1.5 rounded-full <?= $statusPay === 'lunas' ? 'bg-emerald-500' : ($statusPay === 'dp_diterima' ? 'bg-sky-500' : 'bg-amber-500') ?>"></span>
                                                        <?= $statusPay === 'lunas' ? 'Lunas' : ($statusPay === 'dp_diterima' ? 'DP Masuk' : 'Belum Bayar') ?>
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 font-medium pl-1">
                                                        <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <a href="<?= base_url('/app/orders/' . $order['id']) ?>" class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold text-xs border border-purple-200 transition">
                                                    <span>Detail</span>
                                                    <i class="fas fa-arrow-right text-[10px]"></i>
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
                        <span id="orderCountSummary">Menampilkan <?= count($orders ?? []) ?> pesanan</span>
                        <div class="flex items-center gap-1 select-none" id="orderPaginationControls">
                            <button type="button" id="prevOrderBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </button>
                            <div id="orderPaginationNumbers" class="flex items-center gap-1">
                                <!-- Generated by JS -->
                            </div>
                            <button type="button" id="nextOrderBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
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
        containerId: 'ordersTable',
        rowSelector: 'tbody tr.order-table-row',
        entityName: 'pesanan',
        defaultPageSize: 10,
        searchInputId: 'ordersSearchInput',
        sortSelectId: 'ordersSortSelect',
        pageSizeSelectId: 'ordersPageSizeSelect',
        summaryId: 'orderCountSummary',
        prevBtnId: 'prevOrderBtn',
        nextBtnId: 'nextOrderBtn',
        numbersId: 'orderPaginationNumbers',
        emptyStateId: 'emptyOrderRow',
        resetBtnId: 'resetOrdersFiltersBtn',
        defaultSort: 'created_desc',
        filters: [
            { selectId: 'ordersStatusFilter', key: 'status', defaultValue: 'all' }
        ],
        sortDefinitions: {
            created_desc: (a, b) => (parseInt(b.getAttribute('data-created'), 10) || 0) - (parseInt(a.getAttribute('data-created'), 10) || 0),
            created_asc: (a, b) => (parseInt(a.getAttribute('data-created'), 10) || 0) - (parseInt(b.getAttribute('data-created'), 10) || 0),
            total_desc: (a, b) => (parseInt(b.getAttribute('data-total'), 10) || 0) - (parseInt(a.getAttribute('data-total'), 10) || 0),
            total_asc: (a, b) => (parseInt(a.getAttribute('data-total'), 10) || 0) - (parseInt(b.getAttribute('data-total'), 10) || 0),
            order_desc: (a, b) => (b.getAttribute('data-order') || '').localeCompare(a.getAttribute('data-order') || '', 'id', { numeric: true, sensitivity: 'base' }),
            order_asc: (a, b) => (a.getAttribute('data-order') || '').localeCompare(b.getAttribute('data-order') || '', 'id', { numeric: true, sensitivity: 'base' }),
            status_asc: (a, b) => (a.getAttribute('data-status') || '').localeCompare(b.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }),
            status_desc: (a, b) => (b.getAttribute('data-status') || '').localeCompare(a.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' })
        }
    });
});
</script>
<?= $this->endSection() ?>
