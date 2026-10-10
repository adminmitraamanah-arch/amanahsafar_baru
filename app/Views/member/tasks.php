<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Tugas</h1>
                
                <div class="space-y-4">
                    <?php foreach ($tasks ?? [] as $task): ?>
                        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900"><?= $task['judul'] ?></h3>
                                    <p class="text-sm text-gray-500 mt-1">Tenggat: <?= $task['tenggat'] ? date('d M Y', strtotime($task['tenggat'])) : '-' ?></p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    Baru
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-4"><?= substr($task['deskripsi'] ?? '', 0, 100) ?>...</p>
                            
                            <a href="#" class="text-blue-600 hover:underline font-medium">Lihat Detail →</a>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($tasks)): ?>
                        <div class="bg-white rounded-lg shadow p-12 text-center">
                            <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada tugas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?= $this->endSection() ?>
