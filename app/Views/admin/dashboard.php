<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Paket</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">0</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-[#5e0078] transition-all duration-300 group-hover:scale-110 group-hover:bg-[#5e0078] group-hover:text-white">
                                <i class="fas fa-box text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-interactive group bg-white rounded-2xl shadow-sm border border-purple-100/60 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Tugas</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">0</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                                <i class="fas fa-tasks text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
