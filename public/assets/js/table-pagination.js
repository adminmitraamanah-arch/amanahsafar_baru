/**
 * Global Table Pagination & Sorting Helper for Amanah Safar
 * Memastikan fitur pagination dan sorting seragam, konsisten, dan responsif di seluruh tabel sistem.
 */
window.AmanahTable = (function() {
    'use strict';

    function init(config) {
        const container = document.getElementById(config.containerId);
        if (!container) return null;

        const rowSelector = config.rowSelector || 'tbody tr.table-row-item';
        const entityName = config.entityName || 'data';
        let defaultPageSize = parseInt(config.defaultPageSize, 10) || 10;
        let currentPage = 1;
        let pageSize = defaultPageSize;
        let currentSort = config.defaultSort || null;

        // Elements
        const searchInput = config.searchInputId ? document.getElementById(config.searchInputId) : null;
        const sortSelect = config.sortSelectId ? document.getElementById(config.sortSelectId) : null;
        const pageSizeSelect = config.pageSizeSelectId ? document.getElementById(config.pageSizeSelectId) : null;
        const summaryEl = config.summaryId ? document.getElementById(config.summaryId) : null;
        const prevBtn = config.prevBtnId ? document.getElementById(config.prevBtnId) : null;
        const nextBtn = config.nextBtnId ? document.getElementById(config.nextBtnId) : null;
        const numbersContainer = config.numbersId ? document.getElementById(config.numbersId) : null;
        const emptyStateRow = config.emptyStateId ? document.getElementById(config.emptyStateId) : null;

        // Collect all data rows
        let allRows = Array.from(container.querySelectorAll(rowSelector));
        const totalRaw = allRows.length;
        let filteredRows = [...allRows];

        // Setup Page Size Select
        if (pageSizeSelect) {
            pageSizeSelect.value = pageSize.toString();
            pageSizeSelect.addEventListener('change', function() {
                pageSize = parseInt(this.value, 10) || 10;
                currentPage = 1;
                render();
            });
        }

        // Setup Sort Select
        if (sortSelect) {
            if (currentSort) sortSelect.value = currentSort;
            sortSelect.addEventListener('change', function() {
                currentSort = this.value;
                syncHeaderSortIcons();
                currentPage = 1;
                applyFiltersAndRender();
            });
        }

        // Setup Clickable Header Sorts
        const sortableHeaders = container.querySelectorAll('th[data-sort-asc]');
        sortableHeaders.forEach(th => {
            th.classList.add('cursor-pointer', 'select-none', 'group');
            // Ensure sort icon placeholder exists
            if (!th.querySelector('.sort-icon')) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-sort text-[10px] text-slate-300 ml-1.5 sort-icon transition-colors group-hover:text-slate-500';
                th.appendChild(icon);
            }

            th.addEventListener('click', function() {
                const ascKey = this.getAttribute('data-sort-asc');
                const descKey = this.getAttribute('data-sort-desc');

                if (currentSort === ascKey) {
                    currentSort = descKey;
                } else {
                    currentSort = ascKey;
                }

                if (sortSelect) sortSelect.value = currentSort;
                syncHeaderSortIcons();
                currentPage = 1;
                applyFiltersAndRender();
            });
        });

        function syncHeaderSortIcons() {
            sortableHeaders.forEach(th => {
                const ascKey = th.getAttribute('data-sort-asc');
                const descKey = th.getAttribute('data-sort-desc');
                const icon = th.querySelector('.sort-icon');
                if (!icon) return;

                if (currentSort === ascKey) {
                    icon.className = 'fas fa-sort-up text-[11px] text-[#5e0078] ml-1.5 sort-icon';
                } else if (currentSort === descKey) {
                    icon.className = 'fas fa-sort-down text-[11px] text-[#5e0078] ml-1.5 sort-icon';
                } else {
                    icon.className = 'fas fa-sort text-[10px] text-slate-300 ml-1.5 sort-icon transition-colors group-hover:text-slate-500';
                }
            });
        }

        // Setup Custom Filter Selects
        if (Array.isArray(config.filters)) {
            config.filters.forEach(filterCfg => {
                const fEl = document.getElementById(filterCfg.selectId);
                if (fEl) {
                    fEl.addEventListener('change', function() {
                        currentPage = 1;
                        applyFiltersAndRender();
                    });
                }
            });
        }

        // Setup Reset Filters Button
        if (config.resetBtnId) {
            const resetBtn = document.getElementById(config.resetBtnId);
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (sortSelect && config.defaultSort) {
                        sortSelect.value = config.defaultSort;
                        currentSort = config.defaultSort;
                        syncHeaderSortIcons();
                    }
                    if (Array.isArray(config.filters)) {
                        config.filters.forEach(f => {
                            const fEl = document.getElementById(f.selectId);
                            if (fEl) fEl.value = f.defaultValue || 'all';
                        });
                    }
                    currentPage = 1;
                    applyFiltersAndRender();
                });
            }
        }

        // Setup Search Input with Debounce
        if (searchInput) {
            let debounceTimer = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    applyFiltersAndRender();
                }, 150);
            });

            // ⌘K or Ctrl+K shortcut
            window.addEventListener('keydown', function(e) {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        }

        // Prev & Next Buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    render();
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                const totalPages = Math.ceil(filteredRows.length / pageSize) || 1;
                if (currentPage < totalPages) {
                    currentPage++;
                    render();
                }
            });
        }

        function applyFiltersAndRender() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            // Read filter select values
            const activeFilterValues = {};
            if (Array.isArray(config.filters)) {
                config.filters.forEach(f => {
                    const el = document.getElementById(f.selectId);
                    if (el) activeFilterValues[f.key] = el.value;
                });
            }

            // Filter rows
            filteredRows = allRows.filter(row => {
                // Search query match
                if (query) {
                    const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
                    if (!searchData.includes(query)) {
                        return false;
                    }
                }

                // Custom filters match
                if (Array.isArray(config.filters)) {
                    for (const f of config.filters) {
                        const expectedVal = activeFilterValues[f.key];
                        if (expectedVal && expectedVal !== (f.defaultValue || 'all')) {
                            const rowVal = row.getAttribute(`data-${f.key}`) || '';
                            if (rowVal !== expectedVal) {
                                return false;
                            }
                        }
                    }
                }

                return true;
            });

            // Sort filtered rows
            sortRows();

            render();
        }

        function sortRows() {
            if (!currentSort || !config.sortDefinitions || !config.sortDefinitions[currentSort]) {
                return;
            }

            const sortFn = config.sortDefinitions[currentSort];
            filteredRows.sort(sortFn);
        }

        function render() {
            const total = filteredRows.length;
            const totalPages = Math.ceil(total / pageSize) || 1;
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            // Determine slice
            const startIdx = (currentPage - 1) * pageSize;
            const endIdx = startIdx + pageSize;
            const currentSlice = filteredRows.slice(startIdx, endIdx);

            // Physically re-order all filtered rows in exact sorted order
            filteredRows.forEach(r => {
                if (r.parentNode) {
                    r.parentNode.appendChild(r);
                }
            });

            // Hide all rows, then show current page slice
            allRows.forEach(r => r.style.display = 'none');
            currentSlice.forEach(r => {
                r.style.display = '';
            });

            // Empty state row if any
            if (emptyStateRow) {
                if (total === 0) {
                    emptyStateRow.style.display = '';
                    if (emptyStateRow.parentNode) {
                        emptyStateRow.parentNode.appendChild(emptyStateRow);
                    }
                } else {
                    emptyStateRow.style.display = 'none';
                }
            }

            // Summary text format: “Menampilkan 1–10 dari 18 pengguna (Total: 18)”
            if (summaryEl) {
                if (total === 0) {
                    summaryEl.textContent = `Menampilkan 0 dari 0 ${entityName} (Total: ${totalRaw})`;
                } else {
                    const displayStart = startIdx + 1;
                    const displayEnd = Math.min(endIdx, total);
                    summaryEl.textContent = `Menampilkan ${displayStart}–${displayEnd} dari ${total} ${entityName} (Total: ${totalRaw})`;
                }
            }

            // Prev / Next button state
            if (prevBtn) {
                prevBtn.disabled = (currentPage <= 1);
                if (currentPage <= 1) {
                    prevBtn.classList.add('opacity-40', 'cursor-not-allowed');
                } else {
                    prevBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                }
            }

            if (nextBtn) {
                nextBtn.disabled = (currentPage >= totalPages || total === 0);
                if (currentPage >= totalPages || total === 0) {
                    nextBtn.classList.add('opacity-40', 'cursor-not-allowed');
                } else {
                    nextBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                }
            }

            // Pagination Number Buttons (Purple active, clean border inactive)
            if (numbersContainer) {
                numbersContainer.innerHTML = '';
                if (total > 0) {
                    const pages = getPageNumbers(currentPage, totalPages);
                    pages.forEach(p => {
                        if (p === '...') {
                            const dots = document.createElement('span');
                            dots.className = 'w-8 h-8 flex items-center justify-center text-slate-400 text-xs font-semibold select-none';
                            dots.textContent = '...';
                            numbersContainer.appendChild(dots);
                        } else {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.textContent = p;
                            if (p === currentPage) {
                                btn.className = 'w-8 h-8 rounded-lg bg-[#5e0078] text-white font-bold text-xs flex items-center justify-center shadow-xs transition';
                            } else {
                                btn.className = 'w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs flex items-center justify-center transition';
                            }
                            btn.addEventListener('click', () => {
                                currentPage = p;
                                render();
                            });
                            numbersContainer.appendChild(btn);
                        }
                    });
                }
            }
        }

        function getPageNumbers(current, total) {
            if (total <= 7) {
                return Array.from({ length: total }, (_, i) => i + 1);
            }
            if (current <= 3) {
                return [1, 2, 3, 4, '...', total];
            }
            if (current >= total - 2) {
                return [1, '...', total - 3, total - 2, total - 1, total];
            }
            return [1, '...', current - 1, current, current + 1, '...', total];
        }

        // Initial setup
        syncHeaderSortIcons();
        applyFiltersAndRender();

        return {
            refresh: function() {
                allRows = Array.from(container.querySelectorAll(rowSelector));
                applyFiltersAndRender();
            },
            setPage: function(p) {
                currentPage = p;
                render();
            }
        };
    }

    return { init: init };
})();
