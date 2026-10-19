/**
 * T&C Banking - main script (vanilla JS).
 * - Mobile navigation menu toggle
 * - Gift card tabs (Send / Receive / Redeem)
 * - Placeholder forms (no backend wired up yet)
 */
(function () {
    'use strict';

    /* ---------- Mobile navigation toggle ---------- */
    function initNavToggle() {
        var toggle = document.querySelector('.nav-toggle');
        var nav = document.getElementById('primary-nav');

        if (!toggle || !nav) {
            return;
        }

        function closeMenu() {
            nav.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }

        function openMenu() {
            nav.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
        }

        toggle.addEventListener('click', function () {
            if (nav.classList.contains('is-open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Close the menu when a nav link is chosen (single-page navigation feel).
        nav.addEventListener('click', function (event) {
            if (event.target.closest('.nav-link')) {
                closeMenu();
            }
        });

        // Close the menu if the viewport is resized to desktop width.
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                closeMenu();
            }
        });

        // Close on Escape for keyboard accessibility.
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });
    }

    /* ---------- Gift card tabs (WAI-ARIA tab pattern) ---------- */
    function initGiftTabs() {
        var tablist = document.querySelector('.gift-tabs');

        if (!tablist) {
            return;
        }

        var tabs = Array.prototype.slice.call(tablist.querySelectorAll('.gift-tab'));
        var panels = Array.prototype.slice.call(document.querySelectorAll('.gift-panel'));

        function activate(selectedTab) {
            tabs.forEach(function (tab) {
                var isSelected = (tab === selectedTab);
                tab.classList.toggle('is-active', isSelected);
                tab.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                tab.setAttribute('tabindex', isSelected ? '0' : '-1');
            });

            panels.forEach(function (panel) {
                var isMatch = (panel.getAttribute('data-gift-panel') === selectedTab.getAttribute('data-gift-tab'));
                panel.classList.toggle('is-active', isMatch);
                if (isMatch) {
                    panel.removeAttribute('hidden');
                } else {
                    panel.setAttribute('hidden', '');
                }
            });
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activate(tab);
            });

            // Left/Right arrow key navigation between tabs.
            tab.addEventListener('keydown', function (event) {
                var index = tabs.indexOf(tab);
                var next = null;

                if (event.key === 'ArrowRight') {
                    next = tabs[(index + 1) % tabs.length];
                } else if (event.key === 'ArrowLeft') {
                    next = tabs[(index - 1 + tabs.length) % tabs.length];
                } else if (event.key === 'Home') {
                    next = tabs[0];
                } else if (event.key === 'End') {
                    next = tabs[tabs.length - 1];
                }

                if (next) {
                    event.preventDefault();
                    next.focus();
                    activate(next);
                }
            });
        });
    }

    /* ---------- Placeholder forms ----------
     * Buttons/links are placeholders until a backend exists;
     * stop the browser from navigating or showing validation errors. */
    function initPlaceholderForms() {
        var forms = document.querySelectorAll('form[data-placeholder]');

        Array.prototype.forEach.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initNavToggle();
        initGiftTabs();
        initPlaceholderForms();
    });
})();