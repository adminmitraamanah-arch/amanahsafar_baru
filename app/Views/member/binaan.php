<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Daftar Binaan</h1>
                
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
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($binaan ?? [] as $b): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $b['nama_lengkap'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $b['email'] ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $b['no_wa'] ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                                <?= ucfirst(str_replace('_', ' ', $b['role'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?= $b['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                                <?= ucfirst($b['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="#" class="text-blue-600 hover:underline">Lihat</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($binaan)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-users text-4xl opacity-30 mb-2"></i>
                                            <p>Belum ada binaan</p>
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
<?= $this->endSection() ?>
