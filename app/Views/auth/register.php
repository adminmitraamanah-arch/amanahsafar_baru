<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-6 py-12">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-8 rounded-t-2xl">
            <h1 class="text-3xl font-bold text-white">Daftar</h1>
            <p class="text-blue-100 mt-2">Bergabunglah dengan jaringan Amanah Safar</p>
        </div>

        <div class="p-8">
            <form method="POST" class="space-y-4">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="John Doe">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                    <input type="tel" name="no_wa" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="+62812345678">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domisili</label>
                    <input type="text" name="domisili" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Jakarta, Indonesia">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (min. 8 karakter)</label>
                    <input type="password" name="password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher</label>
                    <input type="text" name="kode_voucher" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                        placeholder="XXXXXXXXX">
                </div>

                <button type="submit" class="btn-interactive w-full bg-blue-600 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-700 shadow-sm transition mt-6">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Sudah punya akun? <a href="<?= base_url('/login') ?>" class="text-blue-600 font-medium hover:underline">Login di sini</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
