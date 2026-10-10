<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span>&gt;</span>
                            <span class="text-slate-600 font-semibold">Commissions</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Komisi</h1>
                    </div>
                    <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs">
                        Total Catatan: <strong class="text-slate-900"><?= count($commissions ?? []) ?></strong>
                    </span>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Riwayat Pembagian Komisi</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Catatan snapshot komisi dari transaksi paket yang disetujui.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">Order ID</th>
                                    <th class="px-5 py-3.5">User ID</th>
                                    <th class="px-5 py-3.5">Nominal Komisi</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5 text-right">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($commissions)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Belum ada catatan komisi tersedia.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($commissions as $comm): ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="px-5 py-4 font-mono font-semibold text-slate-900 text-xs">
                                                #<?= esc($comm['order_id']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600 font-medium">
                                                User #<?= esc($comm['user_id']) ?>
                                            </td>
                                            <td class="px-5 py-4 font-bold text-[#5e0078]">
                                                Rp <?= number_format($comm['nominal_snapshot'], 0, ',', '.') ?>
                                            </td>
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
                                            <td class="px-5 py-4 text-xs text-slate-500 text-right">
                                                <?= date('d M Y, H:i', strtotime($comm['created_at'])) ?>
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
<?= $this->endSection() ?>
