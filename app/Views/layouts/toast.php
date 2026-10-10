<!-- GLOBAL FLOATING TOAST NOTIFICATION COMPONENT (Bottom-Right) -->
<div id="toastContainer" 
     class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[99999] flex flex-col items-end gap-2.5 pointer-events-none w-[calc(100vw-2rem)] sm:w-auto max-w-sm sm:max-w-md"
     aria-live="polite" 
     aria-atomic="true">
</div>

<style>
/* Modern Minimalist Toast Animations & Styles Matching Reference Screenshot */
.toast-item {
    pointer-events: auto;
    transform: translate3d(24px, 0, 0);
    opacity: 0;
    transition: transform 280ms cubic-bezier(0.16, 1, 0.3, 1),
                opacity 240ms cubic-bezier(0.16, 1, 0.3, 1),
                max-height 260ms cubic-bezier(0.16, 1, 0.3, 1),
                margin 260ms cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 160px;
}

.toast-item.toast-visible {
    transform: translate3d(0, 0, 0);
    opacity: 1;
}

.toast-item.toast-leaving {
    transform: translate3d(24px, 0, 0);
    opacity: 0;
    max-height: 0;
    margin-bottom: -10px;
    pointer-events: none;
}

.toast-progress-bar {
    transition: width linear;
}

@media (prefers-reduced-motion: reduce) {
    .toast-item {
        transition: none !important;
        transform: none !important;
    }
}
</style>

