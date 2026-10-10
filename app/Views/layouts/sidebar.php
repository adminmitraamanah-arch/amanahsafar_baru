<aside class="w-64 bg-slate-800 text-white">
    <div class="p-4">
        <div class="flex items-center space-x-3 mb-8 px-2">
            <div class="w-9 h-9 bg-gradient-to-tr from-[#5e0078] to-[#8f20ad] rounded-xl flex items-center justify-center font-extrabold text-sm text-white shadow-lg shadow-purple-950/50">AS</div>
            <span class="font-bold text-lg tracking-wide text-white">Amanah Safar</span>
        </div>
        
        <nav class="space-y-2">
            <?php
            $menus = [
                'admin' => [
                    ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/admin/'],
                    ['icon' => 'fas fa-box', 'label' => 'Packages', 'url' => '/admin/packages'],
                    ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/admin/tasks'],
                ],
                'superadmin' => [
                    ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/superadmin/'],
                    ['icon' => 'fas fa-desktop', 'label' => 'Landing Page', 'url' => '/superadmin/landing'],
                    ['icon' => 'fas fa-ticket-alt', 'label' => 'Vouchers', 'url' => '/superadmin/vouchers'],
                    ['icon' => 'fas fa-box', 'label' => 'Packages', 'url' => '/superadmin/packages'],
                    ['icon' => 'fas fa-users', 'label' => 'Users', 'url' => '/superadmin/users'],
                    ['icon' => 'fas fa-handshake', 'label' => 'Commissions', 'url' => '/superadmin/commissions'],
                    ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/superadmin/tasks'],
                    ['icon' => 'fas fa-check-circle', 'label' => 'Verifications', 'url' => '/superadmin/verifications'],
                ],
                'member' => [
                    ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/app/dashboard'],
                    ['icon' => 'fas fa-users', 'label' => 'Binaan', 'url' => '/app/binaan'],
                    ['icon' => 'fas fa-shopping-cart', 'label' => 'Orders', 'url' => '/app/orders'],
                    ['icon' => 'fas fa-tasks', 'label' => 'Tasks', 'url' => '/app/tasks'],
                ]
            ];
            
            $role = session('role');
            $roleKey = 'member';
            if ($role === 'super_admin' || $role === 'superadmin') {
                $roleKey = 'superadmin';
            } elseif ($role === 'admin') {
                $roleKey = 'admin';
            }
            $menu = $menus[$roleKey] ?? [];
            
            $currentUri = '/' . trim(uri_string(), '/');
            foreach ($menu as $item):
                $targetUrl = rtrim($item['url'], '/');
                $isActive = ($currentUri === $targetUrl) || ($targetUrl !== '' && $targetUrl !== '/superadmin' && $targetUrl !== '/admin' && $targetUrl !== '/app' && strpos($currentUri, $targetUrl) === 0);
            ?>
                <a href="<?= base_url($item['url']) ?>" 
                   class="group flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 ease-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-400 <?= $isActive ? 'bg-gradient-to-r from-purple-900/90 to-[#5e0078]/80 text-white font-semibold shadow-md shadow-purple-950/40 border-l-4 border-[#dfb2f1]' : 'text-slate-300 hover:text-white hover:bg-slate-700/60 hover:translate-x-1' ?>">
                    <i class="<?= $item['icon'] ?> text-base w-5 text-center transition-transform duration-200 ease-out <?= $isActive ? 'text-[#dfb2f1]' : 'text-slate-400 group-hover:text-purple-300 group-hover:scale-110' ?>"></i>
                    <span class="tracking-wide"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</aside>
