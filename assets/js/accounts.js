/**
 * T&C Banking - Accounts page behaviour.
 * - Switches between account panels (and updates the URL hash).
 * - Filters transactions by All / Money in / Money out.
 * - Filters transactions with the search box.
 * Self-contained: does not touch main.js.
 */
(function () {
    'use strict';

    var rows = document.querySelectorAll('.account-row');
    var panels = document.querySelectorAll('.accounts-detail');
    if (!rows.length || !panels.length) {
        return;
    }

    /* ---------- Filtering inside one panel ---------- */
    function applyFilters(panel) {
        var activeTab = panel.querySelector('.accounts-detail__tab.is-active');
        var tab = activeTab ? activeTab.getAttribute('data-tab') : 'all';
        var input = panel.querySelector('.accounts-detail__search-input');
        var query = input ? input.value.toLowerCase().trim() : '';
        var txRows = panel.querySelectorAll('.ledger__row');
        var visible = 0;

        txRows.forEach(function (row) {
            var direction = row.getAttribute('data-direction');
            var text = row.getAttribute('data-search') || '';
            var tabOk = tab === 'all' || tab === direction;
            var queryOk = query === '' || text.indexOf(query) !== -1;
            var show = tabOk && queryOk;
            row.hidden = !show;
            row.style.display = show ? '' : 'none';
            if (show) {
                visible++;
            }
        });

        var empty = panel.querySelector('.accounts-detail__empty');
        if (empty) {
            empty.hidden = visible !== 0;
        }
    }

    /* ---------- Show one account panel ---------- */
    function showAccount(id, updateHash) {
        var found = false;

        panels.forEach(function (panel) {
            var match = panel.getAttribute('data-account') === id;
            panel.classList.toggle('is-active', match);
            panel.classList.toggle('is-hidden', !match);
            panel.hidden = !match;
            if (match) {
                found = true;
            }
        });

        if (!found) {
            return false;
        }

        rows.forEach(function (row) {
            var match = row.getAttribute('data-account-target') === id;
            row.classList.toggle('is-active', match);
            row.setAttribute('aria-pressed', match ? 'true' : 'false');
        });

        if (updateHash && window.history && window.history.replaceState) {
            window.history.replaceState(null, '', '#' + id);
        }
        return true;
    }

    /* ---------- Account list clicks ---------- */
    rows.forEach(function (row) {
        row.addEventListener('click', function () {
            showAccount(row.getAttribute('data-account-target'), true);
        });
    });

    /* ---------- Tabs and search, per panel ---------- */
    panels.forEach(function (panel) {
        var tabs = panel.querySelectorAll('.accounts-detail__tab');
        tabs.forEach(function (tabBtn) {
            tabBtn.addEventListener('click', function () {
                tabs.forEach(function (other) {
                    other.classList.remove('is-active');
                    other.setAttribute('aria-selected', 'false');
                });
                tabBtn.classList.add('is-active');
                tabBtn.setAttribute('aria-selected', 'true');
                applyFilters(panel);
            });
        });

        var input = panel.querySelector('.accounts-detail__search-input');
        if (input) {
            input.addEventListener('input', function () {
                applyFilters(panel);
            });
        }

        applyFilters(panel);
    });

    /* ---------- Open the account named in the URL hash ---------- */
    function openFromHash() {
        var id = window.location.hash.replace('#', '');
        if (id) {
            showAccount(id, false);
        }
    }
    openFromHash();
    window.addEventListener('hashchange', openFromHash);
})();