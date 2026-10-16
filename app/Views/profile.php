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
                        <!-- Avatar Container with Edit Pencil Button -->
                        <div class="relative shrink-0">
                            <div id="profileAvatarDisplay" class="w-16 h-16 rounded-2xl bg-[#5e0078] text-white font-extrabold text-2xl flex items-center justify-center shadow-md ring-4 ring-purple-100 shrink-0 select-none overflow-hidden">
                                <?php if (!empty($user['foto_profil']) && is_file(FCPATH . 'uploads/profiles/' . $user['foto_profil'])): ?>
                                    <img id="avatarImage" src="<?= base_url('uploads/profiles/' . esc($user['foto_profil'])) ?>" alt="Foto Profil <?= esc($user['nama_lengkap'] ?? '') ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span id="avatarInitial"><?= strtoupper(substr($user['nama_lengkap'] ?? (session('nama') ?? 'U'), 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Tombol Edit Pencil (Sudut Kanan Atas) -->
                            <button type="button" 
                                    id="btnEditAvatar" 
                                    class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full bg-white text-[#5e0078] hover:bg-purple-50 hover:text-[#4c0062] border border-purple-200 shadow-md flex items-center justify-center text-[10px] transition transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-[#5e0078] cursor-pointer z-10"
                                    title="Ubah foto profil" 
                                    aria-label="Ubah foto profil">
                                <i id="btnEditAvatarIcon" class="fas fa-pencil text-[9px]"></i>
                            </button>

                            <!-- Input File Tersembunyi -->
                            <input type="file" 
                                   id="avatarFileInput" 
                                   name="foto_profil" 
                                   accept="image/jpeg,image/png,image/webp" 
                                   class="hidden" 
                                   aria-hidden="true">
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
                <?php if (!empty($showPks) && !empty($pksInfo)): ?>
                    <!-- Dokumen Perjanjian Kerja Sama (PKS) Card (Khusus Role Kemitraan) -->
                    <?= view('shared/profile_pks_card', ['pksInfo' => $pksInfo]) ?>
                <?php endif; ?>

            </div>
        </main>
    </div>
</div>

<!-- ========================================================
     MODAL EDITOR & CROP FOTO PROFIL (1:1 RASIO)
     ======================================================== -->
<div id="cropPhotoModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative my-6 transform transition-all border border-slate-100 flex flex-col">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fas fa-crop-simple"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 leading-tight">Sesuaikan Foto Profil</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Atur posisi dan perbesaran sebelum menyimpan.</p>
                </div>
            </div>
            <button type="button" id="btnCloseCropModal" class="w-8 h-8 rounded-full bg-slate-200/60 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body: Crop Viewport & Controls -->
        <div class="p-6 space-y-4">
            <!-- Viewport Container (Square 1:1, max 320px) -->
            <div id="cropContainer" class="relative w-64 h-64 sm:w-72 sm:h-72 mx-auto rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 shadow-inner select-none touch-none flex items-center justify-center cursor-grab active:cursor-grabbing">
                <!-- Canvas -->
                <canvas id="cropCanvas" width="320" height="320" class="w-full h-full block"></canvas>

                <!-- SVG Overlay / Mask (Translucent vignette with clear 1:1 square & circular guide) -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 320 320">
                    <defs>
                        <mask id="cropCircularCutout">
                            <rect width="320" height="320" fill="white" />
                            <circle cx="160" cy="160" r="144" fill="black" />
                        </mask>
                    </defs>
                    <!-- Darkened outer area -->
                    <rect width="320" height="320" fill="rgba(15, 23, 42, 0.45)" mask="url(#cropCircularCutout)" />
                    <!-- Circular guide line matching avatar circle -->
                    <circle cx="160" cy="160" r="144" fill="none" stroke="rgba(255, 255, 255, 0.85)" stroke-width="2" stroke-dasharray="6 4" />
                    <!-- 1:1 Square boundary line -->
                    <rect x="16" y="16" width="288" height="288" fill="none" stroke="rgba(255, 255, 255, 0.3)" stroke-width="1.5" rx="16" />
                </svg>
            </div>

            <!-- Hint: Drag to Move -->
            <p class="text-center text-[11px] font-medium text-slate-400 flex items-center justify-center gap-1.5">
                <i class="fas fa-up-down-left-right text-slate-400 text-xs"></i>
                <span>Geser foto untuk menyesuaikan posisi</span>
            </p>

            <!-- Zoom Controls & Realtime Mini Preview -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 flex items-center gap-3">
                <!-- Realtime Mini Avatar Preview -->
                <div class="relative shrink-0 text-center">
                    <canvas id="cropPreviewCanvas" width="96" height="96" class="w-11 h-11 rounded-full border-2 border-purple-200 shadow-sm bg-slate-200 block overflow-hidden object-cover mx-auto"></canvas>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5 block">Avatar</span>
                </div>

                <!-- Zoom Controls -->
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-600">
                        <span>Perbesaran</span>
                        <span id="zoomPercentLabel" class="font-mono text-purple-700">100%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnCropZoomOut" class="w-7 h-7 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-600 flex items-center justify-center text-xs transition shadow-xs" title="Perkecil">
                            <i class="fas fa-minus text-[10px]"></i>
                        </button>
                        <input type="range" id="cropZoomSlider" min="1.0" max="3.0" step="0.01" value="1.0" class="flex-1 h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#5e0078]" title="Slider Perbesaran">
                        <button type="button" id="btnCropZoomIn" class="w-7 h-7 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-600 flex items-center justify-center text-xs transition shadow-xs" title="Perbesar">
                            <i class="fas fa-plus text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer: Action Buttons -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
            <button type="button" id="btnCancelCrop" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 font-bold text-xs sm:text-sm transition">
                Batal
            </button>
            <button type="button" id="btnSaveCrop" class="btn-interactive inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-md shadow-purple-900/20 transition">
                <i id="btnSaveCropIcon" class="fas fa-check text-xs"></i>
                <span id="btnSaveCropText">Simpan Foto</span>
            </button>
        </div>
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

    // ========================================================
    // PENANGANAN INTERAKTIF EDIT FOTO PROFIL & CROPPER (1:1)
    // ========================================================
    const btnEditAvatar = document.getElementById('btnEditAvatar');
    const avatarFileInput = document.getElementById('avatarFileInput');
    const profileAvatarDisplay = document.getElementById('profileAvatarDisplay');
    const btnEditAvatarIcon = document.getElementById('btnEditAvatarIcon');

    // Elemen Modal Crop
    const cropPhotoModal = document.getElementById('cropPhotoModal');
    const btnCloseCropModal = document.getElementById('btnCloseCropModal');
    const btnCancelCrop = document.getElementById('btnCancelCrop');
    const btnSaveCrop = document.getElementById('btnSaveCrop');
    const btnSaveCropIcon = document.getElementById('btnSaveCropIcon');
    const btnSaveCropText = document.getElementById('btnSaveCropText');

    // Elemen Viewport & Canvas
    const cropContainer = document.getElementById('cropContainer');
    const cropCanvas = document.getElementById('cropCanvas');
    const cropPreviewCanvas = document.getElementById('cropPreviewCanvas');
    const cropZoomSlider = document.getElementById('cropZoomSlider');
    const btnCropZoomOut = document.getElementById('btnCropZoomOut');
    const btnCropZoomIn = document.getElementById('btnCropZoomIn');
    const zoomPercentLabel = document.getElementById('zoomPercentLabel');

    const CROP_SIZE = 320;
    let cropCtx = cropCanvas ? cropCanvas.getContext('2d') : null;
    let previewCtx = cropPreviewCanvas ? cropPreviewCanvas.getContext('2d') : null;

    let cropImage = null;
    let baseScale = 1.0;
    let currentZoom = 1.0;
    let offsetX = 0;
    let offsetY = 0;
    let isDragging = false;
    let dragStartX = 0;
    let dragStartY = 0;
    let dragStartOffsetX = 0;
    let dragStartOffsetY = 0;
    let touchPinchDistance = 0;
    let touchStartZoom = 1.0;

    function showToast(msg, isSuccess = false) {
        if (window.toastNotification) {
            if (isSuccess) {
                window.toastNotification.success(msg);
            } else {
                window.toastNotification.error(msg);
            }
        } else {
            alert(msg);
        }
    }

    function openCropModal() {
        if (!cropPhotoModal) return;
        cropPhotoModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeCropModal() {
        if (!cropPhotoModal) return;
        cropPhotoModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        cropImage = null;
        if (avatarFileInput) avatarFileInput.value = '';
    }

    function renderCrop() {
        if (!cropImage || !cropCtx) return;

        const currentScale = baseScale * currentZoom;
        const curW = cropImage.naturalWidth * currentScale;
        const curH = cropImage.naturalHeight * currentScale;

        // BOUNDARY CLAMPING: Area crop selalu terisi gambar tanpa ruang kosong
        offsetX = Math.min(0, Math.max(CROP_SIZE - curW, offsetX));
        offsetY = Math.min(0, Math.max(CROP_SIZE - curH, offsetY));

        cropCtx.clearRect(0, 0, CROP_SIZE, CROP_SIZE);
        cropCtx.imageSmoothingEnabled = true;
        cropCtx.imageSmoothingQuality = 'high';
        cropCtx.drawImage(cropImage, offsetX, offsetY, curW, curH);

        // Update Realtime Mini Preview
        if (previewCtx && cropPreviewCanvas) {
            previewCtx.clearRect(0, 0, 96, 96);
            previewCtx.imageSmoothingEnabled = true;
            previewCtx.imageSmoothingQuality = 'high';
            previewCtx.drawImage(cropCanvas, 0, 0, CROP_SIZE, CROP_SIZE, 0, 0, 96, 96);
        }
    }

    function setZoom(newZoom) {
        if (!cropImage) return;
        const oldZoom = currentZoom;
        newZoom = Math.max(1.0, Math.min(3.0, newZoom));
        if (Math.abs(newZoom - oldZoom) < 0.001) return;

        // Pertahankan zoom berpusat pada titik tengah crop box (CROP_SIZE/2, CROP_SIZE/2)
        const centerCropX = CROP_SIZE / 2;
        const centerCropY = CROP_SIZE / 2;
        const oldScale = baseScale * oldZoom;
        const newScale = baseScale * newZoom;

        const imgPointX = (centerCropX - offsetX) / oldScale;
        const imgPointY = (centerCropY - offsetY) / oldScale;

        offsetX = centerCropX - (imgPointX * newScale);
        offsetY = centerCropY - (imgPointY * newScale);

        currentZoom = newZoom;
        if (cropZoomSlider) cropZoomSlider.value = currentZoom.toFixed(2);
        if (zoomPercentLabel) zoomPercentLabel.textContent = Math.round(currentZoom * 100) + '%';
        renderCrop();
    }

    function initCropEditor() {
        if (!cropImage) return;
        // baseScale menjamin dimensi gambar selalu minimal memenuhi CROP_SIZE x CROP_SIZE
        baseScale = Math.max(CROP_SIZE / cropImage.naturalWidth, CROP_SIZE / cropImage.naturalHeight);
        currentZoom = 1.0;
        if (cropZoomSlider) cropZoomSlider.value = "1.0";
        if (zoomPercentLabel) zoomPercentLabel.textContent = '100%';

        const curW = cropImage.naturalWidth * baseScale;
        const curH = cropImage.naturalHeight * baseScale;
        offsetX = (CROP_SIZE - curW) / 2;
        offsetY = (CROP_SIZE - curH) / 2;

        renderCrop();
    }

    if (btnEditAvatar && avatarFileInput) {
        btnEditAvatar.addEventListener('click', function(e) {
            e.preventDefault();
            avatarFileInput.click();
        });

        avatarFileInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            // Kriteria: Jika pemilihan file dibatalkan, jangan lakukan perubahan atau upload
            if (!file) {
                return;
            }

            // Validasi format file
            const validMimes = ['image/jpeg', 'image/png', 'image/webp'];
            const validExts = ['jpg', 'jpeg', 'png', 'webp'];
            const fileExt = file.name.split('.').pop().toLowerCase();

            if (!validMimes.includes(file.type) && !validExts.includes(fileExt)) {
                showToast('Format file tidak didukung. Harap gunakan format gambar JPG, PNG, atau WEBP.');
                avatarFileInput.value = '';
                return;
            }

            // Batas maksimal ukuran raw file sebelum crop (10MB)
            if (file.size > 10 * 1024 * 1024) {
                showToast('Ukuran file foto mentah terlalu besar (maksimal 10MB).');
                avatarFileInput.value = '';
                return;
            }

            // PENTING: BACA FILE SECARA LOKAL DENGAN FILEREADER - TIDAK MENGIRIM KE SERVER
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    cropImage = img;
                    initCropEditor();
                    openCropModal();
                };
                img.onerror = function() {
                    showToast('Gagal memuat file gambar. Pastikan gambar tidak rusak.');
                    avatarFileInput.value = '';
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    // Event listener navigasi & interaksi crop
    if (cropContainer) {
        // Drag dengan Mouse
        cropContainer.addEventListener('mousedown', function(e) {
            e.preventDefault();
            isDragging = true;
            dragStartX = e.clientX;
            dragStartY = e.clientY;
            dragStartOffsetX = offsetX;
            dragStartOffsetY = offsetY;
        });

        window.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            offsetX = dragStartOffsetX + (e.clientX - dragStartX);
            offsetY = dragStartOffsetY + (e.clientY - dragStartY);
            renderCrop();
        });

        window.addEventListener('mouseup', function() {
            isDragging = false;
        });

        // Wheel Zoom
        cropContainer.addEventListener('wheel', function(e) {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.08 : -0.08;
            setZoom(currentZoom + delta);
        }, { passive: false });

        // Touch Drag & Pinch Zoom (Mobile)
        cropContainer.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                isDragging = true;
                dragStartX = e.touches[0].clientX;
                dragStartY = e.touches[0].clientY;
                dragStartOffsetX = offsetX;
                dragStartOffsetY = offsetY;
            } else if (e.touches.length === 2) {
                isDragging = false;
                touchPinchDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                touchStartZoom = currentZoom;
            }
        }, { passive: true });

        cropContainer.addEventListener('touchmove', function(e) {
            if (e.touches.length === 1 && isDragging) {
                e.preventDefault();
                offsetX = dragStartOffsetX + (e.touches[0].clientX - dragStartX);
                offsetY = dragStartOffsetY + (e.touches[0].clientY - dragStartY);
                renderCrop();
            } else if (e.touches.length === 2) {
                e.preventDefault();
                const newDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                if (touchPinchDistance > 0) {
                    const scaleFactor = newDist / touchPinchDistance;
                    setZoom(touchStartZoom * scaleFactor);
                }
            }
        }, { passive: false });

        cropContainer.addEventListener('touchend', function() {
            isDragging = false;
        });
        cropContainer.addEventListener('touchcancel', function() {
            isDragging = false;
        });
    }

    // Tombol & Slider Zoom
    if (btnCropZoomOut) {
        btnCropZoomOut.addEventListener('click', function() {
            setZoom(currentZoom - 0.15);
        });
    }
    if (btnCropZoomIn) {
        btnCropZoomIn.addEventListener('click', function() {
            setZoom(currentZoom + 0.15);
        });
    }
    if (cropZoomSlider) {
        cropZoomSlider.addEventListener('input', function() {
            setZoom(parseFloat(this.value));
        });
    }

    // Tombol Batal & Tutup Modal
    if (btnCloseCropModal) btnCloseCropModal.addEventListener('click', closeCropModal);
    if (btnCancelCrop) btnCancelCrop.addEventListener('click', closeCropModal);

    // Tombol Simpan Foto (Proses Crop & Eksekusi Upload ke Server)
    if (btnSaveCrop) {
        btnSaveCrop.addEventListener('click', function() {
            if (!cropImage) return;

            // Indikator loading
            btnSaveCrop.disabled = true;
            btnSaveCrop.classList.add('opacity-75', 'cursor-wait');
            if (btnSaveCropIcon) btnSaveCropIcon.className = 'fas fa-spinner fa-spin text-xs';
            if (btnSaveCropText) btnSaveCropText.textContent = 'Menyimpan...';
            if (btnCancelCrop) btnCancelCrop.disabled = true;

            // Ekstraksi hasil crop ke offscreen canvas resolusi tinggi (512x512)
            const currentScale = baseScale * currentZoom;
            const sx = Math.max(0, (-offsetX) / currentScale);
            const sy = Math.max(0, (-offsetY) / currentScale);
            const sWidth = Math.min(cropImage.naturalWidth - sx, CROP_SIZE / currentScale);
            const sHeight = Math.min(cropImage.naturalHeight - sy, CROP_SIZE / currentScale);

            const exportCanvas = document.createElement('canvas');
            const EXPORT_SIZE = 512;
            exportCanvas.width = EXPORT_SIZE;
            exportCanvas.height = EXPORT_SIZE;
            const expCtx = exportCanvas.getContext('2d');
            expCtx.imageSmoothingEnabled = true;
            expCtx.imageSmoothingQuality = 'high';
            expCtx.drawImage(cropImage, sx, sy, sWidth, sHeight, 0, 0, EXPORT_SIZE, EXPORT_SIZE);

            exportCanvas.toBlob(async function(blob) {
                if (!blob) {
                    showToast('Gagal memproses hasil crop foto.');
                    resetSaveBtn();
                    return;
                }

                // Validasi ukuran hasil crop (< 2MB)
                const maxSize = 2 * 1024 * 1024;
                if (blob.size > maxSize) {
                    showToast('Ukuran foto profil melebihi batas maksimal 2MB.');
                    resetSaveBtn();
                    return;
                }

                const formData = new FormData();
                formData.append('foto_profil', blob, 'avatar.jpg');

                const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                const csrfTokenName = '<?= csrf_token() ?>';
                const csrfHash = csrfInput ? csrfInput.value : '<?= csrf_hash() ?>';
                formData.append(csrfTokenName, csrfHash);

                try {
                    const response = await fetch('<?= base_url('/profile/upload-photo') ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.csrf_hash) {
                        document.querySelectorAll('input[name="' + csrfTokenName + '"]').forEach(el => el.value = data.csrf_hash);
                    }

                    if (response.ok && data.success) {
                        const cacheBustUrl = data.foto_url + '?t=' + Date.now();

                        // 1. Perbarui foto pada halaman profil
                        if (profileAvatarDisplay) {
                            profileAvatarDisplay.innerHTML = `<img id="avatarImage" src="${cacheBustUrl}" alt="Foto Profil" class="w-full h-full object-cover">`;
                        }

                        // 2. Perbarui foto pada header navbar
                        const navAvatar = document.querySelector('header button div.rounded-full');
                        if (navAvatar) {
                            navAvatar.innerHTML = `<img src="${cacheBustUrl}" alt="Foto Profil" class="w-full h-full object-cover">`;
                            navAvatar.classList.add('overflow-hidden');
                        }

                        // 3. Perbarui foto pada sidebar
                        const sidebarAvatar = document.querySelector('aside div.w-8.h-8.rounded-full');
                        if (sidebarAvatar) {
                            sidebarAvatar.innerHTML = `<img src="${cacheBustUrl}" alt="Foto Profil" class="w-full h-full object-cover">`;
                            sidebarAvatar.classList.add('overflow-hidden');
                        }

                        showToast(data.message || 'Foto profil berhasil diperbarui.', true);
                        closeCropModal();
                    } else {
                        // Kriteria: Jika upload gagal, tampilkan pesan kesalahan dan pertahankan foto lama
                        showToast(data.message || 'Gagal menyimpan foto profil.');
                    }
                } catch (err) {
                    showToast('Terjadi kesalahan koneksi saat mengunggah foto profil.');
                } finally {
                    resetSaveBtn();
                }
            }, 'image/jpeg', 0.92);
        });
    }

    function resetSaveBtn() {
        if (!btnSaveCrop) return;
        btnSaveCrop.disabled = false;
        btnSaveCrop.classList.remove('opacity-75', 'cursor-wait');
        if (btnSaveCropIcon) btnSaveCropIcon.className = 'fas fa-check text-xs';
        if (btnSaveCropText) btnSaveCropText.textContent = 'Simpan Foto';
        if (btnCancelCrop) btnCancelCrop.disabled = false;
    }
});
</script>
<?= $this->endSection() ?>
