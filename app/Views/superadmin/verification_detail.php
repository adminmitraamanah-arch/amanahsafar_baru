<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Breadcrumb & Top Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <a href="<?= base_url('/superadmin/verifications') ?>" 
                           class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#5e0078] transition">
                            <i class="fas fa-arrow-left text-[11px]"></i>
                            <span>Kembali ke Verifikasi Pengguna</span>
                        </a>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                            <span>Detail Verifikasi Pengguna</span>
                            <?php 
                                $statusBadge = match($verification['status']) {
                                    'terverifikasi' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Terverifikasi'],
                                    'ditolak' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                                    'menunggu_review' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Menunggu Review'],
                                    default => ['class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($verification['status'])]
                                };
                            ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border <?= $statusBadge['class'] ?>">
                                <span class="w-2 h-2 rounded-full <?= $statusBadge['dot'] ?>"></span>
                                <?= $statusBadge['label'] ?>
                            </span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500">Pemeriksaan dokumen identitas KTP, perjanjian kerja sama (PKS), dan data rekening bank mitra.</p>
                    </div>

                    <!-- Quick Actions Header -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="<?= base_url('/superadmin/verifications/' . $verification['id'] . '/pks') ?>" 
                           target="_blank"
                           class="btn-interactive inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/90 text-xs font-semibold shadow-xs transition">
                            <i class="fas fa-print text-slate-400"></i>
                            <span>Cetak PKS</span>
                        </a>

                        <?php if ($verification['status'] !== 'terverifikasi'): ?>
                            <button type="button" onclick="openApproveModal()"
                                    class="btn-interactive inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                                <i class="fas fa-check text-xs"></i>
                                <span>Setujui</span>
                            </button>
                        <?php endif; ?>

                        <?php if ($verification['status'] !== 'ditolak'): ?>
                            <button type="button" onclick="openRejectModal()"
                                    class="btn-interactive inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition">
                                <i class="fas fa-xmark text-xs"></i>
                                <span>Tolak</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
                        <i class="fas fa-circle-check text-emerald-500 text-base shrink-0"></i>
                        <span><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center gap-3">
                        <i class="fas fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                        <span><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($fraudAlerts)): ?>
                    <div class="space-y-3">
                        <?php foreach ($fraudAlerts as $fa): ?>
                            <div class="p-4 rounded-2xl bg-rose-50 border-2 border-rose-300 text-rose-900 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
                                <i class="fas fa-triangle-exclamation text-rose-600 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="font-bold text-rose-800 uppercase tracking-wider block mb-0.5"><?= esc($fa['title']) ?></strong>
                                    <p class="leading-relaxed text-rose-700"><?= esc($fa['message']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Grid Layout: 2 Kolom -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- Kolom Kiri: Dokumen KTP & PKS (7 dari 12) -->
                    <div class="lg:col-span-7 space-y-6">

                        <!-- Card Dokumen KTP -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-900">Dokumen KTP (Kartu Tanda Penduduk)</h2>
                                        <p class="text-[11px] text-slate-400">Verifikasi kartu identitas resmi milik pengguna</p>
                                    </div>
                                </div>
                                <?php if ($ktpExists): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[9px]"></i> Dokumen Tersedia
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 text-[10px] font-bold inline-flex items-center gap-1">
                                        <i class="fas fa-minus-circle text-[9px]"></i> Belum Tersedia
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="p-5">
                                <?php if ($ktpExists): ?>
                                    <div class="space-y-3">
                                        <!-- Container Gambar Preview -->
                                        <div class="relative group bg-slate-950 rounded-xl overflow-hidden border border-slate-200 flex items-center justify-center max-h-[380px] shadow-inner">
                                            <img src="<?= esc($ktpUrl) ?>" 
                                                 alt="KTP <?= esc($user['nama_lengkap']) ?>" 
                                                 class="max-h-[380px] w-auto max-w-full object-contain cursor-pointer transition-transform duration-300 group-hover:scale-[1.01]"
                                                 onclick="openImageLightbox('<?= esc($ktpUrl) ?>', 'KTP - <?= esc($user['nama_lengkap']) ?>')">
                                            
                                            <!-- Overlay Quick Hover -->
                                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 pointer-events-none">
                                                <span class="px-3 py-1.5 rounded-lg bg-white/90 text-slate-900 text-xs font-semibold backdrop-blur-sm shadow">
                                                    <i class="fas fa-magnifying-glass-plus mr-1"></i> Klik untuk Perbesar
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Tombol Aksi Dokumen KTP -->
                                        <div class="flex items-center justify-between gap-2 pt-1 flex-wrap">
                                            <span class="text-[11px] text-slate-400 font-mono">
                                                File: <?= esc(basename($verification['ktp_path'])) ?>
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" 
                                                        onclick="openImageLightbox('<?= esc($ktpUrl) ?>', 'KTP - <?= esc($user['nama_lengkap']) ?>')"
                                                        class="btn-interactive px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 text-[#5e0078] text-xs font-bold inline-flex items-center gap-1.5 border border-purple-200 transition">
                                                    <i class="fas fa-search-plus text-[11px]"></i>
                                                    <span>Perbesar</span>
                                                </button>
                                                <a href="<?= esc($ktpUrl) ?>" target="_blank"
                                                   class="btn-interactive px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold inline-flex items-center gap-1.5 transition">
                                                    <i class="fas fa-arrow-up-right-from-square text-[11px]"></i>
                                                    <span>Buka Tab Baru</span>
                                                </a>
                                                <a href="<?= esc($ktpUrl) ?>?download=1"
                                                   class="btn-interactive px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold inline-flex items-center gap-1.5 transition">
                                                    <i class="fas fa-download text-[11px]"></i>
                                                    <span>Unduh</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="py-12 text-center text-slate-400">
                                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-300 text-2xl mb-3">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <p class="text-xs sm:text-sm font-semibold text-slate-600">Dokumen KTP Belum Diunggah</p>
                                        <p class="text-[11px] text-slate-400 mt-1 max-w-sm mx-auto">Pengguna belum mengunggah foto kartu identitas resmi pada proses verifikasi akun.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Dokumen PKS (Perjanjian Kerja Sama Kemitraan) -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-900">Perjanjian Kerja Sama Digital (PKS)</h2>
                                        <p class="text-[11px] text-slate-400">Akta kesepakatan kemitraan digital resmi Amanah Safar</p>
                                    </div>
                                </div>
                                <?php if ($pksAccepted): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold inline-flex items-center gap-1">
                                        <i class="fas fa-signature text-[9px]"></i> Disetujui Digital
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold inline-flex items-center gap-1">
                                        <i class="fas fa-clock text-[9px]"></i> Menunggu Persetujuan
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="p-5 space-y-4">
                                <!-- Status Card PKS -->
                                <div class="p-4 rounded-xl <?= $pksAccepted ? 'bg-purple-50/50 border border-purple-200/70' : 'bg-amber-50/60 border border-amber-200/70' ?>">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="space-y-1">
                                            <div class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                                <span>Nomor Dokumen:</span>
                                                <code class="px-2 py-0.5 rounded bg-white text-[#5e0078] border border-purple-200 font-mono text-[11px] font-bold"><?= esc($pksNumber) ?></code>
                                            </div>
                                            <div class="text-[11px] text-slate-600">
                                                <?php if ($pksAccepted): ?>
                                                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                                        <i class="fas fa-circle-check text-xs"></i> 
                                                        Disetujui pada: <?= date('d F Y, H:i:s \W\I\B', strtotime($user['agreement_accepted_at'])) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-amber-700 font-medium">Pengguna belum menyelesaikan persetujuan elektronik Perjanjian Pengguna.</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <a href="<?= base_url('/superadmin/verifications/' . $verification['id'] . '/pks') ?>" 
                                               target="_blank"
                                               class="btn-interactive px-3 py-1.5 rounded-lg bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition">
                                                <i class="fas fa-file-pdf text-[11px]"></i>
                                                <span>Lihat &amp; Cetak PKS Resmi</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ringkasan Para Pihak -->
                                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Para Pihak yang Terikat:</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700">
                                        <div class="border-l-2 border-[#5e0078] pl-2.5">
                                            <span class="font-bold text-slate-900 block text-[11px]">PIHAK PERTAMA</span>
                                            <span class="text-xs">PT Amanah Tangguh Mandiri</span>
                                            <span class="block text-[10px] text-slate-400">Penyedia Sistem Amanah Safar</span>
                                        </div>
                                        <div class="border-l-2 border-amber-500 pl-2.5">
                                            <span class="font-bold text-slate-900 block text-[11px]">PIHAK KEDUA (MITRA)</span>
                                            <span class="text-xs font-bold text-slate-900"><?= esc($user['nama_lengkap']) ?></span>
                                            <span class="block text-[10px] text-slate-400">Domisili: <?= esc($user['domisili'] ?: 'Indonesia') ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Box Preview Isi Perjanjian (Pasal 1-8) -->
                                <div class="bg-slate-50/70 border border-slate-200 rounded-xl p-4 max-h-56 overflow-y-auto text-xs leading-relaxed text-slate-700 space-y-3 shadow-inner">
                                    <div class="text-center pb-2 border-b border-slate-200">
                                        <strong class="text-xs text-slate-900 block font-black uppercase">RINGKASAN KLAUSUL PERJANJIAN KERJA SAMA (PKS)</strong>
                                        <span class="text-[10px] text-slate-500">Nomor: <?= esc($pksNumber) ?></span>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 1: KETENTUAN UMUM DAN RUANG LINGKUP</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Perjanjian ini mengatur kerja sama kemitraan syiar, pemasaran, dan pendaftaran jamaah Umrah dan Wisata Halal melalui sistem portal digital Amanah Safar.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 2: HAK DAN KEWAJIBAN PIHAK PERTAMA</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Pihak Pertama berkewajiban menyediakan fasilitas sistem booking, bimbingan berkala, materi promosi, kepastian kuota paket ibadah, serta membayarkan hak ujroh bagi hasil sesuai skema komisi yang berlaku.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 3: HAK DAN KEWAJIBAN PIHAK KEDUA (MITRA)</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Pihak Kedua berhak atas ujroh transaksi dan materi pemasaran resmi, serta berkewajiban menyampaikan informasi paket secara jujur, menjaga nama baik Amanah Safar, dan mematuhi kode etik kemitraan.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 4: PENETAPAN HARGA DAN PENCAIRAN UJROH</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Harga paket ibadah merujuk pada katalog resmi perusahaan. Ujroh kemitraan dibayarkan melalui transfer ke rekening bank terverifikasi milik Mitra setelah transaksi terkonfirmasi sah.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 5: JANGKA WAKTU PERJANJIAN</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Perjanjian ini berlaku efektif terhitung sejak tanggal persetujuan elektronik ini diberikan hingga 1 (satu) tahun ke depan dan dapat diperpanjang secara otomatis atas evaluasi kinerja bersama.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 6: KERAHASIAAN DAN PERLINDUNGAN DATA</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Pihak Kedua wajib menjaga kerahasiaan data pribadi calon jamaah dan informasi operasional internal perusahaan dari pihak ketiga yang tidak berwenang.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 7: SYARAT DAN KETENTUAN ANGGOTA JARINGAN</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Setiap mitra harus terdaftar resmi, mematuhi harga paket resmi, tidak memungut biaya di luar ketentuan, dan akun dapat dinonaktifkan apabila terbukti melakukan pelanggaran hukum atau penipuan.</p>
                                    </div>

                                    <div>
                                        <strong class="text-slate-900 block font-bold text-[11px]">PASAL 8: PENYELESAIAN SENGKETA DAN PENUTUP</strong>
                                        <p class="text-[11px] mt-0.5 text-slate-600">Segala perselisihan yang timbul akan diselesaikan secara musyawarah mufakat. Perjanjian ini dinyatakan sah dan mengikat secara hukum sejak disetujui secara digital.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Kolom Kanan: Identitas Mitra & Informasi Rekening (5 dari 12) -->
                    <div class="lg:col-span-5 space-y-6">

                        <!-- Card Identitas Pengguna -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-900">Identitas Pribadi</h2>
                                        <p class="text-[11px] text-slate-400">Informasi akun dan kontak terdaftar pengguna</p>
                                    </div>
                                </div>
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                    ID #<?= esc($user['id']) ?>
                                </span>
                            </div>

                            <div class="p-5 space-y-4 text-xs">
                                <!-- Profile Hero -->
                                <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#5e0078] to-[#8f20ad] text-white flex items-center justify-center font-black text-lg shadow-sm">
                                        <?= strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-extrabold text-slate-900 text-sm truncate"><?= esc($user['nama_lengkap']) ?></h3>
                                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                            <span class="px-2 py-0.5 rounded-full bg-purple-100 text-[#5e0078] text-[10px] font-bold uppercase tracking-wider">
                                                <?= esc($user['role']) ?>
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full <?= $user['status'] === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' ?> text-[10px] font-bold">
                                                Akun <?= ucfirst(esc($user['status'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Field Rows -->
                                <div class="space-y-3">
                                    <!-- Kode Mitra / Voucher -->
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                        <span class="text-slate-500 font-medium">Kode Mitra:</span>
                                        <div class="flex items-center gap-1.5 font-mono font-bold text-slate-900">
                                            <span><?= esc($voucher['kode'] ?? ('MITRA-' . $user['id'])) ?></span>
                                            <?php if (!empty($voucher['kode'])): ?>
                                                <button type="button" 
                                                        onclick="copyToClipboard('<?= esc($voucher['kode']) ?>', 'Kode Mitra berhasil disalin')"
                                                        class="p-1 rounded text-slate-400 hover:text-[#5e0078] transition" 
                                                        title="Salin Kode">
                                                    <i class="fas fa-copy text-xs"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Nomor WhatsApp -->
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                        <span class="text-slate-500 font-medium">Nomor WhatsApp:</span>
                                        <div class="flex items-center gap-1.5 font-mono">
                                            <span class="font-semibold text-slate-900"><?= esc($user['no_wa'] ?: '-') ?></span>
                                            <?php if (!empty($user['no_wa'])): ?>
                                                <?php 
                                                    $cleanWa = preg_replace('/[^0-9]/', '', $user['no_wa']);
                                                    if (str_starts_with($cleanWa, '0')) {
                                                        $cleanWa = '62' . substr($cleanWa, 1);
                                                    }
                                                ?>
                                                <a href="https://wa.me/<?= $cleanWa ?>" target="_blank"
                                                   class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-[11px] font-bold inline-flex items-center gap-1 transition"
                                                   title="Chat via WhatsApp">
                                                    <i class="fab fa-whatsapp"></i> Chat
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- NIK KTP -->
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                        <span class="text-slate-500 font-medium">NIK KTP (16 Digit):</span>
                                        <span class="font-mono font-bold text-slate-900 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                            <?= esc($verification['nik'] ?? 'Belum diisi') ?>
                                        </span>
                                    </div>

                                    <!-- Email -->
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                        <span class="text-slate-500 font-medium">Email:</span>
                                        <span class="font-semibold text-slate-900 truncate max-w-[200px]" title="<?= esc($user['email']) ?>">
                                            <?= esc($user['email'] ?: '-') ?>
                                        </span>
                                    </div>

                                    <!-- Domisili -->
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                        <span class="text-slate-500 font-medium">Domisili / Wilayah:</span>
                                        <span class="font-semibold text-slate-900">
                                            <?= esc($user['domisili'] ?: '-') ?>
                                        </span>
                                    </div>

                                    <!-- Pembina Langsung -->
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                        <span class="text-slate-500 font-medium">Pembina Langsung:</span>
                                        <span class="font-semibold text-slate-900 text-right">
                                            <?php if (!empty($pembina)): ?>
                                                <?= esc($pembina['nama_lengkap']) ?>
                                                <span class="block text-[10px] font-normal text-slate-400">
                                                    (<?= ucfirst(esc($pembina['role'])) ?><?= !empty($pembina['domisili']) ? ' - ' . esc($pembina['domisili']) : '' ?>)
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400">-</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>

                                    <!-- Tanggal Pendaftaran -->
                                    <div class="flex items-center justify-between py-1.5">
                                        <span class="text-slate-500 font-medium">Terdaftar Sejak:</span>
                                        <span class="text-slate-700">
                                            <?= date('d M Y, H:i', strtotime($user['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Rekening Bank -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-sm">
                                        <i class="fas fa-building-columns"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-900">Informasi Rekening Bank</h2>
                                        <p class="text-[11px] text-slate-400">Rekening tujuan pencairan ujroh / komisi</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 space-y-4 text-xs">
                                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl p-4 text-white shadow-sm space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-purple-300">
                                            <?= esc($verification['nama_bank'] ?: 'Bank Mitra') ?>
                                        </span>
                                        <i class="fas fa-credit-card text-slate-400 text-lg"></i>
                                    </div>

                                    <div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Nomor Rekening</div>
                                        <div class="flex items-center justify-between gap-2 mt-0.5">
                                            <span class="font-mono text-lg font-bold tracking-widest text-white">
                                                <?= esc($verification['nomor_rekening'] ?: '-') ?>
                                            </span>
                                            <?php if (!empty($verification['nomor_rekening'])): ?>
                                                <button type="button" 
                                                        onclick="copyToClipboard('<?= esc($verification['nomor_rekening']) ?>', 'Nomor rekening berhasil disalin')"
                                                        class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition text-xs" 
                                                        title="Salin Nomor Rekening">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-slate-700/60 flex items-center justify-between text-[11px]">
                                        <div>
                                            <div class="text-[9px] text-slate-400 uppercase tracking-wider font-semibold">Atas Nama (Pemilik)</div>
                                            <div class="font-bold text-slate-100 uppercase mt-0.5">
                                                <?= esc($verification['nama_pemilik_rekening'] ?: ($user['nama_lengkap'] ?: '-')) ?>
                                            </div>
                                        </div>
                                        <?php 
                                            $pemilikNama = strtolower(trim((string)$verification['nama_pemilik_rekening']));
                                            $userNama = strtolower(trim((string)$user['nama_lengkap']));
                                            $isMatch = (!empty($pemilikNama) && !empty($userNama)) && (str_contains($userNama, $pemilikNama) || str_contains($pemilikNama, $userNama));
                                        ?>
                                        <?php if ($isMatch): ?>
                                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[10px] font-semibold flex items-center gap-1">
                                                <i class="fas fa-check text-[9px]"></i> Sesuai Nama
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="space-y-2 pt-1">
                                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                                        <span class="text-slate-500">Tanggal Pengajuan:</span>
                                        <span class="font-semibold text-slate-800">
                                            <?= date('d M Y, H:i:s', strtotime($verification['created_at'])) ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                                        <span class="text-slate-500">Terakhir Diperbarui:</span>
                                        <span class="font-semibold text-slate-800">
                                            <?= date('d M Y, H:i:s', strtotime($verification['updated_at'])) ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($verification['reviewer_nama'])): ?>
                                        <div class="flex items-center justify-between py-1 border-b border-slate-50">
                                            <span class="text-slate-500">Direview Oleh:</span>
                                            <span class="font-semibold text-slate-800">
                                                <?= esc($verification['reviewer_nama']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($verification['catatan'])): ?>
                                        <div class="pt-2">
                                            <span class="text-slate-500 block mb-1">Catatan Verifikasi:</span>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs">
                                                <?= nl2br(esc($verification['catatan'])) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Card Keputusan Verifikasi -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-3">
                            <h3 class="text-sm font-bold text-slate-900">Aksi Otorisasi Super Admin</h3>
                            <p class="text-xs text-slate-500">Tentukan status verifikasi akun mitra berdasarkan kelengkapan identitas, rekening, dan kesepakatan PKS.</p>
                            
                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <button type="button" onclick="openApproveModal()"
                                        class="btn-interactive w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs inline-flex items-center justify-center gap-1.5 shadow-xs transition">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Setujui Akun</span>
                                </button>
                                <button type="button" onclick="openRejectModal()"
                                        class="btn-interactive w-full py-2.5 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs inline-flex items-center justify-center gap-1.5 shadow-xs transition">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Tolak Akun</span>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Image Lightbox / Fullscreen Zoom -->
<div id="imageLightboxModal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="relative max-w-5xl max-h-[92vh] w-full flex flex-col items-center">
        <!-- Lightbox Toolbar -->
        <div class="w-full flex items-center justify-between text-white pb-3 px-2">
            <span id="imageLightboxTitle" class="text-sm font-bold truncate">Preview Dokumen</span>
            <div class="flex items-center gap-2">
                <a id="imageLightboxDownloadBtn" href="#" download
                   class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition text-xs" title="Unduh">
                    <i class="fas fa-download"></i>
                </a>
                <a id="imageLightboxNewTabBtn" href="#" target="_blank"
                   class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition text-xs" title="Buka di Tab Baru">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                </a>
                <button type="button" onclick="closeImageLightbox()"
                        class="p-2 rounded-lg bg-white/10 hover:bg-rose-600 text-white transition text-xs" title="Tutup (Esc)">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Lightbox Image -->
        <div class="w-full flex items-center justify-center overflow-auto max-h-[82vh] bg-black/40 rounded-xl p-2">
            <img id="imageLightboxImg" src="" alt="Dokumen" class="max-h-[80vh] w-auto max-w-full object-contain rounded-lg shadow-2xl">
        </div>
    </div>
</div>

<!-- Modal Setujui Verifikasi -->
<div id="approveModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <form method="POST" action="<?= base_url('/superadmin/verifications/' . $verification['id'] . '/approve') ?>">
            <?= csrf_field() ?>
            <div class="p-6 text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto border border-emerald-100">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">Konfirmasi Persetujuan Verifikasi</h3>
                <p class="text-xs text-slate-500">
                    Apakah Anda yakin ingin menyetujui verifikasi untuk pengguna <strong><?= esc($user['nama_lengkap']) ?></strong>? Akun mitra akan langsung aktif dengan status <em>Terverifikasi</em>.
                </p>
                <div class="text-left pt-2">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional):</label>
                    <textarea name="catatan" rows="2" placeholder="Contoh: Dokumen KTP dan rekening terverifikasi valid."
                              class="w-full p-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-200 text-xs font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                    Ya, Setujui Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak Verifikasi -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <form method="POST" action="<?= base_url('/superadmin/verifications/' . $verification['id'] . '/reject') ?>">
            <?= csrf_field() ?>
            <div class="p-6 text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto border border-rose-100">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">Tolak Pengajuan Verifikasi</h3>
                <p class="text-xs text-slate-500">
                    Pengajuan verifikasi untuk <strong><?= esc($user['nama_lengkap']) ?></strong> akan ditolak. Harap masukkan alasan agar pengguna dapat memperbaiki data.
                </p>
                <div class="text-left pt-2">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="catatan" rows="3" required placeholder="Contoh: Foto KTP buram dan tidak terbaca. Harap unggah ulang."
                              class="w-full p-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-200 text-xs font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition">
                    Tolak Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function copyToClipboard(text, message) {
    if (!navigator.clipboard) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        showToastNotification(message || 'Teks berhasil disalin');
        return;
    }
    navigator.clipboard.writeText(text).then(function() {
        showToastNotification(message || 'Teks berhasil disalin');
    });
}

function showToastNotification(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-50 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-semibold shadow-lg flex items-center gap-2 border border-slate-700 animate-in fade-in slide-in-from-bottom-2 duration-200';
    toast.innerHTML = `<i class="fas fa-check-circle text-emerald-400"></i> <span>${msg}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => toast.remove(), 300);
    }, 2200);
}

function openImageLightbox(src, title) {
    const modal = document.getElementById('imageLightboxModal');
    const img = document.getElementById('imageLightboxImg');
    const titleEl = document.getElementById('imageLightboxTitle');
    const newTabBtn = document.getElementById('imageLightboxNewTabBtn');
    const downloadBtn = document.getElementById('imageLightboxDownloadBtn');

    img.src = src;
    titleEl.textContent = title || 'Preview Dokumen';
    newTabBtn.href = src;
    downloadBtn.href = src + (src.includes('?') ? '&download=1' : '?download=1');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeImageLightbox() {
    const modal = document.getElementById('imageLightboxModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function openApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function openRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageLightbox();
        closeApproveModal();
        closeRejectModal();
    }
});
</script>

<?= $this->endSection() ?>
