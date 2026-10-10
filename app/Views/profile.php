<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                
                <!-- Page Title & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">Profil Saya</h1>
                        <p class="text-sm text-gray-600 mt-1">Kelola data profil dan pengaturan keamanan kata sandi akun Anda.</p>
                    </div>
                </div>

                <!-- Main Profile Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Card Top Banner / Summary -->
                    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-[#5e0078] text-white font-extrabold text-2xl flex items-center justify-center shadow-md ring-4 ring-purple-100 shrink-0">
                            <?= strtoupper(substr($user['nama_lengkap'] ?? (session('nama') ?? 'U'), 0, 1)) ?>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 class="text-lg font-bold text-slate-900"><?= esc($user['nama_lengkap'] ?? session('nama')) ?></h2>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-[#5e0078] border border-purple-200 capitalize">
                                    <?= esc(str_replace('_', ' ', $user['role'] ?? session('role'))) ?>
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium"><?= esc($user['email'] ?? session('email')) ?></p>
                        </div>
                    </div>

                    <!-- Form Pengaturan Profil -->
                    <form action="<?= base_url('/profile/update') ?>" method="POST" class="p-6 sm:p-8 space-y-6">
                        <?= csrf_field() ?>

                        <!-- Grid Informasi Akun (Readonly) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50/70 rounded-xl border border-slate-200/70">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    <i class="fas fa-envelope text-slate-400 mr-1.5"></i> Alamat Email
                                </label>
                                <input type="email" value="<?= esc($user['email'] ?? session('email')) ?>" readonly 
                                       class="w-full bg-slate-100/90 border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-600 font-medium cursor-not-allowed select-none">
                                <span class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="fas fa-lock text-[10px]"></i> Email bersifat permanen dan tidak dapat diubah
                                </span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    <i class="fas fa-shield-alt text-slate-400 mr-1.5"></i> Hak Akses Akun
                                </label>
                                <div class="h-[34px] px-3.5 bg-purple-50/70 border border-purple-100 rounded-lg flex items-center">
                                    <span class="text-xs font-bold text-[#5e0078] capitalize">
                                        <?= esc(str_replace('_', ' ', $user['role'] ?? session('role'))) ?>
                                    </span>
                                </div>
                                <span class="text-[11px] text-slate-400 mt-1 block">Tingkat wewenang dalam sistem</span>
                            </div>
                        </div>

                        <!-- Ubah Nama Profil -->
                        <div>
                            <label for="page_nama_lengkap" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Profil <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative max-w-lg">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="nama_lengkap" id="page_nama_lengkap" required minlength="3" maxlength="100"
                                       value="<?= esc(old('nama_lengkap', $user['nama_lengkap'] ?? session('nama'))) ?>"
                                       placeholder="Masukkan nama lengkap Anda"
                                       class="w-full bg-white border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Nama ini akan ditampilkan pada header dan seluruh navigasi sistem.</p>
                        </div>

                        <!-- Section: Ubah Password -->
                        <div class="border-t border-slate-100 pt-6 space-y-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-key text-[#5e0078]"></i> Ubah Kata Sandi / Password
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Biarkan kolom-kolom password ini kosong jika Anda tidak ingin mengubah password akun.</p>
                            </div>

                            <!-- Password Lama -->
                            <div class="max-w-lg">
                                <label for="page_current_password" class="block text-xs font-medium text-slate-700 mb-1">
                                    Password Lama (Saat Ini)
                                </label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="page_current_password"
                                           placeholder="Masukkan password saat ini jika ingin mengganti"
                                           autocomplete="current-password"
                                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                                    <button type="button" onclick="toggleProfilePasswordVisibility('page_current_password', this)" 
                                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" tabindex="-1">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Grid Password Baru & Konfirmasi -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg">
                                <div>
                                    <label for="page_new_password" class="block text-xs font-medium text-slate-700 mb-1">
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="page_new_password" minlength="8"
                                               placeholder="Minimal 8 karakter"
                                               autocomplete="new-password"
                                               class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                                        <button type="button" onclick="toggleProfilePasswordVisibility('page_new_password', this)" 
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" tabindex="-1">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="page_confirm_password" class="block text-xs font-medium text-slate-700 mb-1">
                                        Konfirmasi Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="confirm_password" id="page_confirm_password" minlength="8"
                                               placeholder="Ulangi password baru"
                                               autocomplete="new-password"
                                               class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                                        <button type="button" onclick="toggleProfilePasswordVisibility('page_confirm_password', this)" 
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" tabindex="-1">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="<?= base_url('/dashboard') ?>" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                                Batal
                            </a>
                            <button type="submit" class="btn-interactive px-6 py-2.5 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold rounded-xl shadow-sm flex items-center gap-2 transition">
                                <i class="fas fa-check text-xs"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
<?= $this->endSection() ?>
