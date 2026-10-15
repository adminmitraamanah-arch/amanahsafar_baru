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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Komisi</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data pembagian dan snapshot komisi untuk seluruh tingkatan mitra.</p>
                    </div>
                    <button type="button" onclick="openCreateCommissionModal()" 
                            class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Tambah Komisi</span>
                    </button>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 bg-white">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Daftar Rekap Komisi</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Catatan nominal hak komisi mitra dari transaksi paket jamaah.</p>
                        </div>
                        <span class="text-xs text-slate-500 font-medium bg-slate-50 px-3 py-1 rounded-xl border border-slate-200 shrink-0">
                            Total: <strong class="text-slate-900"><?= count($commissions ?? []) ?></strong> Catatan
                        </span>
                    </div>

                    <!-- Toolbar (Search, Filter, Sort, Per-Page) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="commissionsSearchInput" placeholder="Cari No Order, Nama Mitra, Nominal..." 
                                   class="w-full pl-9 pr-12 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <!-- Sort By -->
                            <select id="commissionsSortSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="created_desc">Urutkan: Tanggal (Terbaru)</option>
                                <option value="created_asc">Urutkan: Tanggal (Terlama)</option>
                                <option value="nominal_desc">Urutkan: Nominal (Tertinggi)</option>
                                <option value="nominal_asc">Urutkan: Nominal (Terendah)</option>
                                <option value="user_asc">Urutkan: Penerima (A - Z)</option>
                                <option value="user_desc">Urutkan: Penerima (Z - A)</option>
                                <option value="order_desc">Urutkan: Order ID (Terbesar)</option>
                                <option value="order_asc">Urutkan: Order ID (Terkecil)</option>
                                <option value="status_asc">Urutkan: Status (A - Z)</option>
                                <option value="status_desc">Urutkan: Status (Z - A)</option>
                            </select>

                            <!-- Filter Status -->
                            <select id="commissionsStatusFilter" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status</option>
                                <option value="pending">Status: Pending</option>
                                <option value="disetujui">Status: Disetujui</option>
                                <option value="dibayar">Status: Dibayar</option>
                                <option value="dibatalkan">Status: Dibatalkan</option>
                            </select>

                            <!-- Per Page Size -->
                            <select id="commissionsPageSizeSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="10">10 / halaman</option>
                                <option value="25">25 / halaman</option>
                                <option value="50">50 / halaman</option>
                                <option value="100">100 / halaman</option>
                            </select>

                            <!-- Reset Button -->
                            <button type="button" id="resetCommissionsFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition text-xs" title="Reset Filter">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="commissionsTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5" data-sort-asc="order_asc" data-sort-desc="order_desc">Order / Transaksi</th>
                                    <th class="px-5 py-3.5" data-sort-asc="user_asc" data-sort-desc="user_desc">Penerima Komisi</th>
                                    <th class="px-5 py-3.5" data-sort-asc="nominal_asc" data-sort-desc="nominal_desc">Nominal Komisi</th>
                                    <th class="px-5 py-3.5" data-sort-asc="status_asc" data-sort-desc="status_desc">Status</th>
                                    <th class="px-5 py-3.5" data-sort-asc="created_asc" data-sort-desc="created_desc">Tanggal</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($commissions)): ?>
                                    <tr id="emptyCommissionRow">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Belum ada catatan komisi tersedia.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr id="emptyCommissionRow" style="display: none;">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Tidak ada catatan komisi yang cocok dengan pencarian atau filter.
                                        </td>
                                    </tr>
                                    <?php foreach ($commissions as $comm): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors commission-table-row"
                                            data-order="<?= esc($comm['order_id']) ?>"
                                            data-user="<?= strtolower(esc($comm['user_nama'] ?? 'user ' . $comm['user_id'])) ?>"
                                            data-nominal="<?= (int)$comm['nominal_snapshot'] ?>"
                                            data-status="<?= esc($comm['status']) ?>"
                                            data-created="<?= strtotime($comm['created_at']) ?>"
                                            data-search="<?= strtolower(esc(($comm['no_order'] ?? '') . ' ' . $comm['order_id'] . ' ' . ($comm['user_nama'] ?? '') . ' ' . ($comm['user_email'] ?? '') . ' ' . $comm['status'] . ' ' . $comm['nominal_snapshot'])) ?>">
                                            
                                            <!-- Order Info -->
                                            <td class="px-5 py-4">
                                                <div class="font-mono font-bold text-slate-900 text-xs">
                                                    <?= !empty($comm['no_order']) ? esc($comm['no_order']) : '#' . esc($comm['order_id']) ?>
                                                </div>
                                                <?php if (!empty($comm['order_total'])): ?>
                                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                                        Total: Rp <?= number_format($comm['order_total'], 0, ',', '.') ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <!-- User Info -->
                                            <td class="px-5 py-4">
                                                <div class="font-semibold text-slate-900 text-xs">
                                                    <?= esc($comm['user_nama'] ?? 'User #' . $comm['user_id']) ?>
                                                </div>
                                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                                                    <span><?= esc($comm['user_email'] ?? '-') ?></span>
                                                    <?php if (!empty($comm['user_role'])): ?>
                                                        <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600 uppercase">
                                                            <?= str_replace('_', ' ', $comm['user_role']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <!-- Nominal -->
                                            <td class="px-5 py-4 font-extrabold text-[#5e0078] text-sm">
                                                Rp <?= number_format($comm['nominal_snapshot'], 0, ',', '.') ?>
                                            </td>

                                            <!-- Status -->
                                            <td class="px-5 py-4">
                                                <?php 
                                                    $statusBadge = match($comm['status']) {
                                                        'pending' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
                                                        'disetujui' => ['class' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Disetujui'],
                                                        'dibayar' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Dibayar'],
                                                        'dibatalkan' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Dibatalkan'],
                                                        default => ['class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($comm['status'])]
                                                    };
                                                ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $statusBadge['class'] ?> border">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $statusBadge['dot'] ?>"></span>
                                                    <?= $statusBadge['label'] ?>
                                                </span>
                                            </td>

                                            <!-- Date -->
                                            <td class="px-5 py-4 text-xs text-slate-500">
                                                <div><?= date('d M Y, H:i', strtotime($comm['created_at'])) ?></div>
                                                <?php if (!empty($comm['paid_at'])): ?>
                                                    <div class="text-[10px] text-emerald-600 font-medium mt-0.5">
                                                        Dibayar: <?= date('d M Y', strtotime($comm['paid_at'])) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Actions (Icon Only) -->
                                            <td class="px-5 py-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                                    <!-- Edit Button -->
                                                    <button type="button" 
                                                            onclick='openEditCommissionModal(<?= json_encode($comm, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' 
                                                            class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 hover:text-[#4c0062] border border-purple-200 transition shadow-xs" 
                                                            title="Edit Komisi" 
                                                            aria-label="Edit Komisi">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <form method="POST" action="<?= base_url('/superadmin/commissions/' . $comm['id'] . '/delete') ?>" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan komisi ini?')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" 
                                                                class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-800 border border-rose-200 transition shadow-xs" 
                                                                title="Hapus Komisi" 
                                                                aria-label="Hapus Komisi">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Pagination Footer -->
                    <div class="px-5 py-3.5 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                        <span id="commissionCountSummary">Menampilkan <?= count($commissions ?? []) ?> komisi</span>
                        <div class="flex items-center gap-1 select-none" id="commissionPaginationControls">
                            <button type="button" id="prevCommissionBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </button>
                            <div id="commissionPaginationNumbers" class="flex items-center gap-1">
                                <!-- Generated by JS -->
                            </div>
                            <button type="button" id="nextCommissionBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                Next <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Tambah Komisi -->
<div id="createCommissionModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-lg w-full border border-slate-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Tambah Komisi Baru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Catat komisi untuk mitra dari transaksi paket.</p>
            </div>
            <button type="button" onclick="closeCreateCommissionModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="<?= base_url('/superadmin/commissions/store') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pilih Transaksi / Pesanan <span class="text-rose-500">*</span></label>
                <select name="order_id" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Pilih Pesanan Terkait --</option>
                    <?php foreach ($orders ?? [] as $ord): ?>
                        <option value="<?= $ord['id'] ?>">
                            #<?= $ord['id'] ?> - <?= esc($ord['no_order']) ?> (Total: Rp <?= number_format($ord['total'], 0, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Penerima Komisi (Mitra) <span class="text-rose-500">*</span></label>
                <select name="user_id" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Pilih Mitra Penerima --</option>
                    <?php foreach ($users ?? [] as $usr): ?>
                        <option value="<?= $usr['id'] ?>">
                            <?= esc($usr['nama_lengkap']) ?> (<?= ucfirst(str_replace('_', ' ', $usr['role'])) ?> - <?= esc($usr['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nominal Komisi (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="nominal_snapshot" min="0" step="1000" required placeholder="Contoh: 1500000"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Komisi</label>
                    <select name="status" id="createCommissionStatus" onchange="togglePaidAtField('create')"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="dibayar">Dibayar</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div id="createPaidAtContainer" class="hidden">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Dibayar</label>
                    <input type="datetime-local" name="paid_at" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateCommissionModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-check mr-1.5"></i> Simpan Komisi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Komisi -->
<div id="editCommissionModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-lg w-full border border-slate-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Edit Data Komisi</h2>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian atau status pembayaran komisi.</p>
            </div>
            <button type="button" onclick="closeEditCommissionModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editCommissionForm" method="POST" action="" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pilih Transaksi / Pesanan <span class="text-rose-500">*</span></label>
                <select name="order_id" id="editCommissionOrderId" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Pilih Pesanan Terkait --</option>
                    <?php foreach ($orders ?? [] as $ord): ?>
                        <option value="<?= $ord['id'] ?>">
                            #<?= $ord['id'] ?> - <?= esc($ord['no_order']) ?> (Total: Rp <?= number_format($ord['total'], 0, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Penerima Komisi (Mitra) <span class="text-rose-500">*</span></label>
                <select name="user_id" id="editCommissionUserId" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Pilih Mitra Penerima --</option>
                    <?php foreach ($users ?? [] as $usr): ?>
                        <option value="<?= $usr['id'] ?>">
                            <?= esc($usr['nama_lengkap']) ?> (<?= ucfirst(str_replace('_', ' ', $usr['role'])) ?> - <?= esc($usr['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nominal Komisi (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="nominal_snapshot" id="editCommissionNominal" min="0" step="1000" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Komisi</label>
                    <select name="status" id="editCommissionStatus" onchange="togglePaidAtField('edit')"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="dibayar">Dibayar</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div id="editPaidAtContainer">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Dibayar</label>
                    <input type="datetime-local" name="paid_at" id="editCommissionPaidAt"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditCommissionModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-check mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePaidAtField(type) {
    const statusSelect = document.getElementById(type === 'create' ? 'createCommissionStatus' : 'editCommissionStatus');
    const container = document.getElementById(type === 'create' ? 'createPaidAtContainer' : 'editPaidAtContainer');
    if (statusSelect.value === 'dibayar') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

function openCreateCommissionModal() {
    togglePaidAtField('create');
    document.getElementById('createCommissionModal').classList.remove('hidden');
}

function closeCreateCommissionModal() {
    document.getElementById('createCommissionModal').classList.add('hidden');
}

function openEditCommissionModal(comm) {
    const form = document.getElementById('editCommissionForm');
    form.action = '<?= base_url('/superadmin/commissions/') ?>/' + comm.id + '/update';
    
    document.getElementById('editCommissionOrderId').value = comm.order_id || '';
    document.getElementById('editCommissionUserId').value = comm.user_id || '';
    document.getElementById('editCommissionNominal').value = parseInt(comm.nominal_snapshot, 10) || 0;
    document.getElementById('editCommissionStatus').value = comm.status || 'pending';
    
    if (comm.paid_at) {
        const dt = new Date(comm.paid_at);
        const yyyy = dt.getFullYear();
        const mm = String(dt.getMonth() + 1).padStart(2, '0');
        const dd = String(dt.getDate()).padStart(2, '0');
        const hh = String(dt.getHours()).padStart(2, '0');
        const min = String(dt.getMinutes()).padStart(2, '0');
        document.getElementById('editCommissionPaidAt').value = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
    } else {
        document.getElementById('editCommissionPaidAt').value = '';
    }
    
    togglePaidAtField('edit');
    document.getElementById('editCommissionModal').classList.remove('hidden');
}

function closeEditCommissionModal() {
    document.getElementById('editCommissionModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    AmanahTable.init({
        containerId: 'commissionsTable',
        rowSelector: 'tbody tr.commission-table-row',
        entityName: 'komisi',
        defaultPageSize: 10,
        searchInputId: 'commissionsSearchInput',
        sortSelectId: 'commissionsSortSelect',
        pageSizeSelectId: 'commissionsPageSizeSelect',
        summaryId: 'commissionCountSummary',
        prevBtnId: 'prevCommissionBtn',
        nextBtnId: 'nextCommissionBtn',
        numbersId: 'commissionPaginationNumbers',
        emptyStateId: 'emptyCommissionRow',
        resetBtnId: 'resetCommissionsFiltersBtn',
        defaultSort: 'created_desc',
        filters: [
            { selectId: 'commissionsStatusFilter', key: 'status', defaultValue: 'all' }
        ],
        sortDefinitions: {
            created_desc: (a, b) => (parseInt(b.getAttribute('data-created'), 10) || 0) - (parseInt(a.getAttribute('data-created'), 10) || 0),
            created_asc: (a, b) => (parseInt(a.getAttribute('data-created'), 10) || 0) - (parseInt(b.getAttribute('data-created'), 10) || 0),
            nominal_desc: (a, b) => (parseInt(b.getAttribute('data-nominal'), 10) || 0) - (parseInt(a.getAttribute('data-nominal'), 10) || 0),
            nominal_asc: (a, b) => (parseInt(a.getAttribute('data-nominal'), 10) || 0) - (parseInt(b.getAttribute('data-nominal'), 10) || 0),
            user_asc: (a, b) => (a.getAttribute('data-user') || '').localeCompare(b.getAttribute('data-user') || '', 'id', { numeric: true, sensitivity: 'base' }),
            user_desc: (a, b) => (b.getAttribute('data-user') || '').localeCompare(a.getAttribute('data-user') || '', 'id', { numeric: true, sensitivity: 'base' }),
            order_desc: (a, b) => (parseInt(b.getAttribute('data-order'), 10) || 0) - (parseInt(a.getAttribute('data-order'), 10) || 0),
            order_asc: (a, b) => (parseInt(a.getAttribute('data-order'), 10) || 0) - (parseInt(b.getAttribute('data-order'), 10) || 0),
            status_asc: (a, b) => (a.getAttribute('data-status') || '').localeCompare(b.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }),
            status_desc: (a, b) => (b.getAttribute('data-status') || '').localeCompare(a.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' })
        }
    });
});
</script>
<?= $this->endSection() ?>
