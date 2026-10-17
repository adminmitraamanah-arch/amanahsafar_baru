<!-- DASHBOARD HEADER / NAVBAR (Clean, Minimalist, Pure White with Solid Purple Accent) -->
<header class="bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3.5 sticky top-0 z-20 flex items-center justify-between select-none">
    
    <!-- Left: Mobile Toggle & Page Title/Breadcrumb -->
    <div class="flex items-center gap-3 min-w-0">
        <!-- Mobile Sidebar Toggle -->
        <button id="mobileSidebarToggle" type="button" class="md:hidden p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition focus:outline-none" aria-label="Buka menu navigasi">
            <i class="fas fa-bars text-base"></i>
        </button>

        <!-- Page Title & Breadcrumb -->
        <div class="min-w-0">
            <h1 class="text-base sm:text-xl font-bold text-slate-900 tracking-tight truncate leading-tight">
                <?= esc($title ?? 'Dashboard') ?>
            </h1>
            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium mt-0.5">
                <a href="<?= base_url('/dashboard') ?>" class="hover:text-[#5e0078] transition">Dashboard</a>
                <span>&gt;</span>
                <span class="text-slate-600 font-semibold truncate"><?= esc($title ?? 'Overview') ?></span>
            </div>
        </div>
    </div>

    <!-- Right: Quick Actions & User Profile -->
    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
        
        <?php if (session('logged_in')): ?>
            <!-- Notifications Icon -->
            <button type="button" class="w-9 h-9 rounded-xl text-slate-400 hover:text-[#5e0078] hover:bg-purple-50/70 flex items-center justify-center text-sm transition relative" title="Belum ada notifikasi baru">
                <i class="fas fa-bell"></i>
            </button>

            <!-- User Profile Dropdown -->
            <div class="relative group">
                <button type="button" class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-1.5 rounded-xl text-slate-700 hover:text-[#5e0078] hover:bg-purple-50/60 transition duration-150 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-[#5e0078] text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm ring-2 ring-purple-100 overflow-hidden">
                        <?php if (!empty(session('foto_profil')) && is_file(FCPATH . 'uploads/profiles/' . session('foto_profil'))): ?>
                            <img src="<?= base_url('uploads/profiles/' . esc(session('foto_profil'))) ?>" alt="Foto Profil" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?= strtoupper(substr(session('nama') ?? 'U', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 leading-tight"><?= esc(session('nama') ?? 'User') ?></p>
                        <p class="text-[10px] text-slate-400 capitalize"><?= str_replace('_', ' ', session('role') ?? 'Role') ?></p>
                    </div>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:rotate-180 transition-transform duration-200"></i>
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-1.5 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-1.5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transform translate-y-1.5 group-hover:translate-y-0 transition-all duration-150 ease-out z-50">
                    <div class="px-4 py-2 border-b border-slate-100 mb-1">
                        <p class="text-xs font-bold text-slate-900 leading-tight"><?= esc(session('nama')) ?></p>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5"><?= esc(session('email')) ?></p>
                    </div>
                    <a href="<?= base_url('/dashboard') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#5e0078] transition-colors">
                        <i class="fas fa-columns text-slate-400 text-xs w-4 text-center"></i> Dashboard
                    </a>
                    <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#5e0078] transition-colors">
                        <i class="fas fa-globe text-slate-400 text-xs w-4 text-center"></i> Kunjungi Website
                    </a>
                    <a href="<?= base_url('/profile') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#5e0078] transition-colors">
                        <i class="fas fa-user-circle text-slate-400 text-xs w-4 text-center"></i> Profil Saya
                    </a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="<?= base_url('/logout') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                        <i class="fas fa-arrow-right-from-bracket text-xs w-4 text-center"></i> Logout
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="<?= base_url('/login') ?>" class="px-4 py-2 text-xs font-bold text-[#5e0078] hover:bg-purple-50 rounded-xl transition">
                Login
            </a>
            <a href="<?= base_url('/register') ?>" class="px-4 py-2 bg-[#5e0078] text-white text-xs font-bold rounded-xl shadow-sm hover:bg-[#4c0062] transition">
                Register
            </a>
        <?php endif; ?>
    </div>
</header>
