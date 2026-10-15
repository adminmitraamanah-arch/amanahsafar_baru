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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Penarikan Komisi</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar permohonan transfer saldo komisi siap cair dari mitra, manager, dan cabang ke rekening bank pribadi.</p>
                    </div>
                    <span class="text-xs text-slate-500 font-medium bg-white px-3.5 py-2 rounded-xl border border-slate-200/80 shadow-2xs">
                        Total Permintaan: <strong class="text-slate-900"><?= count($withdrawals ?? []) ?></strong>
                    </span>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Filter & Search Toolbar -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="wdSearch" placeholder="Cari no penarikan, nama pemohon..." 
                                   class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                        </div>

                        <select id="wdStatusFilter" onchange="filterWd()" 
                                class="px-3 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                            <option value="all">Semua Status</option>
                            <option value="menunggu_transfer">Menunggu Transfer</option>
                            <option value="selesai">Selesai (Ditransfer)</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <!-- Withdrawals Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="withdrawalsTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">No. Penarikan</th>
                                    <th class="px-5 py-3.5">Tanggal</th>
                                    <th class="px-5 py-3.5">Pemohon</th>
                                    <th class="px-5 py-3.5">Rekening Bank Tujuan</th>
                                    <th class="px-5 py-3.5 text-right">Nominal</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5">Bukti Transfer</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($withdrawals)): ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-hand-holding-usd text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada permohonan penarikan dana yang diajukan.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($withdrawals as $w): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors wd-row"
                                            data-status="<?= esc($w['status']) ?>"
                                            data-search="<?= strtolower(esc($w['no_penarikan'] . ' ' . ($w['nama_user'] ?? '') . ' ' . ($w['nama_bank'] ?? '') . ' ' . ($w['nomor_rekening'] ?? ''))) ?>">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-800 text-xs whitespace-nowrap">
                                                <?= esc($w['no_penarikan']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600 whitespace-nowrap">
                                                <?= date('d M Y, H:i', strtotime($w['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-slate-900 text-xs">
                                                    <?= esc($w['nama_user'] ?? 'User #' . $w['user_id']) ?>
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-[#5e0078] border border-purple-100">
                                                        <?= esc(str_replace('_', ' ', $w['role_user'] ?? 'mitra')) ?>
                                                    </span>
                                                    <?php if (!empty($w['no_wa'])): ?>
                                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $w['no_wa']) ?>" target="_blank" class="text-[11px] text-emerald-600 hover:underline">
                                                            <i class="fab fa-whatsapp"></i> <?= esc($w['no_wa']) ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-xs">
                                                <div class="font-bold text-slate-800">
                                                    <?= esc($w['nama_bank']) ?> - <?= esc($w['nomor_rekening']) ?>
                                                </div>
                                                <div class="text-[11px] text-slate-400 mt-0.5">
                                                    a.n. <?= esc($w['nama_pemilik_rekening']) ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-right font-extrabold text-slate-900 whitespace-nowrap">
                                                Rp <?= number_format($w['nominal'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($w['status']) {
                                                        'menunggu_transfer' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'selesai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'ditolak' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                                    };
                                                    ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $w['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?php if (!empty($w['bukti_transfer'])): ?>
                                                    <a href="<?= base_url(esc($w['bukti_transfer'])) ?>" target="_blank" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold transition">
                                                        <i class="fas fa-file-invoice text-xs"></i> Lihat Struk
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-slate-400 italic">Belum ada struk</span>
                                                <?php endif; ?>
                                                <?php if (!empty($w['catatan_admin'])): ?>
                                                    <span class="block text-[11px] text-slate-400 mt-1"><?= esc($w['catatan_admin']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                                <?php if ($w['status'] === 'menunggu_transfer'): ?>
                                                    <button type="button" onclick="openProcessWdModal(<?= htmlspecialchars(json_encode($w), ENT_QUOTES, 'UTF-8') ?>)" 
                                                            class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold text-xs border border-purple-200 transition">
                                                        <i class="fas fa-hand-holding-usd text-[10px]"></i>
                                                        <span>Proses</span>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-slate-400 text-xs">-</span>
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

<!-- Modal Proses Penarikan Dana -->
<div id="processWdModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeProcessWdModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Proses Penarikan Komisi</h3>
                </div>
                <button type="button" onclick="closeProcessWdModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form id="wdProcessForm" method="POST" action="" enctype="multipart/form-data" class="p-5 space-y-4">
                <?= csrf_field() ?>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1.5 text-xs border border-slate-200/70">
                    <div class="flex justify-between">
                        <span class="text-slate-400">No. Penarikan:</span>
                        <span id="wdModalNo" class="font-mono font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Pemohon:</span>
                        <span id="wdModalUser" class="font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Rekening Tujuan:</span>
                        <span id="wdModalRek" class="font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nominal:</span>
                        <span id="wdModalNominal" class="font-extrabold text-emerald-600 text-sm">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Keputusan Admin <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer border border-emerald-500 bg-emerald-50/60 rounded-xl p-3 flex flex-col items-center text-center transition">
                            <input type="radio" name="action" value="approve" checked class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-emerald-800 mt-1">Setujui & Transfer</span>
                            <span class="text-[10px] text-emerald-600 mt-0.5">Sudah Ditransfer</span>
                        </label>
                        <label class="cursor-pointer border border-rose-200 rounded-xl p-3 flex flex-col items-center text-center transition hover:border-rose-400">
                            <input type="radio" name="action" value="reject" class="text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-bold text-rose-800 mt-1">Tolak Permintaan</span>
                            <span class="text-[10px] text-rose-500 mt-0.5">Kembalikan Saldo</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="wdBuktiFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Unggah Bukti Struk Transfer (JPG/PNG/PDF)</label>
                    <input type="file" id="wdBuktiFile" name="bukti_transfer" accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#5e0078] hover:file:bg-purple-100 transition">
                </div>

                <div>
                    <label for="wdCatatanAdmin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan Admin</label>
                    <textarea id="wdCatatanAdmin" name="catatan_admin" rows="2" placeholder="Catatan transfer atau alasan penolakan..."
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeProcessWdModal()" class="btn-interactive flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-interactive flex-1 bg-[#5e0078] hover:bg-[#4c0062] text-white py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                        Simpan Keputusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterWd() {
    const status = document.getElementById('wdStatusFilter').value;
    const search = document.getElementById('wdSearch').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.wd-row');

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

document.getElementById('wdSearch')?.addEventListener('input', filterWd);

function openProcessWdModal(w) {
    const form = document.getElementById('wdProcessForm');
    form.action = "<?= base_url('/superadmin/withdrawals/') ?>/" + w.id + "/process";

    document.getElementById('wdModalNo').textContent = w.no_penarikan;
    document.getElementById('wdModalUser').textContent = w.nama_user || ('User #' + w.user_id);
    document.getElementById('wdModalRek').textContent = w.nama_bank + ' ' + w.nomor_rekening + ' (a.n. ' + w.nama_pemilik_rekening + ')';
    document.getElementById('wdModalNominal').textContent = 'Rp ' + Number(w.nominal).toLocaleString('id-ID');
    document.getElementById('wdCatatanAdmin').value = w.catatan_admin || '';

    document.getElementById('processWdModal').classList.remove('hidden');
}

function closeProcessWdModal() {
    document.getElementById('processWdModal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
