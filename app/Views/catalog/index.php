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
                    <a href="<?= base_url('/login') ?>" class="text-gray-700 hover:text-blue-600 font-medium">Masuk</a>
                    <a href="<?= base_url('/register') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Paket Umroh & Haji</h1>
            <p class="text-lg text-gray-600">Pilih paket perjalanan terbaik untuk ibadah Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($packages ?? [] as $pkg): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col card-interactive">
                    <?php if (!empty($pkg['gambar'])): ?>
                        <div class="h-48 overflow-hidden bg-slate-900">
                            <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                        </div>
                    <?php else: ?>
                        <div class="h-48 bg-gradient-to-br from-[#5e0078] to-[#8f20ad] flex items-center justify-center">
                            <i class="fas fa-kaaba text-white text-6xl opacity-90"></i>
                        </div>
                    <?php endif; ?>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($pkg['nama']) ?></h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?= esc($pkg['deskripsi'] ?? 'Deskripsi tidak tersedia') ?></p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-2xl font-bold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                            </div>

                            <a href="<?= base_url('/catalog/' . $pkg['id']) ?>" class="btn-interactive block w-full text-center bg-[#5e0078] text-white py-3 rounded-xl hover:bg-[#4c0062] font-semibold shadow-md transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($packages)): ?>
            <div class="text-center py-12">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada paket tersedia</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
