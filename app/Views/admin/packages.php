<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Paket</h1>
                    <a href="/admin/packages/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Paket Baru
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($packages ?? [] as $pkg): ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2"><?= $pkg['nama'] ?></h3>
                                <p class="text-gray-600 text-sm mb-3"><?= substr($pkg['deskripsi'] ?? '', 0, 80) ?>...</p>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-blue-600">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        <?= $pkg['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= ucfirst($pkg['status']) ?>
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <a href="/admin/packages/<?= $pkg['id'] ?>/edit" class="flex-1 text-center bg-blue-100 text-blue-600 px-3 py-2 rounded hover:bg-blue-200 text-sm font-medium">Edit</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
