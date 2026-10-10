<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen">
    <!-- Left side - Image -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 items-center justify-center">
        <div class="text-center text-white">
            <div class="w-20 h-20 bg-white rounded-2xl mx-auto mb-6 flex items-center justify-center">
                <span class="text-4xl font-bold text-blue-600">AS</span>
            </div>
            <h1 class="text-4xl font-bold mb-4">Amanah Safar</h1>
            <p class="text-xl text-blue-100 max-w-md">Sistem Manajemen Mitra & Perjalanan Terpercaya</p>
        </div>
    </div>

    <!-- Right side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Login</h2>
                <p class="text-gray-600 mt-2">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            <form method="POST" class="space-y-5">
                <?= csrf_field() ?>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Role</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="pusat">Pusat</option>
                        <option value="cabang">Cabang</option>
                        <option value="manager_mitra">Manager Mitra</option>
                        <option value="mitra">Mitra</option>
                    </select>
                </div>

                <button type="submit" class="btn-interactive w-full bg-blue-600 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-700 shadow-sm transition">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Belum punya akun? <a href="<?= base_url('/register') ?>" class="text-blue-600 font-medium hover:underline">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
