<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Pesanan Saya</h1>
                
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">No. Pesanan</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Total</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($orders ?? [] as $order): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $order['no_order'] ?></td>
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
                                <?php if (empty($orders)): ?>
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
