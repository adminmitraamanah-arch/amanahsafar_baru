<!-- GLOBAL FLOATING TOAST NOTIFICATION COMPONENT (Bottom-Right) -->
<div id="toastContainer" 
     class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-[99999] flex flex-col items-end gap-2.5 pointer-events-none max-w-sm sm:max-w-md w-full px-4 sm:px-0"
     aria-live="polite" 
     aria-atomic="true">
</div>

<style>
/* Toast Animations & Styles */
.toast-item {
    pointer-events: auto;
    transform: translate3d(24px, 12px, 0) scale(0.96);
    opacity: 0;
    transition: transform 320ms cubic-bezier(0.16, 1, 0.3, 1),
                opacity 280ms cubic-bezier(0.16, 1, 0.3, 1),
                max-height 280ms cubic-bezier(0.16, 1, 0.3, 1),
                margin 280ms cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 200px;
}

.toast-item.toast-visible {
    transform: translate3d(0, 0, 0) scale(1);
    opacity: 1;
}

.toast-item.toast-leaving {
    transform: translate3d(32px, 0, 0) scale(0.95);
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
    const TOAST_DURATION_MS = 4000; // 4 seconds (within 3-5s requirement)
    const MAX_VISIBLE_TOASTS = 3;
    const DEDUPE_WINDOW_MS = 3000;

    const recentToastHashes = new Map();

    const TOAST_TYPES = {
        success: {
            title: 'Berhasil',
            icon: 'fas fa-check-circle',
            border: 'border-emerald-200',
            bg: 'bg-white/95',
            iconBg: 'bg-emerald-100 text-emerald-600',
            textColor: 'text-slate-800',
            titleColor: 'text-emerald-900',
            progressBg: 'bg-emerald-500'
        },
        error: {
            title: 'Terjadi Kesalahan',
            icon: 'fas fa-exclamation-circle',
            border: 'border-rose-200',
            bg: 'bg-white/95',
            iconBg: 'bg-rose-100 text-rose-600',
            textColor: 'text-slate-800',
            titleColor: 'text-rose-900',
            progressBg: 'bg-rose-500'
        },
        warning: {
            title: 'Peringatan',
            icon: 'fas fa-exclamation-triangle',
            border: 'border-amber-200',
            bg: 'bg-white/95',
            iconBg: 'bg-amber-100 text-amber-600',
            textColor: 'text-slate-800',
            titleColor: 'text-amber-900',
            progressBg: 'bg-amber-500'
        },
        info: {
            title: 'Informasi',
            icon: 'fas fa-info-circle',
            border: 'border-purple-200',
            bg: 'bg-white/95',
            iconBg: 'bg-purple-100 text-[#5e0078]',
            textColor: 'text-slate-800',
            titleColor: 'text-purple-900',
            progressBg: 'bg-[#5e0078]'
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

    function createToastElement(options) {
        const type = TOAST_TYPES[options.type] || TOAST_TYPES.info;
        const title = options.title || type.title;
        const message = escapeHtml(options.message);

        const toast = document.createElement('div');
        toast.className = `toast-item relative w-full overflow-hidden rounded-2xl border ${type.border} ${type.bg} backdrop-blur-md shadow-xl shadow-purple-950/10 flex flex-col select-none`;
        toast.setAttribute('role', 'alert');

        toast.innerHTML = `
            <div class="p-3.5 sm:p-4 flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl ${type.iconBg} flex items-center justify-center flex-shrink-0 text-base shadow-sm">
                    <i class="${type.icon}"></i>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <h4 class="text-xs font-bold uppercase tracking-wider ${type.titleColor} leading-tight">${escapeHtml(title)}</h4>
                    <p class="text-xs sm:text-sm font-medium ${type.textColor} mt-0.5 leading-snug break-words">${message}</p>
                </div>
                <button type="button" class="toast-close-btn w-6 h-6 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100/80 flex items-center justify-center text-xs transition cursor-pointer flex-shrink-0 focus:outline-none" aria-label="Tutup notifikasi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="h-1 w-full bg-gray-100 overflow-hidden">
                <div class="toast-progress-bar h-full ${type.progressBg}" style="width: 100%;"></div>
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
        }, 290);
    }

    function showToast(options) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        // Normalisasi input
        if (typeof options === 'string') {
            options = { message: options, type: 'info' };
        }
        if (!options || !options.message) return;

        const type = options.type || 'info';
        const message = String(options.message).trim();
        const duration = options.duration || TOAST_DURATION_MS;

        // Deduplikasi: cegah pesan yang sama persis muncul dalam DEDUPE_WINDOW_MS
        const dedupeKey = `${type}:${message}`;
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

        const toastEl = createToastElement({ ...options, type, message });
        container.appendChild(toastEl);

        // Trigger smooth enter animation on next frame
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toastEl.classList.add('toast-visible');
            });
        });

        // Close button click handler
        const closeBtn = toastEl.querySelector('.toast-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dismissToast(toastEl);
            });
        }

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
        success: (msg, title) => showToast({ message: msg, title, type: 'success' }),
        error: (msg, title) => showToast({ message: msg, title, type: 'error' }),
        warning: (msg, title) => showToast({ message: msg, title, type: 'warning' }),
        info: (msg, title) => showToast({ message: msg, title, type: 'info' })
    };

    // Auto-display flashdata messages injected by backend PHP
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        $flashes = [];

        // Success flash
        if (session()->getFlashdata('success')) {
            $flashes[] = [
                'type' => 'success',
                'message' => session()->getFlashdata('success')
            ];
        }

        // Error flash (string or array)
        if (session()->getFlashdata('error')) {
            $err = session()->getFlashdata('error');
            if (is_array($err)) {
                foreach ($err as $msg) {
                    $flashes[] = ['type' => 'error', 'message' => $msg];
                }
            } else {
                $flashes[] = ['type' => 'error', 'message' => $err];
            }
        }

        // Errors array flash
        if (session()->getFlashdata('errors')) {
            $errs = session()->getFlashdata('errors');
            if (is_array($errs)) {
                foreach ($errs as $msg) {
                    $flashes[] = ['type' => 'error', 'message' => $msg];
                }
            } else {
                $flashes[] = ['type' => 'error', 'message' => $errs];
            }
        }

        // Warning flash
        if (session()->getFlashdata('warning')) {
            $flashes[] = [
                'type' => 'warning',
                'message' => session()->getFlashdata('warning')
            ];
        }

        // Info / General message flash
        if (session()->getFlashdata('info')) {
            $flashes[] = [
                'type' => 'info',
                'message' => session()->getFlashdata('info')
            ];
        } elseif (session()->getFlashdata('message')) {
            $flashes[] = [
                'type' => 'info',
                'message' => session()->getFlashdata('message')
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
