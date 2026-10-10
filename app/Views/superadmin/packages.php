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
                    <a href="<?= base_url('/superadmin/packages/create') ?>" class="btn-interactive bg-[#5e0078] text-white px-4 py-2.5 rounded-xl hover:bg-[#4c0062] font-semibold text-sm shadow-sm transition">
                        <i class="fas fa-plus mr-2"></i> Paket Baru
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($packages ?? [] as $pkg): ?>
                        <div class="card-interactive bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                            <?php if (!empty($pkg['gambar'])): ?>
                                <div class="h-44 w-full overflow-hidden bg-gray-100">
                                    <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="h-44 w-full bg-gradient-to-br from-[#5e0078] to-[#8f20ad] flex items-center justify-center text-white">
                                    <i class="fas fa-kaaba text-4xl opacity-80"></i>
                                </div>
                            <?php endif; ?>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2"><?= esc($pkg['nama']) ?></h3>
                                    <p class="text-gray-600 text-sm mb-3"><?= substr(esc($pkg['deskripsi'] ?? ''), 0, 80) ?>...</p>
                                </div>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        <?= $pkg['status'] === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= ucfirst($pkg['status']) ?>
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <a href="<?= base_url('/superadmin/packages/' . $pkg['id'] . '/edit') ?>" class="btn-interactive flex-1 text-center bg-purple-100/70 text-[#5e0078] px-3 py-2 rounded-lg hover:bg-purple-200/80 text-xs font-bold transition">Edit</a>
                                    <form method="POST" action="<?= base_url('/superadmin/packages/' . $pkg['id'] . '/delete') ?>" style="flex:1;" onsubmit="return confirm('Hapus paket ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-interactive w-full bg-rose-50 text-rose-600 px-3 py-2 rounded-lg hover:bg-rose-100 text-xs font-bold transition">Hapus</button>
                                    </form>
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
