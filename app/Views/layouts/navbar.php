<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold">AS</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Amanah Safar</span>
                </a>
            </div>
            <div class="flex items-center space-x-3">
                <?php if (session('logged_in')): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 px-3.5 py-2 rounded-xl text-gray-700 hover:text-[#5e0078] hover:bg-purple-50/80 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#5e0078]">
                            <i class="fas fa-user-circle text-xl text-[#5e0078]"></i>
                            <span class="text-sm font-semibold"><?= session('nama') ?></span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-purple-100/70 py-1.5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transform translate-y-1.5 group-hover:translate-y-0 transition-all duration-200 ease-out z-50">
                            <a href="<?= base_url('/dashboard') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-purple-50 hover:text-[#5e0078] transition-colors duration-150">
                                <i class="fas fa-columns text-gray-400"></i> Dashboard
                            </a>
                            <a href="<?= base_url('/logout') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors duration-150 border-t border-gray-100">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('/login') ?>" class="btn-interactive px-4 py-2 text-sm font-semibold text-[#5e0078] hover:text-[#4c0062] rounded-lg transition-colors">
                        Login
                    </a>
                    <a href="<?= base_url('/register') ?>" class="btn-interactive px-5 py-2 bg-[#5e0078] hover:bg-[#4c0062] text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
