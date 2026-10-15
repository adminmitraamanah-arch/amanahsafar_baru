<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$formatSize = function($bytes) {
    if (!$bytes || $bytes <= 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 1) . ' ' . ($units[$i] ?? 'B');
};

$hasMedia = !empty($task['file_path']);
$isImage = $hasMedia && (str_contains($task['file_type'] ?? '', 'image') || in_array(pathinfo($task['file_path'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']));
$isVideo = $hasMedia && (str_contains($task['file_type'] ?? '', 'video') || in_array(pathinfo($task['file_path'], PATHINFO_EXTENSION), ['mp4', 'webm', 'mov']));
$mediaUrl = $hasMedia ? base_url('uploads/tasks/' . esc($task['file_path'])) : '';
$downloadUrl = $hasMedia ? base_url('/admin/tasks/' . $task['id'] . '/download') : '#';

$tenggatVal = '';
if (!empty($task['tenggat'])) {
    $dt = new DateTime($task['tenggat']);
    $tenggatVal = $dt->format('Y-m-d\TH:i');
}
?>

<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-5xl mx-auto space-y-6">
                <!-- Breadcrumb & Header Navigasi -->
                <div>
                    <a href="<?= base_url('/admin/tasks') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#5e0078] transition mb-3">
                        <i class="fas fa-arrow-left text-[11px]"></i> Kembali ke Kelola Tugas
                    </a>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Edit Tugas &amp; Konten Promosi</h1>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui informasi tugas, copywriting, media materi promosi, dan status publikasi.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= ($task['status'] ?? 'published') === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                <span class="w-2 h-2 rounded-full <?= ($task['status'] ?? 'published') === 'published' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                Status: <?= ucfirst($task['status'] ?? 'published') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Notifikasi Flash -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-700 text-xs sm:text-sm flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-base mt-0.5 shrink-0"></i>
                        <div class="flex-1 font-medium"><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs sm:text-sm flex items-start gap-3">
                        <i class="fas fa-check-circle text-base mt-0.5 shrink-0"></i>
                        <div class="flex-1 font-medium"><?= session()->getFlashdata('success') ?></div>
                    </div>
                <?php endif; ?>

                <!-- Form Edit Tugas -->
                <form method="POST" action="<?= base_url('/admin/tasks/' . $task['id'] . '/update') ?>" enctype="multipart/form-data" class="space-y-6">
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Kolom Kiri: Detail Informasi & Konten (2/3) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Card Informasi Utama -->
                            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-5">
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm font-bold">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Informasi Tugas Promosi</h2>
                                        <p class="text-[11px] text-slate-500">Judul konten, kategori penugasan, dan detail materi.</p>
                                    </div>
                                </div>

                                <!-- Judul Tugas -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Judul Tugas / Nama Konten <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="judul" required value="<?= esc($task['judul'] ?? '') ?>" placeholder="Contoh: Flyer Paket Umrah Ramadhan 1446 H"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>

                                <!-- Kategori Konten -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Kategori Konten <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="kategori" required 
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                        <?php 
                                        $categories = ['Promosi Paket Umrah', 'Testimoni', 'Edukasi', 'Informasi'];
                                        $currentCat = $task['kategori'] ?? 'Promosi Paket Umrah';
                                        if (!in_array($currentCat, $categories)) {
                                            $categories[] = $currentCat;
                                        }
                                        foreach ($categories as $cat): 
                                        ?>
                                            <option value="<?= esc($cat) ?>" <?= $currentCat === $cat ? 'selected' : '' ?>>
                                                <?= esc($cat) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Copywriting Textarea -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                            Materi Copywriting Promosi
                                        </label>
                                        <button type="button" onclick="copyCopywriting()" 
                                                class="btn-interactive px-2.5 py-1 text-xs font-bold rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 inline-flex items-center gap-1.5 transition"
                                                title="Salin teks ke clipboard">
                                            <i class="fas fa-copy text-[11px]"></i>
                                            <span>Salin Teks</span>
                                        </button>
                                    </div>
                                    <textarea id="copywritingInput" name="copywriting" rows="5" placeholder="Tuliskan caption narasi teks copywriting yang siap disalin oleh mitra..."
                                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-normal focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"><?= esc($task['copywriting'] ?? '') ?></textarea>
                                    <p class="text-[11px] text-slate-400 mt-1">Mitra dapat langsung menyalin teks ini beserta format emoji untuk dibagikan ke WhatsApp dan media sosial.</p>
                                </div>

                                <!-- Catatan / Instruksi Pembagian -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Catatan / Instruksi Pembagian
                                    </label>
                                    <textarea name="catatan" rows="3" placeholder="Contoh: Wajib di-posting serentak hari ini pukul 19:00 WIB ke feed Instagram dan status WhatsApp..."
                                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-normal focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"><?= esc($task['catatan'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Media Preview, Ganti Media, Pengaturan Status (1/3) -->
                        <div class="space-y-6">
                            <!-- Card Berkas Media Konten -->
                            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-5">
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm font-bold">
                                        <i class="fas fa-photo-video"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Berkas Media</h2>
                                        <p class="text-[11px] text-slate-500">Preview file aktif dan form upload pengganti.</p>
                                    </div>
                                </div>

                                <!-- Preview Media Aktif -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Media Saat Ini
                                    </label>
                                    <?php if ($hasMedia): ?>
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-2 overflow-hidden space-y-2">
                                            <?php if ($isVideo): ?>
                                                <div class="relative bg-black rounded-lg overflow-hidden flex items-center justify-center max-h-56">
                                                    <video controls class="max-h-56 w-auto max-w-full mx-auto rounded-lg">
                                                        <source src="<?= $mediaUrl ?>" type="<?= esc($task['file_type'] ?? 'video/mp4') ?>">
                                                        Browser Anda tidak mendukung pemutar video.
                                                    </video>
                                                </div>
                                            <?php elseif ($isImage): ?>
                                                <div class="bg-white rounded-lg p-1.5 flex items-center justify-center max-h-56 border border-slate-100 overflow-hidden">
                                                    <img src="<?= $mediaUrl ?>" alt="<?= esc($task['judul'] ?? 'Preview') ?>" class="max-h-52 w-auto max-w-full object-contain rounded-md shadow-sm">
                                                </div>
                                            <?php else: ?>
                                                <div class="p-4 text-center text-slate-500 text-xs">
                                                    <i class="fas fa-file-alt text-2xl mb-1 text-slate-400 block"></i>
                                                    Berkas Dokumen: <?= esc($task['file_name'] ?? 'Media') ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Metadata File -->
                                            <div class="pt-2 px-1 border-t border-slate-200/80 flex flex-col gap-1 text-[11px] text-slate-600">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold truncate max-w-[170px]" title="<?= esc($task['file_name'] ?? basename($task['file_path'])) ?>">
                                                        <i class="<?= $isVideo ? 'fas fa-video text-rose-500' : 'fas fa-image text-[#5e0078]' ?> mr-1"></i>
                                                        <?= esc($task['file_name'] ?? basename($task['file_path'])) ?>
                                                    </span>
                                                    <span class="text-slate-400 text-[10px] shrink-0 font-medium">
                                                        <?= $formatSize($task['file_size'] ?? 0) ?>
                                                    </span>
                                                </div>
                                                <div class="pt-1">
                                                    <a href="<?= $downloadUrl ?>" onclick="showToast('Unduhan konten dimulai...')" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#5e0078] hover:text-[#4c0062] transition">
                                                        <i class="fas fa-download text-[10px]"></i> Unduh File Saat Ini
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/70 text-center">
                                            <i class="fas fa-image text-slate-300 text-2xl mb-1 block"></i>
                                            <span class="text-xs text-slate-500 font-medium">Belum ada media promosi terunggah</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Input Upload File Baru (Pengganti) -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Ganti Media (Opsional)
                                    </label>
                                    <input type="file" name="media_file" accept="image/*,video/*"
                                           class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-[#5e0078] hover:file:bg-purple-200 cursor-pointer border border-slate-200 rounded-xl bg-slate-50 p-1.5 focus:outline-none transition">
                                    <div class="mt-2 p-2.5 rounded-xl bg-purple-50/60 border border-purple-100 text-[11px] text-slate-600 space-y-1">
                                        <p class="font-medium text-[#5e0078] flex items-center gap-1">
                                            <i class="fas fa-shield-alt"></i> Info Upload &amp; Penyimpanan:
                                        </p>
                                        <p class="text-slate-500">
                                            Biarkan kosong untuk mempertahankan file lama. Format didukung: JPG, PNG, WEBP, MP4, WEBM (Maks. 600 MB). Gambar besar otomatis dikompresi.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Pengaturan Publikasi & Tenggat -->
                            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-5">
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm font-bold">
                                        <i class="fas fa-sliders-h"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Pengaturan Publikasi</h2>
                                        <p class="text-[11px] text-slate-500">Status visibilitas dan batas waktu tugas.</p>
                                    </div>
                                </div>

                                <!-- Status Publikasi -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Status Publikasi <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="status" required
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                        <option value="published" <?= ($task['status'] ?? '') === 'published' ? 'selected' : '' ?>>
                                            Published (Tampil ke Mitra)
                                        </option>
                                        <option value="draft" <?= ($task['status'] ?? '') === 'draft' ? 'selected' : '' ?>>
                                            Draft (Disimpan Sementara)
                                        </option>
                                    </select>
                                    <p class="text-[11px] text-slate-400 mt-1">Status <b>Draft</b> tidak akan muncul pada dashboard tugas Mitra.</p>
                                </div>

                                <!-- Tenggat Waktu -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Tenggat Waktu (Opsional)
                                    </label>
                                    <input type="datetime-local" name="tenggat" value="<?= esc($tenggatVal) ?>"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                                </div>

                                <!-- Metadata Waktu Dibuat -->
                                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Dibuat pada:</span>
                                        <span class="font-semibold text-slate-700">
                                            <?= !empty($task['created_at']) ? date('d M Y, H:i', strtotime($task['created_at'])) : '-' ?> WIB
                                        </span>
                                    </div>
                                    <?php if (!empty($task['updated_at'])): ?>
                                        <div class="flex justify-between">
                                            <span>Terakhir diubah:</span>
                                            <span class="font-semibold text-slate-700">
                                                <?= date('d M Y, H:i', strtotime($task['updated_at'])) ?> WIB
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
                        <a href="<?= base_url('/admin/tasks') ?>" class="btn-interactive w-full sm:w-auto text-center px-6 py-2.5 rounded-xl border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left text-xs"></i> Batal / Kembali
                        </a>
                        <button type="submit" class="btn-interactive w-full sm:w-auto px-8 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#5e0078]/20 transition flex items-center justify-center gap-2">
                            <i class="fas fa-save text-xs"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `pointer-events-auto px-4 py-3 rounded-2xl shadow-xl border text-xs font-bold flex items-center gap-2 transform transition-all duration-300 translate-y-3 opacity-0 ${
        type === 'success' 
            ? 'bg-slate-900 text-white border-slate-800' 
            : 'bg-rose-600 text-white border-rose-500'
    }`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-white'} text-sm"></i><span>${message}</span>`;
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-3', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-y-3', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

function copyCopywriting() {
    const input = document.getElementById('copywritingInput');
    const text = input ? input.value : '';
    if (!text || !text.trim()) {
        showToast('Teks copywriting masih kosong', 'error');
        return;
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Teks copywriting berhasil disalin!');
        }).catch(() => {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    try {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        const success = document.execCommand('copy');
        document.body.removeChild(textArea);
        if (success) {
            showToast('Teks copywriting berhasil disalin!');
        } else {
            showToast('Gagal menyalin teks', 'error');
        }
    } catch (e) {
        showToast('Gagal menyalin teks', 'error');
    }
}
</script>
<?= $this->endSection() ?>
