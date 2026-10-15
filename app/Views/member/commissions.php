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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Buku Mutasi Komisi</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Transparansi pencatatan pendapatan komisi jaringan hierarki Anda secara real-time mirip mutasi rekening bank.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <?php if ($saldo_siap_cair >= 50000): ?>
                            <button type="button" onclick="openWithdrawModal()" 
                                    class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-sm transition">
                                <i class="fas fa-hand-holding-usd text-xs"></i>
                                <span>Tarik Saldo Komisi</span>
                            </button>
                        <?php else: ?>
                            <button type="button" disabled title="Saldo minimal penarikan Rp 50.000"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-200 text-slate-400 text-xs sm:text-sm font-bold cursor-not-allowed">
                                <i class="fas fa-hand-holding-usd text-xs"></i>
                                <span>Tarik Saldo Komisi</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 3 Financial Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Card 1: Potensi Komisi (DP) -->
                    <div class="bg-white rounded-2xl border border-amber-200/80 p-5 shadow-xs flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-hourglass-half text-xs"></i>
                                Potensi Komisi (Uang Muka/DP)
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Tahap DP
                            </span>
                        </div>
                        <div>
                            <span class="text-2xl sm:text-3xl font-extrabold text-amber-600">
                                Rp <?= number_format($potensi_komisi, 0, ',', '.') ?>
                            </span>
                            <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                                Komisi tercatat dari pesanan jamaah yang telah membayar DP. Otomatis menjadi <strong>Saldo Siap Cair</strong> setelah pembayaran pelunasan disetujui admin.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2: Saldo Siap Cair -->
                    <div class="bg-white rounded-2xl border border-emerald-200/80 p-5 shadow-xs flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-wallet text-xs"></i>
                                Saldo Siap Cair
                            </span>
                            <?php if ($total_pending_wd > 0): ?>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    Diproses: Rp <?= number_format($total_pending_wd, 0, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Bisa Ditarik
                                </span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600">
                                Rp <?= number_format($saldo_siap_cair, 0, ',', '.') ?>
                            </span>
                            <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                                Komisi yang sudah lunas penuh dan dapat langsung diajukan penarikan ke rekening bank Anda kapan saja (min. Rp 50.000).
                            </p>
                        </div>
                    </div>

                    <!-- Card 3: Total Ditarik -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-xs"></i>
                                Total Komisi Dicairkan
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-[#5e0078] border border-purple-200">
                                Riwayat Sukses
                            </span>
                        </div>
                        <div>
                            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                                Rp <?= number_format($total_ditarik, 0, ',', '.') ?>
                            </span>
                            <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                                Akumulasi seluruh dana komisi yang telah berhasil ditransfer admin ke rekening bank pribadi Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Mutation Card with Tabs -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Tabs & Filters -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <!-- Tab Switcher -->
                        <div class="inline-flex items-center p-1 bg-slate-100 border border-slate-200/90 rounded-xl" role="tablist">
                            <button type="button" id="tabLedgerBtn" onclick="switchTab('ledger')" 
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition bg-white text-[#5e0078] shadow-2xs">
                                <i class="fas fa-list-alt text-xs mr-1.5"></i>
                                Buku Mutasi (<?= count($mutasi ?? []) ?>)
                            </button>
                            <button type="button" id="tabWithdrawalsBtn" onclick="switchTab('withdrawals')" 
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-500 hover:text-slate-800">
                                <i class="fas fa-history text-xs mr-1.5"></i>
                                Riwayat Penarikan (<?= count($withdrawals ?? []) ?>)
                            </button>
                        </div>

                        <!-- Search & Filter for Ledger -->
                        <div id="ledgerFilterControls" class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <div class="relative w-full md:w-64">
                                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" id="mutasiSearchInput" placeholder="Cari keterangan, no order..." 
                                       class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            </div>

                            <select id="mutasiStatusFilter" onchange="filterMutasi()" 
                                    class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status</option>
                                <option value="potensi">Potensi Komisi (DP)</option>
                                <option value="siap_cair">Siap Cair (Lunas)</option>
                                <option value="ditarik">Telah Ditarik</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- SECTION A: TAB BUKU MUTASI LEDGER -->
                    <div id="sectionLedger" class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="mutasiTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">Tanggal & Waktu</th>
                                    <th class="px-5 py-3.5">No. Referensi</th>
                                    <th class="px-5 py-3.5">Keterangan / Rincian Sumber</th>
                                    <th class="px-5 py-3.5 text-center">Peran</th>
                                    <th class="px-5 py-3.5 text-right">Nominal Komisi</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($mutasi)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-receipt text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada catatan mutasi komisi untuk akun Anda.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($mutasi as $m): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors mutasi-row"
                                            data-status="<?= esc($m['status']) ?>"
                                            data-search="<?= strtolower(esc(($m['no_order'] ?? '') . ' ' . $m['keterangan'] . ' ' . ($m['nama_pembeli'] ?? '') . ' ' . $m['role_penerima'] . ' ' . $m['nominal_snapshot'])) ?>">
                                            <td class="px-5 py-4 text-xs text-slate-600 font-medium whitespace-nowrap">
                                                <i class="far fa-clock text-slate-400 mr-1 text-[11px]"></i>
                                                <?= date('d M Y, H:i', strtotime($m['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4 font-mono font-bold text-slate-800 text-xs whitespace-nowrap">
                                                <?php if (!empty($m['order_id'])): ?>
                                                    <a href="<?= base_url('/app/orders/' . $m['order_id']) ?>" class="px-2.5 py-1 bg-purple-50 text-[#5e0078] hover:bg-purple-100 rounded-lg border border-purple-200/80 transition" title="Lihat detail pesanan">
                                                        <?= esc($m['no_order'] ?: ('ORD-' . $m['order_id'])) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-slate-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-slate-900 text-xs">
                                                    <?= esc($m['keterangan']) ?>
                                                </div>
                                                <?php if (!empty($m['nama_pembeli'])): ?>
                                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                                        Pembeli / Jamaah: <span class="font-medium text-slate-600"><?= esc($m['nama_pembeli']) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                                    <?= esc(str_replace('_', ' ', $m['role_penerima'] ?: 'mitra')) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right whitespace-nowrap font-extrabold text-sm
                                                <?= in_array($m['status'], ['siap_cair']) ? 'text-emerald-600' : ($m['status'] === 'potensi' ? 'text-amber-600' : 'text-slate-700') ?>">
                                                + Rp <?= number_format($m['nominal_snapshot'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($m['status']) {
                                                        'potensi' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'siap_cair' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'ditarik' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                        'dibatalkan' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                                    };
                                                    ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $m['status'] === 'siap_cair' ? 'bg-emerald-500' : ($m['status'] === 'potensi' ? 'bg-amber-500' : 'bg-slate-400') ?>"></span>
                                                    <?= ucfirst(str_replace('_', ' ', $m['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                                <button type="button" onclick="showMutationDetail(<?= htmlspecialchars(json_encode($m), ENT_QUOTES, 'UTF-8') ?>)" 
                                                        class="btn-interactive inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                                    <i class="fas fa-info-circle text-[11px]"></i>
                                                    <span>Rincian</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- SECTION B: TAB RIWAYAT PENARIKAN DANA (WITHDRAWALS) -->
                    <div id="sectionWithdrawals" class="hidden overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">No. Penarikan</th>
                                    <th class="px-5 py-3.5">Tanggal Permintaan</th>
                                    <th class="px-5 py-3.5">Rekening Bank Tujuan</th>
                                    <th class="px-5 py-3.5 text-right">Nominal</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5">Bukti Transfer Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($withdrawals)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-hand-holding-usd text-3xl mb-2 text-slate-300 block"></i>
                                            Belum pernah ada riwayat penarikan dana.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($withdrawals as $wd): ?>
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-800 text-xs">
                                                <?= esc($wd['no_penarikan']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?= date('d M Y, H:i', strtotime($wd['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-slate-900 text-xs">
                                                    <?= esc($wd['nama_bank']) ?> - <?= esc($wd['nomor_rekening']) ?>
                                                </div>
                                                <div class="text-[11px] text-slate-400 mt-0.5">
                                                    a.n. <?= esc($wd['nama_pemilik_rekening']) ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-right font-extrabold text-slate-900">
                                                Rp <?= number_format($wd['nominal'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($wd['status']) {
                                                        'menunggu_transfer' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'selesai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'ditolak' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                                    };
                                                    ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $wd['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?php if (!empty($wd['bukti_transfer'])): ?>
                                                    <a href="<?= base_url(esc($wd['bukti_transfer'])) ?>" target="_blank" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold transition">
                                                        <i class="fas fa-file-invoice text-xs"></i> Lihat Struk Bank
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-slate-400 italic">Menunggu proses admin</span>
                                                <?php endif; ?>
                                                <?php if (!empty($wd['catatan_admin'])): ?>
                                                    <span class="block text-[11px] text-slate-400 mt-1"><?= esc($wd['catatan_admin']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Rincian Mutasi Transaksi (Bank-Style Slip) -->
<div id="mutationDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeMutationDetailModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center">
                        <i class="fas fa-receipt text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Rincian Slip Mutasi Komisi</h3>
                        <p class="text-[10px] text-slate-400">Bukti validasi pembagian komisi transaksi</p>
                    </div>
                </div>
                <button type="button" onclick="closeMutationDetailModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <div class="p-5 space-y-3.5 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">No. Referensi Order:</span>
                    <span id="slipOrderNo" class="font-mono font-bold text-slate-900">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Waktu Transaksi:</span>
                    <span id="slipTanggal" class="font-medium text-slate-800">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Pembeli / Jamaah:</span>
                    <span id="slipPembeli" class="font-bold text-slate-800">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Peran Penerima:</span>
                    <span id="slipPeran" class="font-bold uppercase text-slate-800">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Jenis Komisi:</span>
                    <span id="slipJenis" class="font-medium text-slate-800">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Status Komisi:</span>
                    <span id="slipStatus" class="font-bold">-</span>
                </div>
                <div class="p-3.5 bg-purple-50/70 rounded-xl border border-purple-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Nominal Komisi:</span>
                    <span id="slipNominal" class="text-base font-extrabold text-[#5e0078]">Rp 0</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-500 uppercase block mb-1">Keterangan Lengkap:</span>
                    <p id="slipKeterangan" class="p-2.5 bg-slate-50 rounded-xl text-slate-600 text-xs leading-relaxed border border-slate-200/70">-</p>
                </div>
            </div>

            <div class="p-4 bg-slate-50/75 border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeMutationDetailModal()" class="btn-interactive px-5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajukan Penarikan Komisi -->
<div id="withdrawModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeWithdrawModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-xs"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Ajukan Penarikan Komisi</h3>
                </div>
                <button type="button" onclick="closeWithdrawModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form method="POST" action="<?= base_url('/app/commissions/withdraw') ?>" class="p-5 space-y-4">
                <?= csrf_field() ?>

                <div class="p-3.5 bg-emerald-50/70 rounded-xl border border-emerald-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-800">Saldo Siap Cair Anda:</span>
                    <span class="text-sm font-extrabold text-emerald-700">Rp <?= number_format($saldo_siap_cair, 0, ',', '.') ?></span>
                </div>

                <div>
                    <label for="withdrawNominal" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nominal Penarikan (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" id="withdrawNominal" name="nominal" required min="50000" max="<?= (int)$saldo_siap_cair ?>" value="<?= (int)$saldo_siap_cair ?>"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Minimal penarikan Rp 50.000</span>
                </div>

                <div>
                    <label for="wdBank" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Bank Tujuan <span class="text-rose-500">*</span></label>
                    <input type="text" id="wdBank" name="nama_bank" required value="<?= esc($bank_info['nama_bank'] ?? '') ?>" placeholder="Contoh: BCA / BSI / Mandiri"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                </div>

                <div>
                    <label for="wdNoRek" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nomor Rekening Tujuan <span class="text-rose-500">*</span></label>
                    <input type="text" id="wdNoRek" name="nomor_rekening" required value="<?= esc($bank_info['nomor_rekening'] ?? '') ?>" placeholder="Nomor rekening bank Anda"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                </div>

                <div>
                    <label for="wdNamaPemilik" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Pemilik Rekening <span class="text-rose-500">*</span></label>
                    <input type="text" id="wdNamaPemilik" name="nama_pemilik_rekening" required value="<?= esc($bank_info['nama_rekening'] ?? session('nama')) ?>" placeholder="Nama sesuai buku tabungan"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeWithdrawModal()" class="btn-interactive flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-interactive flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                        Ajukan Penarikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const secLedger = document.getElementById('sectionLedger');
    const secWd = document.getElementById('sectionWithdrawals');
    const tabLedger = document.getElementById('tabLedgerBtn');
    const tabWd = document.getElementById('tabWithdrawalsBtn');
    const controls = document.getElementById('ledgerFilterControls');

    if (tab === 'ledger') {
        secLedger.classList.remove('hidden');
        secWd.classList.add('hidden');
        tabLedger.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition bg-white text-[#5e0078] shadow-2xs';
        tabWd.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-500 hover:text-slate-800';
        if (controls) controls.classList.remove('hidden');
    } else {
        secLedger.classList.add('hidden');
        secWd.classList.remove('hidden');
        tabWd.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition bg-white text-[#5e0078] shadow-2xs';
        tabLedger.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-500 hover:text-slate-800';
        if (controls) controls.classList.add('hidden');
    }
}

function filterMutasi() {
    const status = document.getElementById('mutasiStatusFilter').value;
    const search = document.getElementById('mutasiSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.mutasi-row');

    rows.forEach(r => {
        const rowStatus = r.getAttribute('data-status');
        const rowSearch = r.getAttribute('data-search') || '';
        const matchStatus = (status === 'all' || rowStatus === status);
        const matchSearch = (search === '' || rowSearch.includes(search));

        if (matchStatus && matchSearch) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}

document.getElementById('mutasiSearchInput')?.addEventListener('input', filterMutasi);

function showMutationDetail(m) {
    document.getElementById('slipOrderNo').textContent = m.no_order ? m.no_order : ('ORD-' + m.order_id);
    document.getElementById('slipTanggal').textContent = m.created_at;
    document.getElementById('slipPembeli').textContent = m.nama_pembeli || '-';
    document.getElementById('slipPeran').textContent = m.role_penerima ? m.role_penerima.replace('_', ' ') : '-';
    document.getElementById('slipJenis').textContent = m.jenis_komisi ? m.jenis_komisi.replace('_', ' ') : 'Komisi Penjualan';
    document.getElementById('slipStatus').textContent = m.status ? m.status.replace('_', ' ').toUpperCase() : '-';
    document.getElementById('slipNominal').textContent = 'Rp ' + Number(m.nominal_snapshot).toLocaleString('id-ID');
    document.getElementById('slipKeterangan').textContent = m.keterangan || '-';

    document.getElementById('mutationDetailModal').classList.remove('hidden');
}

function closeMutationDetailModal() {
    document.getElementById('mutationDetailModal').classList.add('hidden');
}

function openWithdrawModal() {
    document.getElementById('withdrawModal').classList.remove('hidden');
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
