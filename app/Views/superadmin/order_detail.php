<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php 
$baseRoute = session('role') === 'admin' ? 'admin' : 'superadmin';
?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-6xl mx-auto space-y-6">
                
                <!-- Back Link & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <a href="<?= base_url('/' . $baseRoute . '/orders') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#5e0078] transition mb-2">
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

                    <!-- Change Order Status Form Dropdown -->
                    <form method="POST" action="<?= base_url('/' . $baseRoute . '/orders/' . $order['id'] . '/status') ?>" class="flex items-center gap-2">
                        <?= csrf_field() ?>
                        <select name="status" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#5e0078]">
                            <option value="menunggu_pembayaran" <?= $order['status'] === 'menunggu_pembayaran' ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                            <option value="dp_diterima" <?= $order['status'] === 'dp_diterima' ? 'selected' : '' ?>>DP Diterima</option>
                            <option value="diproses" <?= $order['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="selesai" <?= $order['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $order['status'] === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn-interactive px-3.5 py-2 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold rounded-xl shadow-xs transition">
                            Ubah Status
                        </button>
                    </form>
                </div>

                <!-- 4 Financial Stat Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Tagihan</span>
                        <span class="text-lg sm:text-xl font-extrabold text-slate-900">
                            Rp <?= number_format($order['total'], 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-1">Biaya paket keseluruhan</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Minimal DP</span>
                        <span class="text-lg sm:text-xl font-extrabold text-amber-600">
                            Rp <?= number_format($order['nominal_dp_minimal'] ?? 0, 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-1">Kunci kuota awal</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Diverifikasi</span>
                        <span class="text-lg sm:text-xl font-extrabold text-emerald-600">
                            Rp <?= number_format($order['total_dibayar'] ?? 0, 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] text-emerald-600 font-semibold block mt-1">Dana sah masuk mutasi</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Sisa Tagihan</span>
                        <span class="text-lg sm:text-xl font-extrabold <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'text-rose-600' : 'text-slate-900' ?>">
                            Rp <?= number_format($order['sisa_tagihan'] ?? $order['total'], 0, ',', '.') ?>
                        </span>
                        <span class="text-[10px] <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'text-rose-500 font-medium' : 'text-slate-400' ?> block mt-1">
                            <?= ($order['sisa_tagihan'] ?? $order['total']) > 0 ? 'Belum dilunasi' : 'Lunas penuh' ?>
                        </span>
                    </div>
                </div>

                <!-- Info Pemesan & Item Paket Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Buyer Profile Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center font-bold text-xs">
                                <i class="fas fa-user"></i>
                            </div>
                            <h2 class="text-sm font-bold text-slate-900">Identitas Pemesan</h2>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div>
                                <span class="text-slate-400 block mb-0.5">Nama Lengkap:</span>
                                <span class="font-bold text-slate-900 text-sm"><?= esc($buyer['nama_lengkap'] ?? '-') ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block mb-0.5">Peran / Role:</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-[#5e0078] border border-purple-100">
                                    <?= esc(str_replace('_', ' ', $buyer['role'] ?? '-')) ?>
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-400 block mb-0.5">Email:</span>
                                <span class="font-mono text-slate-700"><?= esc($buyer['email'] ?? '-') ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block mb-0.5">No. WhatsApp / HP:</span>
                                <?php if (!empty($buyer['no_wa'])): ?>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $buyer['no_wa']) ?>" target="_blank" class="font-semibold text-emerald-600 hover:underline flex items-center gap-1">
                                        <i class="fab fa-whatsapp"></i> <?= esc($buyer['no_wa']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Paket Items Card -->
                    <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center text-xs">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <h2 class="text-sm font-bold text-slate-900">Paket Yang Dipesan</h2>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                            <th class="px-5 py-3.5">Nama Paket</th>
                                            <th class="px-5 py-3.5 text-center">Pax</th>
                                            <th class="px-5 py-3.5 text-right">Harga</th>
                                            <th class="px-5 py-3.5 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                        <?php if (!empty($order['items'])): ?>
                                            <?php foreach ($order['items'] as $it): ?>
                                                <tr>
                                                    <td class="px-5 py-4 font-bold text-slate-900">
                                                        <?= esc($it['package_name'] ?? 'Paket') ?>
                                                        <?php if (!empty($it['program_durasi'])): ?>
                                                            <span class="block text-[11px] text-slate-400 font-normal"><?= esc($it['program_durasi']) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-5 py-4 text-center font-bold text-slate-800">
                                                        <?= (int)$it['qty'] ?> Pax
                                                    </td>
                                                    <td class="px-5 py-4 text-right text-slate-600">
                                                        Rp <?= number_format($it['harga_snapshot'], 0, ',', '.') ?>
                                                    </td>
                                                    <td class="px-5 py-4 text-right font-extrabold text-[#5e0078]">
                                                        Rp <?= number_format($it['subtotal'], 0, ',', '.') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if (!empty($order['catatan'])): ?>
                            <div class="p-4 bg-slate-50 border-t border-slate-100 text-xs">
                                <span class="font-bold text-slate-700 block mb-0.5">Catatan / Nama Jamaah:</span>
                                <p class="text-slate-600 whitespace-pre-line"><?= esc($order['catatan']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Riwayat Pembayaran & Verifikasi Bukti Transfer -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Verifikasi Pembayaran & Bukti Transfer</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Setujui atau tolak bukti transfer uang muka (DP) dan pelunasan dari mitra.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">No. Transaksi</th>
                                    <th class="px-5 py-3.5">Tanggal</th>
                                    <th class="px-5 py-3.5">Tahap</th>
                                    <th class="px-5 py-3.5 text-right">Nominal</th>
                                    <th class="px-5 py-3.5">Bank Pengirim</th>
                                    <th class="px-5 py-3.5 text-center">Bukti Transfer</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (!empty($payments)): ?>
                                    <?php foreach ($payments as $p): ?>
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-5 py-4 font-mono font-bold text-slate-800 text-xs">
                                                <?= esc($p['no_transaksi']) ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600 whitespace-nowrap">
                                                <?= date('d M Y, H:i', strtotime($p['created_at'])) ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider
                                                    <?php
                                                    echo match($p['tipe_pembayaran']) {
                                                        'dp' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'pelunasan' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                                        'full' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        default => 'bg-slate-100 text-slate-700'
                                                    };
                                                    ?>">
                                                    <?= strtoupper($p['tipe_pembayaran']) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right font-extrabold text-slate-900 whitespace-nowrap">
                                                Rp <?= number_format($p['nominal'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <div class="font-bold text-slate-800"><?= esc($p['nama_bank_pengirim'] ?: '-') ?></div>
                                                <div class="text-[11px] text-slate-400"><?= esc($p['nomor_rekening_pengirim'] ?: '') ?> (<?= esc($p['nama_pengirim'] ?: '-') ?>)</div>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <?php if (!empty($p['bukti_transfer'])): ?>
                                                    <a href="<?= base_url(esc($p['bukti_transfer'])) ?>" target="_blank" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold text-xs transition">
                                                        <i class="fas fa-file-image text-[11px]"></i> Lihat Struk
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-slate-400 text-xs">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($p['status']) {
                                                        'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'diterima' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'ditolak' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700'
                                                    };
                                                    ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $p['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                                <button type="button" onclick="openVerifyModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" 
                                                        class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] hover:bg-purple-100 font-bold text-xs border border-purple-200 transition">
                                                    <i class="fas fa-check-double text-[10px]"></i>
                                                    <span>Verifikasi</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            <i class="fas fa-money-check text-3xl mb-2 text-slate-300 block"></i>
                                            Belum ada bukti pembayaran yang diunggah untuk pesanan ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Distribusi Komisi Hierarki (Commission Breakdown Matrix) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Distribusi Komisi Jaringan Hierarki</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Rincian alokasi komisi untuk Mitra, Manager, Cabang, dan Kantor Pusat sesuai matriks bisnis.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">Penerima Komisi</th>
                                    <th class="px-5 py-3.5 text-center">Peran</th>
                                    <th class="px-5 py-3.5">Jenis Hak Komisi</th>
                                    <th class="px-5 py-3.5 text-right">Nominal Komisi</th>
                                    <th class="px-5 py-3.5 text-center">Status Komisi</th>
                                    <th class="px-5 py-3.5">Keterangan Mesin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (!empty($commissions)): ?>
                                    <?php 
                                    $sumKomisi = 0;
                                    foreach ($commissions as $comm): 
                                        $sumKomisi += (float)$comm['nominal_snapshot'];
                                    ?>
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-5 py-4 font-bold text-slate-900">
                                                <?= esc($comm['nama_penerima'] ?? ('User #' . $comm['user_id'])) ?>
                                                <span class="block text-[11px] text-slate-400 font-normal"><?= esc($comm['email_penerima'] ?? '') ?></span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-[#5e0078] border border-purple-100">
                                                    <?= esc(str_replace('_', ' ', $comm['role_penerima'] ?: 'mitra')) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs font-semibold text-slate-700">
                                                <?= esc(ucwords(str_replace('_', ' ', $comm['jenis_komisi']))) ?>
                                            </td>
                                            <td class="px-5 py-4 text-right font-extrabold text-sm text-[#5e0078]">
                                                Rp <?= number_format($comm['nominal_snapshot'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php
                                                    echo match($comm['status']) {
                                                        'potensi' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                        'siap_cair' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                        'ditarik' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                        'dibatalkan' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                        default => 'bg-slate-100 text-slate-700'
                                                    };
                                                    ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $comm['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?= esc($comm['keterangan']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-purple-50/40 font-bold border-t border-purple-100">
                                        <td colspan="3" class="px-5 py-3.5 text-slate-700">
                                            Total Alokasi Komisi Pesanan:
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-extrabold text-[#5e0078] text-base">
                                            Rp <?= number_format($sumKomisi, 0, ',', '.') ?>
                                        </td>
                                        <td colspan="2" class="px-5 py-3.5 text-xs text-emerald-700 font-semibold">
                                            <i class="fas fa-check-circle"></i> Sesuai Alokasi Pool Paket
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs">
                                            <i class="fas fa-handshake text-3xl mb-2 text-slate-300 block"></i>
                                            Komisi belum di-generate (akan otomatis tercatat sebagai <em>Potensi</em> setelah pembayaran DP disetujui, atau <em>Siap Cair</em> saat Lunas).
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

<!-- Modal Verifikasi Pembayaran -->
<div id="verifyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeVerifyModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Verifikasi Pembayaran</h3>
                </div>
                <button type="button" onclick="closeVerifyModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form id="verifyForm" method="POST" action="" class="p-5 space-y-4">
                <?= csrf_field() ?>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1.5 text-xs border border-slate-200/70">
                    <div class="flex justify-between">
                        <span class="text-slate-400">No. Transaksi:</span>
                        <span id="vNoTrx" class="font-mono font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tahap Pembayaran:</span>
                        <span id="vTipe" class="font-bold text-slate-800 uppercase">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nominal Transfer:</span>
                        <span id="vNominal" class="font-extrabold text-[#5e0078] text-sm">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tindakan Keputusan <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer border border-emerald-500 bg-emerald-50/60 rounded-xl p-3 flex flex-col items-center text-center transition">
                            <input type="radio" name="action" value="approve" checked class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-emerald-800 mt-1">Setujui Pembayaran</span>
                            <span class="text-[10px] text-emerald-600 mt-0.5">Dana Sah Diterima</span>
                        </label>
                        <label class="cursor-pointer border border-rose-200 rounded-xl p-3 flex flex-col items-center text-center transition hover:border-rose-400">
                            <input type="radio" name="action" value="reject" class="text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-bold text-rose-800 mt-1">Tolak Pembayaran</span>
                            <span class="text-[10px] text-rose-500 mt-0.5">Tidak Valid / Palsu</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="catatanAdmin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan Verifikasi</label>
                    <textarea id="catatanAdmin" name="catatan_admin" rows="3" placeholder="Tuliskan catatan verifikasi atau alasan penolakan..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeVerifyModal()" class="btn-interactive flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">
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
function openVerifyModal(pay) {
    const form = document.getElementById('verifyForm');
    const baseRoute = "<?= $baseRoute ?>";
    form.action = "<?= base_url() ?>/" + baseRoute + "/orders/payments/" + pay.id + "/verify";

    document.getElementById('vNoTrx').textContent = pay.no_transaksi;
    document.getElementById('vTipe').textContent = pay.tipe_pembayaran;
    document.getElementById('vNominal').textContent = 'Rp ' + Number(pay.nominal).toLocaleString('id-ID');
    document.getElementById('catatanAdmin').value = pay.catatan_admin || '';

    document.getElementById('verifyModal').classList.remove('hidden');
}

function closeVerifyModal() {
    document.getElementById('verifyModal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
