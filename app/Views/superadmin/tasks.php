<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span>&gt;</span>
                            <span class="text-slate-600 font-semibold">Tasks</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Tugas</h1>
                    </div>
                    <button type="button" onclick="document.getElementById('taskModal').classList.remove('hidden')" 
                            class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Tugas Baru</span>
                    </button>
                </div>

                <!-- Task List Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if (empty($tasks)): ?>
                        <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs">
                            <i class="fas fa-clipboard-check text-3xl mb-2 text-slate-300"></i>
                            <p>Belum ada tugas yang dibagikan.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($tasks as $task): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 flex flex-col justify-between hover:shadow-md transition">
                                <div>
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <h3 class="text-base font-bold text-slate-900 leading-snug"><?= esc($task['judul']) ?></h3>
                                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-[#5e0078] border border-purple-200/80 shrink-0">
                                            <?= ucfirst(str_replace('_', ' ', $task['target_role'] ?? 'Semua')) ?>
                                        </span>
                                    </div>
                                    <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed"><?= esc($task['deskripsi'] ?? '') ?></p>
                                </div>
                                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-calendar-alt text-[#5e0078]"></i>
                                        <span>Tenggat: <?= $task['tenggat'] ? date('d M Y, H:i', strtotime($task['tenggat'])) : 'Tanpa batas' ?></span>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Task Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-md w-full border border-slate-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Buat Tugas Baru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Berikan tugas terarah untuk jaringan mitra.</p>
            </div>
            <button type="button" onclick="document.getElementById('taskModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="<?= base_url('/superadmin/tasks/store') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Judul Tugas</label>
                <input type="text" name="judul" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Deskripsi &amp; Instruksi</label>
                <textarea name="deskripsi" rows="3" 
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tenggat Waktu</label>
                <input type="datetime-local" name="tenggat" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Target Role</label>
                <select name="target_role" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="mitra">Mitra</option>
                    <option value="manager_mitra">Manager Mitra</option>
                    <option value="cabang">Cabang</option>
                    <option value="pusat">Pusat</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('taskModal').classList.add('hidden')" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-check mr-1.5"></i> Simpan Tugas
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
