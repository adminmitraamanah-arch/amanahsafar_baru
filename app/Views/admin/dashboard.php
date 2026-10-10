<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span>&gt;</span>
                            <span class="text-slate-600 font-semibold">Overview</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Admin Dashboard</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('/admin/packages/create') ?>" 
                           class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Tambah Paket</span>
                        </a>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-base transition-transform group-hover:scale-105">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Paket</span>
                            <span class="text-2xl font-extrabold text-slate-900"><?= $total_packages ?? 0 ?></span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base transition-transform group-hover:scale-105">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Tugas</span>
                            <span class="text-2xl font-extrabold text-emerald-600"><?= $total_tasks ?? 0 ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
<?= $this->endSection() ?>
