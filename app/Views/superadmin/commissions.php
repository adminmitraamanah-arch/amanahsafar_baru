<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Kelola Komisi</h1>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Order ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">User</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nominal</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($commissions ?? [] as $comm): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $comm['order_id'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $comm['user_id'] ?></td>
                                        <td class="px-6 py-4 text-sm font-medium">Rp <?= number_format($comm['nominal_snapshot'], 0, ',', '.') ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?php 
                                                    $statusColor = match($comm['status']) {
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'disetujui' => 'bg-blue-100 text-blue-800',
                                                        'dibayar' => 'bg-green-100 text-green-800',
                                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    echo $statusColor;
                                                ?>">
                                                <?= ucfirst($comm['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($comm['created_at'])) ?></td>
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
<?= $this->endSection() ?>
