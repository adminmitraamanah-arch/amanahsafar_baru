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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Pesanan</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar seluruh transaksi pemesanan paket umroh dari jaringan mitra, manager, dan cabang.</p>
                    </div>
                    <span class="text-xs text-slate-500 font-medium bg-white px-3.5 py-2 rounded-xl border border-slate-200/80 shadow-2xs">
                        Total Transaksi: <strong class="text-slate-900"><?= count($orders ?? []) ?></strong>
                    </span>
                </div>

                <!-- Main Orders Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Toolbar (Search & Filters) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="adminOrderSearch" placeholder="Cari no pesanan, nama pemesan..." 
                                   class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <select id="adminPaymentFilter" onchange="filterOrders()" 
                                    class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Pembayaran</option>
                                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                                <option value="dp_diterima">DP Diterima</option>
                                <option value="lunas">Lunas Penuh</option>
                            </select>

                            <select id="adminStatusFilter" onchange="filterOrders()" 
                                    class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status Pesanan</option>
                                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                                <option value="dp_diterima">DP Diterima</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="adminOrdersTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">No. Pesanan</th>
                                    <th class="px-5 py-3.5">Tanggal</th>
                                    <th class="px-5 py-3.5">Pemesan / Mitra</th>
                                    <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                                    <th class="px-5 py-3.5">Pembayaran</th>
                                    <th class="px-5 py-3.5 text-center">Status Pesanan</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada pesanan masuk di sistem.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $o): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors order-row"
                                            data-status="<?= esc($o['status']) ?>"
                                            data-payment="<?= esc($o['status_pembayaran'] ?? 'menunggu_pembayaran') ?>"
                                            data-search="<?= strtolower(esc($o['no_order'] . ' ' . ($o['nama_mitra'] ?? '') . ' ' . ($o['email_mitra'] ?? '') . ' ' . ($o['role_mitra'] ?? ''))) ?>">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-900 text-xs sm:text-sm whitespace-nowrap">
                                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200/70 select-all"><?= esc($o['no_order']) ?></span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600 font-medium whitespace-nowrap">
                                                <?= date('d M Y, H:i', strtotime($o['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-slate-900 text-xs sm:text-sm">
                                                    <?= esc($o['nama_mitra'] ?? 'User #' . $o['user_id']) ?>
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-[#5e0078] border border-purple-100">
                                                        <?= esc(str_replace('_', ' ', $o['role_mitra'] ?? 'mitra')) ?>
                                                    </span>
                                                    <?php if (!empty($o['no_wa_mitra'])): ?>
                                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $o['no_wa_mitra']) ?>" target="_blank" class="text-[11px] text-emerald-600 hover:underline">
                                                            <i class="fab fa-whatsapp"></i> <?= esc($o['no_wa_mitra']) ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-right font-bold text-[#5e0078] whitespace-nowrap">
                                                Rp <?= number_format($o['total'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider w-fit
                                                        <?php
                                                        $payStatus = $o['status_pembayaran'] ?? 'menunggu_pembayaran';
                                                        echo match($payStatus) {
                                                            'lunas' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                            'dp_diterima' => 'bg-sky-50 text-sky-700 border border-sky-200',
                                                            default => 'bg-amber-50 text-amber-700 border border-amber-200'
                                                        };
                                                        ?>">
                                                        <span class="w-1.5 h-1.5 rounded-full <?= $payStatus === 'lunas' ? 'bg-emerald-500' : ($payStatus === 'dp_diterima' ? 'bg-sky-500' : 'bg-amber-500') ?>"></span>
                                                        <?= $payStatus === 'lunas' ? 'Lunas' : ($payStatus === 'dp_diterima' ? 'DP Masuk' : 'Belum Bayar') ?>
                                                    </span>
                                                    <div class="text-[11px] text-slate-500 mt-1">
                                                        Masuk: <strong class="text-emerald-600">Rp <?= number_format($o['total_dibayar'] ?? 0, 0, ',', '.') ?></strong>
                                                    </div>
                                                    <div class="text-[10px] <?= ($o['sisa_tagihan'] ?? $o['total']) > 0 ? 'text-rose-500' : 'text-slate-400' ?>">
                                                        Sisa: Rp <?= number_format($o['sisa_tagihan'] ?? $o['total'], 0, ',', '.') ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($o['status']) {
                                                        'menunggu_pembayaran' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'dp_diterima' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                        'diproses' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                                        'selesai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'dibatalkan' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                                    };
                                                    ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $o['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                                <a href="<?= base_url('/' . (session('role') === 'admin' ? 'admin' : 'superadmin') . '/orders/' . $o['id']) ?>" 
                                                   class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold text-xs border border-purple-200 transition">
                                                    <span>Kelola</span>
                                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
function filterOrders() {
    const payFilter = document.getElementById('adminPaymentFilter').value;
    const statusFilter = document.getElementById('adminStatusFilter').value;
    const search = document.getElementById('adminOrderSearch').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.order-row');

    rows.forEach(r => {
        const rowPay = r.getAttribute('data-payment');
        const rowStatus = r.getAttribute('data-status');
        const rowSearch = r.getAttribute('data-search') || '';

        const matchPay = (payFilter === 'all' || rowPay === payFilter);
        const matchStatus = (statusFilter === 'all' || rowStatus === statusFilter);
        const matchSearch = (search === '' || rowSearch.includes(search));

        if (matchPay && matchStatus && matchSearch) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}

document.getElementById('adminOrderSearch')?.addEventListener('input', filterOrders);
</script>

<?= $this->endSection() ?>
