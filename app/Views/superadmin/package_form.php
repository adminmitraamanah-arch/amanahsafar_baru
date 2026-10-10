<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-3xl">
                <a href="<?= base_url('/superadmin/packages') ?>" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
                
                <div class="bg-white rounded-lg shadow p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8"><?= isset($package) ? 'Edit Paket' : 'Paket Baru' ?></h1>

                    <form method="POST" action="<?= base_url(isset($package) ? '/superadmin/packages/' . $package['id'] . '/update' : '/superadmin/packages/store') ?>" enctype="multipart/form-data" class="space-y-6">
                        <?= csrf_field() ?>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Paket <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" required value="<?= $package['nama'] ?? '' ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto / Gambar Paket</label>
                            <?php if (!empty($package['gambar'])): ?>
                                <div class="mb-3 flex items-center gap-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <img src="<?= base_url('uploads/packages/' . esc($package['gambar'])) ?>" alt="Gambar Paket" class="w-20 h-14 object-cover rounded-md border shadow-sm">
                                    <div>
                                        <span class="text-xs text-gray-500 block">Gambar Saat Ini:</span>
                                        <span class="text-xs font-semibold text-gray-800"><?= esc($package['gambar']) ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="gambar" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WEBP (Maks 2MB). Disarankan rasio landscape 4:3 atau 16:9.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <input type="text" name="kategori" placeholder="Contoh: Umroh, Umroh Plus, Haji" value="<?= $package['kategori'] ?? '' ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-rose-500">*</span></label>
                                <input type="number" name="harga" step="0.01" required value="<?= $package['harga'] ?? '' ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" placeholder="Tulis rincian fasilitas hotel, maskapai, dan durasi..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?= $package['deskripsi'] ?? '' ?></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-medium">
                                <?= isset($package) ? 'Update' : 'Buat' ?> Paket
                            </button>
                            <a href="/superadmin/packages" class="flex-1 text-center bg-gray-200 text-gray-900 py-2 rounded-lg hover:bg-gray-300 font-medium">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
