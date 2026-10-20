/**
 * T&C Banking - main script (vanilla JS).
 * - Mobile navigation menu toggle
 * - Gift card tabs (Send / Receive / Redeem)
 * - Login/register form validation, strength bar and show/hide toggles
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

    /* ---------- Login form (client-side validation + show/hide) ----------
     * The form posts to the server for a demo notice; validation below
     * only runs when JS is available and blocks obviously bad input. */
    function initLoginForm() {
        var form = document.querySelector('.login-form');

        if (!form) {
            return;
        }

        var idInput = form.querySelector('#login-id');
        var passwordInput = form.querySelector('#login-password');
        var idError = form.querySelector('#login-id-error');
        var passwordError = form.querySelector('#login-password-error');
        var toggle = form.querySelector('.password-toggle');

        var ACCOUNT_PATTERN = /^\d{6,20}$/;
        var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        function setError(input, errorEl, message) {
            errorEl.textContent = message;
            errorEl.hidden = false;
            input.closest('.input-line').classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
        }

        function clearError(input, errorEl) {
            errorEl.textContent = '';
            errorEl.hidden = true;
            input.closest('.input-line').classList.remove('is-invalid');
            input.removeAttribute('aria-invalid');
        }

        function validateId() {
            var value = idInput.value.trim();

            if (value === '') {
                setError(idInput, idError, 'Enter your email or account number.');
                return false;
            }

            if (!EMAIL_PATTERN.test(value) && !ACCOUNT_PATTERN.test(value)) {
                setError(idInput, idError, 'Enter a valid email address or account number.');
                return false;
            }

            clearError(idInput, idError);
            return true;
        }

        function validatePassword() {
            if (passwordInput.value === '') {
                setError(passwordInput, passwordError, 'Enter your password.');
                return false;
            }

            if (passwordInput.value.length < 8) {
                setError(passwordInput, passwordError, 'Password must be at least 8 characters.');
                return false;
            }

            clearError(passwordInput, passwordError);
            return true;
        }

        // Show/hide password: swap input type, label text and icon.
        if (toggle) {
            toggle.addEventListener('click', function () {
                var showing = passwordInput.type === 'text';
                passwordInput.type = showing ? 'password' : 'text';
                toggle.classList.toggle('is-visible', !showing);
                toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
                toggle.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
            });
        }

        // Clear a field's error as soon as the user edits it again.
        idInput.addEventListener('input', function () {
            if (!idError.hidden) {
                clearError(idInput, idError);
            }
        });

        passwordInput.addEventListener('input', function () {
            if (!passwordError.hidden) {
                clearError(passwordInput, passwordError);
            }
        });

        form.addEventListener('submit', function (event) {
            var idValid = validateId();
            var passwordValid = validatePassword();

            if (!idValid || !passwordValid) {
                event.preventDefault();
                (!idValid ? idInput : passwordInput).focus();
            }
            // Valid input: let the form post; PHP shows the demo notice.
        });
    }

    /* ---------- Register form (validation + strength bar + toggles) ----------
     * Posts to the server for a demo notice; validation below only runs when
     * JS is available and blocks obviously bad input. Passwords are never
     * written back into the fields by this script. */
    function initRegisterForm() {
        var form = document.querySelector('.register-form');

        if (!form) {
            return;
        }

        var firstName = form.querySelector('#reg-first-name');
        var lastName = form.querySelector('#reg-last-name');
        var email = form.querySelector('#reg-email');
        var phone = form.querySelector('#reg-phone');
        var accountType = form.querySelector('#reg-account-type');
        var password = form.querySelector('#reg-password');
        var confirmPassword = form.querySelector('#reg-confirm-password');
        var terms = form.querySelector('#reg-terms');

        var firstNameError = form.querySelector('#reg-first-name-error');
        var lastNameError = form.querySelector('#reg-last-name-error');
        var emailError = form.querySelector('#reg-email-error');
        var phoneError = form.querySelector('#reg-phone-error');
        var accountTypeError = form.querySelector('#reg-account-type-error');
        var passwordError = form.querySelector('#reg-password-error');
        var confirmPasswordError = form.querySelector('#reg-confirm-password-error');
        var termsError = form.querySelector('#reg-terms-error');

        var strength = form.querySelector('.strength');
        var strengthFill = form.querySelector('.strength__fill');
        var strengthLevel = form.querySelector('.strength__level');

        if (!firstName || !lastName || !email || !phone || !accountType
            || !password || !confirmPassword || !terms || !firstNameError
            || !lastNameError || !emailError || !phoneError || !accountTypeError
            || !passwordError || !confirmPasswordError || !termsError
            || !strength || !strengthFill || !strengthLevel) {
            return;
        }

        var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var PHONE_PATTERN = /^\+?\d{7,15}$/;

        // Inline errors mirror the login form: red rule + message under the field.
        function setError(input, errorEl, message) {
            errorEl.textContent = message;
            errorEl.hidden = false;

            var line = input.closest('.input-line');
            if (line) {
                line.classList.add('is-invalid');
            }

            var box = input.closest('.checkbox');
            if (box) {
                box.classList.add('is-invalid');
            }

            input.setAttribute('aria-invalid', 'true');
        }

        function clearError(input, errorEl) {
            errorEl.textContent = '';
            errorEl.hidden = true;

            var line = input.closest('.input-line');
            if (line) {
                line.classList.remove('is-invalid');
            }

            var box = input.closest('.checkbox');
            if (box) {
                box.classList.remove('is-invalid');
            }

            input.removeAttribute('aria-invalid');
        }

        function validateFirstName() {
            if (firstName.value.trim() === '') {
                setError(firstName, firstNameError, 'Enter your first name.');
                return false;
            }

            clearError(firstName, firstNameError);
            return true;
        }

        function validateLastName() {
            if (lastName.value.trim() === '') {
                setError(lastName, lastNameError, 'Enter your last name.');
                return false;
            }

            clearError(lastName, lastNameError);
            return true;
        }

        function validateEmail() {
            var value = email.value.trim();

            if (value === '') {
                setError(email, emailError, 'Enter your email address.');
                return false;
            }

            if (!EMAIL_PATTERN.test(value)) {
                setError(email, emailError, 'Enter a valid email address.');
                return false;
            }

            clearError(email, emailError);
            return true;
        }

        function validatePhone() {
            var value = phone.value.trim();

            if (value === '') {
                setError(phone, phoneError, 'Enter your phone number.');
                return false;
            }

            if (!PHONE_PATTERN.test(value)) {
                setError(phone, phoneError, 'Enter a valid phone number (7-15 digits, optional leading +).');
                return false;
            }

            clearError(phone, phoneError);
            return true;
        }

        function validateAccountType() {
            if (accountType.value === '') {
                setError(accountType, accountTypeError, 'Choose an account type.');
                return false;
            }

            clearError(accountType, accountTypeError);
            return true;
        }

        function validatePassword() {
            var value = password.value;

            if (value === '') {
                setError(password, passwordError, 'Choose a password.');
                return false;
            }

            if (value.length < 8 || !/[A-Za-z]/.test(value) || !/\d/.test(value)) {
                setError(password, passwordError, 'Use at least 8 characters with one letter and one number.');
                return false;
            }

            clearError(password, passwordError);
            return true;
        }

        function validateConfirmPassword() {
            if (confirmPassword.value === '') {
                setError(confirmPassword, confirmPasswordError, 'Repeat your password.');
                return false;
            }

            if (confirmPassword.value !== password.value) {
                setError(confirmPassword, confirmPasswordError, 'Passwords do not match.');
                return false;
            }

            clearError(confirmPassword, confirmPasswordError);
            return true;
        }

        function validateTerms() {
            if (!terms.checked) {
                setError(terms, termsError, 'Please accept the Terms and Privacy Policy.');
                return false;
            }

            clearError(terms, termsError);
            return true;
        }

        // Live strength bar: weak (negative) / fair (brass) / strong (positive).
        function updateStrength() {
            var value = password.value;
            var level = '';
            var width = '0%';

            if (value !== '') {
                var score = 0;
                if (value.length >= 8) {
                    score += 1;
                }
                if (/[A-Za-z]/.test(value) && /\d/.test(value)) {
                    score += 1;
                }
                if (value.length >= 12 && /[^A-Za-z0-9]/.test(value)) {
                    score += 1;
                }

                if (score >= 3) {
                    level = 'strong';
                    width = '100%';
                } else if (score === 2) {
                    level = 'fair';
                    width = '66%';
                } else {
                    level = 'weak';
                    width = '33%';
                }
            }

            strength.classList.remove('is-weak');
            strength.classList.remove('is-fair');
            strength.classList.remove('is-strong');
            strengthFill.style.width = width;

            if (level) {
                strength.classList.add('is-' + level);
                strengthLevel.textContent = level.charAt(0).toUpperCase() + level.slice(1);
            } else {
                strengthLevel.textContent = '';
            }
        }

        // Show/hide toggles: one binding per password field (via aria-controls).
        Array.prototype.forEach.call(form.querySelectorAll('.password-toggle'), function (toggle) {
            var target = document.getElementById(toggle.getAttribute('aria-controls'));

            if (!target) {
                return;
            }

            toggle.addEventListener('click', function () {
                var showing = target.type === 'text';
                target.type = showing ? 'password' : 'text';
                toggle.classList.toggle('is-visible', !showing);
                toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
                toggle.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
            });
        });

        // Clear a field's error as soon as the user edits it again.
        function watch(input, errorEl, eventName) {
            input.addEventListener(eventName, function () {
                if (!errorEl.hidden) {
                    clearError(input, errorEl);
                }
            });
        }

        watch(firstName, firstNameError, 'input');
        watch(lastName, lastNameError, 'input');
        watch(email, emailError, 'input');
        watch(phone, phoneError, 'input');
        watch(accountType, accountTypeError, 'change');
        watch(confirmPassword, confirmPasswordError, 'input');
        watch(terms, termsError, 'change');

        password.addEventListener('input', function () {
            updateStrength();

            if (!passwordError.hidden) {
                clearError(password, passwordError);
            }
            // Editing the password invalidates a previous "match" result.
            if (!confirmPasswordError.hidden) {
                clearError(confirmPassword, confirmPasswordError);
            }
        });

        form.addEventListener('submit', function (event) {
            var checks = [
                [validateFirstName, firstName],
                [validateLastName, lastName],
                [validateEmail, email],
                [validatePhone, phone],
                [validateAccountType, accountType],
                [validatePassword, password],
                [validateConfirmPassword, confirmPassword],
                [validateTerms, terms]
            ];
            var invalid = [];

            checks.forEach(function (pair) {
                if (!pair[0]()) {
                    invalid.push(pair[1]);
                }
            });

            if (invalid.length > 0) {
                event.preventDefault();
                invalid[0].focus();
            }
            // Valid input: let the form post; PHP shows the demo notice.
        });

        updateStrength();
    }

    document.addEventListener('DOMContentLoaded', function () {
        initNavToggle();
        initGiftTabs();
        initPlaceholderForms();
        initLoginForm();
        initRegisterForm();
    });
})();