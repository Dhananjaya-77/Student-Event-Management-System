/**
 * EventHub Theme Manager
 * Supports complete Dark Mode and Light Mode with persistence and zero flicker.
 */
(function () {
    const STORAGE_KEY = 'eventhub-theme';
    const FALLBACK_KEY = 'eventhub_theme';

    function getSavedTheme() {
        try {
            return localStorage.getItem(STORAGE_KEY) || localStorage.getItem(FALLBACK_KEY);
        } catch (e) {
            return null;
        }
    }

    function getSystemTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    function getCurrentTheme() {
        const saved = getSavedTheme();
        if (saved === 'dark' || saved === 'light') {
            return saved;
        }
        return getSystemTheme();
    }

    function updateToggleButtons(theme) {
        const isDark = theme === 'dark';
        const nextTheme = isDark ? 'light' : 'dark';
        const labelText = isDark ? 'Light mode' : 'Dark mode';
        const iconSymbol = isDark ? '☀️' : '🌙';
        const tooltip = `Switch to ${nextTheme} mode`;

        // Floating toggle button
        const floatingBtn = document.getElementById('themeToggle');
        if (floatingBtn) {
            floatingBtn.innerHTML = `<span class="theme-toggle-icon" aria-hidden="true">${iconSymbol}</span><span class="theme-toggle-text">${labelText}</span>`;
            floatingBtn.setAttribute('aria-label', tooltip);
            floatingBtn.setAttribute('title', tooltip);
            floatingBtn.setAttribute('data-current-theme', theme);
        }

        // Header / inline toggle buttons
        const inlineBtns = document.querySelectorAll('.theme-toggle-btn, [data-theme-toggle]');
        inlineBtns.forEach(btn => {
            if (btn === floatingBtn) return;
            const iconEl = btn.querySelector('.theme-icon') || btn.querySelector('i');
            if (iconEl) {
                if (iconEl.tagName.toLowerCase() === 'i') {
                    // Tabler icon support
                    iconEl.className = isDark ? 'ti ti-sun theme-icon' : 'ti ti-moon theme-icon';
                } else {
                    iconEl.textContent = iconSymbol;
                }
            } else {
                btn.innerHTML = `<span class="theme-icon" aria-hidden="true">${iconSymbol}</span>`;
            }
            btn.setAttribute('aria-label', tooltip);
            btn.setAttribute('title', tooltip);
        });
    }

    function applyTheme(theme) {
        const validTheme = theme === 'dark' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', validTheme);
        document.documentElement.dataset.theme = validTheme;

        if (document.body) {
            document.body.classList.toggle('dark', validTheme === 'dark');
            document.body.classList.toggle('light', validTheme === 'light');
        }

        try {
            localStorage.setItem(STORAGE_KEY, validTheme);
            localStorage.setItem(FALLBACK_KEY, validTheme);
        } catch (e) {
            // Ignore quota or disabled storage errors
        }

        updateToggleButtons(validTheme);
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || getCurrentTheme();
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
    }

    // Apply immediately to prevent FOUC (flash of unstyled content)
    const initialTheme = getCurrentTheme();
    document.documentElement.setAttribute('data-theme', initialTheme);
    document.documentElement.dataset.theme = initialTheme;

    // Set up UI components once DOM is ready
    function initUI() {
        if (document.body) {
            document.body.classList.toggle('dark', initialTheme === 'dark');
            document.body.classList.toggle('light', initialTheme === 'light');
        }

        // Create floating toggle button if not present
        let floatingBtn = document.getElementById('themeToggle');
        if (!floatingBtn) {
            floatingBtn = document.createElement('button');
            floatingBtn.id = 'themeToggle';
            floatingBtn.className = 'theme-toggle';
            floatingBtn.type = 'button';
            floatingBtn.setAttribute('aria-live', 'polite');
            document.body.appendChild(floatingBtn);
        }

        floatingBtn.onclick = function (e) {
            e.preventDefault();
            toggleTheme();
        };

        // Attach listeners to any inline buttons
        const inlineBtns = document.querySelectorAll('.theme-toggle-btn, [data-theme-toggle]');
        inlineBtns.forEach(btn => {
            btn.onclick = function (e) {
                e.preventDefault();
                toggleTheme();
            };
        });

        updateToggleButtons(initialTheme);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initUI);
    } else {
        initUI();
    }

    // Listen for OS theme preference changes if user hasn't explicitly saved a choice
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const handler = (e) => {
            const saved = getSavedTheme();
            if (!saved) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        };
        if (mediaQuery.addEventListener) {
            mediaQuery.addEventListener('change', handler);
        } else if (mediaQuery.addListener) {
            mediaQuery.addListener(handler);
        }
    }

    // Synchronize theme across multiple open tabs
    window.addEventListener('storage', (e) => {
        if (e.key === STORAGE_KEY || e.key === FALLBACK_KEY) {
            if (e.newValue && (e.newValue === 'dark' || e.newValue === 'light')) {
                applyTheme(e.newValue);
            }
        }
    });

    // Expose toggleTheme to global scope for any custom onclick handlers
    window.toggleTheme = toggleTheme;
    window.applyTheme = applyTheme;
})();
