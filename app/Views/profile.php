<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                
                <!-- Header Section -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">Profil Saya</h1>
                            <p class="text-sm text-gray-600 mt-1">Kelola data profil dan pengaturan keamanan kata sandi akun Anda.</p>
                        </div>
                        <a href="<?= base_url('/dashboard') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-purple-200 text-slate-700 hover:text-[#5e0078] rounded-xl text-xs font-semibold shadow-sm transition self-start sm:self-auto">
                            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>

                <!-- Main Profile Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Card Top Banner / User Badge -->
                    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-5 bg-slate-50/50">
                        <div class="w-16 h-16 rounded-2xl bg-[#5e0078] text-white font-extrabold text-2xl flex items-center justify-center shadow-md ring-4 ring-purple-100 shrink-0 select-none">
                            <?= strtoupper(substr($user['nama_lengkap'] ?? (session('nama') ?? 'U'), 0, 1)) ?>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 class="text-lg font-bold text-slate-900"><?= esc($user['nama_lengkap'] ?? session('nama')) ?></h2>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-[#5e0078] border border-purple-200 capitalize">
                                    <?= esc(str_replace('_', ' ', $user['role'] ?? session('role'))) ?>
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                <i class="fas fa-envelope text-slate-400 text-xs"></i> <?= esc($user['email'] ?? session('email')) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Form Pengaturan Profil -->
                    <form id="profilePageForm" action="<?= base_url('/profile/update') ?>" method="POST" class="p-6 sm:p-8 space-y-6">
                        <?= csrf_field() ?>

                        <!-- Grid Informasi Akun (Readonly) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50/80 rounded-xl border border-slate-200/70">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    <i class="fas fa-envelope text-slate-400 mr-1.5"></i> Alamat Email
                                </label>
                                <input type="email" value="<?= esc($user['email'] ?? session('email')) ?>" readonly 
                                       class="w-full bg-slate-100/90 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-600 font-medium cursor-not-allowed select-none">
                                <span class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="fas fa-lock text-[10px]"></i> Email bersifat permanen dan tidak dapat diubah
                                </span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    <i class="fas fa-shield-alt text-slate-400 mr-1.5"></i> Hak Akses Akun
                                </label>
                                <div class="h-[38px] px-3.5 bg-purple-50/70 border border-purple-100 rounded-lg flex items-center">
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
                                       placeholder="Masukkan nama profil Anda"
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
                                <p class="text-xs text-slate-500 mt-0.5">Biarkan kolom-kolom password ini kosong jika Anda tidak ingin mengubah password akun saat ini.</p>
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
                                    <button type="button" onclick="togglePasswordVisibility('page_current_password', this)" 
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
                                        <button type="button" onclick="togglePasswordVisibility('page_new_password', this)" 
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
                                        <button type="button" onclick="togglePasswordVisibility('page_confirm_password', this)" 
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" tabindex="-1">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="<?= base_url('/dashboard') ?>" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                                Batal
                            </a>
                            <button type="submit" class="btn-interactive px-6 py-2.5 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold rounded-xl shadow-sm flex items-center gap-2 transition">
                                <i class="fas fa-check text-xs"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Dokumen Perjanjian Kerja Sama (PKS) Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-[#5e0078] flex items-center justify-center text-xl shrink-0 shadow-inner">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Perjanjian Kerja Sama (PKS)</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Dokumen legalitas resmi kemitraan Anda dengan PT Amanah Tangguh Mandiri.</p>
                            </div>
                        </div>
                        <div>
                            <?php if (!empty($pksInfo['is_accepted'])): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-check-circle text-xs text-emerald-500"></i> Disetujui &amp; Aktif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class="fas fa-clock text-xs text-amber-500"></i> Belum Disetujui
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        <?php if (!empty($pksInfo['is_accepted'])): ?>
                            <!-- Detail Metadata PKS -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50/80 rounded-xl border border-slate-200/70">
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                        Nomor Perjanjian (PKS)
                                    </span>
                                    <div class="text-xs font-mono font-bold text-[#5e0078] bg-white px-3 py-2 rounded-lg border border-slate-200 flex items-center justify-between">
                                        <span><?= esc($pksInfo['pks_number']) ?></span>
                                        <i class="fas fa-certificate text-purple-400"></i>
                                    </div>
                                    <span class="text-[11px] text-slate-400 mt-1 block">Nomor registrasi unik kemitraan resmi</span>
                                </div>
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                        Waktu Persetujuan Digital
                                    </span>
                                    <div class="text-xs font-semibold text-slate-800 bg-white px-3 py-2 rounded-lg border border-slate-200 flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-emerald-500"></i>
                                        <span><?= format_tanggal_indonesia($pksInfo['accepted_at'], true) ?> WIB</span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 mt-1 block">Tercatat sah pada log sistem Amanah Safar</span>
                                </div>
                            </div>

                            <!-- Tindakan Unduh PDF -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                                <div class="text-xs text-slate-600 flex items-start gap-2 max-w-xl">
                                    <i class="fas fa-info-circle text-[#5e0078] mt-0.5 shrink-0"></i>
                                    <span>Unduh salinan resmi dokumen PKS dalam format PDF untuk keperluan arsip pribadi atau kelengkapan administrasi kemitraan Anda.</span>
                                </div>
                                <a href="<?= esc($pksInfo['download_url']) ?>" 
                                   class="btn-interactive inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold rounded-xl shadow-sm transition shrink-0">
                                    <i class="fas fa-file-pdf text-sm"></i>
                                    <span>Download PDF PKS</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Status Belum Disetujui -->
                            <div class="p-4 bg-amber-50/70 rounded-xl border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-exclamation-triangle text-amber-500 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <h3 class="text-xs font-bold text-amber-900">Perjanjian Belum Disetujui</h3>
                                        <p class="text-xs text-amber-700 mt-0.5">Anda belum menyelesaikan tahap persetujuan Perjanjian Kerja Sama (PKS). Dokumen PDF akan tersedia setelah Anda menyetujui PKS.</p>
                                    </div>
                                </div>
                                <a href="<?= base_url('/agreement') ?>" 
                                   class="btn-interactive inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition shrink-0">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                    <span>Buka Halaman PKS</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profilePageForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const currPass = document.getElementById('page_current_password')?.value || '';
            const newPass = document.getElementById('page_new_password')?.value || '';
            const confPass = document.getElementById('page_confirm_password')?.value || '';

            if (currPass || newPass || confPass) {
                if (!currPass) {
                    e.preventDefault();
                    if (window.toastNotification) {
                        window.toastNotification.error('Password lama wajib diisi untuk mengubah password');
                    }
                    document.getElementById('page_current_password')?.focus();
                    return;
                }
                if (newPass.length < 8) {
                    e.preventDefault();
                    if (window.toastNotification) {
                        window.toastNotification.error('Password baru minimal 8 karakter');
                    }
                    document.getElementById('page_new_password')?.focus();
                    return;
                }
                if (newPass !== confPass) {
                    e.preventDefault();
                    if (window.toastNotification) {
                        window.toastNotification.error('Konfirmasi password baru tidak cocok');
                    }
                    document.getElementById('page_confirm_password')?.focus();
                    return;
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
