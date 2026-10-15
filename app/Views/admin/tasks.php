<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Tugas &amp; Konten Promosi</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Buat, publikasikan, dan bagikan materi promosi beserta copywriting untuk seluruh mitra.</p>
                    </div>
                    <button type="button" onclick="openCreateTaskModal()" 
                            class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Buat Tugas Baru</span>
                    </button>
                </div>

                <!-- Toolbar (Search, Filter Kategori, Filter Status, Sort, Per-Page) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col md:flex-row items-center justify-between gap-3">
                    <div class="relative w-full md:w-80">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="tasksSearchInput" placeholder="Cari judul, kategori, copywriting..." 
                               class="w-full pl-9 pr-12 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] focus:bg-white transition">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                        <!-- Filter Kategori -->
                        <select id="tasksKategoriFilter" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="all">Semua Kategori</option>
                            <option value="promosi paket umrah">Promosi Paket Umrah</option>
                            <option value="testimoni">Testimoni</option>
                            <option value="edukasi">Edukasi</option>
                            <option value="informasi">Informasi</option>
                        </select>

                        <!-- Filter Status -->
                        <select id="tasksStatusFilter" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="all">Semua Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>

                        <!-- Sort By -->
                        <select id="tasksSortSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="created_desc">Urutkan: Tanggal (Terbaru)</option>
                            <option value="created_asc">Urutkan: Tanggal (Terlama)</option>
                            <option value="judul_asc">Urutkan: Judul (A - Z)</option>
                            <option value="judul_desc">Urutkan: Judul (Z - A)</option>
                        </select>

                        <!-- Per Page Size -->
                        <select id="tasksPageSizeSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="10">10 / halaman</option>
                            <option value="25">25 / halaman</option>
                            <option value="50">50 / halaman</option>
                            <option value="100">100 / halaman</option>
                        </select>

                        <!-- Reset Button -->
                        <button type="button" id="resetTasksFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition text-xs" title="Reset Filter">
                            <i class="fas fa-redo-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Task Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start" id="tasksGridContainer">
                    <div id="emptyTaskCard" class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs <?= empty($tasks) ? '' : 'hidden' ?>">
                        <i class="fas fa-tasks text-3xl mb-2 text-slate-300 block"></i>
                        <p>Tidak ada tugas promosi yang sesuai dengan filter atau kata kunci.</p>
                    </div>

                    <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                            <?php 
                                $kategori = $task['kategori'] ?? 'Promosi Paket Umrah';
                                $isPublished = ($task['status'] ?? 'published') === 'published';
                                $hasMedia = !empty($task['file_path']);
                                $fileUrl = $hasMedia ? base_url('uploads/tasks/' . esc($task['file_path'])) : '';
                                $fileType = $task['file_type'] ?? 'image';
                                $downloadUrl = base_url('/admin/tasks/' . $task['id'] . '/download');
                            ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition-all duration-200 self-start w-full task-card-item"
                                 data-judul="<?= strtolower(esc($task['judul'])) ?>"
                                 data-kategori="<?= strtolower(esc($kategori)) ?>"
                                 data-status="<?= esc($task['status'] ?? 'published') ?>"
                                 data-created="<?= strtotime($task['created_at']) ?>"
                                 data-search="<?= strtolower(esc($task['judul'] . ' ' . $kategori . ' ' . ($task['deskripsi'] ?? '') . ' ' . ($task['copywriting'] ?? '') . ' ' . ($task['catatan'] ?? ''))) ?>">
                                
                                <!-- Hidden Raw Data to prevent attribute quoting and encoding issues -->
                                <textarea id="raw-copy-<?= $task['id'] ?>" class="hidden" aria-hidden="true"><?= esc($task['copywriting'] ?? '') ?></textarea>
                                <textarea id="raw-catatan-<?= $task['id'] ?>" class="hidden" aria-hidden="true"><?= esc($task['catatan'] ?? '') ?></textarea>
                                <input type="hidden" id="raw-judul-<?= $task['id'] ?>" value="<?= esc($task['judul'] ?? '', 'attr') ?>">

                                <!-- Top Media Banner (Tepat seperti card katalog: h-44 w-full) -->
                                <?php if ($hasMedia): ?>
                                    <div class="h-44 w-full overflow-hidden bg-slate-900 relative group cursor-pointer"
                                         onclick="openMediaModalById('<?= $fileUrl ?>', '<?= $fileType ?>', <?= $task['id'] ?>, '<?= $downloadUrl ?>')"
                                         title="Klik untuk melihat frame dan ukuran asli">
                                        
                                        <?php if ($fileType === 'video'): ?>
                                            <video preload="metadata" class="w-full h-full object-cover pointer-events-none group-hover:scale-105 transition-transform duration-300">
                                                <source src="<?= $fileUrl ?>#t=0.5">
                                            </video>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-black/10 group-hover:from-black/85 transition-opacity"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-12 h-12 rounded-full bg-white/95 backdrop-blur-md text-[#5e0078] shadow-xl flex items-center justify-center pl-1 group-hover:scale-110 group-hover:bg-[#5e0078] group-hover:text-white transition-all duration-200">
                                                    <i class="fas fa-play text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="absolute bottom-2.5 left-2.5 flex items-center gap-1.5 text-white text-[10px] font-bold bg-black/60 backdrop-blur-xs px-2 py-0.5 rounded-md pointer-events-none">
                                                <i class="fas fa-video text-[9px] text-rose-400"></i>
                                                <span>Preview Video</span>
                                            </div>
                                        <?php else: ?>
                                            <img src="<?= $fileUrl ?>" alt="<?= esc($task['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-black/35 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                                <div class="w-11 h-11 rounded-full bg-white/95 backdrop-blur-md text-slate-800 shadow-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-search-plus text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="absolute bottom-2.5 left-2.5 flex items-center gap-1.5 text-white text-[10px] font-bold bg-black/60 backdrop-blur-xs px-2 py-0.5 rounded-md pointer-events-none">
                                                <i class="fas fa-image text-[9px] text-purple-300"></i>
                                                <span>Preview Gambar</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="h-44 w-full bg-gradient-to-br from-[#5e0078] to-[#4c0062] flex flex-col items-center justify-center text-white p-4 text-center cursor-pointer"
                                         onclick="openMediaModal('', 'none', '<?= esc($task['judul'], 'js') ?>', '', <?= json_encode($task['copywriting'] ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>, <?= json_encode($task['catatan'] ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)">
                                        <i class="fas fa-bullhorn text-4xl opacity-80 mb-2"></i>
                                        <span class="text-xs font-semibold tracking-wide">Tugas Tanpa Media</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Card Body (Tepat seperti card katalog: p-5 flex-1 flex flex-col justify-between space-y-4) -->
                                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#5e0078] block truncate">
                                                <?= esc($kategori) ?>
                                            </span>
                                            <span class="text-[11px] text-slate-400 flex items-center gap-1 shrink-0">
                                                <i class="far fa-calendar-alt text-[#5e0078]"></i>
                                                <?= date('d M Y', strtotime($task['created_at'])) ?>
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug line-clamp-1" title="<?= esc($task['judul'], 'attr') ?>">
                                            <?= esc($task['judul']) ?>
                                        </h3>
                                        
                                        <!-- Copywriting Section dengan Dropdown Lihat Selengkapnya -->
                                        <?php 
                                            $copyTextContent = $task['copywriting'] ?: ($task['deskripsi'] ?? 'Materi promosi siap bagikan.');
                                            $isLongCopy = mb_strlen(trim($copyTextContent)) > 80 || substr_count($copyTextContent, "\n") >= 2;
                                        ?>
                                        <div class="space-y-1">
                                            <div id="copywriting-text-<?= $task['id'] ?>" class="text-slate-500 text-xs line-clamp-2 leading-relaxed transition-all duration-200 whitespace-pre-line">
                                                <?= esc($copyTextContent) ?>
                                            </div>
                                            <?php if ($isLongCopy): ?>
                                                <button type="button" 
                                                        onclick="toggleCopywritingDropdown(<?= $task['id'] ?>, this)" 
                                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-[#5e0078] hover:text-[#450059] transition focus:outline-none select-none py-0.5">
                                                    <span class="toggle-label">Lihat Selengkapnya</span>
                                                    <i class="fas fa-chevron-down text-[9px] transition-transform duration-200"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                        <!-- Salin Copywriting Button -->
                                        <?php if (!empty($task['copywriting'])): ?>
                                            <button type="button" 
                                                    onclick="copyTaskCopywriting(<?= $task['id'] ?>, this)" 
                                                    class="btn-interactive inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 text-[11px] font-bold transition">
                                                <i class="fas fa-copy text-[10px]"></i>
                                                <span>Salin Teks</span>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-[11px] text-slate-400 italic">Tanpa copywriting</span>
                                        <?php endif; ?>

                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $isPublished ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= $isPublished ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                            <?= ucfirst($task['status'] ?? 'published') ?>
                                        </span>
                                    </div>

                                    <!-- Action Buttons (Format persis seperti card katalog) -->
                                    <div class="flex gap-2 pt-1">
                                        <?php if ($hasMedia): ?>
                                            <a href="<?= $downloadUrl ?>" 
                                               onclick="triggerDownloadToast()" 
                                               class="btn-interactive px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center shrink-0" 
                                               title="Download Konten">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('/admin/tasks/edit/' . $task['id']) ?>" 
                                           class="btn-interactive flex-1 text-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 px-3 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>

                                        <form method="POST" action="<?= base_url('/admin/tasks/' . $task['id'] . '/delete') ?>" style="flex:1;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn-interactive w-full bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/80 px-3 py-2 rounded-xl text-xs font-bold transition">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination Footer Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <span id="tasksCountSummary">Menampilkan <?= count($tasks ?? []) ?> tugas</span>
                    <div class="flex items-center gap-1 select-none" id="tasksPaginationControls">
                        <button type="button" id="prevTaskBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fas fa-arrow-left text-[10px]"></i> Previous
                        </button>
                        <div id="tasksPaginationNumbers" class="flex items-center gap-1">
                            <!-- Generated by JS -->
                        </div>
                        <button type="button" id="nextTaskBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Next <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Preview Konten (Video / Gambar) -->
<div id="mediaPreviewModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 z-50 overflow-y-auto" onclick="handleMediaModalBackdropClick(event)">
    <div class="bg-white rounded-3xl shadow-2xl p-4 sm:p-5 w-fit min-w-[320px] sm:min-w-[360px] max-w-[92vw] sm:max-w-4xl mx-auto border border-slate-100 my-auto flex flex-col max-h-[calc(100vh-48px)]">
        <!-- Header Modal Preview -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 gap-3 min-w-0">
            <div class="flex items-center gap-2 min-w-0 pr-2">
                <span id="mediaModalBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-[#5e0078] border border-purple-200/80 shrink-0">
                    Media
                </span>
                <h3 id="mediaModalTitle" class="text-sm sm:text-base font-bold truncate text-slate-900 max-w-[200px] sm:max-w-xs md:max-w-sm">
                    Preview Konten
                </h3>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a id="mediaModalDownloadBtn" href="#" onclick="triggerDownloadToast()" class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200/80 text-xs font-bold transition">
                    <i class="fas fa-download text-xs"></i>
                    <span class="hidden sm:inline">Download</span>
                </a>
                <button type="button" onclick="closeMediaModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition" title="Tutup (Esc)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Body / Media Preview Bersih (Dihitung presisi berdasarkan 100vh agar muat utuh dalam 1 layar tanpa scroll) -->
        <div class="mt-3 flex items-center justify-center bg-slate-50/70 rounded-2xl p-1.5 sm:p-2 border border-slate-100 overflow-hidden" style="max-height: calc(100vh - 145px);">
            <!-- Frame Asli Video Player -->
            <video id="fullframeVideoPlayer" controls 
                   class="hidden w-auto max-w-full rounded-xl bg-black shadow-md mx-auto focus:outline-none max-h-[calc(100vh-160px)]" 
                   style="max-height: calc(100vh - 160px); object-fit: contain;">
                Browser Anda tidak mendukung pemutar video.
            </video>

            <!-- Frame Asli Image Viewer -->
            <img id="fullframeImageViewer" src="" alt="Preview Konten" 
                 class="hidden w-auto max-w-full object-contain rounded-xl shadow-md mx-auto max-h-[calc(100vh-160px)]" 
                 style="max-height: calc(100vh - 160px); object-fit: contain;">
        </div>
    </div>
</div>

<!-- Modal Buat Tugas Baru -->
<div id="createTaskModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-xl w-full border border-slate-100 my-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Buat Tugas &amp; Konten Promosi Baru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Sediakan konten gambar/video dan copywriting untuk disebarkan oleh mitra.</p>
            </div>
            <button type="button" onclick="closeCreateTaskModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="<?= base_url('/admin/tasks/store') ?>" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Judul Tugas / Nama Konten <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" required placeholder="Contoh: Banner Promo Umroh Awal Musim 1448H"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kategori Konten <span class="text-rose-500">*</span></label>
                    <select name="kategori" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="Promosi Paket Umrah">Promosi Paket Umrah</option>
                        <option value="Testimoni">Testimoni</option>
                        <option value="Edukasi">Edukasi</option>
                        <option value="Informasi">Informasi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="published">Published (Langsung Tampil ke Mitra)</option>
                        <option value="draft">Draft (Simpan Draf Internal)</option>
                    </select>
                </div>
            </div>

            <!-- Upload Konten Media -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Upload Konten (Gambar / Video)</label>
                <input type="file" name="media_file" accept="image/*,video/*"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#5e0078] hover:file:bg-purple-100 cursor-pointer border border-slate-300 rounded-xl bg-slate-50 p-1.5 focus:outline-none">
                <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                    <i class="fas fa-shield-alt text-[#5e0078]"></i>
                    Maks. 600 MB (Format: JPG, PNG, WEBP, MP4, WEBM). Gambar besar otomatis dikompresi.
                </p>
            </div>

            <!-- Copywriting Textarea -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Copywriting Promosi</label>
                <textarea name="copywriting" rows="4" placeholder="Tuliskan materi teks copywriting yang siap disalin oleh mitra..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition"></textarea>
            </div>

            <!-- Catatan / Instruksi -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Catatan / Instruksi Pembagian</label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Wajib dibagikan ke WhatsApp Story & feed Instagram pukul 19:00 WIB..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tenggat Waktu (Opsional)</label>
                <input type="datetime-local" name="tenggat" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateTaskModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-check mr-1.5"></i> Simpan &amp; Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Toast Notification Container -->
<div id="toastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `pointer-events-auto px-4 py-3 rounded-2xl shadow-xl border text-xs font-bold flex items-center gap-2 transform transition-all duration-300 translate-y-3 opacity-0 ${
        type === 'success' 
            ? 'bg-slate-900 text-white border-slate-800' 
            : 'bg-rose-600 text-white border-rose-500'
    }`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-white'}"></i> <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-3', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-y-3', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function copyCopywritingText(text, btn) {
    if (!text || !text.trim()) {
        showToast('Tidak ada teks copywriting untuk disalin.', 'error');
        return;
    }

    const setSuccessState = () => {
        showToast('Copywriting berhasil disalin ke clipboard!', 'success');
        if (btn) {
            const originalHtml = btn.dataset.originalHtml || btn.innerHTML;
            btn.dataset.originalHtml = originalHtml;
            btn.innerHTML = '<i class="fas fa-check text-[10px] text-emerald-600"></i> <span class="text-emerald-700">Tersalin!</span>';
            btn.classList.add('bg-emerald-50', 'border-emerald-300');
            btn.classList.remove('text-[#5e0078]', 'bg-white', 'border-purple-200');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('bg-emerald-50', 'border-emerald-300');
                btn.classList.add('text-[#5e0078]', 'bg-white', 'border-purple-200');
            }, 2000);
        }
    };

    const setErrorState = () => {
        showToast('Gagal menyalin teks secara otomatis. Silakan salin manual.', 'error');
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text)
            .then(setSuccessState)
            .catch(() => {
                fallbackCopyText(text, setSuccessState, setErrorState);
            });
    } else {
        fallbackCopyText(text, setSuccessState, setErrorState);
    }
}

function copyTaskCopywriting(taskId, btn) {
    const rawEl = document.getElementById('raw-copy-' + taskId);
    const text = rawEl ? rawEl.value : '';
    copyCopywritingText(text, btn);
}

function openMediaModalById(url, type, taskId, downloadUrl) {
    const title = document.getElementById('raw-judul-' + taskId)?.value || 'Preview Konten';
    const copywriting = document.getElementById('raw-copy-' + taskId)?.value || '';
    const catatan = document.getElementById('raw-catatan-' + taskId)?.value || '';
    openMediaModal(url, type, title, downloadUrl, copywriting, catatan);
}

function fallbackCopyText(text, onSuccess, onError) {
    try {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.position = 'fixed';
        textArea.style.width = '2em';
        textArea.style.height = '2em';
        textArea.style.padding = '0';
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';
        textArea.style.background = 'transparent';
        textArea.style.opacity = '0';
        textArea.setAttribute('readonly', '');
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        textArea.setSelectionRange(0, 999999);
        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);
        if (successful) {
            onSuccess();
        } else {
            onError();
        }
    } catch (err) {
        onError();
    }
}

function triggerDownloadToast() {
    showToast('Unduhan konten dimulai...');
}

function toggleCopywritingDropdown(taskId, btn) {
    const textEl = document.getElementById('copywriting-text-' + taskId);
    if (!textEl) return;

    const isExpanded = !textEl.classList.contains('line-clamp-2');
    const label = btn.querySelector('.toggle-label');
    const icon = btn.querySelector('i');

    if (isExpanded) {
        textEl.classList.add('line-clamp-2');
        if (label) label.textContent = 'Lihat Selengkapnya';
        if (icon) {
            icon.classList.remove('fa-chevron-up', 'rotate-180');
            icon.classList.add('fa-chevron-down');
        }
    } else {
        textEl.classList.remove('line-clamp-2');
        if (label) label.textContent = 'Sembunyikan';
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up', 'rotate-180');
        }
    }
}

function openMediaModal(url, type, title, downloadUrl) {
    const modal = document.getElementById('mediaPreviewModal');
    const badge = document.getElementById('mediaModalBadge');
    const titleEl = document.getElementById('mediaModalTitle');
    const downloadBtn = document.getElementById('mediaModalDownloadBtn');
    const video = document.getElementById('fullframeVideoPlayer');
    const image = document.getElementById('fullframeImageViewer');

    if (!modal) return;

    titleEl.textContent = title || 'Preview Konten';
    if (downloadUrl) {
        downloadBtn.href = downloadUrl;
        downloadBtn.classList.remove('hidden');
    } else {
        downloadBtn.classList.add('hidden');
    }

    if (type === 'video' && url) {
        badge.textContent = 'Video';
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 shrink-0';
        image.classList.add('hidden');
        image.src = '';

        video.src = url;
        video.classList.remove('hidden');
        video.currentTime = 0;
        video.play().catch(() => {});
    } else if (type === 'image' && url) {
        badge.textContent = 'Gambar';
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-[#5e0078] border border-purple-200/80 shrink-0';
        video.pause();
        video.src = '';
        video.classList.add('hidden');

        image.src = url;
        image.classList.remove('hidden');
    } else {
        badge.textContent = 'Media';
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 shrink-0';
        video.pause();
        video.src = '';
        video.classList.add('hidden');
        image.src = '';
        image.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeMediaModal() {
    const modal = document.getElementById('mediaPreviewModal');
    const video = document.getElementById('fullframeVideoPlayer');
    const image = document.getElementById('fullframeImageViewer');

    if (modal) {
        modal.classList.add('hidden');
    }
    if (video) {
        video.pause();
        video.currentTime = 0;
        video.src = '';
    }
    if (image) {
        image.src = '';
    }
    document.body.classList.remove('overflow-hidden');
}

function handleMediaModalBackdropClick(e) {
    if (e.target.id === 'mediaPreviewModal') {
        closeMediaModal();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('mediaPreviewModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeMediaModal();
        }
    }
});

function openCreateTaskModal() {
    document.getElementById('createTaskModal').classList.remove('hidden');
}

function closeCreateTaskModal() {
    document.getElementById('createTaskModal').classList.add('hidden');
}



document.addEventListener('DOMContentLoaded', function() {
    AmanahTable.init({
        containerId: 'tasksGridContainer',
        rowSelector: '.task-card-item',
        entityName: 'tugas promosi',
        defaultPageSize: 10,
        searchInputId: 'tasksSearchInput',
        sortSelectId: 'tasksSortSelect',
        pageSizeSelectId: 'tasksPageSizeSelect',
        summaryId: 'tasksCountSummary',
        prevBtnId: 'prevTaskBtn',
        nextBtnId: 'nextTaskBtn',
        numbersId: 'tasksPaginationNumbers',
        emptyStateId: 'emptyTaskCard',
        resetBtnId: 'resetTasksFiltersBtn',
        defaultSort: 'created_desc',
        filters: [
            { selectId: 'tasksKategoriFilter', key: 'kategori', defaultValue: 'all' },
            { selectId: 'tasksStatusFilter', key: 'status', defaultValue: 'all' }
        ],
        sortDefinitions: {
            created_desc: (a, b) => (parseInt(b.getAttribute('data-created'), 10) || 0) - (parseInt(a.getAttribute('data-created'), 10) || 0),
            created_asc: (a, b) => (parseInt(a.getAttribute('data-created'), 10) || 0) - (parseInt(b.getAttribute('data-created'), 10) || 0),
            judul_asc: (a, b) => (a.getAttribute('data-judul') || '').localeCompare(b.getAttribute('data-judul') || '', 'id', { numeric: true, sensitivity: 'base' }),
            judul_desc: (a, b) => (b.getAttribute('data-judul') || '').localeCompare(a.getAttribute('data-judul') || '', 'id', { numeric: true, sensitivity: 'base' })
        }
    });
});
</script>
<?= $this->endSection() ?>
