<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Super Admin Dashboard</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Pengguna</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= $total_users ?? 0 ?></p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-[#5e0078] group-hover:text-white">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Pesanan</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= $total_orders ?? 0 ?></p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                                <i class="fas fa-shopping-cart text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Pengguna Terbaru</h2>
                        <span class="text-xs text-gray-400 font-medium">Data Terkini</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-6 py-3.5 text-left font-bold">Nama</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Email</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Role</th>
                                    <th class="px-6 py-3.5 text-left font-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php foreach ($recent_users ?? [] as $user): ?>
                                    <tr class="table-row-interactive">
                                        <td class="px-6 py-4 font-semibold text-gray-900"><?= esc($user['nama_lengkap']) ?></td>
                                        <td class="px-6 py-4 text-gray-600 text-xs"><?= esc($user['email']) ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 bg-purple-100/70 text-purple-800 rounded-full text-xs font-semibold">
                                                <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                                <?= $user['status'] === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                                <?= ucfirst($user['status']) ?>
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
