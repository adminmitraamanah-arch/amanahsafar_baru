<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Page Title & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">Manajemen Landing Page</h1>
                        <p class="text-sm text-gray-600 mt-1">Atur durasi otomatis perpindahan slide hero dan kelola konten slide aktif.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('/') ?>" target="_blank" class="btn-interactive px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center gap-2 transition">
                            <i class="fas fa-external-link-alt"></i> Pratinjau Landing Page
                        </a>
                        <button onclick="document.getElementById('modalAddSlide').classList.remove('hidden')" class="btn-interactive px-4 py-2 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-semibold rounded-lg shadow-sm flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i> Tambah Slide Baru
                        </button>
                        <button onclick="document.getElementById('modalAddTestimonial').classList.remove('hidden')" class="btn-interactive px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center gap-2 transition">
                            <i class="fas fa-comment-dots"></i> Tambah Testimoni
                        </button>
                    </div>
                </div>

                <!-- CARD 1: PENGATURAN ANNOUNCEMENT BAR (HEADER ATAS) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold">
                                <i class="fas fa-bullhorn text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Announcement Bar</h2>
                                <p class="text-xs text-gray-500">Kelola pesan promosi bar paling atas, badge resmi, tautan aksi, dan pratinjau langsung.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="previewStatusBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= (($settings['announcement_active'] ?? '1') === '1') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' ?>">
                                <span class="w-2 h-2 rounded-full <?= (($settings['announcement_active'] ?? '1') === '1') ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' ?>"></span>
                                <span id="previewStatusText"><?= (($settings['announcement_active'] ?? '1') === '1') ? 'Status: Aktif' : 'Status: Nonaktif' ?></span>
                            </span>
                        </div>
                    </div>

                    <!-- LIVE PREVIEW CONTAINER -->
                    <div class="p-6 bg-slate-50/70 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-eye text-[#5e0078]"></i> Live Preview (Tampilan Publik Real-time)
                            </label>
                            <span class="text-[11px] text-gray-400">Pratinjau otomatis berubah saat formulir diedit</span>
                        </div>
                        
                        <div class="rounded-xl overflow-hidden shadow-sm border border-purple-900/30">
                            <!-- Public Appearance Simulated Bar -->
                            <div id="liveAnnouncementBar" class="bg-[#3e004f] text-purple-100 text-xs py-2.5 px-4 text-center font-medium border-b border-purple-900/50 transition-all duration-300 <?= (($settings['announcement_active'] ?? '1') === '0') ? 'opacity-40 grayscale-[50%]' : '' ?>">
                                <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                                    <span id="previewBadgeEl" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#5e0078] text-white text-[11px] font-semibold tracking-wide <?= (($settings['announcement_show_badge'] ?? '1') === '0') ? 'hidden' : '' ?>">
                                        <i class="fas fa-star text-amber-300 text-[10px]"></i> <span id="previewBadgeText"><?= esc($settings['announcement_badge_text'] ?? 'Resmi & Terpercaya') ?></span>
                                    </span>
                                    <span id="previewMessageEl" class="leading-relaxed"><?= esc($settings['announcement_text'] ?? ($settings['site_announcement'] ?? 'Pendaftaran Umroh & Haji Musim 1448 H Telah Dibuka. Kuota Terbatas!')) ?></span>
                                    <a id="previewLinkEl" href="#" onclick="return false;" class="underline hover:text-white font-semibold ml-1 transition-colors <?= (($settings['announcement_show_link'] ?? '1') === '0') ? 'hidden' : '' ?>">
                                        <span id="previewLinkText"><?= esc($settings['announcement_link_text'] ?? 'Lihat Jadwal →') ?></span>
                                        <i id="previewExternalIcon" class="fas fa-external-link-alt text-[9px] ml-1 <?= (($settings['announcement_link_target_blank'] ?? '0') === '1') ? '' : 'hidden' ?>"></i>
                                    </a>
                                </div>
                            </div>
                            <div id="previewDisabledNotice" class="bg-amber-50 px-4 py-2 border-t border-amber-200 text-amber-800 text-xs flex items-center gap-2 <?= (($settings['announcement_active'] ?? '1') === '1') ? 'hidden' : '' ?>">
                                <i class="fas fa-info-circle text-amber-600"></i>
                                <span><strong>Pemberitahuan:</strong> Announcement bar saat ini dalam status <strong>Nonaktif</strong> dan tidak akan ditampilkan pada landing page publik.</span>
                            </div>
                        </div>
                    </div>

                    <!-- FORM SETTINGS -->
                    <form action="<?= base_url('/superadmin/landing/settings') ?>" method="POST" class="p-6">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_type" value="announcement">

                        <div class="space-y-6">
                            <!-- Toggle Utama: Aktifkan / Nonaktifkan -->
                            <div class="flex items-center justify-between p-4 bg-purple-50/50 rounded-xl border border-purple-100">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">Aktifkan Announcement Bar</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Tampilkan bar pengumuman pada posisi paling atas di landing page publik.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="inputAnnouncementActive" name="announcement_active" value="1" 
                                           <?= (($settings['announcement_active'] ?? '1') === '1') ? 'checked' : '' ?> 
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#5e0078] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5e0078]"></div>
                                </label>
                            </div>

                            <!-- Grid Input Konten -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Isi Pesan Pengumuman -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Isi Pengumuman <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea id="inputAnnouncementText" name="announcement_text" rows="2" required
                                              placeholder="Contoh: Pendaftaran Umroh & Haji Musim 1448 H Telah Dibuka. Kuota Terbatas!"
                                              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none"><?= esc($settings['announcement_text'] ?? ($settings['site_announcement'] ?? 'Pendaftaran Umroh & Haji Musim 1448 H Telah Dibuka. Kuota Terbatas!')) ?></textarea>
                                    <p class="text-[11px] text-gray-500 mt-1">Pesan teks utama yang ditampilkan di tengah announcement bar.</p>
                                </div>

                                <!-- Pengaturan Badge Pill -->
                                <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-200 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-tag text-[#5e0078]"></i> Badge Promosi
                                        </h4>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" id="inputShowBadge" name="announcement_show_badge" value="1"
                                                   <?= (($settings['announcement_show_badge'] ?? '1') === '1') ? 'checked' : '' ?>
                                                   class="rounded border-gray-300 text-[#5e0078] focus:ring-[#5e0078]">
                                            <span class="text-xs font-semibold text-gray-700">Tampilkan Badge</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Teks Badge</label>
                                        <input type="text" id="inputBadgeText" name="announcement_badge_text"
                                               value="<?= esc($settings['announcement_badge_text'] ?? 'Resmi & Terpercaya') ?>"
                                               placeholder="Contoh: Resmi & Terpercaya"
                                               class="w-full bg-white border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                                        <p class="text-[11px] text-gray-400 mt-1">Disertai ikon bintang kuning secara otomatis.</p>
                                    </div>
                                </div>

                                <!-- Pengaturan Link Aksi -->
                                <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-200 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-link text-[#5e0078]"></i> Tautan / Link Aksi
                                        </h4>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" id="inputShowLink" name="announcement_show_link" value="1"
                                                   <?= (($settings['announcement_show_link'] ?? '1') === '1') ? 'checked' : '' ?>
                                                   class="rounded border-gray-300 text-[#5e0078] focus:ring-[#5e0078]">
                                            <span class="text-xs font-semibold text-gray-700">Tampilkan Link</span>
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Teks Link</label>
                                            <input type="text" id="inputLinkText" name="announcement_link_text"
                                                   value="<?= esc($settings['announcement_link_text'] ?? 'Lihat Jadwal →') ?>"
                                                   placeholder="Contoh: Lihat Jadwal →"
                                                   class="w-full bg-white border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1.5">URL Tujuan Link</label>
                                            <input type="text" id="inputLinkUrl" name="announcement_link_url"
                                                   value="<?= esc($settings['announcement_link_url'] ?? '/catalog') ?>"
                                                   placeholder="Contoh: /catalog atau https://..."
                                                   class="w-full bg-white border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="pt-1">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" id="inputTargetBlank" name="announcement_link_target_blank" value="1"
                                                   <?= (($settings['announcement_link_target_blank'] ?? '0') === '1') ? 'checked' : '' ?>
                                                   class="rounded border-gray-300 text-[#5e0078] focus:ring-[#5e0078]">
                                            <span class="text-xs font-medium text-gray-700">Buka link di tab baru (<code class="text-[11px] bg-gray-200 px-1 py-0.5 rounded">target="_blank"</code>)</span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="btn-interactive w-full sm:w-auto px-6 py-2.5 rounded-lg bg-[#5e0078] hover:bg-[#4c0062] text-white font-bold text-xs shadow-sm flex items-center justify-center gap-2 transition">
                                <i class="fas fa-save"></i> Simpan Pengaturan Announcement Bar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CARD 2: PENGATURAN DURASI SLIDE & KONTEN GLOBAL -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                                <i class="fas fa-stopwatch text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Durasi Slide &amp; Pengaturan Umum</h2>
                                <p class="text-xs text-gray-500">Super Admin dapat menentukan kecepatan transisi slide dan kontak resmi.</p>
                            </div>
                        </div>
                    </div>

                    <form action="<?= base_url('/superadmin/landing/settings') ?>" method="POST" class="p-6">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_type" value="general">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- Durasi Detik Slide -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Durasi Pergantian Slide (Detik) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="slide_interval_seconds" min="1" max="60" 
                                           value="<?= esc($settings['slide_interval_seconds'] ?? '5') ?>" required
                                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-semibold text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                                    <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">detik</span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-1">Default 5 detik (Direkomendasikan: 4 s/d 8 detik).</p>
                            </div>

                            <!-- Teks Announcement Bar -->
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Teks Pengumuman Header (Top Bar)
                                </label>
                                <input type="text" name="site_announcement" 
                                       value="<?= esc($settings['site_announcement'] ?? 'Pendaftaran Umroh & Haji Musim 1448 H Telah Dibuka. Kuota Terbatas!') ?>"
                                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                                <p class="text-[11px] text-gray-500 mt-1">Pesan promosi berjalan di bagian paling atas halaman.</p>
                            </div>

                            <!-- Link Tombol WhatsApp Melayang -->
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Link Tombol WhatsApp Melayang (CS)
                                </label>
                                <input type="url" name="whatsapp_cs_url" 
                                       value="<?= esc($settings['whatsapp_cs_url'] ?? '') ?>"
                                       placeholder="https://wa.me/6281234567890?text=Halo..."
                                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                                <p class="text-[11px] text-gray-500 mt-1">Link WhatsApp lengkap untuk tombol melayang di pojok kanan landing page.</p>
                            </div>

                            <!-- Kontak WhatsApp -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Nomor WhatsApp Konsultasi
                                </label>
                                <input type="text" name="contact_whatsapp" 
                                       value="<?= esc($settings['contact_whatsapp'] ?? '6281234567890') ?>"
                                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                            </div>

                            <!-- Kontak Telepon -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Nomor Telepon Kantor
                                </label>
                                <input type="text" name="contact_phone" 
                                       value="<?= esc($settings['contact_phone'] ?? '(021) 7890-1234') ?>"
                                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                            </div>

                            <!-- Kontak Email -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Email Resmi
                                </label>
                                <input type="email" name="contact_email" 
                                       value="<?= esc($settings['contact_email'] ?? 'info@amanahsafar.com') ?>"
                                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                            </div>

                            <!-- Alamat Kantor -->
                            <div class="md:col-span-2 lg:col-span-3">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Alamat Kantor Resmi
                                </label>
                                <textarea name="contact_address" rows="2" 
                                          class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none"><?= esc($settings['contact_address'] ?? '') ?></textarea>
                            </div>

                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="btn-interactive px-6 py-2.5 rounded-lg bg-[#5e0078] hover:bg-[#4c0062] text-white font-bold text-xs shadow-sm transition">
                                <i class="fas fa-save mr-1.5"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CARD 2: DAFTAR SLIDE HERO GAMBAR AKTIF -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold">
                                <i class="fas fa-images text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Daftar Slide Gambar (Hero Carousel)</h2>
                                <p class="text-xs text-gray-500">Kelola gambar latar, judul promosi, serta tombol aksi tiap slide.</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-[#5e0078]">
                            Total: <?= count($slides) ?> Slide
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-[11px] uppercase tracking-wider font-bold text-gray-600 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3.5">Urutan</th>
                                    <th class="px-6 py-3.5">Pratinjau Gambar</th>
                                    <th class="px-6 py-3.5">Judul &amp; Badge</th>
                                    <th class="px-6 py-3.5">Deskripsi</th>
                                    <th class="px-6 py-3.5">Tombol CTA</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                <?php if (empty($slides)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada slide hero. Silakan klik tombol "Tambah Slide Baru".
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($slides as $slide): ?>
                                    <tr class="table-row-interactive hover:bg-purple-50/50 transition-colors duration-150">
                                        <td class="px-6 py-4 font-bold text-gray-700">
                                            #<?= esc($slide['order_num']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="w-24 h-14 rounded-lg overflow-hidden bg-gray-900 border border-gray-200 shadow-sm relative group">
                                                <?php if (($slide['media_type'] ?? '') === 'video' || preg_match('/\.(mp4|webm|ogg|mov)$/i', $slide['image_url'])): ?>
                                                    <video src="<?= esc($slide['image_url']) ?>" class="w-full h-full object-cover" muted></video>
                                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/70 text-white text-[9px] font-bold flex items-center gap-1">
                                                        <i class="fas fa-video text-[8px] text-purple-300"></i> Video
                                                    </span>
                                                <?php else: ?>
                                                    <img src="<?= esc($slide['image_url']) ?>" alt="Slide" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold block w-max mb-1">
                                                <?= esc($slide['badge_text']) ?>
                                            </span>
                                            <strong class="text-gray-900 font-bold text-sm block"><?= esc($slide['title']) ?></strong>
                                            <span class="text-gray-500 text-[11px]"><?= esc($slide['subtitle']) ?></span>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs text-gray-600 truncate" title="<?= esc($slide['description']) ?>">
                                            <?= esc($slide['description']) ?>
                                        </td>
                                        <td class="px-6 py-4 space-y-1">
                                            <div class="flex items-center gap-1.5 text-amber-700 font-semibold">
                                                <i class="fas fa-link text-[10px]"></i> <?= esc($slide['cta_primary_text'] ?: '-') ?>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-gray-500">
                                                <i class="fas fa-link text-[10px]"></i> <?= esc($slide['cta_secondary_text'] ?: '-') ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($slide['status'] === 'aktif'): ?>
                                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                                    Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-bold">
                                                    Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button onclick='openEditModal(<?= json_encode($slide) ?>)' 
                                                    class="btn-interactive p-2 text-purple-700 hover:bg-purple-100/70 rounded-lg transition" title="Edit Slide">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('/superadmin/landing/slides/delete/' . $slide['id']) ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus slide ini?')"
                                               class="btn-interactive p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition inline-block" title="Hapus Slide">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CARD 3: DAFTAR TESTIMONI JAMAAH (KATA JAMAAH) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                                <i class="fas fa-comment-dots text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Daftar Testimoni Jamaah (Kata Jamaah)</h2>
                                <p class="text-xs text-gray-500">Kelola ulasan jamaah, foto portrait, kota asal, dan paket umroh. Jika foto tidak diupload/NULL, kartu otomatis menampilkan warna ungu elegan.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-amber-100 text-amber-800">
                                Total: <?= count($testimonials ?? []) ?> Testimoni
                            </span>
                            <button onclick="document.getElementById('modalAddTestimonial').classList.remove('hidden')" class="btn-interactive px-3.5 py-1.5 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-semibold rounded-lg shadow-sm flex items-center gap-1.5 transition">
                                <i class="fas fa-plus"></i> Tambah Baru
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-[11px] uppercase tracking-wider font-bold text-gray-600 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3.5">Urutan</th>
                                    <th class="px-6 py-3.5">Foto Jamaah</th>
                                    <th class="px-6 py-3.5">Nama &amp; Kota Asal</th>
                                    <th class="px-6 py-3.5">Program &amp; Keberangkatan</th>
                                    <th class="px-6 py-3.5">Ulasan Jamaah</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                <?php if (empty($testimonials)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada data testimoni jamaah. Silakan klik tombol "Tambah Baru".
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($testimonials ?? [] as $t): ?>
                                    <tr class="table-row-interactive hover:bg-amber-50/40 transition-colors duration-150">
                                        <td class="px-6 py-4 font-bold text-gray-700">
                                            #<?= esc($t['order_num']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if (!empty($t['foto'])): ?>
                                                <div class="w-14 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shadow-sm relative group">
                                                    <img src="<?= esc($t['foto']) ?>" alt="<?= esc($t['nama_jamaah']) ?>" class="w-full h-full object-cover">
                                                </div>
                                            <?php else: ?>
                                                <!-- Fallback warna ungu murni jika foto NULL / belum diupload -->
                                                <div class="w-14 h-16 rounded-lg bg-gradient-to-br from-[#5e0078] to-[#3a004a] text-white flex flex-col items-center justify-center p-1 text-center shadow-sm border border-purple-200/40" title="Foto kosong: Tampil warna ungu">
                                                    <i class="fas fa-kaaba text-amber-300 text-sm mb-1"></i>
                                                    <span class="text-[9px] font-bold uppercase tracking-tight text-purple-200 leading-tight">Ungu</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900"><?= esc($t['nama_jamaah']) ?></div>
                                            <div class="text-[11px] text-amber-700 font-semibold flex items-center gap-1 mt-0.5">
                                                <i class="fas fa-map-marker-alt text-[10px]"></i> <?= esc($t['kota_asal']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-[#5e0078] border border-purple-200">
                                                <?= esc($t['program_paket']) ?>
                                            </span>
                                            <div class="text-[11px] text-gray-500 font-medium mt-1">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i> <?= esc($t['tahun_keberangkatan']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs">
                                            <div class="flex text-amber-400 text-[10px] mb-1">
                                                <?php for ($i = 1; $i <= (int)($t['rating'] ?? 5); $i++): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="text-gray-600 line-clamp-2 italic leading-relaxed text-[11px]">
                                                "<?= esc($t['testimoni']) ?>"
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($t['status'] === 'aktif'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <button onclick='openEditTestimonialModal(<?= json_encode($t) ?>)' 
                                                    class="btn-interactive p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition" title="Edit Testimoni">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('/superadmin/landing/testimonials/delete/' . $t['id']) ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus testimoni <?= esc($t['nama_jamaah']) ?>?')"
                                               class="btn-interactive p-2 text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 rounded-lg transition" title="Hapus Testimoni">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH SLIDE -->
<div id="modalAddSlide" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Tambah Slide Baru</h3>
            <button onclick="document.getElementById('modalAddSlide').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <form action="<?= base_url('/superadmin/landing/slides/store') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Upload File Media (Gambar / Video hingga 1GB) -->
                <div class="sm:col-span-2 bg-purple-50/50 p-4 rounded-xl border border-purple-100 space-y-3">
                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">
                        <i class="fas fa-cloud-upload-alt text-[#5e0078] mr-1"></i> Upload File Media (Gambar atau Video) <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="media_file" accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-xs focus:ring-2 focus:ring-[#5e0078] file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200">
                    <div class="flex items-center justify-between text-[11px] text-gray-500 pt-1">
                        <span>Format didukung: <strong>JPG, PNG, WEBP, MP4, WEBM</strong></span>
                        <span class="font-bold text-[#5e0078]">Maksimal: 1 GB</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Badge Atas</label>
                    <input type="text" name="badge_text" placeholder="Contoh: PPIU Resmi Kemenag" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Urut &amp; Status</label>
                    <div class="flex gap-2">
                        <input type="number" name="order_num" value="1" min="1" class="w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-900">
                        <select name="status" class="w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-2 py-2 text-xs text-gray-900 font-semibold">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul Utama <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Wujudkan Ibadah Khusyuk & Terencana" required class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Subjudul</label>
                    <input type="text" name="subtitle" placeholder="Contoh: Bersama Keluarga Tercinta" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Tuliskan keterangan fasilitas hotel, maskapai, dsb..." class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Teks Tombol Utama</label>
                    <input type="text" name="cta_primary_text" placeholder="Konsultasi WhatsApp" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Link Tombol Utama</label>
                    <input type="text" name="cta_primary_url" placeholder="https://wa.me/..." class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Teks Tombol Kedua</label>
                    <input type="text" name="cta_secondary_text" placeholder="Jelajahi Paket" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Link Tombol Kedua</label>
                    <input type="text" name="cta_secondary_url" placeholder="#program atau /catalog" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalAddSlide').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#5e0078] hover:bg-[#4c0062] text-white rounded-lg">Simpan Slide</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT SLIDE -->
<div id="modalEditSlide" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Edit Slide Hero</h3>
            <button onclick="document.getElementById('modalEditSlide').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <form id="formEditSlide" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Upload File Media Edit (Gambar / Video hingga 1GB) -->
                <div class="sm:col-span-2 bg-purple-50/50 p-4 rounded-xl border border-purple-100 space-y-3">
                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">
                        <i class="fas fa-cloud-upload-alt text-[#5e0078] mr-1"></i> Upload File Media (Gambar atau Video)
                    </label>
                    
                    <div id="editMediaPreview" class="p-2 bg-white rounded-lg border border-gray-200 hidden items-center gap-3">
                        <span class="text-xs text-gray-500 font-medium">File Saat Ini:</span>
                        <a id="editMediaLink" href="#" target="_blank" class="text-xs font-bold text-blue-600 hover:underline truncate max-w-sm">Pratinjau Media</a>
                    </div>

                    <!-- Hidden input to preserve existing media URL when not replacing -->
                    <input type="hidden" id="edit_image_url" name="image_url">

                    <input type="file" name="media_file" accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-xs focus:ring-2 focus:ring-[#5e0078] file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200">
                    <div class="flex items-center justify-between text-[11px] text-gray-500 pt-1">
                        <span>Biarkan kosong jika tidak ingin mengganti file. Format: <strong>JPG, PNG, WEBP, MP4, WEBM</strong></span>
                        <span class="font-bold text-[#5e0078]">Maksimal: 1 GB</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Badge Atas</label>
                    <input type="text" id="edit_badge_text" name="badge_text" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Urut &amp; Status</label>
                    <div class="flex gap-2">
                        <input type="number" id="edit_order_num" name="order_num" min="1" class="w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-900">
                        <select id="edit_status" name="status" class="w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-2 py-2 text-xs text-gray-900 font-semibold">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul Utama <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_title" name="title" required class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Subjudul</label>
                    <input type="text" id="edit_subtitle" name="subtitle" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Singkat</label>
                    <textarea id="edit_description" name="description" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Teks Tombol Utama</label>
                    <input type="text" id="edit_cta_primary_text" name="cta_primary_text" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Link Tombol Utama</label>
                    <input type="text" id="edit_cta_primary_url" name="cta_primary_url" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Teks Tombol Kedua</label>
                    <input type="text" id="edit_cta_secondary_text" name="cta_secondary_text" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Link Tombol Kedua</label>
                    <input type="text" id="edit_cta_secondary_url" name="cta_secondary_url" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900">
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalEditSlide').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#5e0078] hover:bg-[#4c0062] text-white rounded-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(slide) {
    document.getElementById('formEditSlide').action = '<?= base_url('/superadmin/landing/slides/update/') ?>' + slide.id;
    document.getElementById('edit_image_url').value = slide.image_url || '';
    
    // Set preview current file
    const previewDiv = document.getElementById('editMediaPreview');
    const previewLink = document.getElementById('editMediaLink');
    if (slide.image_url) {
        previewDiv.classList.remove('hidden');
        previewDiv.classList.add('flex');
        previewLink.href = slide.image_url;
        const isVideo = slide.media_type === 'video' || /\.(mp4|webm|ogg|mov)$/i.test(slide.image_url);
        previewLink.innerHTML = (isVideo ? '<i class="fas fa-video text-purple-600 mr-1"></i> File Video: ' : '<i class="fas fa-image text-blue-600 mr-1"></i> File Gambar: ') + '<span class="underline">' + slide.image_url.split('/').pop() + '</span>';
    } else {
        previewDiv.classList.add('hidden');
        previewDiv.classList.remove('flex');
    }

    document.getElementById('edit_badge_text').value = slide.badge_text || '';
    document.getElementById('edit_order_num').value = slide.order_num || '1';
    document.getElementById('edit_status').value = slide.status || 'aktif';
    document.getElementById('edit_title').value = slide.title || '';
    document.getElementById('edit_subtitle').value = slide.subtitle || '';
    document.getElementById('edit_description').value = slide.description || '';
    document.getElementById('edit_cta_primary_text').value = slide.cta_primary_text || '';
    document.getElementById('edit_cta_primary_url').value = slide.cta_primary_url || '';
    document.getElementById('edit_cta_secondary_text').value = slide.cta_secondary_text || '';
    document.getElementById('edit_cta_secondary_url').value = slide.cta_secondary_url || '';
    
    document.getElementById('modalEditSlide').classList.remove('hidden');
}

function openEditTestimonialModal(t) {
    document.getElementById('formEditTestimonial').action = '<?= base_url('/superadmin/landing/testimonials/update/') ?>' + t.id;
    document.getElementById('edit_testi_nama').value = t.nama_jamaah || '';
    document.getElementById('edit_testi_program').value = t.program_paket || '';
    document.getElementById('edit_testi_tahun').value = t.tahun_keberangkatan || '';
    document.getElementById('edit_testi_kota').value = t.kota_asal || '';
    document.getElementById('edit_testi_rating').value = t.rating || '5';
    document.getElementById('edit_testi_order').value = t.order_num || '1';
    document.getElementById('edit_testi_status').value = t.status || 'aktif';
    document.getElementById('edit_testi_isi').value = t.testimoni || '';

    const previewDiv = document.getElementById('editTestiPhotoPreview');
    const previewImg = document.getElementById('editTestiImg');
    const previewPurple = document.getElementById('editTestiPurpleFallback');
    
    if (t.foto && t.foto.trim() !== '') {
        previewDiv.classList.remove('hidden');
        previewImg.classList.remove('hidden');
        previewImg.src = t.foto;
        previewPurple.classList.add('hidden');
    } else {
        previewDiv.classList.remove('hidden');
        previewImg.classList.add('hidden');
        previewPurple.classList.remove('hidden');
    }

    document.getElementById('modalEditTestimonial').classList.remove('hidden');
}

// Announcement Bar Live Preview Sync Controller
(function() {
    const inputActive = document.getElementById('inputAnnouncementActive');
    const inputShowBadge = document.getElementById('inputShowBadge');
    const inputBadgeText = document.getElementById('inputBadgeText');
    const inputAnnouncementText = document.getElementById('inputAnnouncementText');
    const inputShowLink = document.getElementById('inputShowLink');
    const inputLinkText = document.getElementById('inputLinkText');
    const inputLinkUrl = document.getElementById('inputLinkUrl');
    const inputTargetBlank = document.getElementById('inputTargetBlank');

    const liveBar = document.getElementById('liveAnnouncementBar');
    const previewBadgeEl = document.getElementById('previewBadgeEl');
    const previewBadgeText = document.getElementById('previewBadgeText');
    const previewMessageEl = document.getElementById('previewMessageEl');
    const previewLinkEl = document.getElementById('previewLinkEl');
    const previewLinkText = document.getElementById('previewLinkText');
    const previewExternalIcon = document.getElementById('previewExternalIcon');
    const previewDisabledNotice = document.getElementById('previewDisabledNotice');
    const previewStatusBadge = document.getElementById('previewStatusBadge');
    const previewStatusText = document.getElementById('previewStatusText');

    if (!liveBar) return;

    function updatePreview() {
        // 1. Status Aktif
        const isActive = inputActive ? inputActive.checked : true;
        if (isActive) {
            liveBar.classList.remove('opacity-40', 'grayscale-[50%]');
            if (previewDisabledNotice) previewDisabledNotice.classList.add('hidden');
            if (previewStatusBadge) {
                previewStatusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200';
                previewStatusBadge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span><span>Status: Aktif</span>';
            }
        } else {
            liveBar.classList.add('opacity-40', 'grayscale-[50%]');
            if (previewDisabledNotice) previewDisabledNotice.classList.remove('hidden');
            if (previewStatusBadge) {
                previewStatusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200';
                previewStatusBadge.innerHTML = '<span class="w-2 h-2 rounded-full bg-gray-400"></span><span>Status: Nonaktif</span>';
            }
        }

        // 2. Badge Promosi
        const showBadge = inputShowBadge ? inputShowBadge.checked : true;
        const badgeVal = inputBadgeText ? inputBadgeText.value.trim() : '';
        if (showBadge && badgeVal.length > 0) {
            if (previewBadgeEl) previewBadgeEl.classList.remove('hidden');
            if (previewBadgeText) previewBadgeText.textContent = badgeVal;
        } else {
            if (previewBadgeEl) previewBadgeEl.classList.add('hidden');
        }

        // 3. Teks Pesan Pengumuman
        const msgVal = inputAnnouncementText ? inputAnnouncementText.value : '';
        if (previewMessageEl) previewMessageEl.textContent = msgVal;

        // 4. Link Aksi
        const showLink = inputShowLink ? inputShowLink.checked : true;
        const linkVal = inputLinkText ? inputLinkText.value.trim() : '';
        if (showLink && linkVal.length > 0) {
            if (previewLinkEl) previewLinkEl.classList.remove('hidden');
            if (previewLinkText) previewLinkText.textContent = linkVal;
        } else {
            if (previewLinkEl) previewLinkEl.classList.add('hidden');
        }

        // 5. Ikon Tab Baru
        const isTargetBlank = inputTargetBlank ? inputTargetBlank.checked : false;
        if (previewExternalIcon) {
            if (isTargetBlank) {
                previewExternalIcon.classList.remove('hidden');
            } else {
                previewExternalIcon.classList.add('hidden');
            }
        }
    }

    // Pasang listener pada seluruh elemen input
    [inputActive, inputShowBadge, inputBadgeText, inputAnnouncementText, inputShowLink, inputLinkText, inputLinkUrl, inputTargetBlank].forEach(el => {
        if (!el) return;
        el.addEventListener('input', updatePreview);
        el.addEventListener('change', updatePreview);
    });

    updatePreview();
})();
</script>

<!-- MODAL TAMBAH TESTIMONI -->
<div id="modalAddTestimonial" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                    <i class="fas fa-comment-dots text-sm"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">Tambah Testimoni Jamaah Baru</h3>
            </div>
            <button onclick="document.getElementById('modalAddTestimonial').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <form action="<?= base_url('/superadmin/landing/testimonials/store') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Nama Jamaah -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Nama Jamaah <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_jamaah" required placeholder="Contoh: H. Bambang Prakoso" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Upload Foto Jamaah -->
                <div class="sm:col-span-2 bg-purple-50/40 p-3.5 rounded-xl border border-purple-100 space-y-2">
                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">
                        <i class="fas fa-camera text-[#5e0078] mr-1"></i> Foto Jamaah (Opsional)
                    </label>
                    <input type="file" name="foto_file" accept="image/*"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg bg-white text-xs focus:ring-2 focus:ring-[#5e0078] file:mr-4 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200">
                    <p class="text-[11px] text-gray-500">
                        <i class="fas fa-info-circle text-[#5e0078] mr-1"></i> Jika foto belum ada/dikosongkan, sistem secara otomatis menampilkan warna ungu elegan sesuai database.
                    </p>
                </div>

                <!-- Program / Paket Umroh -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Program / Paket Umroh</label>
                    <input type="text" name="program_paket" placeholder="Contoh: Umroh Syawal 1447 H" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Tahun / Keberangkatan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tahun Keberangkatan</label>
                    <input type="text" name="tahun_keberangkatan" placeholder="Contoh: 1447 H / 2026" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Kota Asal -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kota / Kabupaten Asal</label>
                    <input type="text" name="kota_asal" placeholder="Contoh: Bogor" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rating Bintang</label>
                    <select name="rating" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                        <option value="5" selected>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3">⭐⭐⭐ (3 Bintang)</option>
                    </select>
                </div>

                <!-- Urutan & Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Urutan Tampil (#)</label>
                    <input type="number" name="order_num" value="1" min="1" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Publikasi</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                        <option value="aktif" selected>Aktif (Tampil di Landing)</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <!-- Isi Testimoni -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Isi Ulasan / Testimoni Jamaah <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="testimoni" rows="3" required placeholder="Tuliskan pengalaman jamaah selama perjalanan ibadah..." 
                              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none"></textarea>
                </div>

            </div>
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalAddTestimonial').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#5e0078] hover:bg-[#4c0062] text-white rounded-lg shadow-sm">Simpan Testimoni</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT TESTIMONI -->
<div id="modalEditTestimonial" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                    <i class="fas fa-edit text-sm"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">Edit Testimoni Jamaah</h3>
            </div>
            <button onclick="document.getElementById('modalEditTestimonial').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <form id="formEditTestimonial" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Nama Jamaah -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Nama Jamaah <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="edit_testi_nama" name="nama_jamaah" required 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Foto Saat Ini & Upload Pengganti -->
                <div class="sm:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-200 space-y-3">
                    <div id="editTestiPhotoPreview" class="flex items-center gap-3">
                        <img id="editTestiImg" src="" alt="Foto" class="w-12 h-14 object-cover rounded-lg border border-gray-300 shadow-xs hidden">
                        <div id="editTestiPurpleFallback" class="w-12 h-14 rounded-lg bg-gradient-to-br from-[#5e0078] to-[#3a004a] text-white flex flex-col items-center justify-center text-[9px] font-bold p-1 text-center shadow-xs hidden">
                            <i class="fas fa-kaaba text-amber-300 text-xs mb-0.5"></i>
                            Ungu
                        </div>
                        <div class="text-xs text-gray-600">
                            <span class="font-bold text-gray-800 block">Status Foto Saat Ini</span>
                            <span class="text-[11px] text-gray-500">Unggah file baru di bawah ini jika ingin mengganti foto.</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">
                            <i class="fas fa-camera text-[#5e0078] mr-1"></i> Ganti Foto Jamaah (Opsional)
                        </label>
                        <input type="file" name="foto_file" accept="image/*"
                               class="w-full px-3 py-1.5 border border-gray-300 rounded-lg bg-white text-xs focus:ring-2 focus:ring-[#5e0078] file:mr-4 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200">
                    </div>
                </div>

                <!-- Program / Paket Umroh -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Program / Paket Umroh</label>
                    <input type="text" id="edit_testi_program" name="program_paket" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Tahun / Keberangkatan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tahun Keberangkatan</label>
                    <input type="text" id="edit_testi_tahun" name="tahun_keberangkatan" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Kota Asal -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kota / Kabupaten Asal</label>
                    <input type="text" id="edit_testi_kota" name="kota_asal" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rating Bintang</label>
                    <select id="edit_testi_rating" name="rating" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                        <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3">⭐⭐⭐ (3 Bintang)</option>
                    </select>
                </div>

                <!-- Urutan & Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Urutan Tampil (#)</label>
                    <input type="number" id="edit_testi_order" name="order_num" min="1" 
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Publikasi</label>
                    <select id="edit_testi_status" name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none">
                        <option value="aktif">Aktif (Tampil di Landing)</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <!-- Isi Testimoni -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Isi Ulasan / Testimoni Jamaah <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="edit_testi_isi" name="testimoni" rows="3" required 
                              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#5e0078] focus:outline-none"></textarea>
                </div>

            </div>
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalEditTestimonial').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#5e0078] hover:bg-[#4c0062] text-white rounded-lg shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
