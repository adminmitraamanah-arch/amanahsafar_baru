<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Voucher</h1>
                    <button onclick="document.getElementById('generateModal').classList.remove('hidden')" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Generate Voucher
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Kode</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role Tujuan</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Dibuat</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($vouchers ?? [] as $voucher): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $voucher['kode'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= ucfirst(str_replace('_', ' ', $voucher['role_tujuan'])) ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?php 
                                                    $statusColor = match($voucher['status']) {
                                                        'tersedia' => 'bg-green-100 text-green-800',
                                                        'terpakai' => 'bg-blue-100 text-blue-800',
                                                        'dinonaktifkan' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    echo $statusColor;
                                                ?>">
                                                <?= ucfirst($voucher['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($voucher['created_at'])) ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <?php if ($voucher['status'] === 'tersedia'): ?>
                                                <form method="POST" action="<?= base_url('/superadmin/vouchers/disable/' . $voucher['id']) ?>" style="display:inline;">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="text-red-600 hover:underline">Nonaktifkan</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6">Generate Voucher</h2>
        
        <form method="POST" action="<?= base_url('/superadmin/vouchers/generate') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="qty" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Tujuan</label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="mitra">Mitra</option>
                    <option value="manager_mitra">Manager Mitra</option>
                    <option value="cabang">Cabang</option>
                    <option value="pusat">Pusat</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Generate</button>
                <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" 
                    class="flex-1 bg-gray-200 text-gray-900 py-2 rounded-lg hover:bg-gray-300">Batal</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
