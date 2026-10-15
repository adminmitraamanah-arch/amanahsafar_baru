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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Voucher</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Buat kode voucher pendaftaran Mitra dan tentukan pembina langsungnya.</p>
                    </div>
                    <button type="button" onclick="openGenerateModal()" 
                            class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Generate Voucher Baru</span>
                    </button>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 bg-white">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Daftar Voucher Registrasi</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Voucher khusus untuk pendaftaran akun Mitra dengan relasi pembina hierarki.</p>
                        </div>
                        <span class="text-xs text-slate-500 font-medium bg-slate-50 px-3 py-1 rounded-xl border border-slate-200 shrink-0">
                            Total: <strong class="text-slate-900"><?= count($vouchers ?? []) ?></strong> Voucher
                        </span>
                    </div>

                    <!-- Toolbar (Search bar & Filter controls) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="vouchersSearchInput" placeholder="Cari kode voucher, pembina..." 
                                   class="w-full pl-9 pr-12 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <!-- Sort By -->
                            <select id="voucherSortSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="created_desc">Urutkan: Dibuat (Terbaru)</option>
                                <option value="created_asc">Urutkan: Dibuat (Terlama)</option>
                                <option value="kode_asc">Urutkan: Kode (A - Z)</option>
                                <option value="kode_desc">Urutkan: Kode (Z - A)</option>
                                <option value="pembina_asc">Urutkan: Pembina (A - Z)</option>
                                <option value="pembina_desc">Urutkan: Pembina (Z - A)</option>
                                <option value="status_asc">Urutkan: Status (A - Z)</option>
                                <option value="status_desc">Urutkan: Status (Z - A)</option>
                                <option value="expired_asc">Urutkan: Masa Berlaku (Terdekat)</option>
                                <option value="expired_desc">Urutkan: Masa Berlaku (Terjauh)</option>
                            </select>

                            <!-- Filter Role Tujuan -->
                            <select id="voucherRoleFilter" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Role Tujuan</option>
                                <option value="cabang">Role: Cabang</option>
                                <option value="manager_mitra">Role: Manager Mitra</option>
                                <option value="mitra">Role: Mitra</option>
                            </select>

                            <!-- Filter Status -->
                            <select id="voucherStatusFilter" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status</option>
                                <option value="tersedia">Status: Tersedia</option>
                                <option value="terpakai">Status: Terpakai</option>
                                <option value="dinonaktifkan">Status: Nonaktif</option>
                            </select>

                            <!-- Per Page Size -->
                            <select id="voucherPageSizeSelect" class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="10">10 / halaman</option>
                                <option value="25">25 / halaman</option>
                                <option value="50">50 / halaman</option>
                                <option value="100">100 / halaman</option>
                            </select>

                            <!-- Reset Button -->
                            <button type="button" id="resetVoucherFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition text-xs" title="Reset Filter">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Floating Bulk Action Bar (Konsisten di Seluruh Halaman Super Admin) -->
                    <div id="voucherBulkActionBar" 
                         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden bg-white text-slate-800 shadow-2xl rounded-2xl px-5 py-3 border border-slate-200/90 backdrop-blur-md flex items-center gap-4 transition-all duration-200"
                         style="background-color: rgba(255, 255, 255, 0.98); border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1);">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" style="background-color: #10b981;"></span>
                            <span class="text-xs font-semibold text-slate-600" style="color: #475569;">
                                <strong id="selectedVouchersCount" class="text-slate-900 font-extrabold text-sm" style="color: #0f172a;">0</strong> voucher dipilih
                            </span>
                        </div>
                        <div class="h-4 w-px bg-slate-200" style="background-color: #cbd5e1;"></div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="cancelVoucherSelectionBtn" 
                                    class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 font-bold transition text-xs border border-slate-200/80 cursor-pointer"
                                    style="background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">
                                Batal
                            </button>
                            <button type="button" id="bulkDeleteVouchersBtn" 
                                    class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold transition text-xs flex items-center gap-1.5 shadow-sm cursor-pointer"
                                    style="background-color: #e11d48; color: #ffffff;">
                                <i class="fas fa-trash-alt text-xs"></i>
                                <span>Hapus Terpilih</span>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Bulk Delete Form -->
                    <form id="voucherBulkDeleteForm" method="POST" action="<?= base_url('/superadmin/vouchers/bulk-delete') ?>" class="hidden">
                        <?= csrf_field() ?>
                        <div id="voucherBulkDeleteInputs"></div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="vouchersTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-3.5 w-12 text-center">
                                        <input type="checkbox" id="selectAllVouchers" class="w-4 h-4 rounded border-slate-300 text-[#5e0078] focus:ring-[#5e0078] cursor-pointer" title="Pilih Semua Voucher">
                                    </th>
                                    <th class="px-5 py-3.5" data-sort-asc="kode_asc" data-sort-desc="kode_desc">Kode Voucher</th>
                                    <th class="px-5 py-3.5">Role Tujuan</th>
                                    <th class="px-5 py-3.5" data-sort-asc="pembina_asc" data-sort-desc="pembina_desc">Pembina Langsung</th>
                                    <th class="px-5 py-3.5" data-sort-asc="status_asc" data-sort-desc="status_desc">Status</th>
                                    <th class="px-5 py-3.5" data-sort-asc="expired_asc" data-sort-desc="expired_desc">Masa Berlaku</th>
                                    <th class="px-5 py-3.5" data-sort-asc="created_asc" data-sort-desc="created_desc">Dibuat Pada</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($vouchers)): ?>
                                    <tr id="emptyVoucherRow">
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-ticket-alt text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada voucher yang dibuat. Klik tombol di atas untuk membuat voucher baru.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr id="emptyVoucherRow" style="display: none;">
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-search text-3xl mb-2 text-slate-300 block"></i>
                                            Tidak ada voucher yang cocok dengan filter atau kata kunci pencarian.
                                        </td>
                                    </tr>
                                    <?php foreach ($vouchers as $voucher): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors voucher-table-row"
                                            data-id="<?= esc($voucher['id']) ?>"
                                            data-kode="<?= strtolower(esc($voucher['kode'])) ?>"
                                            data-role="<?= esc($voucher['role_tujuan'] ?? 'mitra') ?>"
                                            data-pembina="<?= strtolower(esc($voucher['nama_pembina'] ?? '')) ?>"
                                            data-status="<?= esc($voucher['status']) ?>"
                                            data-expired="<?= !empty($voucher['expired_at']) ? strtotime($voucher['expired_at']) : 9999999999 ?>"
                                            data-created="<?= strtotime($voucher['created_at']) ?>"
                                            data-search="<?= strtolower(esc($voucher['kode'] . ' ' . ($voucher['nama_pembina'] ?? '') . ' ' . $voucher['status'] . ' ' . ($voucher['role_pembina'] ?? '') . ' ' . ($voucher['nama_pengguna'] ?? '') . ' ' . ($voucher['role_tujuan'] ?? ''))) ?>">
                                            <td class="px-4 py-4 text-center">
                                                <input type="checkbox" class="voucher-row-checkbox w-4 h-4 rounded border-slate-300 text-[#5e0078] focus:ring-[#5e0078] cursor-pointer" value="<?= esc($voucher['id']) ?>" data-kode="<?= esc($voucher['kode']) ?>">
                                            </td>
                                            <td class="px-5 py-4 font-mono font-bold text-slate-900 text-xs sm:text-sm">
                                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200/70 select-all"><?= esc($voucher['kode']) ?></span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php if (($voucher['role_tujuan'] ?? '') === 'cabang'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                                        <i class="fas fa-code-branch text-[9px]"></i> Cabang
                                                    </span>
                                                <?php elseif (($voucher['role_tujuan'] ?? '') === 'manager_mitra'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wider">
                                                        <i class="fas fa-user-tie text-[9px]"></i> Manager
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-[#5e0078] border border-purple-200 uppercase tracking-wider">
                                                        <i class="fas fa-user text-[9px]"></i> Mitra
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php if (!empty($voucher['pembina_id']) && !empty($voucher['nama_pembina'])): ?>
                                                    <span class="font-bold text-slate-900 text-xs block leading-tight"><?= esc($voucher['nama_pembina']) ?></span>
                                                    <?php if ($voucher['role_pembina'] === 'pusat'): ?>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 mt-1">
                                                            <i class="fas fa-building text-[9px]"></i> Kantor Pusat (Kode: PS-<?= esc($voucher['kode_pembina_user'] ?? '001') ?>)
                                                        </span>
                                                    <?php elseif ($voucher['role_pembina'] === 'cabang'): ?>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 mt-1">
                                                            <i class="fas fa-code-branch text-[9px]"></i> Cabang <?= esc($voucher['domisili_pembina'] ?? '') ?> (Kode: CB-<?= esc($voucher['kode_pembina_user'] ?? '001') ?>)
                                                        </span>
                                                    <?php elseif ($voucher['role_pembina'] === 'manager_mitra'): ?>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 mt-1">
                                                            <i class="fas fa-user-tie text-[9px]"></i> Manager Mitra (Kode: MG-<?= esc($voucher['kode_pembina_user'] ?? '001') ?>) &bull; Induk: <?= esc($voucher['nama_induk'] ?? 'Pusat/Cabang') ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-[10px] text-slate-500 font-medium"><?= ucfirst(str_replace('_', ' ', $voucher['role_pembina'])) ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold text-amber-700 bg-amber-50 border border-amber-200">
                                                        <i class="fas fa-exclamation-triangle text-[9px]"></i> Belum Terkait
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php 
                                                    $statusBadge = match($voucher['status']) {
                                                        'tersedia' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Tersedia'],
                                                        'terpakai' => ['class' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Terpakai' . (!empty($voucher['nama_pengguna']) ? ' (' . esc($voucher['nama_pengguna']) . ')' : '')],
                                                        'dinonaktifkan' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Nonaktif'],
                                                        default => ['class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($voucher['status'])]
                                                    };
                                                ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $statusBadge['class'] ?> border">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $statusBadge['dot'] ?>"></span>
                                                    <?= $statusBadge['label'] ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-500">
                                                <?php if (!empty($voucher['expired_at'])): ?>
                                                    <?php $isExpired = strtotime($voucher['expired_at']) < time(); ?>
                                                    <span class="<?= $isExpired ? 'text-rose-600 font-bold' : 'text-slate-600' ?>">
                                                        <?= date('d M Y', strtotime($voucher['expired_at'])) ?>
                                                        <?= $isExpired ? '(Expired)' : '' ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-slate-400">Permanen</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-500">
                                                <?= date('d M Y, H:i', strtotime($voucher['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                                    <!-- Tombol Salin Kode Voucher -->
                                                    <button type="button" 
                                                            onclick="copyVoucherCode('<?= esc($voucher['kode'], 'js') ?>', this)" 
                                                            class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 hover:text-[#4c0062] border border-purple-200 transition shadow-xs" 
                                                            title="Salin Kode Voucher" 
                                                            aria-label="Salin Kode Voucher">
                                                        <i class="fas fa-copy text-xs"></i>
                                                    </button>

                                                    <!-- Tombol Hapus Voucher Permanen -->
                                                    <form method="POST" action="<?= base_url('/superadmin/vouchers/delete/' . $voucher['id']) ?>" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher <?= esc($voucher['kode']) ?> secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-800 border border-rose-200 transition shadow-xs" title="Hapus Voucher" aria-label="Hapus Voucher">
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
                        <span id="voucherCountSummary">Menampilkan <?= count($vouchers ?? []) ?> voucher</span>
                        <div class="flex items-center gap-1 select-none" id="voucherPaginationControls">
                            <button type="button" id="prevVoucherBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </button>
                            <div id="voucherPaginationNumbers" class="flex items-center gap-1">
                                <!-- Generated by JS -->
                            </div>
                            <button type="button" id="nextVoucherBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                Next <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Generate Voucher Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-lg w-full border border-slate-100 my-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Generate Voucher Registrasi</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan role tujuan dan pembina langsung untuk akses pendaftaran.</p>
            </div>
            <button type="button" onclick="closeGenerateModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="<?= base_url('/superadmin/vouchers/generate') ?>" class="space-y-4" onsubmit="return validateGenerateForm()">
            <?= csrf_field() ?>
            
            <!-- Pilihan 1: Role Tujuan Registrasi -->
            <div>
                <label for="role_tujuan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">1. Role Tujuan Registrasi <span class="text-rose-500">*</span></label>
                <select id="role_tujuan" name="role_tujuan" required onchange="onRoleTujuanChange()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="mitra">Mitra</option>
                    <option value="manager_mitra">Manager Mitra</option>
                    <option value="cabang">Cabang</option>
                </select>
                <p id="roleTujuanHint" class="text-[11px] text-slate-400 mt-1">Voucher ini akan otomatis menentukan hak akses & role akun saat pendaftar registrasi.</p>
            </div>

            <!-- Jumlah Voucher -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Voucher <span class="text-rose-500">*</span></label>
                <input type="number" name="qty" min="1" max="100" value="1" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition">
                <p class="text-[11px] text-slate-400 mt-1">Maksimal 100 voucher per pembuatan.</p>
            </div>

            <!-- Pilihan 2: Jenis Pembina -->
            <div>
                <label for="jenis_pembina" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">2. Jenis Pembina Langsung <span class="text-rose-500">*</span></label>
                <select id="jenis_pembina" name="jenis_pembina" required onchange="onJenisPembinaChange()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Pilih Jenis Pembina --</option>
                    <option value="pusat">Kantor Pusat</option>
                    <option value="cabang">Cabang</option>
                    <option value="manager_mitra">Manager Mitra</option>
                </select>
                <p id="jenisPembinaHint" class="text-[11px] text-slate-400 mt-1">Pilih entitas pembina langsung.</p>
            </div>

            <!-- Pilihan 3: Akun Pembina Langsung -->
            <div>
                <label for="pembina_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">3. Akun Pembina Langsung <span class="text-rose-500">*</span></label>
                <select id="pembina_id" name="pembina_id" required disabled onchange="updateVoucherPreview()"
                        class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition disabled:opacity-60 disabled:cursor-not-allowed">
                    <option value="">-- Pilih Jenis Pembina Terlebih Dahulu --</option>
                </select>
                <p id="pembinaHint" class="text-[11px] text-slate-400 mt-1">Pilih jenis pembina di atas untuk melihat akun yang tersedia.</p>
            </div>

            <!-- Live Preview Format Voucher -->
            <div id="voucherPreviewBox" class="hidden p-3.5 bg-purple-50/50 rounded-xl border border-purple-200/80 transition-all">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fas fa-barcode text-[#5e0078]"></i> Format Voucher yang Dihasilkan
                    </span>
                    <span class="text-[10px] text-purple-700 font-semibold bg-purple-100 px-2 py-0.5 rounded-md">
                        Auto-Urut
                    </span>
                </div>
                <div class="p-2.5 bg-white rounded-lg border border-purple-100 flex items-center justify-between">
                    <div>
                        <span id="previewVoucherCode" class="font-mono font-black text-[#5e0078] text-sm sm:text-base tracking-wider block select-all">-</span>
                        <span id="previewVoucherDesc" class="text-[11px] text-slate-500 font-medium">Nomor urut dimulai dari urutan berikutnya</span>
                    </div>
                    <span id="previewQtyBadge" class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">1 Voucher</span>
                </div>
                <p class="text-[10.5px] text-slate-400 mt-1.5 leading-relaxed">
                    Format: <code class="text-slate-600 font-mono">[ROLE]-[JENIS]-[KODE]-[URUT]</code>. Nomor urut bertambah otomatis per pembina & role tujuan.
                </p>
            </div>

            <!-- Masa Berlaku (Opsional) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Masa Berlaku (Opsional)</label>
                <input type="date" name="expired_at" min="<?= date('Y-m-d') ?>"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition">
                <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika voucher berlaku permanen tanpa tanggal kedaluwarsa.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeGenerateModal()" 
                        class="btn-interactive px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-check mr-1.5"></i> Simpan &amp; Generate Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Data akun pembina dari backend
const pusatAccounts = <?= json_encode($pusatAccounts ?? []) ?>;
const cabangAccounts = <?= json_encode($cabangAccounts ?? []) ?>;
const managerAccounts = <?= json_encode($managerAccounts ?? []) ?>;

function openGenerateModal() {
    document.getElementById('generateModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    onRoleTujuanChange();
}

function closeGenerateModal() {
    document.getElementById('generateModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function onRoleTujuanChange() {
    const roleSelect = document.getElementById('role_tujuan');
    const jenisSelect = document.getElementById('jenis_pembina');
    const roleHint = document.getElementById('roleTujuanHint');
    const jenisHint = document.getElementById('jenisPembinaHint');
    const selectedRole = roleSelect ? roleSelect.value : 'mitra';

    jenisSelect.innerHTML = '';

    if (selectedRole === 'cabang') {
        // Cabang wajib dibina oleh Kantor Pusat
        let optPusat = document.createElement('option');
        optPusat.value = 'pusat';
        optPusat.textContent = 'Kantor Pusat (Otomatis)';
        jenisSelect.appendChild(optPusat);
        jenisSelect.value = 'pusat';
        jenisHint.textContent = 'Pendaftaran Cabang otomatis dan wajib dibina langsung oleh Kantor Pusat.';
        roleHint.textContent = 'Menghasilkan voucher ber-prefix CBG-... untuk pendaftaran akun Cabang.';
    } else if (selectedRole === 'manager_mitra') {
        // Manager Mitra bisa dibina Pusat atau Cabang
        let defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Pilih Jenis Pembina (Pusat / Cabang) --';
        jenisSelect.appendChild(defaultOpt);

        let optPusat = document.createElement('option');
        optPusat.value = 'pusat';
        optPusat.textContent = 'Kantor Pusat';
        jenisSelect.appendChild(optPusat);

        let optCabang = document.createElement('option');
        optCabang.value = 'cabang';
        optCabang.textContent = 'Cabang';
        jenisSelect.appendChild(optCabang);

        jenisHint.textContent = 'Manager Mitra dapat dibina langsung oleh Kantor Pusat atau Cabang.';
        roleHint.textContent = 'Menghasilkan voucher ber-prefix MGR-... untuk pendaftaran akun Manager Mitra.';
    } else {
        // Mitra bisa dibina Pusat, Cabang, atau Manager Mitra
        let defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Pilih Jenis Pembina --';
        jenisSelect.appendChild(defaultOpt);

        let optPusat = document.createElement('option');
        optPusat.value = 'pusat';
        optPusat.textContent = 'Kantor Pusat';
        jenisSelect.appendChild(optPusat);

        let optCabang = document.createElement('option');
        optCabang.value = 'cabang';
        optCabang.textContent = 'Cabang';
        jenisSelect.appendChild(optCabang);

        let optManager = document.createElement('option');
        optManager.value = 'manager_mitra';
        optManager.textContent = 'Manager Mitra';
        jenisSelect.appendChild(optManager);

        jenisHint.textContent = 'Mitra dapat dibina oleh Kantor Pusat, Cabang, atau Manager Mitra.';
        roleHint.textContent = 'Menghasilkan voucher ber-prefix MIT-... untuk pendaftaran akun Mitra.';
    }

    onJenisPembinaChange();
}

function onJenisPembinaChange() {
    const roleSelect = document.getElementById('role_tujuan');
    const jenisSelect = document.getElementById('jenis_pembina');
    const pembinaSelect = document.getElementById('pembina_id');
    const hint = document.getElementById('pembinaHint');
    const selectedJenis = jenisSelect.value;
    const selectedRole = roleSelect ? roleSelect.value : 'mitra';

    pembinaSelect.innerHTML = '';

    if (!selectedJenis) {
        pembinaSelect.disabled = true;
        pembinaSelect.classList.add('bg-slate-100');
        pembinaSelect.classList.remove('bg-slate-50');
        pembinaSelect.innerHTML = '<option value="">-- Pilih Jenis Pembina Terlebih Dahulu --</option>';
        hint.textContent = 'Pilih jenis pembina di atas untuk melihat akun yang tersedia.';
        updateVoucherPreview();
        return;
    }

    let accounts = [];
    if (selectedJenis === 'pusat') {
        accounts = pusatAccounts;
    } else if (selectedJenis === 'cabang') {
        accounts = cabangAccounts;
    } else if (selectedJenis === 'manager_mitra') {
        accounts = managerAccounts;
    }

    if (accounts.length === 0) {
        pembinaSelect.disabled = true;
        pembinaSelect.classList.add('bg-slate-100');
        pembinaSelect.classList.remove('bg-slate-50');
        pembinaSelect.innerHTML = '<option value="">-- Tidak ada akun aktif ditemukan --</option>';
        hint.textContent = 'Tidak ada akun aktif untuk jenis pembina yang dipilih.';
        updateVoucherPreview();
        return;
    }

    pembinaSelect.disabled = false;
    pembinaSelect.classList.remove('bg-slate-100');
    pembinaSelect.classList.add('bg-slate-50');
    
    let defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- Pilih Akun ' + (selectedJenis === 'pusat' ? 'Pusat' : (selectedJenis === 'cabang' ? 'Cabang' : 'Manager Mitra')) + ' --';
    pembinaSelect.appendChild(defaultOpt);

    const prefixMap = {
        'cabang': 'CBG',
        'manager_mitra': 'MGR',
        'mitra': 'MIT'
    };
    const currentPrefixRole = prefixMap[selectedRole] || 'MIT';

    accounts.forEach(acc => {
        let opt = document.createElement('option');
        opt.value = acc.id;

        // Ambil nextUrut sesuai role tujuan yang dipilih
        let nextUrut = acc.next_urut_mit || 1;
        if (selectedRole === 'cabang') {
            nextUrut = acc.next_urut_cbg || 1;
        } else if (selectedRole === 'manager_mitra') {
            nextUrut = acc.next_urut_mgr || 1;
        }

        opt.dataset.prefix = `${currentPrefixRole}-${acc.jenis_prefix}-${acc.kode_pembina}`;
        opt.dataset.nextUrut = nextUrut;

        if (selectedJenis === 'pusat') {
            opt.textContent = `[PS-${acc.kode_pembina}] ${acc.nama_lengkap} (Kantor Pusat)`;
        } else if (selectedJenis === 'cabang') {
            opt.textContent = `[CB-${acc.kode_pembina}] ${acc.nama_lengkap} (Cabang ${acc.domisili || ''})`;
        } else if (selectedJenis === 'manager_mitra') {
            const indukRoleFmt = (acc.role_induk === 'pusat') ? 'Pusat' : 'Cabang';
            opt.textContent = `[MG-${acc.kode_pembina}] ${acc.nama_lengkap} (Induk: ${acc.nama_induk} - ${indukRoleFmt})`;
        }

        pembinaSelect.appendChild(opt);
    });

    if (selectedJenis === 'manager_mitra') {
        hint.textContent = 'Setiap Manager Mitra menampilkan kode identitas dan relasi induknya (Pusat atau Cabang).';
    } else {
        hint.textContent = 'Pilih akun pembina. Format kode dan nomor urut akan ditentukan otomatis.';
    }

    // Jika hanya ada 1 pilihan di akun (misal Pusat), auto-pilih
    if (accounts.length === 1) {
        pembinaSelect.selectedIndex = 1;
    }

    updateVoucherPreview();
}

function updateVoucherPreview() {
    const roleSelect = document.getElementById('role_tujuan');
    const pembinaSelect = document.getElementById('pembina_id');
    const previewBox = document.getElementById('voucherPreviewBox');
    const previewCode = document.getElementById('previewVoucherCode');
    const previewDesc = document.getElementById('previewVoucherDesc');
    const previewQty = document.getElementById('previewQtyBadge');
    const qtyInput = document.querySelector('input[name="qty"]');

    const selectedOption = pembinaSelect.options[pembinaSelect.selectedIndex];
    const qty = Math.max(1, Math.min(100, parseInt(qtyInput ? qtyInput.value : 1) || 1));
    const roleName = roleSelect ? (roleSelect.options[roleSelect.selectedIndex]?.text?.trim() || 'Mitra') : 'Mitra';

    if (!selectedOption || !selectedOption.value || !selectedOption.dataset.prefix) {
        previewBox.classList.add('hidden');
        return;
    }

    const prefix = selectedOption.dataset.prefix;
    const startUrut = parseInt(selectedOption.dataset.nextUrut) || 1;
    const endUrut = startUrut + qty - 1;

    const startFmt = String(startUrut).padStart(4, '0');
    const endFmt = String(endUrut).padStart(4, '0');

    previewBox.classList.remove('hidden');
    previewQty.textContent = `${qty} Voucher`;

    if (qty === 1) {
        previewCode.textContent = `${prefix}-${startFmt}`;
        previewDesc.textContent = `Voucher pendaftaran ${roleName} #${startFmt} untuk pembina ini`;
    } else {
        previewCode.textContent = `${prefix}-${startFmt} s/d ${prefix}-${endFmt}`;
        previewDesc.textContent = `Voucher pendaftaran ${roleName} #${startFmt} s/d #${endFmt} (${qty} voucher berurutan)`;
    }
}

// Bind qty input change to preview
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.querySelector('input[name="qty"]');
    if (qtyInput) {
        qtyInput.addEventListener('input', updateVoucherPreview);
    }
});

function validateGenerateForm() {
    const pembinaSelect = document.getElementById('pembina_id');
    if (!pembinaSelect.value) {
        alert('Wajib memilih akun pembina langsung sebelum menyimpan voucher.');
        pembinaSelect.focus();
        return false;
    }
    return true;
}

// Close on escape key
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGenerateModal();
    }
});

// AmanahTable Initialization (Search, Filtering, Sorting & Pagination)
document.addEventListener('DOMContentLoaded', function() {
    AmanahTable.init({
        containerId: 'vouchersTable',
        rowSelector: 'tbody tr.voucher-table-row',
        entityName: 'voucher',
        defaultPageSize: 10,
        searchInputId: 'vouchersSearchInput',
        sortSelectId: 'voucherSortSelect',
        pageSizeSelectId: 'voucherPageSizeSelect',
        summaryId: 'voucherCountSummary',
        prevBtnId: 'prevVoucherBtn',
        nextBtnId: 'nextVoucherBtn',
        numbersId: 'voucherPaginationNumbers',
        emptyStateId: 'emptyVoucherRow',
        resetBtnId: 'resetVoucherFiltersBtn',
        defaultSort: 'created_desc',
        filters: [
            { selectId: 'voucherRoleFilter', key: 'role', defaultValue: 'all' },
            { selectId: 'voucherStatusFilter', key: 'status', defaultValue: 'all' }
        ],
        sortDefinitions: {
            created_desc: (a, b) => ((parseInt(b.getAttribute('data-created'), 10) || 0) - (parseInt(a.getAttribute('data-created'), 10) || 0)) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            created_asc: (a, b) => ((parseInt(a.getAttribute('data-created'), 10) || 0) - (parseInt(b.getAttribute('data-created'), 10) || 0)) || ((parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0)),
            kode_asc: (a, b) => (a.getAttribute('data-kode') || '').localeCompare(b.getAttribute('data-kode') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            kode_desc: (a, b) => (b.getAttribute('data-kode') || '').localeCompare(a.getAttribute('data-kode') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            pembina_asc: (a, b) => (a.getAttribute('data-pembina') || '').localeCompare(b.getAttribute('data-pembina') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            pembina_desc: (a, b) => (b.getAttribute('data-pembina') || '').localeCompare(a.getAttribute('data-pembina') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            status_asc: (a, b) => (a.getAttribute('data-status') || '').localeCompare(b.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            status_desc: (a, b) => (b.getAttribute('data-status') || '').localeCompare(a.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
            expired_asc: (a, b) => ((parseInt(a.getAttribute('data-expired'), 10) || 0) - (parseInt(b.getAttribute('data-expired'), 10) || 0)) || ((parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0)),
            expired_desc: (a, b) => ((parseInt(b.getAttribute('data-expired'), 10) || 0) - (parseInt(a.getAttribute('data-expired'), 10) || 0)) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0))
        }
    });

    // Bulk Selection & Bulk Delete Handler for Vouchers
    const selectAllCheckbox = document.getElementById('selectAllVouchers');
    const bulkBar = document.getElementById('voucherBulkActionBar');
    const countEl = document.getElementById('selectedVouchersCount');
    const cancelBtn = document.getElementById('cancelVoucherSelectionBtn');
    const deleteBtn = document.getElementById('bulkDeleteVouchersBtn');
    const form = document.getElementById('voucherBulkDeleteForm');
    const inputsContainer = document.getElementById('voucherBulkDeleteInputs');

    if (selectAllCheckbox && bulkBar) {
        function getVisibleRowCheckboxes() {
            const rows = document.querySelectorAll('tbody tr.voucher-table-row');
            const visibleCheckboxes = [];
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.voucher-row-checkbox');
                    if (cb) visibleCheckboxes.push(cb);
                }
            });
            return visibleCheckboxes;
        }

        function getAllCheckedBoxes() {
            return document.querySelectorAll('tbody .voucher-row-checkbox:checked');
        }

        function updateBulkBarState() {
            const checked = getAllCheckedBoxes();
            const count = checked.length;
            if (count > 0) {
                countEl.textContent = count;
                bulkBar.classList.remove('hidden');
            } else {
                bulkBar.classList.add('hidden');
            }

            const visible = getVisibleRowCheckboxes();
            if (visible.length > 0) {
                const allVisibleChecked = visible.every(cb => cb.checked);
                const someVisibleChecked = visible.some(cb => cb.checked);
                selectAllCheckbox.checked = allVisibleChecked;
                selectAllCheckbox.indeterminate = !allVisibleChecked && someVisibleChecked;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        selectAllCheckbox.addEventListener('change', function() {
            const visible = getVisibleRowCheckboxes();
            visible.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateBulkBarState();
        });

        document.getElementById('vouchersTable').addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('voucher-row-checkbox')) {
                updateBulkBarState();
            }
        });

        cancelBtn.addEventListener('click', function() {
            document.querySelectorAll('tbody .voucher-row-checkbox').forEach(cb => cb.checked = false);
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            updateBulkBarState();
        });

        deleteBtn.addEventListener('click', function() {
            const checked = getAllCheckedBoxes();
            const count = checked.length;
            if (count === 0) return;

            if (confirm(`Apakah Anda yakin ingin menghapus ${count} voucher yang dipilih secara permanen? Tindakan ini tidak dapat dibatalkan.`)) {
                inputsContainer.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    inputsContainer.appendChild(input);
                });
                form.submit();
            }
        });

        const resetBtn = document.getElementById('resetVoucherFiltersBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                setTimeout(updateBulkBarState, 100);
            });
        }
    }
});

// Auto Copy Voucher Code Handler
function copyVoucherCode(code, btn) {
    if (!code) return;

    function onCopied() {
        if (btn) {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-xs text-emerald-600"></i>';
            btn.classList.remove('bg-purple-50', 'text-[#5e0078]', 'border-purple-200');
            btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                btn.classList.add('bg-purple-50', 'text-[#5e0078]', 'border-purple-200');
            }, 1500);
        }

        if (window.toast && window.toast.success) {
            window.toast.success(`Kode voucher ${code} berhasil disalin ke clipboard!`, 'Berhasil Disalin');
        } else if (window.showToast) {
            window.showToast({
                message: `Kode voucher ${code} berhasil disalin ke clipboard!`,
                title: 'Berhasil Disalin',
                type: 'green'
            });
        }
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(onCopied).catch(() => fallbackCopy(code, onCopied));
    } else {
        fallbackCopy(code, onCopied);
    }
}

function fallbackCopy(text, callback) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        const successful = document.execCommand('copy');
        if (successful && callback) callback();
    } catch (err) {
        prompt('Salin kode voucher secara manual:', text);
    }
    document.body.removeChild(textArea);
}
</script>
<?= $this->endSection() ?>
