<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Mitra</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ringkasan performa penjualan dan jaringan kemitraan Anda.</p>
                    </div>
                </div>

                <!-- Stats Grid (DevzeMart 4-card grid) -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                    <a href="<?= base_url('/app/orders') ?>" class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-base transition-transform group-hover:scale-105">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Pesanan</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= $total_orders ?? 0 ?></span>
                        </div>
                    </a>

                    <a href="<?= base_url('/app/commissions') ?>" class="bg-white rounded-2xl border border-amber-200/80 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base transition-transform group-hover:scale-105">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">Tahap DP</span>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Potensi Komisi</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-amber-600">Rp <?= number_format($potensi_komisi ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </a>

                    <a href="<?= base_url('/app/commissions') ?>" class="bg-white rounded-2xl border border-emerald-200/80 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base transition-transform group-hover:scale-105">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Siap Tarik</span>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Saldo Siap Cair</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-emerald-600">Rp <?= number_format($saldo_siap_cair ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </a>

                    <a href="<?= base_url('/app/binaan') ?>" class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base transition-transform group-hover:scale-105">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Binaan</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= $total_binaan ?? 0 ?></span>
                        </div>
                    </a>
                </div>

                <!-- Recent Orders Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Pesanan Terbaru</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi pemesanan paket umroh jaringan Anda.</p>
                        </div>
                        <a href="<?= base_url('/app/orders') ?>" class="text-xs font-semibold text-[#5e0078] hover:underline">Lihat Semua</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">No. Pesanan</th>
                                    <th class="px-5 py-3.5">Tanggal</th>
                                    <th class="px-5 py-3.5">Total Biaya</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($recent_orders)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-inbox text-3xl opacity-40 mb-2 block"></i>
                                            Belum ada pesanan terbaru.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="px-5 py-4 font-mono font-semibold text-slate-900 text-xs">
                                                <?= esc($order['no_order']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-slate-500 text-xs">
                                                <?= date('d M Y', strtotime($order['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4 font-bold text-[#5e0078]">
                                                Rp <?= number_format($order['total'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php 
                                                    $statusBadge = match($order['status']) {
                                                        'menunggu_pembayaran' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Pembayaran'],
                                                        'dibayar' => ['class' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Dibayar'],
                                                        'diproses' => ['class' => 'bg-purple-50 text-[#5e0078] border-purple-200', 'label' => 'Diproses'],
                                                        'selesai' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Selesai'],
                                                        'dibatalkan' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Dibatalkan'],
                                                        default => ['class' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => ucfirst($order['status'])]
                                                    };
                                                ?>
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium border <?= $statusBadge['class'] ?>">
                                                    <?= $statusBadge['label'] ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <a href="<?= base_url('/app/orders/' . $order['id']) ?>" 
                                                   class="btn-interactive inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200 transition">
                                                    <span>Detail</span>
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
<?= $this->endSection() ?>
