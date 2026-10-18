<?php
/**
 * Komponen Bersama: Kartu Dokumen Perjanjian Kerja Sama (PKS)
 * Digunakan pada halaman Profil Saya untuk role jaringan kemitraan (mitra, manager_mitra, cabang, pusat).
 * Role administratif (admin, super_admin, superadmin) tidak memanggil komponen ini.
 */
?>
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-[#5e0078] flex items-center justify-center text-xl shrink-0 shadow-inner">
                <i class="fas fa-file-contract"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Perjanjian Kerja Sama (PKS)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Dokumen legalitas resmi kemitraan Anda dengan PT Amanah Tangguh Mandiri.</p>
            </div>
        </div>
        <div>
            <?php if (!empty($pksInfo['is_accepted'])): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fas fa-check-circle text-xs text-emerald-500"></i> Disetujui &amp; Aktif
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    <i class="fas fa-clock text-xs text-amber-500"></i> Belum Disetujui
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="p-6 sm:p-8 space-y-6">
        <?php if (!empty($pksInfo['is_accepted'])): ?>
            <!-- Detail Metadata PKS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50/80 rounded-xl border border-slate-200/70">
                <div>
                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Nomor Perjanjian (PKS)
                    </span>
                    <div class="text-xs font-mono font-bold text-[#5e0078] bg-white px-3 py-2 rounded-lg border border-slate-200 flex items-center justify-between">
                        <span><?= esc($pksInfo['pks_number']) ?></span>
                        <i class="fas fa-certificate text-purple-400"></i>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Nomor registrasi unik kemitraan resmi</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Waktu Persetujuan Digital
                    </span>
                    <div class="text-xs font-semibold text-slate-800 bg-white px-3 py-2 rounded-lg border border-slate-200 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-emerald-500"></i>
                        <span><?= format_tanggal_indonesia($pksInfo['accepted_at'], true) ?> WIB</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Tercatat sah pada log sistem Amanah Safar</span>
                </div>
            </div>

            <a href="<?= base_url('/registration/confirmation') ?>" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-semibold text-[#5e0078] underline">
                <i class="fab fa-whatsapp" aria-hidden="true"></i> Konfirmasi Pendaftaran via WhatsApp
            </a>

            <!-- Tindakan Unduh PDF -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="text-xs text-slate-600 flex items-start gap-2 max-w-xl">
                    <i class="fas fa-info-circle text-[#5e0078] mt-0.5 shrink-0"></i>
                    <span>Unduh salinan resmi dokumen PKS dalam format PDF untuk keperluan arsip pribadi atau kelengkapan administrasi kemitraan Anda.</span>
                </div>
                <a href="<?= esc($pksInfo['download_url']) ?>" 
                   class="btn-interactive inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold rounded-xl shadow-sm transition shrink-0">
                    <i class="fas fa-file-pdf text-sm"></i>
                    <span>Download PDF PKS</span>
                </a>
            </div>
        <?php else: ?>
            <!-- Status Belum Disetujui -->
            <div class="p-4 bg-amber-50/70 rounded-xl border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <h3 class="text-xs font-bold text-amber-900">Perjanjian Belum Disetujui</h3>
                        <p class="text-xs text-amber-700 mt-0.5">Anda belum menyelesaikan tahap persetujuan Perjanjian Kerja Sama (PKS). Dokumen PDF akan tersedia setelah Anda menyetujui PKS.</p>
                    </div>
                </div>
                <a href="<?= base_url('/agreement') ?>" 
                   class="btn-interactive inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition shrink-0">
                    <i class="fas fa-external-link-alt text-xs"></i>
                    <span>Buka Halaman PKS</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
