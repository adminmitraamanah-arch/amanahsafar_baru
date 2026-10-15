<!-- DASHBOARD SIDEBAR (White background, Solid Purple Active, Modern & Minimalist) -->
<!-- Mobile Backdrop -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 md:hidden hidden transition-opacity duration-200"></div>

<!-- Sidebar Container (Fixed on left with 16rem width on desktop, modal drawer on mobile) -->
<aside id="sidebarContainer" class="fixed inset-y-0 left-0 z-50 md:z-30 h-screen w-64 shrink-0 bg-white border-r border-slate-200/80 flex flex-col justify-between select-none transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0">
    
    <!-- Top Brand Header -->
    <div>
        <div class="h-16 px-6 border-b border-slate-100 flex items-center justify-between">
            <a href="<?= base_url('/') ?>" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-[#5e0078] rounded-xl flex items-center justify-center font-bold text-sm text-white shadow-sm transition-transform duration-150 group-hover:scale-105">
                    AS
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-base tracking-tight text-slate-900 leading-tight">Amanah Safar</span>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Management</span>
                </div>
            </a>
            <button id="closeSidebarBtn" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 focus:outline-none" aria-label="Tutup menu">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Navigation Menus -->
        <div class="p-4 overflow-y-auto max-h-[calc(100vh-8rem)]">
            <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Menu Utama</p>
            <nav class="space-y-1">
                <?php
                $menus = [
                    'admin' => [
                        ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/admin/'],
                        ['icon' => 'fas fa-box', 'label' => 'Katalog Paket', 'url' => '/admin/packages'],
                        ['icon' => 'fas fa-shopping-cart', 'label' => 'Kelola Pesanan', 'url' => '/admin/orders'],
                        ['icon' => 'fas fa-handshake', 'label' => 'Komisi', 'url' => '/admin/commissions'],
                        ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/admin/tasks'],
                    ],
                    'superadmin' => [
                        ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/superadmin/'],
                        ['icon' => 'fas fa-desktop', 'label' => 'Landing Page', 'url' => '/superadmin/landing'],
                        ['icon' => 'fas fa-ticket-alt', 'label' => 'Vouchers', 'url' => '/superadmin/vouchers'],
                        ['icon' => 'fas fa-box', 'label' => 'Katalog Paket', 'url' => '/superadmin/packages'],
                        ['icon' => 'fas fa-shopping-cart', 'label' => 'Kelola Pesanan', 'url' => '/superadmin/orders'],
                        ['icon' => 'fas fa-users', 'label' => 'Users', 'url' => '/superadmin/users'],
                        ['icon' => 'fas fa-handshake', 'label' => 'Komisi', 'url' => '/superadmin/commissions'],
                        ['icon' => 'fas fa-wallet', 'label' => 'Penarikan Dana', 'url' => '/superadmin/withdrawals'],
                        ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/superadmin/tasks'],
                        ['icon' => 'fas fa-check-circle', 'label' => 'Verifications', 'url' => '/superadmin/verifications'],
                    ],
                    'pusat' => [
                        ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/app/dashboard'],
                        ['icon' => 'fas fa-box', 'label' => 'Katalog Paket', 'url' => '/admin/packages'],
                        ['icon' => 'fas fa-users', 'label' => 'Binaan', 'url' => '/app/binaan'],
                        ['icon' => 'fas fa-shopping-cart', 'label' => 'Orders', 'url' => '/app/orders'],
                        ['icon' => 'fas fa-receipt', 'label' => 'Mutasi & Komisi', 'url' => '/app/commissions'],
                        ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/app/tasks'],
                    ],
                    'member' => [
                        ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/app/dashboard'],
                        ['icon' => 'fas fa-box', 'label' => 'Katalog Paket', 'url' => '/app/packages'],
                        ['icon' => 'fas fa-users', 'label' => 'Binaan', 'url' => '/app/binaan'],
                        ['icon' => 'fas fa-shopping-cart', 'label' => 'Orders', 'url' => '/app/orders'],
                        ['icon' => 'fas fa-receipt', 'label' => 'Mutasi & Komisi', 'url' => '/app/commissions'],
                        ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/app/tasks'],
                    ]
                ];
                
                $role = session('role');
                $roleKey = 'member';
                if ($role === 'super_admin' || $role === 'superadmin') {
                    $roleKey = 'superadmin';
                } elseif ($role === 'admin') {
                    $roleKey = 'admin';
                } elseif ($role === 'pusat') {
                    $roleKey = 'pusat';
                }
                $menu = $menus[$roleKey] ?? [];
                
                $currentUri = '/' . trim(uri_string(), '/');
                foreach ($menu as $item):
                    $targetUrl = rtrim($item['url'], '/');
                    $isActive = ($currentUri === $targetUrl) || ($targetUrl !== '' && $targetUrl !== '/superadmin' && $targetUrl !== '/admin' && $targetUrl !== '/app' && strpos($currentUri, $targetUrl) === 0);
                ?>
                    <a href="<?= base_url($item['url']) ?>" 
                       class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-150 ease-out <?= $isActive ? 'bg-[#5e0078] text-white font-semibold shadow-sm' : 'text-slate-600 hover:text-[#5e0078] hover:bg-purple-50/70' ?>">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="<?= $item['icon'] ?> text-base w-5 text-center flex-shrink-0 transition-colors <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-[#5e0078]' ?>"></i>
                            <span class="truncate"><?= $item['label'] ?></span>
                        </div>
                        <?php if ($isActive): ?>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/80 shrink-0"></span>
                        <?php else: ?>
                            <i class="fas fa-chevron-right text-[10px] text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></i>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>

    <!-- Bottom Account & Logout Section -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-[#5e0078] text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                    <?= strtoupper(substr(session('nama') ?? 'U', 0, 1)) ?>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 truncate leading-tight"><?= esc(session('nama') ?? 'User') ?></p>
                    <p class="text-[10px] text-slate-400 capitalize truncate"><?= str_replace('_', ' ', session('role') ?? 'Role') ?></p>
                </div>
            </div>
            <a href="<?= base_url('/logout') ?>" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Logout">
                <i class="fas fa-arrow-right-from-bracket text-xs"></i>
            </a>
        </div>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarContainer = document.getElementById('sidebarContainer');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const mobileToggleBtn = document.getElementById('mobileSidebarToggle');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');

    function openSidebar() {
        if (sidebarContainer && sidebarBackdrop) {
            sidebarContainer.classList.add('mobile-open');
            sidebarContainer.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        if (sidebarContainer && sidebarBackdrop) {
            sidebarContainer.classList.remove('mobile-open');
            sidebarContainer.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    if (mobileToggleBtn) mobileToggleBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

    // Close on Escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth < 768) {
            closeSidebar();
        }
    });

    // Auto cleanup when window is resized to desktop/laptop (>= 768px)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            if (sidebarBackdrop) sidebarBackdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});
</script>
