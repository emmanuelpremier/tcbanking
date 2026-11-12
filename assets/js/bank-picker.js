// ============================================================================
// T&C Banking - bank-picker.js
// Searchable bank field for the Transfers page ("To another bank").
// Type the first letters and matching banks appear. If the bank is not in the
// list, whatever the user typed is kept. Works with mouse, touch and keyboard.
// Needs: #bank-picker (data-banks JSON), #bank, #bank-list, .bank-picker__toggle
// ============================================================================

(function () {
    'use strict';

    const root = document.getElementById('bank-picker');
    if (!root) return;

    const input  = document.getElementById('bank');
    const list   = document.getElementById('bank-list');
    const toggle = root.querySelector('.bank-picker__toggle');
    const status = root.querySelector('.bank-picker__status');

    let banks = [];
    try {
        banks = JSON.parse(root.getAttribute('data-banks') || '[]');
    } catch (e) {
        banks = [];
    }
    // If the list is missing, the field simply stays a normal text box.
    if (!input || !list || !banks.length) return;

    let options = [];     // the <li role="option"> elements currently shown
    let matches = [];     // the banks behind those options
    let activeIndex = -1;

    // ---------- Matching ----------
    function rank(bank, q) {
        const name = bank.n.toLowerCase();
        if (name.indexOf(q) === 0) return 0;                       // name starts with it
        if (name.split(/[\s\-&]+/).some(w => w.indexOf(q) === 0)) return 1; // a word starts with it
        const keys = (bank.k || '').toLowerCase().split(/\s+/);
        if (keys.some(k => k && k.indexOf(q) === 0)) return 2;     // short name / abbreviation
        if (q.length >= 3 && name.indexOf(q) !== -1) return 3;     // appears inside the name
        return -1;
    }

    function search(q) {
        return banks
            .map(b => ({ bank: b, r: rank(b, q) }))
            .filter(x => x.r !== -1)
            .sort((a, b) => a.r - b.r || a.bank.n.localeCompare(b.bank.n))
            .map(x => x.bank);
    }

    // ---------- Rendering ----------
    function clearList() {
        while (list.firstChild) list.removeChild(list.firstChild);
        options = [];
        matches = [];
        activeIndex = -1;
        input.removeAttribute('aria-activedescendant');
    }

    function addHeading(text) {
        const li = document.createElement('li');
        li.className = 'bank-picker__heading';
        li.setAttribute('role', 'presentation');
        li.textContent = text;
        list.appendChild(li);
    }

    function addOption(bank, q) {
        const li = document.createElement('li');
        li.className = 'bank-picker__option';
        li.id = 'bank-opt-' + options.length;
        li.setAttribute('role', 'option');
        li.setAttribute('aria-selected', 'false');

        // Bold the part the user typed
        const name = bank.n;
        const at = q ? name.toLowerCase().indexOf(q) : -1;
        if (at !== -1) {
            li.appendChild(document.createTextNode(name.slice(0, at)));
            const strong = document.createElement('strong');
            strong.textContent = name.slice(at, at + q.length);
            li.appendChild(strong);
            li.appendChild(document.createTextNode(name.slice(at + q.length)));
        } else {
            li.textContent = name;
        }

        li.addEventListener('click', function () { choose(bank); });
        list.appendChild(li);
        options.push(li);
        matches.push(bank);
    }

    function render(rawQuery) {
        const q = rawQuery.trim().toLowerCase();
        clearList();

        if (q === '') {
            const byName = (a, b) => a.n.localeCompare(b.n);
            const popular = banks.filter(b => b.p).sort(byName);
            const others  = banks.filter(b => !b.p).sort(byName);
            if (popular.length) {
                addHeading('Popular banks');
                popular.forEach(b => addOption(b, ''));
            }
            if (others.length) {
                addHeading(popular.length ? 'Other banks' : 'All banks');
                others.forEach(b => addOption(b, ''));
            }
            setStatus(options.length + ' banks available');
            return;
        }

        const found = search(q);
        found.forEach(b => addOption(b, q));

        if (!found.length) {
            const li = document.createElement('li');
            li.className = 'bank-picker__empty';
            li.setAttribute('role', 'presentation');
            li.textContent = 'No bank found for \u201C' + rawQuery.trim() + '\u201D. You can keep what you typed.';
            list.appendChild(li);
            setStatus('No bank found. Your typed bank name will be used.');
        } else {
            setStatus(found.length + (found.length === 1 ? ' bank found' : ' banks found'));
        }
    }

    function setStatus(text) {
        if (status) status.textContent = text;
    }

    // ---------- Open / close ----------
    function isOpen() {
        return !list.hidden;
    }

    function open() {
        list.hidden = false;
        input.setAttribute('aria-expanded', 'true');
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
    }

    function close() {
        list.hidden = true;
        input.setAttribute('aria-expanded', 'false');
        input.removeAttribute('aria-activedescendant');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
        options.forEach(o => {
            o.classList.remove('is-active');
            o.setAttribute('aria-selected', 'false');
        });
    }

    function setActive(index) {
        if (!options.length) return;
        if (index < 0) index = options.length - 1;
        if (index >= options.length) index = 0;
        options.forEach(o => {
            o.classList.remove('is-active');
            o.setAttribute('aria-selected', 'false');
        });
        activeIndex = index;
        const el = options[index];
        el.classList.add('is-active');
        el.setAttribute('aria-selected', 'true');
        input.setAttribute('aria-activedescendant', el.id);
        el.scrollIntoView({ block: 'nearest' });
    }

    // ---------- Choosing ----------
    function choose(bank) {
        input.value = bank.n;
        close();
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    // If the text is exactly a bank name, or an unambiguous short name such as
    // "uba" or "gtb", turn it into the full bank name. Anything else is kept.
    function normalise() {
        const t = input.value.trim().toLowerCase();
        if (!t) return;
        const exact = banks.filter(b => b.n.toLowerCase() === t);
        if (exact.length === 1) {
            input.value = exact[0].n;
            return;
        }
        const byKey = banks.filter(b => (b.k || '').toLowerCase().split(/\s+/).indexOf(t) !== -1);
        if (byKey.length === 1) {
            input.value = byKey[0].n;
        }
    }

    // ---------- Events ----------
    input.addEventListener('input', function () {
        render(input.value);
        open();
    });

    input.addEventListener('focus', function () {
        render(input.value);
        open();
    });

    input.addEventListener('click', function () {
        if (!isOpen()) {
            render(input.value);
            open();
        }
    });

    input.addEventListener('blur', function () {
        normalise();
        close();
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    input.addEventListener('keydown', function (e) {
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (!isOpen()) { render(input.value); open(); }
                setActive(activeIndex + 1);
                break;
            case 'ArrowUp':
                e.preventDefault();
                if (!isOpen()) { render(input.value); open(); }
                setActive(activeIndex === -1 ? options.length - 1 : activeIndex - 1);
                break;
            case 'Enter':
                if (isOpen()) {
                    e.preventDefault();
                    if (activeIndex >= 0) {
                        choose(matches[activeIndex]);
                    } else {
                        normalise();
                        close();
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
                break;
            case 'Escape':
                if (isOpen()) {
                    e.preventDefault();
                    e.stopPropagation();
                    close();
                }
                break;
            default:
                break;
        }
    });

    // Keep the cursor in the text box when the list or arrow is pressed
    list.addEventListener('mousedown', function (e) { e.preventDefault(); });

    if (toggle) {
        toggle.addEventListener('mousedown', function (e) { e.preventDefault(); });
        toggle.addEventListener('click', function () {
            if (isOpen()) {
                close();
            } else {
                input.focus();
                render('');   // the arrow always shows every bank
                open();
            }
        });
    }
})();