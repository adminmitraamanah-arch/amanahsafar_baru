<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Kelola Pengguna</h1>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">WhatsApp</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($users ?? [] as $user): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $user['nama_lengkap'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $user['email'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $user['no_wa'] ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                                <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?= $user['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?php 
                                                    $verifColor = match($user['verification_status']) {
                                                        'belum_verifikasi' => 'bg-gray-100 text-gray-800',
                                                        'menunggu_review' => 'bg-yellow-100 text-yellow-800',
                                                        'terverifikasi' => 'bg-green-100 text-green-800',
                                                        'ditolak' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    echo $verifColor;
                                                ?>">
                                                <?= ucfirst(str_replace('_', ' ', $user['verification_status'])) ?>
                                            </span>
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
<?= $this->endSection() ?>
