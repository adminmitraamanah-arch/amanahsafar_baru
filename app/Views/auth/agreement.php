<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <?= $this->include('layouts/navbar') ?>
    
    <div class="max-w-3xl mx-auto px-6 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Perjanjian Pengguna</h1>
            <p class="text-gray-600 mb-8">Harap baca dan setujui perjanjian berikut sebelum melanjutkan</p>

            <div class="bg-gray-50 rounded-lg p-6 mb-8 max-h-64 overflow-y-auto">
                <h3 class="font-bold text-lg mb-4">Syarat dan Ketentuan Amanah Safar</h3>
                <p class="text-sm text-gray-700 space-y-3">
                    <p>1. Setiap anggota jaringan harus memiliki lisensi yang sah dan terdaftar.</p>
                    <p>2. Komisi akan dibayarkan sesuai dengan aturan yang telah ditetapkan oleh perusahaan.</p>
                    <p>3. Anggota harus mematuhi kode etik dan standar profesional yang ditetapkan.</p>
                    <p>4. Data pribadi dan transaksi Anda akan dijaga kerahasiaannya.</p>
                    <p>5. Perusahaan berhak untuk menangguhkan atau membatalkan keanggotaan jika terjadi pelanggaran.</p>
                    <p>6. Semua keputusan perusahaan bersifat final dan tidak dapat digugat.</p>
                </p>
            </div>

            <form method="POST" class="space-y-4">
                <?= csrf_field() ?>
                
                <label class="flex items-start space-x-3">
                    <input type="checkbox" name="agree" required class="mt-1 w-4 h-4 rounded">
                    <span class="text-sm text-gray-600">
                        Saya telah membaca dan menyetujui semua <strong>Syarat dan Ketentuan</strong> yang berlaku
                    </span>
                </label>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Setuju dan Lanjutkan
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
