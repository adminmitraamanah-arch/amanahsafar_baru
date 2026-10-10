<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Action & Breadcrumb Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span>&gt;</span>
                            <span class="text-slate-600 font-semibold">Overview</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Overview Dashboard</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('/superadmin/users') ?>" 
                           class="btn-interactive inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                            <i class="fas fa-users text-xs"></i>
                            <span>Kelola Pengguna</span>
                        </a>
                    </div>
                </div>

                <!-- 6 Stat Cards Row (Modern Minimalist Grid matching screenshot) -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
                    
                    <!-- Card 1: Total Pengguna -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Users</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= number_format($total_users ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 2: Pengguna Aktif -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Active Users</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-emerald-600"><?= number_format($active_users ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 3: Nonaktif -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-user-slash"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Nonaktif</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-rose-600"><?= number_format($inactive_users ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 4: Terverifikasi -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Verified</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-blue-600"><?= number_format($verified_users ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 5: Total Paket -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Paket Aktif</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= number_format($total_packages ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 6: Total Pesanan -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Orders</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= number_format($total_orders ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>

                </div>

                <!-- Modern Table Container (DevzeMart Clean Style) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Table Toolbar (Search bar & Filter/Action) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                        <div class="relative w-full sm:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="recentUserSearch" placeholder="Cari nama, email, role..." 
                                   class="w-full pl-9 pr-12 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] focus:bg-white transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <a href="<?= base_url('/superadmin/users') ?>" 
                               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition">
                                <i class="fas fa-list-ul text-slate-400 text-xs"></i>
                                <span>Lihat Semua Pengguna</span>
                            </a>
                        </div>
                    </div>

                    <!-- Table Data -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="recentUsersTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5 w-16 text-center">#</th>
                                    <th class="px-5 py-3.5">Detail Pengguna</th>
                                    <th class="px-5 py-3.5">Role</th>
                                    <th class="px-5 py-3.5">Status Akun</th>
                                    <th class="px-5 py-3.5">Verifikasi</th>
                                    <th class="px-5 py-3.5">Terdaftar Pada</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($recent_users)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Belum ada pengguna terdaftar di sistem.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_users as $idx => $user): 
                                        $initial = strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1));
                                        $createdDate = !empty($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : '-';
                                    ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors user-row">
                                            <!-- ID / Index -->
                                            <td class="px-5 py-3.5 text-center text-xs font-semibold text-slate-400">
                                                #<?= esc($user['id']) ?>
                                            </td>

                                            <!-- User Details (Avatar Circle, Name, Email) -->
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-full bg-purple-100 text-[#5e0078] font-bold text-xs flex items-center justify-center shrink-0">
                                                        <?= $initial ?>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="font-bold text-slate-900 truncate leading-tight text-xs sm:text-sm"><?= esc($user['nama_lengkap']) ?></p>
                                                        <p class="text-[11px] text-slate-400 truncate mt-0.5"><?= esc($user['email']) ?></p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Role -->
                                            <td class="px-5 py-3.5">
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                                </span>
                                            </td>

                                            <!-- Status Akun -->
                                            <td class="px-5 py-3.5">
                                                <?php if (($user['status'] ?? '') === 'aktif'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        <span>Active</span>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                        <span>Nonaktif</span>
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Status Verifikasi -->
                                            <td class="px-5 py-3.5">
                                                <?php if (($user['verification_status'] ?? '') === 'terverifikasi'): ?>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        Verified
                                                    </span>
                                                <?php elseif (($user['verification_status'] ?? '') === 'menunggu_review'): ?>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                        Review
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                        Unverified
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Registered Date -->
                                            <td class="px-5 py-3.5 text-xs text-slate-500">
                                                <?= $createdDate ?>
                                            </td>

                                            <!-- Aksi -->
                                            <td class="px-5 py-3.5 text-right">
                                                <a href="<?= base_url('/superadmin/users') ?>" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#5e0078] hover:bg-purple-50 transition" 
                                                   title="Kelola Pengguna">
                                                    <i class="fas fa-ellipsis-h text-xs"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Pagination Footer (matching screenshot pattern) -->
                    <div class="px-5 py-3.5 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                        <span id="recentUserCount">Menampilkan <?= count($recent_users ?? []) ?> pengguna terbaru</span>
                        <div class="flex items-center gap-1.5 select-none">
                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed text-xs flex items-center gap-1">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </span>
                            <span class="px-3 py-1.5 rounded-lg bg-[#5e0078] text-white font-bold text-xs">
                                1
                            </span>
                            <a href="<?= base_url('/superadmin/users') ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition">
                                Next <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('recentUserSearch');
    const table = document.getElementById('recentUsersTable');
    if (!searchInput || !table) return;

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = table.querySelectorAll('tbody tr.user-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const countEl = document.getElementById('recentUserCount');
        if (countEl) {
            countEl.textContent = `Menampilkan ${visibleCount} dari ${rows.length} pengguna terbaru`;
        }
    });

    // ⌘K or Ctrl+K shortcut
    window.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });
});
</script>

<?= $this->endSection() ?>
