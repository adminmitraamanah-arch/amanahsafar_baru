<?php if (session('logged_in')): ?>
<!-- MODAL PROFIL SAYA -->
<div id="profileModal" 
     class="fixed inset-0 z-[9999] bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 transition-opacity duration-200" 
     aria-modal="true" 
     role="dialog" 
     aria-labelledby="profileModalTitle">
    
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200/80 max-w-lg w-full max-h-[92vh] overflow-y-auto transform transition-all duration-200 scale-95 opacity-0" id="profileModalContent">
        
        <!-- Header Modal -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-base shadow-sm ring-1 ring-purple-100">
                    <i class="fas fa-user-circle text-lg"></i>
                </div>
                <div>
                    <h3 id="profileModalTitle" class="text-base font-bold text-slate-900 leading-tight">Profil Saya</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pengaturan informasi akun dan kata sandi Anda</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeProfileModal()" 
                    class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition focus:outline-none" 
                    aria-label="Tutup modal">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Form Edit Profil -->
        <form id="profileModalForm" action="<?= base_url('/profile/update') ?>" method="POST" class="p-6 space-y-5">
            <?= csrf_field() ?>

            <!-- Informasi Akun (Readonly) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 p-4 bg-slate-50/80 rounded-xl border border-slate-200/70">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fas fa-envelope text-slate-400 mr-1"></i> Email
                    </label>
                    <input type="email" 
                           value="<?= esc(session('email')) ?>" 
                           readonly 
                           class="w-full bg-slate-100/90 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-medium cursor-not-allowed select-none">
                    <span class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1">
                        <i class="fas fa-lock text-[9px]"></i> Email tidak dapat diubah
                    </span>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fas fa-shield-alt text-slate-400 mr-1"></i> Hak Akses
                    </label>
                    <div class="h-[34px] px-3 bg-purple-50/70 border border-purple-100 rounded-lg flex items-center">
                        <span class="text-xs font-bold text-[#5e0078] capitalize truncate">
                            <?= esc(str_replace('_', ' ', session('role') ?? 'User')) ?>
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Sesuai peran aktif</span>
                </div>
            </div>

            <!-- Nama Profil (Editable) -->
            <div>
                <label for="profile_modal_nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Profil <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" 
                           name="nama_lengkap" 
                           id="profile_modal_nama" 
                           required 
                           minlength="3" 
                           maxlength="100"
                           value="<?= esc(session('nama')) ?>" 
                           placeholder="Masukkan nama profil Anda"
                           class="w-full bg-white border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Nama ini akan ditampilkan pada header dan menu akun.</p>
            </div>

            <!-- Bagian Ubah Password -->
            <div class="border-t border-slate-100 pt-4 space-y-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-key text-[#5e0078]"></i> Ubah Password
                        </h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Kosongkan kolom ini jika tidak ingin mengubah password akun.</p>
                    </div>
                </div>

                <!-- Password Lama -->
                <div>
                    <label for="profile_modal_current_password" class="block text-xs font-medium text-slate-700 mb-1">
                        Password Lama (Saat Ini)
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="current_password" 
                               id="profile_modal_current_password"
                               placeholder="Masukkan password lama untuk konfirmasi"
                               autocomplete="current-password"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                        <button type="button" 
                                onclick="toggleProfilePasswordVisibility('profile_modal_current_password', this)" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" 
                                tabindex="-1"
                                aria-label="Lihat password">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Baru & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="profile_modal_new_password" class="block text-xs font-medium text-slate-700 mb-1">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="new_password" 
                                   id="profile_modal_new_password" 
                                   minlength="8"
                                   placeholder="Minimal 8 karakter"
                                   autocomplete="new-password"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                            <button type="button" 
                                    onclick="toggleProfilePasswordVisibility('profile_modal_new_password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" 
                                    tabindex="-1"
                                    aria-label="Lihat password">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="profile_modal_confirm_password" class="block text-xs font-medium text-slate-700 mb-1">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="confirm_password" 
                                   id="profile_modal_confirm_password" 
                                   minlength="8"
                                   placeholder="Ulangi password baru"
                                   autocomplete="new-password"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:border-transparent focus:outline-none transition">
                            <button type="button" 
                                    onclick="toggleProfilePasswordVisibility('profile_modal_confirm_password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" 
                                    tabindex="-1"
                                    aria-label="Lihat password">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer / Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" 
                        onclick="closeProfileModal()" 
                        class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold rounded-xl shadow-sm flex items-center gap-2 transition">
                    <i class="fas fa-check text-xs"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openProfileModal() {
    const modal = document.getElementById('profileModal');
    const content = document.getElementById('profileModalContent');
    if (!modal) return;
    
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });
    
    // Focus ke input nama
    setTimeout(() => {
        const inputNama = document.getElementById('profile_modal_nama');
        if (inputNama) inputNama.focus();
    }, 100);
}

function closeProfileModal() {
    const modal = document.getElementById('profileModal');
    const content = document.getElementById('profileModalContent');
    if (!modal) return;
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        // Reset password fields
        const curr = document.getElementById('profile_modal_current_password');
        const next = document.getElementById('profile_modal_new_password');
        const conf = document.getElementById('profile_modal_confirm_password');
        if (curr) curr.value = '';
        if (next) next.value = '';
        if (conf) conf.value = '';
    }, 180);
}

function toggleProfilePasswordVisibility(inputId, btn) {
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

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('profileModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeProfileModal();
        }
    }
});

// Tutup dengan klik di luar card modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('profileModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeProfileModal();
            }
        });
    }

    // Client-side quick check saat submit
    const form = document.getElementById('profileModalForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const newPass = document.getElementById('profile_modal_new_password')?.value || '';
            const confPass = document.getElementById('profile_modal_confirm_password')?.value || '';
            const currPass = document.getElementById('profile_modal_current_password')?.value || '';

            if (newPass || confPass || currPass) {
                if (!currPass) {
                    e.preventDefault();
                    if (window.toastNotification) {
                        window.toastNotification.error('Password lama wajib diisi untuk mengubah password');
                    } else {
                        alert('Password lama wajib diisi untuk mengubah password');
                    }
                    document.getElementById('profile_modal_current_password')?.focus();
                    return;
                }
                if (newPass.length < 8) {
                    e.preventDefault();
                    if (window.toastNotification) {
                        window.toastNotification.error('Password baru minimal 8 karakter');
                    } else {
                        alert('Password baru minimal 8 karakter');
                    }
                    document.getElementById('profile_modal_new_password')?.focus();
                    return;
                }
                if (newPass !== confPass) {
                    e.preventDefault();
                    if (window.toastNotification) {
                        window.toastNotification.error('Konfirmasi password baru tidak cocok');
                    } else {
                        alert('Konfirmasi password baru tidak cocok');
                    }
                    document.getElementById('profile_modal_confirm_password')?.focus();
                    return;
                }
            }
        });
    }
});
</script>
<?php endif; ?>
