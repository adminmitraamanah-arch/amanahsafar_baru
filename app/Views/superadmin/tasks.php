<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col">
        <?= $this->include('layouts/navbar') ?>
        
        <div class="flex-1 overflow-auto p-8">
            <div class="max-w-7xl">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Tugas</h1>
                    <button onclick="document.getElementById('taskModal').classList.remove('hidden')" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Tugas Baru
                    </button>
                </div>

                <div class="space-y-4">
                    <?php foreach ($tasks ?? [] as $task): ?>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900"><?= $task['judul'] ?></h3>
                                    <p class="text-gray-600 text-sm mt-1"><?= substr($task['deskripsi'] ?? '', 0, 100) ?>...</p>
                                    <div class="flex gap-4 mt-3 text-sm text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i><?= $task['tenggat'] ? date('d M Y', strtotime($task['tenggat'])) : 'Tidak ada' ?></span>
                                        <span><i class="fas fa-users mr-1"></i><?= ucfirst(str_replace('_', ' ', $task['target_role'] ?? '-')) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6">Tugas Baru</h2>
        
        <form method="POST" action="<?= base_url('/superadmin/tasks/store') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="judul" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tenggat</label>
                <input type="datetime-local" name="tenggat" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Role</label>
                <select name="target_role" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="mitra">Mitra</option>
                    <option value="manager_mitra">Manager Mitra</option>
                    <option value="cabang">Cabang</option>
                    <option value="pusat">Pusat</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Buat</button>
                <button type="button" onclick="document.getElementById('taskModal').classList.add('hidden')" 
                    class="flex-1 bg-gray-200 text-gray-900 py-2 rounded-lg hover:bg-gray-300">Batal</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
