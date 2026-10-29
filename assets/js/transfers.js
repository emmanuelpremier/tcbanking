// ============================================================================
// T&C Banking - transfers.js
// Multi-step transfer page behaviour.
// ============================================================================

(function () {
    'use strict';

    // ---------- DOM references ----------
    const form        = document.getElementById('transfers-form');
    const step1       = document.getElementById('step-1');
    const step2       = document.getElementById('step-2');
    const step3       = document.getElementById('step-3');
    const continueBtn = document.getElementById('continue-btn');
    const editBtn     = document.getElementById('edit-btn');
    const confirmBtn  = document.getElementById('confirm-btn');
    const anotherBtn  = document.getElementById('another-btn');

    const fromAccountSel = document.getElementById('from-account');
    const amountInput    = document.getElementById('amount');
    const acctNumInput   = document.getElementById('account-number');
    const recipientInput = document.getElementById('recipient-name');
    const narrationInput = document.getElementById('narration');
    const pinInput       = document.getElementById('pin');

    const typeTabs   = document.querySelectorAll('.gift-tab');
    const contacts   = document.querySelectorAll('.contact');
    const chips      = document.querySelectorAll('.chip');

    // Review display elements (step 2)
    const reviewFromAcc   = document.getElementById('review-from');
    const reviewFromAmt   = document.getElementById('review-from-amt');
    const reviewType      = document.getElementById('review-type');
    const reviewToName    = document.getElementById('review-to');
    const reviewToAcc     = document.getElementById('review-account');
    const reviewBank      = document.getElementById('review-bank');
    const reviewAmount    = document.getElementById('review-amount');
    const reviewNarration = document.getElementById('review-narration');

    const refNumberEl = document.getElementById('ref-number');
    const doneAmountEl = document.getElementById('done-amount');
    const doneRecipientEl = document.getElementById('done-recipient');

    // Error containers
    const acctNumError = document.getElementById('account-error');
    const recipientError = document.getElementById('recipient-error');
    const amountError = document.getElementById('amount-error');
    const pinError = document.getElementById('pin-error');

    // ---------- State ----------
    let currentStep = 1;
    let selectedContact = null;
    let selectedChip = null;
    let transferType = 'self'; // 'self' or 'third'
    let availableBalance = 0;
    let currency = '₦';

    // ---------- Init ----------
    document.addEventListener('DOMContentLoaded', function () {
        initFromAccount();
        initTypeTabs();
        initContacts();
        initAmountChips();
        initAmountInput();
        initContinueButton();
        initEditButton();
        initConfirmButton();
        initAnotherButton();
    });

    // ---------- From account ----------
    function initFromAccount() {
        if (!fromAccountSel) return;
        fromAccountSel.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            availableBalance = parseFloat(opt.getAttribute('data-available').replace(/,/g, '')) || 0;
            currency = opt.getAttribute('data-currency') || '₦';
            updateAmountDisplay();
        });
        // Trigger change on first option to set initial balance
        if (fromAccountSel.options.length > 0) {
            fromAccountSel.dispatchEvent(new Event('change'));
        }
    }

    // ---------- Transfer type tabs ----------
    function initTypeTabs() {
        typeTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                typeTabs.forEach(t => t.classList.remove('is-active'));
                this.classList.add('is-active');
                transferType = this.dataset.type === 'other' ? 'third' : 'self';
                toggleBankField();
            });
        });
    }

    function toggleBankField() {
        const bankField = document.getElementById('bank-field');
        if (!bankField) return;
        if (transferType === 'third') {
            bankField.style.display = 'block';
        } else {
            bankField.style.display = 'none';
        }
    }

    // ---------- Contacts ----------
    function initContacts() {
        contacts.forEach(function (contact) {
            contact.addEventListener('click', function () {
                contacts.forEach(c => c.classList.remove('is-selected'));
                this.classList.add('is-selected');
                selectedContact = {
                    name: this.dataset.name || '',
                    account: this.dataset.account || '',
                    bank: this.dataset.bank || '',
                    initials: this.dataset.initials || ''
                };
                recipientInput.value = selectedContact.name;
                acctNumInput.value = selectedContact.account;
                validateStep1();
            });
        });
    }

    // ---------- Amount chips ----------
    function initAmountChips() {
        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                chips.forEach(c => c.classList.remove('is-active'));
                this.classList.add('is-active');
                selectedChip = this;
                amountInput.value = this.dataset.amount;
                validateStep1();
            });
        });
    }

    // ---------- Amount input ----------
    function initAmountInput() {
        if (!amountInput) return;

        amountInput.addEventListener('input', function () {
            // Strip any non-numeric except dot
            let val = this.value.replace(/[^0-9.]/g, '');
            // Prevent multiple dots
            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
            this.value = val;

            chips.forEach(c => c.classList.remove('is-active'));
            selectedChip = null;
            validateStep1();
            updateAmountDisplay();
        });

        amountInput.addEventListener('blur', function () {
            updateAmountDisplay();
        });
    }

    function updateAmountDisplay() {
        if (!amountInput) return;
        const raw = parseFloat(amountInput.value) || 0;
        if (raw > 0) {
            amountInput.value = formatCurrency(raw);
        }
    }

    function formatCurrency(num) {
        return currency + ' ' + num.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function parseCurrency(str) {
        return parseFloat(str.replace(/[^0-9.]/g, '')) || 0;
    }

    // ---------- Step switching ----------
    function showStep(stepNum) {
        document.querySelectorAll('.step').forEach(function (s) {
            s.classList.remove('step--active');
            s.setAttribute('hidden', '');
        });

        const target = document.getElementById('step-' + stepNum);
        if (target) {
            target.classList.add('step--active');
            target.removeAttribute('hidden');
        }

        document.querySelectorAll('.progress__step').forEach(function (s, idx) {
            if (idx + 1 === stepNum) {
                s.classList.add('progress__step--active');
            } else {
                s.classList.remove('progress__step--active');
            }
        });

        currentStep = stepNum;
    }

    // ---------- Validation ----------
    function validateStep1() {
        let valid = true;

        // Account number validation (only for third-party)
        if (transferType === 'third') {
            const acctVal = acctNumInput.value.replace(/\D/g, '');
            if (acctVal.length !== 10) {
                showError(acctNumError, 'Please enter a 10-digit account number');
                valid = false;
            } else {
                hideError(acctNumError);
            }
        } else {
            hideError(acctNumError);
        }

        // Recipient name required
        if (!recipientInput.value.trim()) {
            showError(recipientError, 'Recipient name is required');
            valid = false;
        } else {
            hideError(recipientError);
        }

        // Amount validation
        const amount = parseCurrency(amountInput.value);
        if (amount <= 0) {
            showError(amountError, 'Amount must be greater than 0');
            valid = false;
        } else if (amount > availableBalance) {
            showError(amountError, 'Amount exceeds available balance (' + formatCurrency(availableBalance) + ')');
            valid = false;
        } else {
            hideError(amountError);
        }

        continueBtn.disabled = !valid;
        return valid;
    }

    function validateStep2() {
        let valid = true;
        const pinVal = pinInput.value.replace(/\D/g, '');

        if (pinVal.length !== 4) {
            showError(pinError, 'Please enter a 4-digit PIN');
            valid = false;
        } else {
            hideError(pinError);
        }

        confirmBtn.disabled = !valid;
        return valid;
    }

    function showError(el, msg) {
        if (!el) return;
        el.textContent = msg;
        el.style.display = 'block';
    }

    function hideError(el) {
        if (!el) return;
        el.style.display = 'none';
    }

    // ---------- Continue button ----------
    function initContinueButton() {
        if (!continueBtn) return;
        continueBtn.addEventListener('click', function () {
            if (!validateStep1()) return;

            populateReview();
            showStep(2);
        });

        // Real-time validation on input
        [acctNumInput, recipientInput, amountInput].forEach(function (el) {
            if (el) el.addEventListener('input', validateStep1);
        });
    }

    // ---------- Populate review (step 2) ----------
    function populateReview() {
        const accOpt = fromAccountSel.options[fromAccountSel.selectedIndex];
        const amount = parseCurrency(amountInput.value);

        if (reviewFromAcc) reviewFromAcc.textContent = accOpt ? accOpt.text : '';
        if (reviewFromAmt) reviewFromAmt.textContent = formatCurrency(availableBalance);
        if (reviewType) reviewType.textContent = transferType === 'third' ? 'Third-party transfer' : 'Self transfer';
        if (reviewToName) reviewToName.textContent = recipientInput.value.trim();
        if (reviewToAcc) reviewToAcc.textContent = acctNumInput.value;
        if (reviewBank) {
            reviewBank.textContent = transferType === 'third' ? (document.getElementById('bank').value || '—') : '—';
        }
        if (reviewAmount) reviewAmount.textContent = formatCurrency(amount);
        if (reviewNarration) reviewNarration.textContent = narrationInput.value.trim() || '—';
    }

    // ---------- Edit button ----------
    function initEditButton() {
        if (!editBtn) return;
        editBtn.addEventListener('click', function () {
            showStep(1);
        });
    }

    // ---------- Confirm button ----------
    function initConfirmButton() {
        if (!confirmBtn) return;
        confirmBtn.addEventListener('click', function () {
            if (!validateStep2()) return;

            const ref = generateReference();
            const amount = parseCurrency(amountInput.value);

            if (refNumberEl) refNumberEl.textContent = ref;
            if (doneAmountEl) doneAmountEl.textContent = formatCurrency(amount) + ' to ' + recipientInput.value.trim();
            if (doneRecipientEl) doneRecipientEl.textContent = recipientInput.value.trim();

            showStep(3);
        });

        pinInput.addEventListener('input', validateStep2);
    }

    // ---------- Another transfer ----------
    function initAnotherButton() {
        if (!anotherBtn) return;
        anotherBtn.addEventListener('click', function () {
            // Reset form
            form.reset();
            selectedContact = null;
            selectedChip = null;
            transferType = 'self';
            currentStep = 1;

            // Reset UI
            typeTabs.forEach(t => t.classList.remove('is-active'));
            typeTabs[0].classList.add('is-active');
            contacts.forEach(c => c.classList.remove('is-selected'));
            chips.forEach(c => c.classList.remove('is-active'));
            pinInput.value = '';
            narrationInput.value = '';
            acctNumInput.value = '';
            recipientInput.value = '';
            amountInput.value = '';

            toggleBankField();
            validateStep1();
            showStep(1);
        });
    }

    // ---------- Reference generation ----------
    function generateReference() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let ref = 'TRF';
        for (let i = 0; i < 9; i++) {
            ref += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return ref;
    }

})();
