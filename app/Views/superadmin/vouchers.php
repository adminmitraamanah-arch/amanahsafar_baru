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
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Daftar Voucher Registrasi</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Voucher khusus untuk pendaftaran akun Mitra dengan relasi pembina hierarki.</p>
                        </div>
                        <span class="text-xs text-slate-500 font-medium bg-slate-50 px-3 py-1 rounded-xl border border-slate-200">
                            Total: <strong class="text-slate-900"><?= count($vouchers ?? []) ?></strong> Voucher
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">Kode Voucher</th>
                                    <th class="px-5 py-3.5">Role Tujuan</th>
                                    <th class="px-5 py-3.5">Pembina Langsung</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5">Masa Berlaku</th>
                                    <th class="px-5 py-3.5">Dibuat Pada</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($vouchers)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-ticket-alt text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada voucher yang dibuat. Klik tombol di atas untuk membuat voucher baru.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($vouchers as $voucher): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-900 text-xs sm:text-sm">
                                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200/70 select-all"><?= esc($voucher['kode']) ?></span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-[#5e0078] border border-purple-200 uppercase tracking-wider">
                                                    <?= esc($voucher['role_tujuan'] ?? 'mitra') ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php if (!empty($voucher['pembina_id']) && !empty($voucher['nama_pembina'])): ?>
                                                    <span class="font-bold text-slate-900 text-xs block leading-tight"><?= esc($voucher['nama_pembina']) ?></span>
                                                    <?php if ($voucher['role_pembina'] === 'pusat'): ?>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 mt-1">
                                                            <i class="fas fa-building text-[9px]"></i> Kantor Pusat
                                                        </span>
                                                    <?php elseif ($voucher['role_pembina'] === 'cabang'): ?>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 mt-1">
                                                            <i class="fas fa-code-branch text-[9px]"></i> Cabang <?= esc($voucher['domisili_pembina'] ?? '') ?>
                                                        </span>
                                                    <?php elseif ($voucher['role_pembina'] === 'manager_mitra'): ?>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 mt-1">
                                                            <i class="fas fa-user-tie text-[9px]"></i> Manager Mitra &bull; Induk: <?= esc($voucher['nama_induk'] ?? 'Pusat/Cabang') ?>
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
                                                <?php if ($voucher['status'] === 'tersedia'): ?>
                                                    <form method="POST" action="<?= base_url('/superadmin/vouchers/disable/' . $voucher['id']) ?>" class="inline-block" onsubmit="return confirm('Nonaktifkan voucher ini? Voucher tidak akan dapat digunakan lagi.')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn-interactive inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                                            <i class="fas fa-ban text-[10px]"></i> Nonaktifkan
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-xs text-slate-300 italic">-</span>
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

<!-- Generate Voucher Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-lg w-full border border-slate-100 my-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Generate Voucher Mitra</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan pembina langsung sebelum membuat voucher pendaftaran.</p>
            </div>
            <button type="button" onclick="closeGenerateModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="<?= base_url('/superadmin/vouchers/generate') ?>" class="space-y-4" onsubmit="return validateGenerateForm()">
            <?= csrf_field() ?>
            
            <!-- Info Role Tujuan (Mitra) -->
            <div class="p-3 bg-purple-50/70 rounded-xl border border-purple-100 flex items-center justify-between text-xs">
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Role Tujuan Registrasi:</span>
                    <span class="font-bold text-slate-800 text-sm">Mitra</span>
                </div>
                <span class="px-2.5 py-1 bg-white rounded-lg border border-purple-200 text-[#5e0078] text-xs font-bold">
                    <i class="fas fa-lock text-[10px] mr-1"></i> Khusus Mitra
                </span>
            </div>

            <!-- Jumlah Voucher -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Voucher <span class="text-rose-500">*</span></label>
                <input type="number" name="qty" min="1" max="100" value="1" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition">
                <p class="text-[11px] text-slate-400 mt-1">Maksimal 100 voucher per pembuatan.</p>
            </div>

            <!-- Pilihan 1: Jenis Pembina -->
            <div>
                <label for="jenis_pembina" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">1. Pilih Jenis Pembina <span class="text-rose-500">*</span></label>
                <select id="jenis_pembina" name="jenis_pembina" required onchange="onJenisPembinaChange()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Pilih Jenis Pembina --</option>
                    <option value="pusat">Kantor Pusat</option>
                    <option value="cabang">Cabang</option>
                    <option value="manager_mitra">Manager Mitra</option>
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Pilih entitas pembina: Pusat, Cabang, atau Manager Mitra.</p>
            </div>

            <!-- Pilihan 2: Akun Pembina Langsung -->
            <div>
                <label for="pembina_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">2. Akun Pembina Langsung <span class="text-rose-500">*</span></label>
                <select id="pembina_id" name="pembina_id" required disabled
                        class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] focus:bg-white focus:outline-none transition disabled:opacity-60 disabled:cursor-not-allowed">
                    <option value="">-- Pilih Jenis Pembina Terlebih Dahulu --</option>
                </select>
                <p id="pembinaHint" class="text-[11px] text-slate-400 mt-1">Pilih jenis pembina di atas untuk melihat akun yang tersedia.</p>
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
}

function closeGenerateModal() {
    document.getElementById('generateModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function onJenisPembinaChange() {
    const jenisSelect = document.getElementById('jenis_pembina');
    const pembinaSelect = document.getElementById('pembina_id');
    const hint = document.getElementById('pembinaHint');
    const selectedJenis = jenisSelect.value;

    pembinaSelect.innerHTML = '';

    if (!selectedJenis) {
        pembinaSelect.disabled = true;
        pembinaSelect.classList.add('bg-slate-100');
        pembinaSelect.classList.remove('bg-slate-50');
        pembinaSelect.innerHTML = '<option value="">-- Pilih Jenis Pembina Terlebih Dahulu --</option>';
        hint.textContent = 'Pilih jenis pembina di atas untuk melihat akun yang tersedia.';
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
        return;
    }

    pembinaSelect.disabled = false;
    pembinaSelect.classList.remove('bg-slate-100');
    pembinaSelect.classList.add('bg-slate-50');
    
    let defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- Pilih Akun ' + (selectedJenis === 'pusat' ? 'Pusat' : (selectedJenis === 'cabang' ? 'Cabang' : 'Manager Mitra')) + ' --';
    pembinaSelect.appendChild(defaultOpt);

    accounts.forEach(acc => {
        let opt = document.createElement('option');
        opt.value = acc.id;

        if (selectedJenis === 'pusat') {
            opt.textContent = `${acc.nama_lengkap} (Kantor Pusat - ${acc.email})`;
        } else if (selectedJenis === 'cabang') {
            opt.textContent = `${acc.nama_lengkap} (Cabang ${acc.domisili || ''}) - ${acc.email}`;
        } else if (selectedJenis === 'manager_mitra') {
            const indukRoleFmt = (acc.role_induk === 'pusat') ? 'Pusat' : 'Cabang';
            opt.textContent = `${acc.nama_lengkap} (Induk: ${acc.nama_induk} - ${indukRoleFmt})`;
        }

        pembinaSelect.appendChild(opt);
    });

    if (selectedJenis === 'manager_mitra') {
        hint.textContent = 'Setiap Manager Mitra menampilkan informasi induknya (Pusat atau Cabang).';
    } else {
        hint.textContent = 'Pilih akun yang akan menjadi pembina langsung bagi Mitra pendaftar.';
    }
}

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
</script>
<?= $this->endSection() ?>
