<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Pesanan</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= $total_orders ?? 0 ?></p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-[#5e0078] transition-all duration-300 group-hover:scale-110 group-hover:bg-[#5e0078] group-hover:text-white">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Komisi Pending</p>
                                <p class="text-2xl font-extrabold text-gray-900 mt-1">Rp 0</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Binaan</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">0</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Status Akun</p>
                                <p class="text-sm font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-6 py-3.5 text-left font-bold">No. Pesanan</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Total</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Status</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php foreach ($recent_orders ?? [] as $order): ?>
                                    <tr class="table-row-interactive hover:bg-purple-50/40 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= $order['no_order'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                                <?php 
                                                    $statusColor = match($order['status']) {
                                                        'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-800',
                                                        'dibayar' => 'bg-blue-100 text-blue-800',
                                                        'diproses' => 'bg-purple-100 text-purple-800',
                                                        'selesai' => 'bg-green-100 text-green-800',
                                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    echo $statusColor;
                                                ?>">
                                                <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="/app/orders/<?= $order['id'] ?>" class="text-blue-600 hover:underline">Lihat</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($recent_orders)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl opacity-30 mb-2"></i>
                                            <p>Belum ada pesanan</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
