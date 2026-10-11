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
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Paket</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            <?php if ($userRole === 'mitra'): ?>
                                Pilih dan pesan paket perjalanan umroh & haji resmi untuk jamaah Anda.
                            <?php elseif ($userRole === 'manager_mitra'): ?>
                                Katalog paket acuan untuk pembinaan jaringan kemitraan Anda.
                            <?php else: ?>
                                Katalog paket perjalanan umroh & haji resmi Amanah Safar.
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Role Badge Indicator -->
                    <div class="flex items-center gap-2">
                        <?php if ($userRole === 'mitra'): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-[#5e0078] border border-purple-200 text-xs font-bold">
                                <i class="fas fa-shopping-bag text-xs"></i> Mode Pemesanan Mitra
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold">
                                <i class="fas fa-book-open text-xs"></i> Mode Referensi (Read-Only)
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Package Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($packages)): ?>
                        <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs">
                            <i class="fas fa-box-open text-3xl mb-2 text-slate-300"></i>
                            <p>Saat ini belum ada paket aktif yang tersedia dalam katalog.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($packages as $pkg): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition duration-200 group">
                                <?php if (!empty($pkg['gambar'])): ?>
                                    <div class="h-48 w-full overflow-hidden bg-slate-100 relative">
                                        <img src="<?= base_url('uploads/packages/' . esc($pkg['gambar'])) ?>" alt="<?= esc($pkg['nama']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2.5 py-1 rounded-lg bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                                <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="h-48 w-full bg-[#5e0078] flex flex-col items-center justify-center text-white relative">
                                        <i class="fas fa-kaaba text-4xl opacity-80 mb-1"></i>
                                        <span class="text-xs font-semibold opacity-90 tracking-wider uppercase"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                    <div>
                                        <span class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1"><?= esc($pkg['kategori'] ?? 'Umroh') ?></span>
                                        <h3 class="text-base font-bold text-slate-900 mb-1.5 leading-snug group-hover:text-[#5e0078] transition-colors"><?= esc($pkg['nama']) ?></h3>
                                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed"><?= esc($pkg['deskripsi'] ?? 'Paket perjalanan resmi amanah safar dengan akomodasi dan bimbingan ibadah lengkap.') ?></p>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                        <div>
                                            <span class="text-[10px] text-slate-400 block font-medium">Mulai dari</span>
                                            <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format($pkg['harga'], 0, ',', '.') ?></span>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Tersedia
                                        </span>
                                    </div>

                                    <div class="flex gap-2 pt-1">
                                        <!-- Detail Button (All Roles) -->
                                        <button type="button" 
                                                onclick="openDetailModal(<?= htmlspecialchars(json_encode([
                                                    'id' => $pkg['id'],
                                                    'nama' => $pkg['nama'],
                                                    'kategori' => $pkg['kategori'] ?? 'Umroh',
                                                    'harga' => $pkg['harga'],
                                                    'harga_fmt' => 'Rp ' . number_format($pkg['harga'], 0, ',', '.'),
                                                    'deskripsi' => $pkg['deskripsi'] ?? '',
                                                    'gambar' => !empty($pkg['gambar']) ? base_url('uploads/packages/' . esc($pkg['gambar'])) : null
                                                ]), ENT_QUOTES, 'UTF-8') ?>)"
                                                class="btn-interactive flex-1 text-center bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-3 py-2.5 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                        </button>

                                        <!-- Order Button (Only for Mitra) -->
                                        <?php if ($userRole === 'mitra'): ?>
                                            <button type="button" 
                                                    onclick="openOrderModal(<?= htmlspecialchars(json_encode([
                                                        'id' => $pkg['id'],
                                                        'nama' => $pkg['nama'],
                                                        'harga' => (float)$pkg['harga'],
                                                        'harga_fmt' => 'Rp ' . number_format($pkg['harga'], 0, ',', '.')
                                                    ]), ENT_QUOTES, 'UTF-8') ?>)"
                                                    class="btn-interactive flex-1 text-center bg-[#5e0078] hover:bg-[#4c0062] text-white px-3 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                                                <i class="fas fa-shopping-cart mr-1"></i> Pesan
                                            </button>
                                        <?php else: ?>
                                            <span class="flex-1 inline-flex items-center justify-center text-center bg-slate-100 text-slate-400 px-3 py-2.5 rounded-xl text-xs font-semibold cursor-not-allowed border border-slate-200" title="Pemesanan paket hanya dapat dilakukan oleh akun Mitra">
                                                <i class="fas fa-lock mr-1 text-[10px]"></i> Khusus Mitra
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Detail Paket (Untuk Semua Role) -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div id="detailImageContainer" class="h-48 w-full bg-[#5e0078] flex items-center justify-center text-white relative">
                <i class="fas fa-kaaba text-5xl opacity-80"></i>
                <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white hover:bg-black/60 flex items-center justify-center transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <span id="detailKategori" class="text-[11px] font-bold text-[#5e0078] uppercase tracking-wider block mb-1"></span>
                    <h3 id="detailNama" class="text-xl font-extrabold text-slate-900"></h3>
                </div>
                
                <div class="p-3.5 bg-purple-50/60 rounded-xl border border-purple-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-600">Biaya Paket:</span>
                    <span id="detailHarga" class="text-lg font-extrabold text-[#5e0078]"></span>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Deskripsi & Fasilitas:</h4>
                    <div id="detailDeskripsi" class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-200/80 max-h-48 overflow-y-auto"></div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button" onclick="closeDetailModal()" class="btn-interactive px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Pemesanan Paket (Hanya untuk Mitra) -->