<script>
(function() {
    // Toast Configuration
    const TOAST_DURATION_MS = 3800; // 3.8 seconds (within 3-5s requirement)
    const MAX_VISIBLE_TOASTS = 3;
    const DEDUPE_WINDOW_MS = 3000;

    const recentToastHashes = new Map();

    // Palet Warna: Hijau (Success), Kuning (Warning / Role Mismatch), Merah (Error / Logout / Deletion)
    const TOAST_TYPES = {
        green: {
            leftBorder: 'border-l-4 border-l-emerald-500',
            border: 'border border-emerald-200/80',
            bg: 'bg-emerald-50/95',
            iconBg: 'bg-emerald-500',
            icon: 'fas fa-check',
            defaultTitle: 'Berhasil',
            progressBg: 'bg-emerald-500'
        },
        warning: {
            leftBorder: 'border-l-4 border-l-amber-500',
            border: 'border border-amber-200/80',
            bg: 'bg-amber-50/95',
            iconBg: 'bg-amber-500',
            icon: 'fas fa-info',
            defaultTitle: 'Peringatan',
            progressBg: 'bg-amber-500'
        },
        red: {
            leftBorder: 'border-l-4 border-l-rose-500',
            border: 'border border-rose-200/80',
            bg: 'bg-rose-50/95',
            iconBg: 'bg-rose-500',
            icon: 'fas fa-exclamation',
            defaultTitle: 'Terjadi Kesalahan',
            progressBg: 'bg-rose-500'
        }
    };

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Resolusi tipe warna dan judul kontekstual
    function resolveToastDetails(options) {
        let type = String(options.type || 'green').toLowerCase();
        const msg = String(options.message || '').trim();
        const lowerMsg = msg.toLowerCase();
        let title = options.title || '';

        // Deteksi Tipe Kuning (Warning / Role Mismatch)
        if (type === 'warning' || type === 'amber' || type === 'yellow' || lowerMsg.includes('role tidak sesuai') || lowerMsg.includes('pemilihan role') || lowerMsg.includes('role yang dipilih')) {
            type = 'warning';
            if (!title) {
                title = (lowerMsg.includes('role')) ? 'Role Tidak Sesuai' : 'Peringatan';
            }
        } 
        // Deteksi Tipe Merah (Error / Logout / Deletion / Failure)
        else if (
            type === 'red' || 
            type === 'error' || 
            type === 'danger' || 
            type === 'logout' || 
            type === 'deleted' || 
            type === 'deactivated' || 
            lowerMsg.includes('logout') || 
            lowerMsg.includes('dihapus') || 
            lowerMsg.includes('hapus') || 
            lowerMsg.includes('dinonaktifkan') || 
            lowerMsg.includes('nonaktif') || 
            lowerMsg.includes('gagal') || 
            lowerMsg.includes('kesalahan') || 
            lowerMsg.includes('tidak sesuai') || 
            lowerMsg.includes('tidak valid') || 
            lowerMsg.includes('ditolak') || 
            lowerMsg.includes('tidak ditemukan')
        ) {
            type = 'red';
            if (!title) {
                if (lowerMsg.includes('logout')) title = 'Logout';
                else if (lowerMsg.includes('hapus') || lowerMsg.includes('dihapus')) title = 'Data Dihapus';
                else if (lowerMsg.includes('nonaktif')) title = 'Akun Dinonaktifkan';
                else title = 'Terjadi Kesalahan';
            }
        } 
        // Deteksi Tipe Hijau (Success / Login / Add / Save / Activate)
        else {
            type = 'green';
            if (!title) {
                if (lowerMsg.includes('login') || lowerMsg.includes('masuk')) title = 'Login Berhasil';
                else if (lowerMsg.includes('tambah') || lowerMsg.includes('ditambahkan') || lowerMsg.includes('dibuat')) title = 'Data Ditambahkan';
                else if (lowerMsg.includes('tersimpan') || lowerMsg.includes('perubahan') || lowerMsg.includes('disimpan')) title = 'Perubahan Tersimpan';
                else if (lowerMsg.includes('diaktifkan')) title = 'Akun Diaktifkan';
                else title = 'Berhasil';
            }
        }

        const palette = TOAST_TYPES[type] || TOAST_TYPES.green;
        let icon = palette.icon;
        if (type === 'red') {
            if (lowerMsg.includes('logout')) icon = 'fas fa-arrow-right-from-bracket';
            else if (lowerMsg.includes('hapus')) icon = 'fas fa-trash-alt';
            else if (lowerMsg.includes('nonaktif')) icon = 'fas fa-user-slash';
        } else if (type === 'green') {
            if (lowerMsg.includes('login') || lowerMsg.includes('masuk')) icon = 'fas fa-right-to-bracket';
            else if (lowerMsg.includes('tambah') || lowerMsg.includes('buat')) icon = 'fas fa-plus';
            else if (lowerMsg.includes('tersimpan') || lowerMsg.includes('perubahan')) icon = 'fas fa-check-double';
        }

        return {
            type,
            title,
            message: msg,
            palette,
            icon
        };
    }

    function createToastElement(details) {
        const { palette, title, message, icon } = details;

        const toast = document.createElement('div');
        toast.className = `toast-item relative w-full overflow-hidden rounded-xl ${palette.border} ${palette.leftBorder} ${palette.bg} backdrop-blur-md shadow-lg shadow-slate-900/5 flex flex-col select-none`;
        toast.setAttribute('role', 'alert');

        // Layout persis screenshot referensi: tanpa tombol close manual
        toast.innerHTML = `
            <div class="p-3.5 sm:p-4 flex items-start gap-3">
                <div class="w-6 h-6 rounded-full ${palette.iconBg} text-white flex items-center justify-center text-[11px] flex-shrink-0 shadow-sm mt-0.5">
                    <i class="${icon}"></i>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug tracking-tight">${escapeHtml(title)}</h4>
                    <p class="text-xs text-slate-600 font-normal mt-0.5 leading-snug break-words">${escapeHtml(message)}</p>
                </div>
            </div>
            <div class="h-0.5 w-full bg-slate-100 overflow-hidden">
                <div class="toast-progress-bar h-full ${palette.progressBg}" style="width: 100%;"></div>
            </div>
        `;

        return toast;
    }

    function dismissToast(toast) {
        if (!toast || toast.classList.contains('toast-leaving')) return;
        toast.classList.remove('toast-visible');
        toast.classList.add('toast-leaving');

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 270);
    }

    function showToast(options) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        // Normalisasi input
        if (typeof options === 'string') {
            options = { message: options, type: 'green' };
        }
        if (!options || !options.message) return;

        const details = resolveToastDetails(options);
        const duration = options.duration || TOAST_DURATION_MS;

        // Deduplikasi: cegah pesan yang sama persis muncul dalam DEDUPE_WINDOW_MS
        const dedupeKey = `${details.type}:${details.message}`;
        const now = Date.now();
        if (recentToastHashes.has(dedupeKey)) {
            const lastSeen = recentToastHashes.get(dedupeKey);
            if (now - lastSeen < DEDUPE_WINDOW_MS) {
                return; // Duplicate detected, ignore
            }
        }
        recentToastHashes.set(dedupeKey, now);

        // Bersihkan hash lama
        for (const [key, timestamp] of recentToastHashes.entries()) {
            if (now - timestamp > DEDUPE_WINDOW_MS * 2) {
                recentToastHashes.delete(key);
            }
        }

        // Limit visible toasts (FIFO)
        const activeToasts = container.querySelectorAll('.toast-item:not(.toast-leaving)');
        if (activeToasts.length >= MAX_VISIBLE_TOASTS) {
            dismissToast(activeToasts[0]);
        }

        const toastEl = createToastElement(details);
        container.appendChild(toastEl);

        // Trigger smooth enter animation on next frame
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toastEl.classList.add('toast-visible');
            });
        });

        // Auto dismiss & Progress bar logic
        const progressBar = toastEl.querySelector('.toast-progress-bar');
        let remainingTime = duration;
        let startTime = Date.now();
        let timerId = null;

        function startProgress(ms) {
            if (progressBar) {
                progressBar.style.transitionDuration = `${ms}ms`;
                progressBar.style.width = '0%';
            }
            timerId = setTimeout(() => {
                dismissToast(toastEl);
            }, ms);
        }

        function pauseProgress() {
            if (timerId) {
                clearTimeout(timerId);
                timerId = null;
                const elapsed = Date.now() - startTime;
                remainingTime = Math.max(500, remainingTime - elapsed);
                if (progressBar) {
                    const computedWidth = window.getComputedStyle(progressBar).width;
                    progressBar.style.transitionDuration = '0ms';
                    progressBar.style.width = computedWidth;
                }
            }
        }

        function resumeProgress() {
            startTime = Date.now();
            startProgress(remainingTime);
        }

        // Pause on mouse hover, resume on mouse leave
        toastEl.addEventListener('mouseenter', pauseProgress);
        toastEl.addEventListener('mouseleave', resumeProgress);

        // Initial progress start
        startProgress(duration);
    }

    // Expose global Toast API
    window.showToast = showToast;
    window.toast = {
        green: (msg, title) => showToast({ message: msg, title, type: 'green' }),
        success: (msg, title) => showToast({ message: msg, title, type: 'green' }),
        warning: (msg, title) => showToast({ message: msg, title, type: 'warning' }),
        amber: (msg, title) => showToast({ message: msg, title, type: 'warning' }),
        red: (msg, title) => showToast({ message: msg, title, type: 'red' }),
        error: (msg, title) => showToast({ message: msg, title, type: 'red' }),
        info: (msg, title) => showToast({ message: msg, title, type: 'warning' })
    };

    // Listen to custom DOM event for decoupled notifications
    window.addEventListener('app:toast', function(e) {
        if (e.detail) {
            showToast(e.detail);
        }
    });

    // Auto-display flashdata messages injected by backend PHP
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        $flashes = [];

        // 1. Success flash -> Hijau (Green)
        if (session()->getFlashdata('success')) {
            $flashes[] = [
                'type' => 'green',
                'message' => session()->getFlashdata('success')
            ];
        }

        // 2. Warning flash -> Kuning (Yellow / Amber)
        if (session()->getFlashdata('warning')) {
            $msg = session()->getFlashdata('warning');
            $flashes[] = [
                'type' => 'warning',
                'title' => (stripos($msg, 'role') !== false) ? 'Role Tidak Sesuai' : 'Peringatan',
                'message' => $msg
            ];
        }

        // 3. Logout flash -> Merah (Red)
        if (session()->getFlashdata('logout')) {
            $flashes[] = [
                'type' => 'red',
                'title' => 'Logout',
                'message' => session()->getFlashdata('logout')
            ];
        }

        // 4. Deletion flash -> Merah (Red)
        if (session()->getFlashdata('deleted')) {
            $flashes[] = [
                'type' => 'red',
                'title' => 'Data Dihapus',
                'message' => session()->getFlashdata('deleted')
            ];
        }

        // 5. Deactivation flash -> Merah (Red)
        if (session()->getFlashdata('deactivated')) {
            $flashes[] = [
                'type' => 'red',
                'title' => 'Akun Dinonaktifkan',
                'message' => session()->getFlashdata('deactivated')
            ];
        }

        // 6. Error flash (string or array) -> Merah (Red)
        if (session()->getFlashdata('error')) {
            $err = session()->getFlashdata('error');
            if (is_array($err)) {
                foreach ($err as $msg) {
                    $flashes[] = ['type' => 'red', 'message' => $msg];
                }
            } else {
                $flashes[] = ['type' => 'red', 'message' => $err];
            }
        }

        // 7. Validation errors array -> Merah (Red)
        if (session()->getFlashdata('errors')) {
            $errs = session()->getFlashdata('errors');
            if (is_array($errs)) {
                foreach ($errs as $msg) {
                    $flashes[] = ['type' => 'red', 'title' => 'Validasi Form', 'message' => $msg];
                }
            } else {
                $flashes[] = ['type' => 'red', 'title' => 'Validasi Form', 'message' => $errs];
            }
        }

        // 8. Danger flash -> Merah (Red)
        if (session()->getFlashdata('danger')) {
            $flashes[] = ['type' => 'red', 'message' => session()->getFlashdata('danger')];
        }

        // 9. Info / General message flash
        if (session()->getFlashdata('info')) {
            $flashes[] = [
                'type' => 'green',
                'message' => session()->getFlashdata('info')
            ];
        } elseif (session()->getFlashdata('message')) {
            $flashes[] = [
                'type' => 'green',
                'message' => session()->getFlashdata('message')
            ];
        } elseif (session()->getFlashdata('status')) {
            $flashes[] = [
                'type' => 'green',
                'message' => session()->getFlashdata('status')
            ];
        }
        ?>

        const initialFlashes = <?= json_encode($flashes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        if (Array.isArray(initialFlashes) && initialFlashes.length > 0) {
            initialFlashes.forEach((flash, index) => {
                setTimeout(() => {
                    showToast(flash);
                }, index * 200);
            });
        }
    });
})();
</script>
