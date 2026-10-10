<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span>&gt;</span>
                            <span class="text-slate-600 font-semibold">Packages</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Paket</h1>
                    </div>
                    <a href="<?= base_url('/superadmin/packages/create') ?>" 
                       class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Paket Baru</span>
                    </a>
                </div>

                <!-- Package Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($packages)): ?>
                        <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs">
                            <i class="fas fa-box-open text-3xl mb-2 text-slate-300"></i>
                            <p>Belum ada paket umroh terdaftar.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($packages as $pkg): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition duration-200">
                                <?php if (!empty($pkg['gambar'])): ?>
                                    <div class="h-44 w-full overflow-hidden bg-slate-100">
                                        <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="h-44 w-full bg-[#5e0078] flex items-center justify-center text-white">
                                        <i class="fas fa-kaaba text-4xl opacity-80"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                    <div>
                                        <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                        <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug"><?= esc($pkg['nama']) ?></h3>
                                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed"><?= esc($pkg['deskripsi'] ?? '') ?></p>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                        <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?= $pkg['status'] === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= $pkg['status'] === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                            <?= ucfirst($pkg['status']) ?>
                                        </span>
                                    </div>

                                    <div class="flex gap-2 pt-1">
                                        <a href="<?= base_url('/superadmin/packages/' . $pkg['id'] . '/edit') ?>" 
                                           class="btn-interactive flex-1 text-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 px-3 py-2 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form method="POST" action="<?= base_url('/superadmin/packages/' . $pkg['id'] . '/delete') ?>" style="flex:1;" onsubmit="return confirm('Hapus paket ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn-interactive w-full bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/80 px-3 py-2 rounded-xl text-xs font-bold transition">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>
<?= $this->endSection() ?>
