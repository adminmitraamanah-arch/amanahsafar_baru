<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl">
                <a href="/app/orders" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
                
                <div class="bg-white rounded-lg shadow p-8 mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $order['no_order'] ?? '-' ?></h1>
                    
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Pesan</p>
                            <p class="font-medium"><?= date('d M Y', strtotime($order['created_at'])) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Total</p>
                            <p class="font-medium text-lg">Rp <?= number_format($order['total'], 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Status</p>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
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
                        </div>
                    </div>
                    
                    <?php if ($order['catatan']): ?>
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <p class="text-sm text-gray-600">Catatan</p>
                            <p class="text-gray-900"><?= $order['catatan'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?= $this->endSection() ?>
