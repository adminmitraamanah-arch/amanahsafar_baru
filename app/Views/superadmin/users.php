<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex h-screen bg-gray-50/50">
    <?= $this->include('layouts/sidebar') ?>
    
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <?= $this->include('layouts/navbar') ?>
        
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Pengguna</h1>
                        <p class="text-sm text-gray-500 mt-1">Kelola data seluruh role pengguna, status akun, dan hak akses.</p>
                    </div>
                </div>

                <!-- Stat Summary Cards -->
                <?php
                $totalUsers = count($users ?? []);
                $activeUsers = 0;
                $inactiveUsers = 0;
                foreach ($users ?? [] as $u) {
                    if (($u['status'] ?? '') === 'aktif') $activeUsers++;
                    if (($u['status'] ?? '') === 'nonaktif') $inactiveUsers++;
                }
                ?>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total Pengguna</span>
                            <span class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1 block"><?= $totalUsers ?></span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#5e0078] flex items-center justify-center text-xl">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Akun Aktif</span>
                            <span class="text-2xl sm:text-3xl font-bold text-emerald-600 mt-1 block"><?= $activeUsers ?></span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Akun Nonaktif</span>
                            <span class="text-2xl sm:text-3xl font-bold text-rose-600 mt-1 block"><?= $inactiveUsers ?></span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                            <i class="fas fa-user-slash"></i>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-base font-bold text-gray-900">Daftar Pengguna Sistem</h2>
                        <span class="text-xs font-medium text-gray-500">Total: <?= count($users ?? []) ?> Akun</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/75 border-b border-gray-200/70 text-xs font-bold uppercase tracking-wider text-gray-600">
                                    <th class="px-6 py-3.5">Nama &amp; Domisili</th>
                                    <th class="px-6 py-3.5">Kontak</th>
                                    <th class="px-6 py-3.5">Role</th>
                                    <th class="px-6 py-3.5">Pembina</th>
                                    <th class="px-6 py-3.5">Status Akun</th>
                                    <th class="px-6 py-3.5">Verifikasi</th>
                                    <th class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 text-sm">
                                            Belum ada pengguna terdaftar.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): 
                                        $isSelf = ((int)$user['id'] === (int)($current_user_id ?? 0));
                                    ?>
                                        <tr class="hover:bg-purple-50/20 transition-colors">
                                            <!-- Nama & Domisili -->
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900 flex items-center gap-2">
                                                    <span><?= esc($user['nama_lengkap']) ?></span>
                                                    <?php if ($isSelf): ?>
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-[#5e0078]">Anda</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1 text-[11px]"></i>
                                                    <?= esc($user['domisili'] ?: 'Belum diisi') ?>
                                                </div>
                                            </td>

                                            <!-- Kontak -->
                                            <td class="px-6 py-4">
                                                <div class="text-xs text-gray-700 font-medium">
                                                    <i class="far fa-envelope text-gray-400 mr-1"></i>
                                                    <?= esc($user['email']) ?>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fab fa-whatsapp text-emerald-500 mr-1"></i>
                                                    <?= esc($user['no_wa']) ?>
                                                </div>
                                            </td>

                                            <!-- Role -->
                                            <td class="px-6 py-4">
                                                <?php
                                                $roleConfig = match($user['role']) {
                                                    'super_admin' => ['bg' => 'bg-purple-100 text-purple-800 border-purple-200', 'label' => 'Super Admin'],
                                                    'admin' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-200', 'label' => 'Admin'],
                                                    'pusat' => ['bg' => 'bg-indigo-100 text-indigo-800 border-indigo-200', 'label' => 'Pusat'],
                                                    'cabang' => ['bg' => 'bg-cyan-100 text-cyan-800 border-cyan-200', 'label' => 'Cabang'],
                                                    'manager_mitra' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Manager Mitra'],
                                                    'mitra' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Mitra'],
                                                    default => ['bg' => 'bg-gray-100 text-gray-800 border-gray-200', 'label' => ucfirst($user['role'])]
                                                };
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $roleConfig['bg'] ?>">
                                                    <?= $roleConfig['label'] ?>
                                                </span>
                                            </td>

                                            <!-- Pembina -->
                                            <td class="px-6 py-4 text-xs text-gray-600">
                                                <?php if (!empty($user['nama_pembina'])): ?>
                                                    <span class="font-medium text-gray-800"><?= esc($user['nama_pembina']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Status Akun -->
                                            <td class="px-6 py-4">
                                                <?php if ($user['status'] === 'aktif'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                    </span>
                                                <?php elseif ($user['status'] === 'nonaktif'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Verifikasi -->
                                            <td class="px-6 py-4">
                                                <?php 
                                                $verifBadge = match($user['verification_status']) {
                                                    'terverifikasi' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Terverifikasi'],
                                                    'menunggu_review' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Review'],
                                                    'ditolak' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Ditolak'],
                                                    default => ['class' => 'bg-gray-50 text-gray-600 border-gray-200', 'label' => 'Belum Verif']
                                                };
                                                ?>
                                                <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-medium border <?= $verifBadge['class'] ?>">
                                                    <?= $verifBadge['label'] ?>
                                                </span>
                                            </td>

                                            <!-- Aksi -->
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Tombol Edit -->
                                                    <button type="button" 
                                                            onclick='openEditModal(<?= htmlspecialchars(json_encode($user), ENT_QUOTES, "UTF-8") ?>)' 
                                                            class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-purple-50 text-[#5e0078] hover:bg-purple-100 border border-purple-200 transition">
                                                        <i class="fas fa-edit text-xs"></i>
                                                        <span>Edit</span>
                                                    </button>

                                                    <!-- Tombol Nonaktifkan / Aktifkan Kembali -->
                                                    <?php if ($user['status'] === 'aktif'): ?>
                                                        <?php if ($isSelf): ?>
                                                            <button type="button" disabled 
                                                                    title="Tidak dapat menonaktifkan akun sendiri"
                                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                                                                <i class="fas fa-ban text-xs"></i>
                                                                <span>Nonaktifkan</span>
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" 
                                                                    onclick="openDeactivateModal(<?= $user['id'] ?>, '<?= esc($user['nama_lengkap'], 'js') ?>')" 
                                                                    class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                                                <i class="fas fa-user-slash text-xs"></i>
                                                                <span>Nonaktifkan</span>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <!-- Aktifkan Kembali -->
                                                        <button type="button" 
                                                                onclick="openActivateModal(<?= $user['id'] ?>, '<?= esc($user['nama_lengkap'], 'js') ?>')" 
                                                                class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition">
                                                            <i class="fas fa-user-check text-xs"></i>
                                                            <span>Aktifkan Kembali</span>
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
                </div>

            </div>
        </main>
    </div>
</div>

<!-- ========================================================
     MODAL 1: EDIT DATA PENGGUNA
     ======================================================== -->
<div id="editUserModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden relative my-8 transform transition-all">
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Edit Data Pengguna</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui informasi profil, role, dan pembinaan tanpa mengubah password.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-gray-200/60 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-sm transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Form -->
        <form id="editUserForm" method="POST" action="" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div>
                <label for="edit_nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="edit_nama_lengkap" name="nama_lengkap" required 
                       class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="edit_email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" required 
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="edit_no_wa" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Nomor WhatsApp</label>
                    <input type="text" id="edit_no_wa" name="no_wa" required 
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                </div>
            </div>

            <!-- Domisili -->
            <div>
                <label for="edit_domisili" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Domisili / Kota</label>
                <input type="text" id="edit_domisili" name="domisili" required 
                       class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Role -->
                <div>
                    <label for="edit_role" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Role Akun</label>
                    <select id="edit_role" name="role" required 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
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
                    <label for="edit_verification_status" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Status Verifikasi</label>
                    <select id="edit_verification_status" name="verification_status" required 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="menunggu_review">Menunggu Review</option>
                        <option value="belum_verifikasi">Belum Verifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <!-- Pembina -->
            <div>
                <label for="edit_pembina_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Pembina / Atasan Langsung</label>
                <select id="edit_pembina_id" name="pembina_id" 
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#5e0078] focus:bg-white focus:outline-none transition">
                    <option value="">-- Tanpa Pembina (Tingkat Tertinggi) --</option>
                    <?php foreach ($pembinas ?? [] as $pem): ?>
                        <option value="<?= $pem['id'] ?>">
                            <?= esc($pem['nama_lengkap']) ?> (<?= ucfirst(str_replace('_', ' ', $pem['role'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Pembina menentukan hierarki jaringan dan pembagian komisi binaan.</p>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="btn-interactive px-5 py-2.5 rounded-xl bg-[#5e0078] hover:bg-[#4c0062] text-white text-xs font-bold shadow-md transition">
                    <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL 2: KONFIRMASI NONAKTIFKAN AKUN
     ======================================================== -->
<div id="deactivateUserModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative p-6 space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto">
            <i class="fas fa-user-slash"></i>
        </div>

        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-gray-900">Nonaktifkan Akun Pengguna?</h3>
            <p class="text-xs text-gray-600 leading-relaxed">
                Apakah Anda yakin ingin menonaktifkan akun <strong id="deactivateUserName" class="text-gray-900 font-bold"></strong>?
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
                    class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" 
                    class="btn-interactive px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md transition">
                <i class="fas fa-ban mr-1.5"></i> Ya, Nonaktifkan Akun
            </button>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL 3: KONFIRMASI AKTIFKAN KEMBALI AKUN
     ======================================================== -->
<div id="activateUserModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative p-6 space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mx-auto">
            <i class="fas fa-user-check"></i>
        </div>

        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-gray-900">Aktifkan Kembali Akun?</h3>
            <p class="text-xs text-gray-600 leading-relaxed">
                Apakah Anda ingin mengaktifkan kembali akun <strong id="activateUserName" class="text-gray-900 font-bold"></strong>?
            </p>
            <p class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                Setelah diaktifkan, pengguna akan dapat langsung login kembali ke dalam sistem menggunakan email dan password mereka.
            </p>
        </div>

        <form id="activateUserForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            <?= csrf_field() ?>
            <button type="button" onclick="closeActivateModal()" 
                    class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" 
                    class="btn-interactive px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition">
                <i class="fas fa-check mr-1.5"></i> Aktifkan Akun
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

    // Close modals on escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closeDeactivateModal();
            closeActivateModal();
        }
    });

    // Close modals on backdrop click
    ['editUserModal', 'deactivateUserModal', 'activateUserModal'].forEach(id => {
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
</script>

<?= $this->endSection() ?>
