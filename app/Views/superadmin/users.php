<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-[#f8fafc]">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="sidebar-main-content md:ml-64 flex flex-col min-h-screen min-w-0 transition-all duration-200">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Page Title Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Pengguna</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs">
                            <i class="fas fa-database text-slate-400 mr-1.5"></i> Total: <strong class="text-slate-900"><?= count($users ?? []) ?></strong> Akun
                        </span>
                    </div>
                </div>

                <!-- Stat Summary Row (6 Cards Grid - DevzeMart Reference) -->
                <?php
                $totalUsers = count($users ?? []);
                $activeUsers = 0;
                $inactiveUsers = 0;
                $kycVerified = 0;
                $kycReview = 0;
                $kycUnverified = 0;

                foreach ($users ?? [] as $u) {
                    $st = $u['status'] ?? '';
                    $vst = $u['verification_status'] ?? '';

                    if ($st === 'aktif') $activeUsers++;
                    if ($st === 'nonaktif') $inactiveUsers++;

                    if ($vst === 'terverifikasi') $kycVerified++;
                    elseif ($vst === 'menunggu_review') $kycReview++;
                    else $kycUnverified++;
                }
                ?>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
                    
                    <!-- Card 1: Active Users -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Active Users</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= number_format($activeUsers, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 2: Banned / Nonaktif -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-user-slash"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Banned / Nonaktif</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-rose-600"><?= number_format($inactiveUsers, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 3: KYC Verified -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">KYC Verified</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-emerald-600"><?= number_format($kycVerified, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 4: KYC In Review -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">KYC Review</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-amber-600"><?= number_format($kycReview, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 5: KYC Unverified -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-id-badge"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">KYC Unverified</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-700"><?= number_format($kycUnverified, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Card 6: Total Users -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-sm transition-transform group-hover:scale-105">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Total Users</span>
                            <span class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= number_format($totalUsers, 0, ',', '.') ?></span>
                        </div>
                    </div>

                </div>

                <!-- Table Card Container (DevzeMart Minimalist Style) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Toolbar (Search bar & Filter controls) -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3 bg-white">
                        <div class="relative w-full md:w-80">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="usersSearchInput" placeholder="Cari pengguna... (nama, email, wa, role)" 
                                   class="w-full pl-9 pr-12 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#5e0078] focus:bg-white transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200/80 rounded px-1.5 py-0.5 pointer-events-none">⌘K</span>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                            <!-- Sort By -->
                            <select id="userSortSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="id_asc">Urutkan: ID (Terkecil)</option>
                                <option value="id_desc">Urutkan: ID (Terbaru)</option>
                                <option value="nama_asc">Urutkan: Nama (A - Z)</option>
                                <option value="nama_desc">Urutkan: Nama (Z - A)</option>
                                <option value="role_asc">Urutkan: Role (A - Z)</option>
                                <option value="role_desc">Urutkan: Role (Z - A)</option>
                                <option value="pembina_asc">Urutkan: Pembina (A - Z)</option>
                                <option value="pembina_desc">Urutkan: Pembina (Z - A)</option>
                                <option value="status_asc">Urutkan: Status (Aktif Dahulu)</option>
                                <option value="status_desc">Urutkan: Status (Nonaktif Dahulu)</option>
                            </select>

                            <!-- Filter Status -->
                            <select id="statusFilter" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Status Akun</option>
                                <option value="aktif">Status: Aktif</option>
                                <option value="nonaktif">Status: Nonaktif</option>
                            </select>

                            <!-- Filter Role -->
                            <select id="roleFilter" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="all">Semua Role</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="pusat">Pusat</option>
                                <option value="cabang">Cabang</option>
                                <option value="manager_mitra">Manager Mitra</option>
                                <option value="mitra">Mitra</option>
                            </select>

                            <!-- Per Page Size -->
                            <select id="userPageSizeSelect" class="px-3 py-2 bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#5e0078] transition">
                                <option value="10">10 / halaman</option>
                                <option value="25">25 / halaman</option>
                                <option value="50">50 / halaman</option>
                                <option value="100">100 / halaman</option>
                            </select>

                            <!-- Reset Button -->
                            <button type="button" id="resetFiltersBtn" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition text-xs" title="Reset Filter">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Table Data -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[760px]" id="usersTable">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5 w-16 text-center" data-sort-asc="id_asc" data-sort-desc="id_desc">#</th>
                                    <th class="px-5 py-3.5" data-sort-asc="nama_asc" data-sort-desc="nama_desc">Detail Pengguna</th>
                                    <th class="px-5 py-3.5">Kontak &amp; Domisili</th>
                                    <th class="px-5 py-3.5" data-sort-asc="role_asc" data-sort-desc="role_desc">Role</th>
                                    <th class="px-5 py-3.5" data-sort-asc="pembina_asc" data-sort-desc="pembina_desc">Pembina</th>
                                    <th class="px-5 py-3.5" data-sort-asc="status_asc" data-sort-desc="status_desc">Status Akun</th>
                                    <th class="px-5 py-3.5">KYC Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                <?php if (empty($users)): ?>
                                    <tr id="emptyRow">
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                                            Belum ada pengguna terdaftar di sistem.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): 
                                        $isSelf = ((int)$user['id'] === (int)($current_user_id ?? 0));
                                        $initial = strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1));
                                    ?>
                                        <tr class="hover:bg-purple-50/30 transition-colors user-table-row" 
                                            data-id="<?= esc($user['id']) ?>"
                                            data-nama="<?= strtolower(esc($user['nama_lengkap'])) ?>"
                                            data-status="<?= esc($user['status']) ?>" 
                                            data-role="<?= esc($user['role']) ?>"
                                            data-pembina="<?= strtolower(esc($user['nama_pembina'] ?? '')) ?>"
                                            data-search="<?= strtolower(esc($user['nama_lengkap'] . ' ' . $user['email'] . ' ' . $user['no_wa'] . ' ' . $user['domisili'] . ' ' . $user['role'] . ' ' . ($user['nama_pembina'] ?? ''))) ?>">
                                            
                                            <!-- ID -->
                                            <td class="px-5 py-4 text-center text-xs font-semibold text-slate-400">
                                                #<?= esc($user['id']) ?>
                                            </td>

                                            <!-- User Details (Avatar, Name, Email) -->
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-full bg-purple-100 text-[#5e0078] font-bold text-xs flex items-center justify-center shrink-0">
                                                        <?= $initial ?>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-slate-900 flex items-center gap-1.5 leading-tight text-xs sm:text-sm">
                                                            <span><?= esc($user['nama_lengkap']) ?></span>
                                                            <?php if ($isSelf): ?>
                                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-[#5e0078]">Anda</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="text-[11px] text-slate-400 truncate mt-0.5"><?= esc($user['email']) ?></p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Kontak & Domisili -->
                                            <td class="px-5 py-4">
                                                <div class="text-xs text-slate-700 font-medium flex items-center gap-1.5">
                                                    <i class="fab fa-whatsapp text-emerald-500 text-xs"></i>
                                                    <span><?= esc($user['no_wa'] ?: '-') ?></span>
                                                </div>
                                                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1.5">
                                                    <i class="fas fa-map-marker-alt text-slate-300 text-[10px]"></i>
                                                    <span><?= esc($user['domisili'] ?: 'Belum diisi') ?></span>
                                                </div>
                                            </td>

                                            <!-- Role -->
                                            <td class="px-5 py-4">
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                                </span>
                                            </td>

                                            <!-- Pembina -->
                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                <?php if (!empty($user['nama_pembina'])): ?>
                                                    <span class="font-semibold text-slate-800"><?= esc($user['nama_pembina']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-slate-400 italic">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Status Akun (Soft Pill Badge) -->
                                            <td class="px-5 py-4">
                                                <?php if ($user['status'] === 'aktif'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                                    </span>
                                                <?php elseif ($user['status'] === 'nonaktif'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- KYC Status -->
                                            <td class="px-5 py-4">
                                                <?php if ($user['verification_status'] === 'terverifikasi'): ?>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        Verified
                                                    </span>
                                                <?php elseif ($user['verification_status'] === 'menunggu_review'): ?>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                        Review
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                        Unverified
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Aksi -->
                                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                                <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                                    <!-- Edit Button -->
                                                    <button type="button" 
                                                            onclick='openEditModal(<?= htmlspecialchars(json_encode($user), ENT_QUOTES, "UTF-8") ?>)' 
                                                            class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-purple-50 text-[#5e0078] hover:bg-purple-100 hover:text-[#4c0062] border border-purple-200 transition shadow-xs"
                                                            title="Edit Pengguna"
                                                            aria-label="Edit Pengguna">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>

                                                    <!-- Nonaktifkan / Aktifkan Button -->
                                                    <?php if ($user['status'] === 'aktif'): ?>
                                                        <?php if ($isSelf): ?>
                                                            <button type="button" disabled 
                                                                    title="Tidak dapat menonaktifkan akun sendiri"
                                                                    aria-label="Tidak dapat menonaktifkan akun sendiri"
                                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200">
                                                                <i class="fas fa-ban text-xs"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" 
                                                                    onclick="openDeactivateModal(<?= $user['id'] ?>, '<?= esc($user['nama_lengkap'], 'js') ?>')" 
                                                                    class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800 border border-amber-200 transition shadow-xs"
                                                                    title="Nonaktifkan Akun"
                                                                    aria-label="Nonaktifkan Akun">
                                                                <i class="fas fa-user-slash text-xs"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <!-- Aktifkan Kembali -->
                                                        <button type="button" 
                                                                onclick="openActivateModal(<?= $user['id'] ?>, '<?= esc($user['nama_lengkap'], 'js') ?>')" 
                                                                class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:text-emerald-800 border border-emerald-200 transition shadow-xs"
                                                                title="Aktifkan Kembali Akun"
                                                                aria-label="Aktifkan Kembali Akun">
                                                            <i class="fas fa-user-check text-xs"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <!-- Hapus Button -->
                                                    <?php if ($isSelf): ?>
                                                        <button type="button" disabled 
                                                                title="Tidak dapat menghapus akun sendiri"
                                                                aria-label="Tidak dapat menghapus akun sendiri"
                                                                class="w-8 h-8 rounded-xl inline-flex items-center justify-center bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" 
                                                                onclick="openDeleteModal(<?= $user['id'] ?>, '<?= esc($user['nama_lengkap'], 'js') ?>', '<?= esc($user['email'], 'js') ?>')" 
                                                                class="btn-interactive w-8 h-8 rounded-xl inline-flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-800 border border-rose-200 transition shadow-xs"
                                                                title="Hapus Pengguna"
                                                                aria-label="Hapus Pengguna">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Pagination Footer (matching screenshot pattern) -->
                    <div class="px-5 py-3.5 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                        <span id="userCountSummary">Menampilkan <?= count($users ?? []) ?> pengguna</span>
                        <div class="flex items-center gap-1 select-none" id="paginationControls">
                            <button type="button" id="prevPageBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left text-[10px]"></i> Previous
                            </button>
                            <div id="paginationNumbers" class="flex items-center gap-1">
                                <!-- Generated by JS -->
                            </div>
                            <button type="button" id="nextPageBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center gap-1 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                Next <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<!-- ========================================================
     MODAL 1: EDIT DATA PENGGUNA
     ======================================================== -->
<div id="editUserModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden relative my-8 transform transition-all border border-slate-100">
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Edit Data Pengguna</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi profil, role, dan pembinaan tanpa mengubah password.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-200/60 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Form -->
        <form id="editUserForm" method="POST" action="" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div>
                <label for="edit_nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="edit_nama_lengkap" name="nama_lengkap" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="edit_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="edit_no_wa" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor WhatsApp</label>
                    <input type="text" id="edit_no_wa" name="no_wa" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                </div>
            </div>

            <!-- Domisili -->
            <div>
                <label for="edit_domisili" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Domisili / Kota</label>
                <input type="text" id="edit_domisili" name="domisili" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Role -->
                <div>
                    <label for="edit_role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Role Akun</label>
                    <select id="edit_role" name="role" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="pusat">Pusat</option>
                        <option value="cabang">Cabang</option>
                        <option value="manager_mitra">Manager Mitra</option>
                        <option value="mitra">Mitra</option>
                    </select>
                </div>

                <!-- Status Verifikasi -->
                <div>
                    <label for="edit_verification_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Verifikasi</label>
                    <select id="edit_verification_status" name="verification_status" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="menunggu_review">Menunggu Review</option>
                        <option value="belum_verifikasi">Belum Verifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <!-- Pembina -->
            <div>
                <label for="edit_pembina_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pembina / Atasan Langsung</label>
                <select id="edit_pembina_id" name="pembina_id" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Tanpa Pembina (Tingkat Tertinggi) --</option>
                    <?php foreach ($pembinas ?? [] as $pem): ?>
                        <option value="<?= $pem['id'] ?>">
                            <?= esc($pem['nama_lengkap']) ?> (<?= ucfirst(str_replace('_', ' ', $pem['role'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Pembina menentukan hierarki jaringan dan pembagian komisi binaan.</p>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-sm transition">
                    <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL 2: KONFIRMASI NONAKTIFKAN AKUN
     ======================================================== -->
<div id="deactivateUserModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative p-6 space-y-4 border border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto">
            <i class="fas fa-user-slash"></i>
        </div>

        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-slate-900">Nonaktifkan Akun Pengguna?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menonaktifkan akun <strong id="deactivateUserName" class="text-slate-900 font-bold"></strong>?
            </p>
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-left text-xs text-amber-800 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <i class="fas fa-info-circle text-amber-600"></i> Informasi Penting:
                </div>
                <p class="text-[11px] leading-relaxed">
                    • Pengguna <strong>tidak dapat login</strong> lagi ke dalam sistem.<br>
                    • Sesi yang sedang aktif di perangkat mana pun akan <strong>langsung diakhiri otomatis</strong>.<br>
                    • Data transaksi, riwayat komisi, dan relasi pembinaan <strong>tetap aman tersimpan</strong>.<br>
                    • Anda dapat mengaktifkan kembali akun ini kapan saja.
                </p>
            </div>
        </div>

        <form id="deactivateUserForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            <?= csrf_field() ?>
            <button type="button" onclick="closeDeactivateModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="submit" 
                    class="btn-interactive px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition">
                <i class="fas fa-ban mr-1.5"></i> Ya, Nonaktifkan Akun
            </button>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL 3: KONFIRMASI AKTIFKAN KEMBALI AKUN
     ======================================================== -->
<div id="activateUserModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative p-6 space-y-4 border border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mx-auto">
            <i class="fas fa-user-check"></i>
        </div>

        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-slate-900">Aktifkan Kembali Akun?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda ingin mengaktifkan kembali akun <strong id="activateUserName" class="text-slate-900 font-bold"></strong>?
            </p>
            <p class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                Setelah diaktifkan, pengguna akan dapat langsung login kembali ke dalam sistem menggunakan email dan password mereka.
            </p>
        </div>

        <form id="activateUserForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            <?= csrf_field() ?>
            <button type="button" onclick="closeActivateModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="submit" 
                    class="btn-interactive px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                <i class="fas fa-check mr-1.5"></i> Aktifkan Akun
            </button>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL 4: KONFIRMASI HAPUS PENGGUNA
     ======================================================== -->
<div id="deleteUserModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative p-6 space-y-4 border border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto">
            <i class="fas fa-trash-alt"></i>
        </div>

        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-slate-900">Hapus Pengguna?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun <strong id="deleteUserName" class="text-slate-900 font-bold"></strong> (<span id="deleteUserEmail" class="text-slate-500 font-medium"></span>)?
            </p>
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-left text-xs text-rose-800 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <i class="fas fa-exclamation-triangle text-rose-600"></i> Perhatian:
                </div>
                <p class="text-[11px] leading-relaxed text-rose-700">
                    • Akun akan dihapus dari sistem dan tidak dapat login kembali.<br>
                    • Seluruh sesi aktif di semua perangkat akan langsung dihentikan.<br>
                    • Relasi pembina pada mitra binaan akan disesuaikan otomatis.
                </p>
            </div>
        </div>

        <form id="deleteUserForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            <?= csrf_field() ?>
            <button type="button" onclick="closeDeleteModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="submit" 
                    class="btn-interactive px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition">
                <i class="fas fa-trash-alt mr-1.5"></i> Ya, Hapus Pengguna
            </button>
        </form>
    </div>
</div>

<!-- JAVASCRIPT CONTROLLERS -->
<script>
    const baseUrl = '<?= base_url() ?>';

    // 1. Edit Modal Controller
    function openEditModal(user) {
        if (!user) return;
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');

        form.action = `${baseUrl}/superadmin/users/update/${user.id}`;
        document.getElementById('edit_nama_lengkap').value = user.nama_lengkap || '';
        document.getElementById('edit_email').value = user.email || '';
        document.getElementById('edit_no_wa').value = user.no_wa || '';
        document.getElementById('edit_domisili').value = user.domisili || '';
        document.getElementById('edit_role').value = user.role || 'mitra';
        document.getElementById('edit_verification_status').value = user.verification_status || 'belum_verifikasi';
        document.getElementById('edit_pembina_id').value = user.pembina_id || '';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('editUserModal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // 2. Deactivate Modal Controller
    function openDeactivateModal(userId, userName) {
        const modal = document.getElementById('deactivateUserModal');
        const form = document.getElementById('deactivateUserForm');
        const nameEl = document.getElementById('deactivateUserName');

        form.action = `${baseUrl}/superadmin/users/deactivate/${userId}`;
        nameEl.textContent = userName || 'pengguna ini';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeactivateModal() {
        const modal = document.getElementById('deactivateUserModal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // 3. Activate Modal Controller
    function openActivateModal(userId, userName) {
        const modal = document.getElementById('activateUserModal');
        const form = document.getElementById('activateUserForm');
        const nameEl = document.getElementById('activateUserName');

        form.action = `${baseUrl}/superadmin/users/activate/${userId}`;
        nameEl.textContent = userName || 'pengguna ini';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeActivateModal() {
        const modal = document.getElementById('activateUserModal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // 4. Delete Modal Controller
    function openDeleteModal(userId, userName, userEmail) {
        const modal = document.getElementById('deleteUserModal');
        const form = document.getElementById('deleteUserForm');
        const nameEl = document.getElementById('deleteUserName');
        const emailEl = document.getElementById('deleteUserEmail');

        form.action = `${baseUrl}/superadmin/users/delete/${userId}`;
        nameEl.textContent = userName || 'pengguna ini';
        emailEl.textContent = userEmail || '';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteUserModal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close modals on escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closeDeactivateModal();
            closeActivateModal();
            closeDeleteModal();
        }
    });

    // Close modals on backdrop click
    ['editUserModal', 'deactivateUserModal', 'activateUserModal', 'deleteUserModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === el) {
                    el.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
    });

    // 4. AmanahTable Initialization (Search, Filtering, Sorting & Pagination)
    document.addEventListener('DOMContentLoaded', function() {
        AmanahTable.init({
            containerId: 'usersTable',
            rowSelector: 'tbody tr.user-table-row',
            entityName: 'pengguna',
            defaultPageSize: 10,
            searchInputId: 'usersSearchInput',
            sortSelectId: 'userSortSelect',
            pageSizeSelectId: 'userPageSizeSelect',
            summaryId: 'userCountSummary',
            prevBtnId: 'prevPageBtn',
            nextBtnId: 'nextPageBtn',
            numbersId: 'paginationNumbers',
            emptyStateId: 'emptyRow',
            resetBtnId: 'resetFiltersBtn',
            defaultSort: 'id_asc',
            filters: [
                { selectId: 'statusFilter', key: 'status', defaultValue: 'all' },
                { selectId: 'roleFilter', key: 'role', defaultValue: 'all' }
            ],
            sortDefinitions: {
                id_asc: (a, b) => (parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0),
                id_desc: (a, b) => (parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0),
                nama_asc: (a, b) => (a.getAttribute('data-nama') || '').localeCompare(b.getAttribute('data-nama') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0)),
                nama_desc: (a, b) => (b.getAttribute('data-nama') || '').localeCompare(a.getAttribute('data-nama') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
                role_asc: (a, b) => (a.getAttribute('data-role') || '').localeCompare(b.getAttribute('data-role') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0)),
                role_desc: (a, b) => (b.getAttribute('data-role') || '').localeCompare(a.getAttribute('data-role') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
                pembina_asc: (a, b) => (a.getAttribute('data-pembina') || '').localeCompare(b.getAttribute('data-pembina') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0)),
                pembina_desc: (a, b) => (b.getAttribute('data-pembina') || '').localeCompare(a.getAttribute('data-pembina') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0)),
                status_asc: (a, b) => (a.getAttribute('data-status') || '').localeCompare(b.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(a.getAttribute('data-id'), 10) || 0) - (parseInt(b.getAttribute('data-id'), 10) || 0)),
                status_desc: (a, b) => (b.getAttribute('data-status') || '').localeCompare(a.getAttribute('data-status') || '', 'id', { numeric: true, sensitivity: 'base' }) || ((parseInt(b.getAttribute('data-id'), 10) || 0) - (parseInt(a.getAttribute('data-id'), 10) || 0))
            }
        });
    });
</script>

<?= $this->endSection() ?>
