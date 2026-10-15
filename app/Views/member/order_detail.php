<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-6xl mx-auto space-y-6">
                
                <!-- Back Link & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <a href="<?= base_url('/app/orders') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#5e0078] transition mb-2">
                            <i class="fas fa-arrow-left text-[11px]"></i> Kembali ke Daftar Pesanan
                        </a>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pesanan <?= esc($order['no_order']) ?></h1>
                            <span class="px-3 py-1 rounded-full text-xs font-bold font-mono uppercase tracking-wider
                                <?php
                                $statusOrder = $order['status'];
                                echo match($statusOrder) {
                                    'menunggu_pembayaran' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                    'dp_diterima' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                    'diproses' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                    'selesai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                    'dibatalkan' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                    default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                };
                                ?>">
                                <?= ucfirst(str_replace('_', ' ', $statusOrder)) ?>
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                <?php
                                $statusPay = $order['status_pembayaran'] ?? 'menunggu_pembayaran';
                                echo match($statusPay) {
                                    'lunas' => 'bg-emerald-500 text-white shadow-2xs',
                                    'dp_diterima' => 'bg-sky-500 text-white shadow-2xs',
                                    default => 'bg-amber-500 text-white shadow-2xs'
                                };
                                ?>">
                                <i class="fas <?= $statusPay === 'lunas' ? 'fa-check-circle' : 'fa-clock' ?> mr-1 text-[10px]"></i>
                                <?= $statusPay === 'lunas' ? 'Lunas Penuh' : ($statusPay === 'dp_diterima' ? 'DP Terbayar' : 'Menunggu Pembayaran') ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Dibuat pada <?= date('d M Y, H:i', strtotime($order['created_at'])) ?> WIB</p>
                    </div>

                    <!-- Action: Upload Payment Proof Button -->
                    <?php if (!in_array($order['status_pembayaran'] ?? '', ['lunas']) && !in_array($order['status'], ['selesai', 'dibatalkan'])): ?>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openPaymentModal()" 
                                    class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                                <i class="fas fa-receipt text-xs"></i>
                                <span>Unggah Bukti Transfer</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 4 Financial Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Tagihan Paket</span>
                        <span class="text-lg sm:text-xl font-extrabold text-slate-900">
                            Rp <?= number_format($order['total'], 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-1">Biaya keseluruhan paket</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Minimal DP (Uang Muka)</span>
                        <span class="text-lg sm:text-xl font-extrabold text-amber-600">
                            Rp <?= number_format($order['nominal_dp_minimal'] ?? 0, 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-1">Ketentuan batas awal kunci seat</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Diverifikasi</span>
                        <span class="text-lg sm:text-xl font-extrabold text-emerald-600">
                            Rp <?= number_format($order['total_dibayar'] ?? 0, 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] text-emerald-600 font-semibold block mt-1">Dana masuk disetujui</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Sisa Tagihan Pelunasan</span>
                        <span class="text-lg sm:text-xl font-extrabold <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'text-rose-600' : 'text-slate-900' ?>">
                            Rp <?= number_format($order['sisa_tagihan'] ?? $order['total'], 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'text-rose-500 font-medium' : 'text-slate-400' ?> block mt-1">
                            <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'Wajib dilunasi sebelum jadwal' : 'Tagihan lunas penuh' ?>
                        </span>
                    </div>
                </div>

                <!-- Paket Yang Dipesan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center text-xs">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h2 class="text-sm font-bold text-slate-900">Rincian Paket Jamaah</h2>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">Nama Paket</th>
                                    <th class="px-5 py-3.5">Durasi / Jadwal</th>
                                    <th class="px-5 py-3.5 text-center">Jumlah Pax</th>
                                    <th class="px-5 py-3.5 text-right">Harga Satuan</th>
                                    <th class="px-5 py-3.5 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (!empty($order['items'])): ?>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-5 py-4 font-bold text-slate-900">
                                                <?= esc($item['package_name'] ?? 'Paket Umroh') ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?= esc($item['program_durasi'] ?? '-') ?>
                                                <?php if (!empty($item['tanggal_keberangkatan'])): ?>
                                                    <span class="block text-[11px] text-slate-400">Keberangkatan: <?= date('d M Y', strtotime($item['tanggal_keberangkatan'])) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4 text-center font-bold text-slate-800">
                                                <?= (int)$item['qty'] ?> Jamaah
                                            </td>
                                            <td class="px-5 py-4 text-right text-slate-700">
                                                Rp <?= number_format($item['harga_snapshot'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-right font-extrabold text-[#5e0078]">
                                                Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-5 py-6 text-center text-slate-400 text-xs">Tidak ada data item paket.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($order['catatan'])): ?>
                        <div class="p-4 bg-slate-50 border-t border-slate-100 text-xs">
                            <span class="font-bold text-slate-700 block mb-1">Catatan Pemesanan / Nama Jamaah:</span>
                            <p class="text-slate-600 whitespace-pre-line leading-relaxed"><?= esc($order['catatan']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Payment Instructions Card (Rekening Pembayaran Resmi) -->
                <div class="bg-linear-to-r from-purple-50/80 to-slate-50 rounded-2xl border border-purple-100 p-5 sm:p-6 shadow-xs">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#5e0078] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <i class="fas fa-university text-base"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-slate-900">Rekening Resmi Pembayaran Amanah Safar</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Mohon lakukan transfer hanya ke rekening resmi berikut. Simpan struk/bukti transfer untuk diunggah.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                <div class="bg-white p-3.5 rounded-xl border border-purple-100/80 shadow-2xs flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Bank Syariah Indonesia (BSI)</span>
                                        <span class="font-mono font-extrabold text-slate-900 text-sm">719 882 9901</span>
                                        <span class="text-[11px] text-slate-400 block">a.n. PT Amanah Safar Wisata</span>
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText('7198829901'); alert('Nomor rekening BSI berhasil disalin!');" 
                                            class="text-xs px-2.5 py-1.5 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-semibold transition" title="Salin Rekening">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>

                                <div class="bg-white p-3.5 rounded-xl border border-purple-100/80 shadow-2xs flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">Bank Central Asia (BCA)</span>
                                        <span class="font-mono font-extrabold text-slate-900 text-sm">822 091 4321</span>
                                        <span class="text-[11px] text-slate-400 block">a.n. PT Amanah Safar Wisata</span>
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText('8220914321'); alert('Nomor rekening BCA berhasil disalin!');" 
                                            class="text-xs px-2.5 py-1.5 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-semibold transition" title="Salin Rekening">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran (Multi-stage DP & Pelunasan) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Riwayat Pembayaran & Bukti Transfer</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Catatan seluruh transaksi uang muka (DP) dan pelunasan yang telah diunggah.</p>
                        </div>
                        <?php if (!in_array($order['status_pembayaran'] ?? '', ['lunas']) && !in_array($order['status'], ['selesai', 'dibatalkan'])): ?>
                            <button type="button" onclick="openPaymentModal()" 
                                    class="text-xs font-bold text-[#5e0078] hover:underline flex items-center gap-1.5">
                                <i class="fas fa-plus-circle"></i> Tambah Bukti Transfer
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">No. Transaksi</th>
                                    <th class="px-5 py-3.5">Tanggal</th>
                                    <th class="px-5 py-3.5">Tahap Pembayaran</th>
                                    <th class="px-5 py-3.5 text-right">Nominal</th>
                                    <th class="px-5 py-3.5 text-center">Bukti Transfer</th>
                                    <th class="px-5 py-3.5 text-center">Status Verifikasi</th>
                                    <th class="px-5 py-3.5">Catatan Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (!empty($payments)): ?>
                                    <?php foreach ($payments as $pay): ?>
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-800 text-xs">
                                                <?= esc($pay['no_transaksi']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?= date('d M Y, H:i', strtotime($pay['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider
                                                    <?php
                                                    echo match($pay['tipe_pembayaran']) {
                                                        'dp' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'pelunasan' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                                        'full' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        default => 'bg-slate-100 text-slate-700'
                                                    };
                                                    ?>">
                                                    <?= strtoupper($pay['tipe_pembayaran']) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right font-bold text-slate-900">
                                                Rp <?= number_format($pay['nominal'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <?php if (!empty($pay['bukti_transfer'])): ?>
                                                    <a href="<?= base_url(esc($pay['bukti_transfer'])) ?>" target="_blank" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold text-xs transition">
                                                        <i class="fas fa-image text-[10px]"></i> Lihat Bukti
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-slate-400 text-xs">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($pay['status']) {
                                                        'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'diterima' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'ditolak' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                                    };
                                                    ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $pay['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?= esc($pay['catatan_admin'] ?: '-') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-receipt text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada bukti pembayaran diunggah untuk pesanan ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Unggah Bukti Pembayaran -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closePaymentModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-xs"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Unggah Bukti Transfer</h3>
                </div>
                <button type="button" onclick="closePaymentModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form method="POST" action="<?= base_url('/app/orders/' . $order['id'] . '/pay') ?>" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                <?= csrf_field() ?>

                <!-- Pilihan Tahap Pembayaran (DP / Pelunasan / Bayar Penuh) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tahap Pembayaran <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <?php 
                        $totalDibayarSoFar = (float)($order['total_dibayar'] ?? 0);
                        $sisaTagihanSekarang = (float)($order['sisa_tagihan'] ?? $order['total']);
                        $isDpMode = ($totalDibayarSoFar == 0);
                        ?>
                        <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center text-center transition hover:border-[#5e0078] <?= $isDpMode ? 'border-[#5e0078] bg-purple-50/50' : 'border-slate-200' ?>">
                            <input type="radio" name="tipe_pembayaran" value="dp" <?= $isDpMode ? 'checked' : '' ?> onchange="onPaymentTypeChange()" class="text-[#5e0078] focus:ring-[#5e0078]">
                            <span class="text-xs font-bold text-slate-800 mt-1">Uang Muka (DP)</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Min Rp <?= number_format($order['nominal_dp_minimal'] ?? 0, 0, ',', '.') ?></span>
                        </label>

                        <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center text-center transition hover:border-[#5e0078] <?= !$isDpMode ? 'border-[#5e0078] bg-purple-50/50' : 'border-slate-200' ?>">
                            <input type="radio" name="tipe_pembayaran" value="pelunasan" <?= !$isDpMode ? 'checked' : '' ?> onchange="onPaymentTypeChange()" class="text-[#5e0078] focus:ring-[#5e0078]">
                            <span class="text-xs font-bold text-slate-800 mt-1">Pelunasan</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Sisa Tagihan</span>
                        </label>

                        <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center text-center transition hover:border-[#5e0078] border-slate-200">
                            <input type="radio" name="tipe_pembayaran" value="full" onchange="onPaymentTypeChange()" class="text-[#5e0078] focus:ring-[#5e0078]">
                            <span class="text-xs font-bold text-slate-800 mt-1">Bayar Penuh</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">100% Lunas</span>
                        </label>
                    </div>
                </div>

                <!-- Input Nominal Transfer -->
                <div>
                    <label for="nominalInput" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nominal Transfer (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" id="nominalInput" name="nominal" required min="1" 
                               value="<?= $isDpMode ? (int)($order['nominal_dp_minimal'] ?? 0) : (int)$sisaTagihanSekarang ?>"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                    </div>
                    <span id="nominalHelperText" class="text-[10px] text-slate-400 mt-1 block">
                        <?= $isDpMode ? ('Minimal transfer DP adalah Rp ' . number_format($order['nominal_dp_minimal'] ?? 0, 0, ',', '.')) : ('Sisa tagihan: Rp ' . number_format($sisaTagihanSekarang, 0, ',', '.')) ?>
                    </span>
                </div>

                <!-- Rincian Rekening Pengirim -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="bankPengirim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Bank Pengirim <span class="text-rose-500">*</span></label>
                        <input type="text" id="bankPengirim" name="nama_bank_pengirim" required placeholder="Contoh: BCA / BSI / Mandiri"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                    </div>
                    <div>
                        <label for="rekPengirim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">No. Rekening Pengirim <span class="text-rose-500">*</span></label>
                        <input type="text" id="rekPengirim" name="nomor_rekening_pengirim" required placeholder="Nomor rekening Anda"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                    </div>
                </div>

                <div>
                    <label for="namaPengirim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Atas Nama Pengirim <span class="text-rose-500">*</span></label>
                    <input type="text" id="namaPengirim" name="nama_pengirim" required placeholder="Nama pemilik rekening pentransfer"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition">
                </div>

                <!-- Unggah Berkas Bukti Transfer -->
                <div>
                    <label for="buktiFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Berkas Bukti Transfer (JPG, PNG, PDF max 5MB) <span class="text-rose-500">*</span></label>
                    <input type="file" id="buktiFile" name="bukti_transfer" required accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#5e0078] hover:file:bg-purple-100 transition">
                </div>

                <div>
                    <label for="catatanBayar" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                    <textarea id="catatanBayar" name="catatan" rows="2" placeholder="Catatan atau nomor referensi bank..."
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closePaymentModal()" class="btn-interactive flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-interactive flex-1 bg-[#5e0078] hover:bg-[#4c0062] text-white py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const minDp = <?= (float)($order['nominal_dp_minimal'] ?? 0) ?>;
const sisaTagihan = <?= (float)($order['sisa_tagihan'] ?? $order['total']) ?>;
const totalOrder = <?= (float)$order['total'] ?>;

function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

function onPaymentTypeChange() {
    const selected = document.querySelector('input[name="tipe_pembayaran"]:checked').value;
    const input = document.getElementById('nominalInput');
    const helper = document.getElementById('nominalHelperText');

    if (selected === 'dp') {
        input.value = Math.round(minDp);
        helper.textContent = 'Minimal transfer DP adalah Rp ' + new Intl.NumberFormat('id-ID').format(minDp);
    } else if (selected === 'pelunasan') {
        input.value = Math.round(sisaTagihan);
        helper.textContent = 'Sisa tagihan yang harus dilunasi adalah Rp ' + new Intl.NumberFormat('id-ID').format(sisaTagihan);
    } else if (selected === 'full') {
        input.value = Math.round(totalOrder);
        helper.textContent = 'Total tagihan pembayaran penuh Rp ' + new Intl.NumberFormat('id-ID').format(totalOrder);
    }
}
</script>

<?= $this->endSection() ?>
