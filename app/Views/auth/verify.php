<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <?= $this->include('layouts/navbar') ?>
    
    <div class="max-w-3xl mx-auto px-6 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Data Diri</h1>
            <p class="text-gray-600 mb-8">Lengkapi data diri Anda untuk melanjutkan proses aktivasi akun</p>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>

                <!-- KTP Upload -->
                <div class="border-2 border-dashed border-blue-300 rounded-lg p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Upload KTP</label>
                    <input type="file" name="ktp" accept="image/*" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Selfie Upload -->
                <div class="border-2 border-dashed border-blue-300 rounded-lg p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Upload Selfie (Swafoto)</label>
                    <input type="file" name="swafoto" accept="image/*" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Bank Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                        <input type="text" name="nama_bank" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="BCA, Mandiri, dst">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening</label>
                    <input type="text" name="nama_pemilik_rekening" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Lanjutkan ke Perjanjian
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