<?php if ($userRole === 'mitra'): ?>
<div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeOrderModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#5e0078] flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-xs"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Pemesanan Paket</h3>
                </div>
                <button type="button" onclick="closeOrderModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form method="POST" action="<?= base_url('/app/packages/order') ?>" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" id="orderPackageId" name="package_id" value="">

                <div>
                    <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Paket yang Dipilih</span>
                    <h4 id="orderPackageNama" class="text-sm font-bold text-slate-900"></h4>
                    <p id="orderPackageHargaFmt" class="text-xs font-semibold text-[#5e0078] mt-0.5"></p>
                </div>

                <div>
                    <label for="orderQty" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Jamaah / Paket <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="changeQty(-1)" class="w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center font-bold text-slate-600 transition">-</button>
                        <input type="number" id="orderQty" name="qty" value="1" min="1" max="100" required oninput="calcTotal()"
                               class="flex-1 text-center py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-[#5e0078] focus:bg-white transition">
                        <button type="button" onclick="changeQty(1)" class="w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center font-bold text-slate-600 transition">+</button>
                    </div>
                </div>

                <div>
                    <label for="orderCatatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan / Nama Jamaah</label>
                    <textarea id="orderCatatan" name="catatan" rows="3" placeholder="Tuliskan nama-nama calon jamaah atau catatan khusus lainnya..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#5e0078]/20 focus:border-[#5e0078] transition"></textarea>
                </div>

                <div class="p-3.5 bg-purple-50/70 rounded-xl border border-purple-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Total Pembayaran:</span>
                    <span id="orderTotalFmt" class="text-base font-extrabold text-[#5e0078]">Rp 0</span>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeOrderModal()" class="btn-interactive flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-interactive flex-1 bg-[#5e0078] hover:bg-[#4c0062] text-white py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                        Konfirmasi Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
let currentPrice = 0;

function openDetailModal(pkg) {
    const modal = document.getElementById('detailModal');
    const imgContainer = document.getElementById('detailImageContainer');
    
    if (pkg.gambar) {
        imgContainer.innerHTML = `
            <img src="${pkg.gambar}" alt="${pkg.nama}" class="w-full h-full object-cover">
            <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white hover:bg-black/60 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
    } else {
        imgContainer.innerHTML = `
            <i class="fas fa-kaaba text-5xl opacity-80"></i>
            <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white hover:bg-black/60 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
    }

    document.getElementById('detailKategori').textContent = pkg.kategori || 'Umroh';
    document.getElementById('detailNama').textContent = pkg.nama || '';
    document.getElementById('detailHarga').textContent = pkg.harga_fmt || '';
    document.getElementById('detailDeskripsi').textContent = pkg.deskripsi || 'Tidak ada deskripsi detail tambahan untuk paket ini.';

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function openOrderModal(pkg) {
    const modal = document.getElementById('orderModal');
    if (!modal) return;

    currentPrice = pkg.harga || 0;
    document.getElementById('orderPackageId').value = pkg.id;
    document.getElementById('orderPackageNama').textContent = pkg.nama;
    document.getElementById('orderPackageHargaFmt').textContent = pkg.harga_fmt + ' / jamaah';
    document.getElementById('orderQty').value = 1;
    document.getElementById('orderCatatan').value = '';

    calcTotal();

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function changeQty(delta) {
    const qtyInput = document.getElementById('orderQty');
    let val = parseInt(qtyInput.value) || 1;
    val = Math.max(1, val + delta);
    qtyInput.value = val;
    calcTotal();
}

function calcTotal() {
    const qtyInput = document.getElementById('orderQty');
    const totalEl = document.getElementById('orderTotalFmt');
    let qty = parseInt(qtyInput.value) || 1;
    if (qty < 1) qty = 1;
    const total = qty * currentPrice;
    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
<?= $this->endSection() ?>
