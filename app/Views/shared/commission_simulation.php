<?php $commissionDetailMode = !empty($commissionDetailMode); ?>
<section id="commissionSimulation" data-simulation-mode="<?= $commissionDetailMode ? 'detail' : 'form' ?>" class="commission-sim" aria-labelledby="commissionSimulationTitle">
    <div class="commission-sim-heading">
        <div>
            <h3 id="commissionSimulationTitle"><?= $commissionDetailMode ? 'Distribusi Komisi' : 'Simulasi Distribusi Komisi' ?></h3>
            <?php if ($commissionDetailMode): ?><p data-simulation-package class="font-semibold"></p><?php endif; ?>
            <p>Lihat pembagian komisi berdasarkan siapa yang closing dan posisi pembinanya.</p>
        </div>
        <strong data-simulation-pool></strong>
    </div>
    <p class="commission-sim-help"><?= $commissionDetailMode ? 'Distribusi per jemaah berdasarkan konfigurasi paket yang dipilih. Informasi ini tidak membuat transaksi komisi.' : 'Simulasi per jemaah mengikuti draft form dan aturan sistem saat ini. Perubahan baru tersimpan melalui tombol simpan paket.' ?></p>
    <p data-simulation-notice class="commission-sim-notice" role="status" hidden></p>
    <div data-simulation-cards aria-live="polite" aria-atomic="true"></div>
    <p class="commission-sim-help">Bagian Pusat masuk ke akun Pusat aktif yang dipilih sistem; Super Admin menjadi cadangan jika akun Pusat tidak tersedia.</p>
    <noscript>Aktifkan JavaScript untuk melihat simulasi komisi.</noscript>
</section>
<style>
    .commission-sim { border-top: 1px solid #e2e8f0; padding-top: 20px; color: #334155; min-width: 0; }
    .commission-sim-heading { display: flex; flex-wrap: wrap; align-items: start; justify-content: space-between; gap: 12px; }
    .commission-sim-heading h3 { font-size: 15px; font-weight: 700; margin: 0; color: #0f172a; }
    .commission-sim-heading p, .commission-sim-help, .commission-sim-description { font-size: 12px; line-height: 1.6; margin: 6px 0; }
    .commission-sim-heading > strong { background: #faf5ff; color: #5e0078; padding: 8px 12px; border-radius: 8px; font-size: 13px; }
    .commission-sim-help { color: #475569; margin: 12px 0; }
    .commission-sim-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-top: 12px; background: #fff; }
    .commission-sim-card h4 { margin: 0; font-size: 13px; font-weight: 700; color: #0f172a; }
    .commission-sim-variant + .commission-sim-variant { border-top: 1px dashed #cbd5e1; padding-top: 12px; margin-top: 12px; }
    .commission-sim-label { font-size: 12px; font-weight: 600; margin: 10px 0 8px; }
    .commission-sim-recipients { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 180px), 1fr)); gap: 8px; margin: 10px 0; }
    .commission-sim-recipient { border: 1px solid; border-radius: 8px; padding: 12px; min-width: 0; overflow-wrap: anywhere; }
    .commission-sim-recipient dt { font-size: 12px; }
    .commission-sim-recipient dd { margin: 0; }
    .commission-sim-amount { font-size: 16px; font-weight: 700; line-height: 1.8; }
    .commission-sim-source { font-size: 11px; line-height: 1.5; }
    .commission-sim-mitra { color: #065f46; background: #ecfdf5; border-color: #a7f3d0; }
    .commission-sim-manager { color: #1e40af; background: #eff6ff; border-color: #bfdbfe; }
    .commission-sim-cabang { color: #92400e; background: #fffbeb; border-color: #fde68a; }
    .commission-sim-pusat { color: #6b21a8; background: #faf5ff; border-color: #e9d5ff; }
    .commission-sim-validation { display: flex; flex-wrap: wrap; gap: 4px 16px; justify-content: space-between; font-size: 11px; line-height: 1.6; }
    .commission-sim-valid { color: #065f46; }
    .commission-sim-invalid { color: #9f1239; }
    .commission-sim-notice { padding: 12px; border: 1px solid #fcd34d; border-radius: 8px; background: #fffbeb; color: #92400e; font-size: 12px; line-height: 1.6; margin-top: 12px; }
</style>
<?php $commissionScriptVersion = substr(hash_file('sha256', dirname(__DIR__, 3) . '/public/assets/js/commission-simulation.js'), 0, 12); ?>
<script src="<?= base_url('assets/js/commission-simulation.js') ?>?v=<?= $commissionScriptVersion ?>" defer></script>
