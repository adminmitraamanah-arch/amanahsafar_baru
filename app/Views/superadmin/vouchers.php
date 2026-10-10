<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span>&gt;</span>
                            <span class="text-slate-600 font-semibold">Vouchers</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Voucher</h1>
                    </div>
                    <button type="button" onclick="document.getElementById('generateModal').classList.remove('hidden')" 
                            class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Generate Voucher</span>
                    </button>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Daftar Voucher Registrasi</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Voucher khusus untuk pendaftaran akun mitra dan hierarki jaringan.</p>
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
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5">Dibuat Pada</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($vouchers)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Belum ada voucher yang dibuat.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($vouchers as $voucher): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-900 text-xs sm:text-sm">
                                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200/70"><?= esc($voucher['kode']) ?></span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    <?= ucfirst(str_replace('_', ' ', $voucher['role_tujuan'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <?php 
                                                    $statusBadge = match($voucher['status']) {
                                                        'tersedia' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Tersedia'],
                                                        'terpakai' => ['class' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Terpakai'],
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
                                                <?= date('d M Y, H:i', strtotime($voucher['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <?php if ($voucher['status'] === 'tersedia'): ?>
                                                    <form method="POST" action="<?= base_url('/superadmin/vouchers/disable/' . $voucher['id']) ?>" class="inline-block" onsubmit="return confirm('Nonaktifkan voucher ini?')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn-interactive inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                                            <i class="fas fa-ban text-[10px]"></i> Nonaktifkan
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-xs text-slate-400 italic">-</span>
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

<!-- Generate Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-md w-full border border-slate-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Generate Voucher</h2>
                <p class="text-xs text-slate-500 mt-0.5">Buat voucher pendaftaran baru untuk calon mitra.</p>
            </div>
            <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="<?= base_url('/superadmin/vouchers/generate') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jumlah Voucher</label>
                <input type="number" name="qty" min="1" max="100" value="1" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Role Tujuan</label>
                <select name="role" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="mitra">Mitra</option>
                    <option value="manager_mitra">Manager Mitra</option>
                    <option value="cabang">Cabang</option>
                    <option value="pusat">Pusat</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-plus mr-1.5"></i> Generate Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
