<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <i class="fas fa-plane text-blue-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-900">Amanah Safar</span>
                </div>
                <div class="flex gap-4">
                    <a href="/catalog" class="text-gray-700 hover:text-blue-600">← Kembali</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-64 bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                <i class="fas fa-kaaba text-white text-8xl opacity-80"></i>
            </div>
            
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $package['nama'] ?></h1>
                
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-3xl font-bold text-blue-600">Rp <?= number_format($package['harga'], 0, ',', '.') ?></span>
                    <?php if ($package['kategori']): ?>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            <?= ucfirst($package['kategori']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="prose max-w-none mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi Paket</h3>
                    <p class="text-gray-700 whitespace-pre-line"><?= $package['deskripsi'] ?? 'Deskripsi tidak tersedia' ?></p>
                </div>

                <div class="flex gap-4">
                    <?php if (session('logged_in')): ?>
                        <button class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-shopping-cart mr-2"></i> Pesan Sekarang
                        </button>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" class="flex-1 text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Memesan
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('/catalog') ?>" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
