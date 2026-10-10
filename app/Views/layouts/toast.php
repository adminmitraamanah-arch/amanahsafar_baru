<!-- GLOBAL MINIMALIST FLOATING TOAST NOTIFICATION (Bottom-Right) -->
<div id="toastContainer" 
     class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[99999] flex flex-col items-end gap-2.5 pointer-events-none w-[calc(100vw-2rem)] sm:w-auto max-w-sm sm:max-w-md"
     aria-live="polite" 
     aria-atomic="true">
</div>

<style>
/* Modern Minimalist Toast Animations & Styles */
.toast-item {
    pointer-events: auto;
    transform: translate3d(24px, 0, 0);
    opacity: 0;
    transition: transform 280ms cubic-bezier(0.16, 1, 0.3, 1),
                opacity 240ms cubic-bezier(0.16, 1, 0.3, 1),
                max-height 260ms cubic-bezier(0.16, 1, 0.3, 1),
                margin 260ms cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 140px;
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

    // Exactly Two Primary Color Palettes: Hijau (Green) and Merah (Red)
    const TOAST_TYPES = {
        green: {
            border: 'border-emerald-200/90',
            bg: 'bg-white/95',
            iconBg: 'bg-emerald-50 text-emerald-600 border border-emerald-100',
            textColor: 'text-slate-800',
            progressBg: 'bg-emerald-500'
        },
        red: {
            border: 'border-rose-200/90',
            bg: 'bg-white/95',
            iconBg: 'bg-rose-50 text-rose-600 border border-rose-100',
            textColor: 'text-slate-800',
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

    // Determine strict 2-color mapping: either 'green' or 'red'
    function resolveColorType(type, message) {
        const lowerType = String(type || '').toLowerCase();
        const lowerMsg = String(message || '').toLowerCase();

        // Merah: logout, penghapusan data, dan kegagalan aksi / nonaktif
        if (
            lowerType === 'red' ||
            lowerType === 'error' ||
            lowerType === 'danger' ||
            lowerType === 'logout' ||
            lowerType === 'deleted' ||
            lowerType === 'deactivated' ||
            lowerType === 'warning' ||
            lowerMsg.includes('logout') ||
            lowerMsg.includes('dihapus') ||
            lowerMsg.includes('hapus') ||
            lowerMsg.includes('dinonaktifkan') ||
            lowerMsg.includes('nonaktif') ||
            lowerMsg.includes('gagal') ||
            lowerMsg.includes('kesalahan') ||
            lowerMsg.includes('tidak valid') ||
            lowerMsg.includes('tidak sesuai') ||
            lowerMsg.includes('ditolak') ||
            lowerMsg.includes('tidak ditemukan')
        ) {
            return 'red';
        }

        // Hijau: login berhasil dan penambahan data berhasil, tersimpan, diaktifkan
        return 'green';
    }

    // Contextual icon matching
    function getContextualIcon(colorType, message) {
        const lowerMsg = String(message || '').toLowerCase();

        if (colorType === 'red') {
            if (lowerMsg.includes('logout') || lowerMsg.includes('keluar')) {
                return 'fas fa-arrow-right-from-bracket';
            }
            if (lowerMsg.includes('hapus') || lowerMsg.includes('dihapus')) {
                return 'fas fa-trash-alt';
            }
            if (lowerMsg.includes('nonaktif')) {
                return 'fas fa-user-slash';
            }
            return 'fas fa-circle-exclamation';
        } else {
            if (lowerMsg.includes('login') || lowerMsg.includes('masuk')) {
                return 'fas fa-right-to-bracket';
            }
            if (lowerMsg.includes('tambah') || lowerMsg.includes('ditambahkan') || lowerMsg.includes('buat') || lowerMsg.includes('dibuat')) {
                return 'fas fa-plus';
            }
            if (lowerMsg.includes('simpan') || lowerMsg.includes('tersimpan') || lowerMsg.includes('perbarui')) {
                return 'fas fa-check-double';
            }
            return 'fas fa-check';
        }
    }

    function createToastElement(options) {
        const rawType = options.type || 'green';
        const rawMessage = options.message || '';
        const colorType = resolveColorType(rawType, rawMessage);
        const palette = TOAST_TYPES[colorType];
        const iconClass = getContextualIcon(colorType, rawMessage);
        const safeMessage = escapeHtml(rawMessage);

        const toast = document.createElement('div');
        toast.className = `toast-item relative w-full overflow-hidden rounded-xl border ${palette.border} ${palette.bg} backdrop-blur-md shadow-lg shadow-slate-900/5 flex flex-col select-none`;
        toast.setAttribute('role', 'alert');

        // Modern, clean, minimal layout without manual close button
        toast.innerHTML = `
            <div class="px-4 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg ${palette.iconBg} flex items-center justify-center flex-shrink-0 text-xs shadow-none">
                    <i class="${iconClass}"></i>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <p class="text-xs sm:text-sm font-semibold ${palette.textColor} leading-snug break-words">${safeMessage}</p>
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

        const message = String(options.message).trim();
        const duration = options.duration || TOAST_DURATION_MS;
        const colorType = resolveColorType(options.type, message);

        // Deduplikasi: cegah pesan yang sama persis muncul dalam DEDUPE_WINDOW_MS
        const dedupeKey = `${colorType}:${message}`;
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

        const toastEl = createToastElement({ ...options, message, type: colorType });
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
        green: (msg) => showToast({ message: msg, type: 'green' }),
        red: (msg) => showToast({ message: msg, type: 'red' }),
        success: (msg) => showToast({ message: msg, type: 'green' }),
        error: (msg) => showToast({ message: msg, type: 'red' }),
        warning: (msg) => showToast({ message: msg, type: 'red' }),
        info: (msg) => showToast({ message: msg, type: 'green' })
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

        // 2. Logout flash -> Merah (Red)
        if (session()->getFlashdata('logout')) {
            $flashes[] = [
                'type' => 'red',
                'message' => session()->getFlashdata('logout')
            ];
        }

        // 3. Deletion flash -> Merah (Red)
        if (session()->getFlashdata('deleted')) {
            $flashes[] = [
                'type' => 'red',
                'message' => session()->getFlashdata('deleted')
            ];
        }

        // 4. Deactivation flash -> Merah (Red)
        if (session()->getFlashdata('deactivated')) {
            $flashes[] = [
                'type' => 'red',
                'message' => session()->getFlashdata('deactivated')
            ];
        }

        // 5. Error flash (string or array) -> Merah (Red)
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

        // 6. Validation errors array -> Merah (Red)
        if (session()->getFlashdata('errors')) {
            $errs = session()->getFlashdata('errors');
            if (is_array($errs)) {
                foreach ($errs as $msg) {
                    $flashes[] = ['type' => 'red', 'message' => $msg];
                }
            } else {
                $flashes[] = ['type' => 'red', 'message' => $errs];
            }
        }

        // 7. Danger or Warning flash -> Merah (Red)
        if (session()->getFlashdata('danger')) {
            $flashes[] = ['type' => 'red', 'message' => session()->getFlashdata('danger')];
        } elseif (session()->getFlashdata('warning')) {
            $flashes[] = ['type' => 'red', 'message' => session()->getFlashdata('warning')];
        }

        // 8. Info / General message flash
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
