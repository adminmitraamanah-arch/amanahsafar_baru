<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto space-y-6">
                <div>
                    <a href="<?= base_url('/superadmin/packages') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#5e0078] transition mb-3">
                        <i class="fas fa-arrow-left text-[11px]"></i> Kembali ke Katalog Paket
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?= isset($package) ? 'Edit Paket' : 'Tambah Paket Baru' ?></h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi informasi paket perjalanan umroh atau haji.</p>
                </div>
                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <form method="POST" action="<?= base_url(isset($package) ? '/superadmin/packages/' . $package['id'] . '/update' : '/superadmin/packages/store') ?>" enctype="multipart/form-data" class="space-y-6">
                        <?= csrf_field() ?>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nama Paket <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" required value="<?= esc($package['nama'] ?? '') ?>" placeholder="Contoh: Paket Umroh VIP Ramadhan"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Foto / Gambar Paket</label>
                            <?php if (!empty($package['gambar'])): ?>
                                <div class="mb-3 flex items-center gap-4 p-3 bg-purple-50/50 border border-purple-100 rounded-xl">
                                    <img src="<?= base_url('uploads/packages/' . esc($package['gambar'])) ?>" alt="Gambar Paket" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-sm">
                                    <div>
                                        <span class="text-[11px] text-slate-500 block">Gambar Saat Ini:</span>
                                        <span class="text-xs font-semibold text-slate-800"><?= esc($package['gambar']) ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="gambar" accept="image/*"
                                class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-[#5e0078] file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-[#5e0078] hover:file:bg-purple-200 cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, PNG, WEBP (Maks 2MB). Disarankan rasio landscape 4:3 atau 16:9.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Kategori</label>
                                <input type="text" name="kategori" placeholder="Contoh: Umroh, Umroh Plus, Haji" value="<?= esc($package['kategori'] ?? '') ?>"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Harga (Rp) <span class="text-rose-500">*</span></label>
                                <input type="number" name="harga" step="1" required value="<?= esc($package['harga'] ?? '') ?>" placeholder="32000000"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Deskripsi & Fasilitas</label>
                            <textarea name="deskripsi" rows="4" placeholder="Tulis rincian fasilitas hotel bintang, maskapai, durasi program, dan benefit lainnya..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"><?= esc($package['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" class="btn-interactive flex-1 bg-[#5e0078] hover:bg-[#4c0062] text-white py-2.5 px-4 rounded-xl text-sm font-bold shadow-sm transition">
                                <i class="fas fa-save mr-1.5"></i> <?= isset($package) ? 'Simpan Perubahan' : 'Buat Paket Baru' ?>
                            </button>
                            <a href="<?= base_url('/superadmin/packages') ?>" class="btn-interactive flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 px-4 rounded-xl text-sm font-bold transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?= $this->endSection() ?>
