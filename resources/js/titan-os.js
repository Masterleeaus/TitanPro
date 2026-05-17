// Titan OS JavaScript
//
// This file defines basic behaviours for the Business OS shell.  In this
// initial pass it only listens for launcher toggle events and could be
// extended to handle keyboard shortcuts, focus trapping and responsive
// adjustments.

document.addEventListener('DOMContentLoaded', () => {
    // ===== Launcher logic =====
    const launcher = document.querySelector('[data-titan-os-launcher]');
    const launcherPanel = document.querySelector('[data-titan-os-launcher-panel]');
    const launcherToggleButtons = document.querySelectorAll('[data-titan-os-launcher-toggle]');

    let launcherOpen = false;
    let activeCategory = 'all';
    let searchQuery = '';

    if (launcher) {
        // Helper functions
        const openLauncher = () => {
            launcher.classList.remove('hidden');
            launcherOpen = true;
            // Prevent body scroll when modal is open
            document.body.classList.add('overflow-hidden');
            updateCards();
        };
        const closeLauncher = () => {
            launcher.classList.add('hidden');
            launcherOpen = false;
            document.body.classList.remove('overflow-hidden');
        };
        const updateCards = () => {
            const cards = launcher.querySelectorAll('[data-app-card]');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const category = card.getAttribute('data-category') || 'other';
                const matchesSearch = searchQuery === '' || name.includes(searchQuery);
                const matchesCategory = activeCategory === 'all' || category === activeCategory;
                if (matchesSearch && matchesCategory) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        };
        const resetCategoryButtons = () => {
            const buttons = launcher.querySelectorAll('[data-category-button]');
            buttons.forEach(btn => {
                const cat = btn.getAttribute('data-category');
                // Reset classes
                btn.classList.remove('bg-gray-200','dark:bg-gray-800','font-semibold');
                btn.classList.remove('bg-gray-100','dark:bg-gray-700');
                // Apply state
                if (cat === activeCategory) {
                    btn.classList.add('bg-gray-200','dark:bg-gray-800','font-semibold');
                } else {
                    btn.classList.add('bg-gray-100','dark:bg-gray-700');
                }
            });
        };

        // Toggle buttons open/close
        launcherToggleButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (launcherOpen) {
                    closeLauncher();
                } else {
                    openLauncher();
                }
            });
        });

        // Listen to global events
        window.addEventListener('titan-os-toggle-launcher', () => {
            if (launcherOpen) {
                closeLauncher();
            } else {
                openLauncher();
            }
        });
        window.addEventListener('titan-os-close-launcher', () => {
            if (launcherOpen) {
                closeLauncher();
            }
        });

        // Global keybindings: Ctrl+K toggle launcher; Escape closes
        window.addEventListener('keydown', (event) => {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                if (launcherOpen) {
                    closeLauncher();
                } else {
                    openLauncher();
                }
            }
            if (event.key === 'Escape' && launcherOpen) {
                event.preventDefault();
                closeLauncher();
            }
        });

        // Close on backdrop click
        launcher.addEventListener('click', (e) => {
            if (e.target === launcher) {
                closeLauncher();
            }
        });

        // Search input
        const searchInput = launcher.querySelector('[data-launcher-search]');
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                searchQuery = searchInput.value.trim().toLowerCase();
                updateCards();
            });
        }

        // Category buttons
        const categoryButtons = launcher.querySelectorAll('[data-category-button]');
        categoryButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                activeCategory = btn.getAttribute('data-category') || 'all';
                resetCategoryButtons();
                updateCards();
            });
        });
        // Initial state
        resetCategoryButtons();
        updateCards();
    }

    // ===== Assistant dock logic =====
    const dock = document.querySelector('[data-titan-zero-dock]');
    if (dock) {
        const toggleBtn = dock.querySelector('[data-titan-zero-dock-toggle]');
        const panel = dock.querySelector('[data-titan-zero-dock-panel]');
        const modeBtn = dock.querySelector('[data-titan-zero-dock-toggle-mode]');
        const closeBtn = dock.querySelector('[data-titan-zero-dock-close]');
        let mode = panel ? panel.getAttribute('data-mode') || 'dock' : 'dock';

        const updateMode = () => {
            if (!panel) return;
            if (mode === 'dock') {
                panel.classList.remove('fixed','inset-0','w-full','h-full');
                panel.classList.add('w-96','h-96');
                panel.setAttribute('data-mode','dock');
            } else {
                panel.classList.add('fixed','inset-0','w-full','h-full');
                panel.classList.remove('w-96','h-96');
                panel.setAttribute('data-mode','fullscreen');
            }
        };
        const openDock = () => {
            if (!panel || !toggleBtn) return;
            panel.classList.remove('hidden');
            toggleBtn.classList.add('hidden');
            mode = 'dock';
            updateMode();
        };
        const closeDock = () => {
            if (!panel || !toggleBtn) return;
            panel.classList.add('hidden');
            toggleBtn.classList.remove('hidden');
        };
        const toggleMode = () => {
            mode = (mode === 'dock') ? 'fullscreen' : 'dock';
            updateMode();
        };

        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openDock();
            });
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                closeDock();
            });
        }
        if (modeBtn) {
            modeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                toggleMode();
            });
        }
    }
});