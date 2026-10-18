<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-[#f8fafc] flex items-center justify-center p-4 sm:p-6">
    <section class="w-full max-w-xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6" aria-labelledby="confirmationTitle">
        <div>
            <h1 id="confirmationTitle" class="text-2xl font-bold text-slate-900">Konfirmasi Pendaftaran</h1>
            <p class="text-sm text-slate-600 mt-2">PKS telah disetujui. Silakan lanjutkan konfirmasi pendaftaran melalui WhatsApp.</p>
        </div>
        <?php if (!$confirmationError): ?>
        <p class="text-sm text-slate-700" role="status">
            <?= $clickedAt ? 'Link konfirmasi WhatsApp telah diklik.' : 'Link konfirmasi WhatsApp belum diklik.' ?>
        </p>
        <?php endif; ?>
        <?php if ($confirmationError): ?>
            <p role="alert" class="p-4 rounded-xl bg-rose-50 text-rose-800 text-sm"><?= esc($confirmationError) ?></p>
            <a href="<?= base_url('/registration/confirmation') ?>" class="inline-flex items-center px-4 py-3 text-sm font-semibold text-[#5e0078] underline">Muat ulang dan coba kembali</a>
        <?php else: ?>
            <form method="post" action="<?= base_url('/registration/confirmation') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#5e0078] hover:bg-[#4c0062] text-white text-sm font-bold rounded-xl transition">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                    Konfirmasi Pendaftaran via WhatsApp
                </button>
            </form>
        <?php endif; ?>
        <p class="text-sm text-slate-600">Periksa pesan, lalu tekan Kirim di WhatsApp. Sistem hanya mencatat klik, bukan pengiriman atau penerimaan pesan.</p>
        <p class="text-sm text-slate-600">Gunakan tombol Kembali di browser untuk kembali ke halaman ini. Halaman ini juga dapat dibuka dari Profil Saya.</p>
        <a href="<?= base_url('/dashboard') ?>" class="inline-flex items-center px-4 py-3 text-sm font-semibold text-[#5e0078] underline">Kembali ke Dashboard</a>
    </section>
</main>
<?= $this->endSection() ?>
