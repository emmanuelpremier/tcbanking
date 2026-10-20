<?php
/**
 * T&C Banking - register page (sign up).
 * Pattern: set page vars, include shared data, validate server-side,
 * include shared header, output content, include shared footer.
 * The form posts back to this page for validation only; no account is
 * created and no database is used yet (demo).
 */

$pageTitle = 'Open an account';
$activePage = 'register';

require_once __DIR__ . '/includes/data.php';

// ---- Server-side validation (demo only; nothing is stored) ---------------
$errors     = [];
$showNotice = false;

$firstName   = '';
$lastName    = '';
$email       = '';
$phone       = '';
$accountType = '';
$terms       = false;
// Password values are deliberately never written back into the form.

$accountTypes = ['Savings', 'Current', 'Business'];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $firstName   = isset($_POST['first_name']) ? trim((string) $_POST['first_name']) : '';
    $lastName    = isset($_POST['last_name']) ? trim((string) $_POST['last_name']) : '';
    $email       = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
    $phone       = isset($_POST['phone']) ? trim((string) $_POST['phone']) : '';
    $accountType = isset($_POST['account_type']) ? (string) $_POST['account_type'] : '';
    $password    = isset($_POST['password']) ? (string) $_POST['password'] : '';
    $confirm     = isset($_POST['confirm_password']) ? (string) $_POST['confirm_password'] : '';
    $terms       = !empty($_POST['terms']);

    if ($firstName === '') {
        $errors['first_name'] = 'Enter your first name.';
    }

    if ($lastName === '') {
        $errors['last_name'] = 'Enter your last name.';
    }

    if ($email === '') {
        $errors['email'] = 'Enter your email address.';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if ($phone === '') {
        $errors['phone'] = 'Enter your phone number.';
    } elseif (preg_match('/^\+?\d{7,15}$/', $phone) !== 1) {
        $errors['phone'] = 'Enter a valid phone number (7-15 digits, optional leading +).';
    }

    if (!in_array($accountType, $accountTypes, true)) {
        $errors['account_type'] = 'Choose an account type.';
    }

    if ($password === '') {
        $errors['password'] = 'Choose a password.';
    } elseif (strlen($password) < 8
              || preg_match('/[A-Za-z]/', $password) !== 1
              || preg_match('/\d/', $password) !== 1) {
        $errors['password'] = 'Use at least 8 characters with one letter and one number.';
    }

    if ($confirm === '') {
        $errors['confirm_password'] = 'Repeat your password.';
    } elseif ($confirm !== $password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (!$terms) {
        $errors['terms'] = 'Please accept the Terms and Privacy Policy.';
    }

    if (!$errors) {
        // Success (demo): show the banner and reset the fields.
        $showNotice  = true;
        $firstName   = '';
        $lastName    = '';
        $email       = '';
        $phone       = '';
        $accountType = '';
        $terms       = false;
    }
}

require __DIR__ . '/includes/header.php';
?>
<!-- ============ Register: two-panel section ============ -->
<section class="login" aria-labelledby="register-heading">
    <div class="container login__inner">
        <!-- Left panel: forest, three onboarding steps -->
        <div class="login__panel login__panel--brand">
            <p class="login__label">Open an account</p>
            <h1 class="login__title" id="register-heading">Start banking with T&amp;C</h1>
            <p class="login__lede">A few short steps stand between you and a new account. This demo page shows how the sign-up flow could look and feel.</p>

            <ol class="register-steps">
                <li><span class="register-steps__num">1</span><span>Create your profile</span></li>
                <li><span class="register-steps__num">2</span><span>Verify your identity</span></li>
                <li><span class="register-steps__num">3</span><span>Fund your account</span></li>
            </ol>
        </div>

        <!-- Right panel: paper, registration form -->
        <div class="login__panel login__panel--form">
            <?php if ($showNotice): ?>
                <p class="login__notice" role="status">Account creation is not connected yet (demo)</p>
            <?php endif; ?>

            <p class="login__label login__label--brass">New account</p>
            <h2 class="login__subtitle">Create your account</h2>

            <form class="register-form" method="post" action="<?= BASE_URL ?>register.php" novalidate>
                <div class="register-form__row">
                    <div class="field">
                        <label class="field__label" for="reg-first-name">First name</label>
                        <div class="input-line<?= isset($errors['first_name']) ? ' is-invalid' : '' ?>">
                            <input class="input" type="text" id="reg-first-name" name="first_name"
                                   autocomplete="given-name"
                                   value="<?= htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8') ?>"
                                   aria-describedby="reg-first-name-error"
                                  <?= isset($errors['first_name']) ? 'aria-invalid="true"' : '' ?>>
                        </div>
                        <p class="field__error" id="reg-first-name-error"<?= isset($errors['first_name']) ? '' : ' hidden' ?>><?= isset($errors['first_name']) ? htmlspecialchars($errors['first_name'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                    </div>
                    <div class="field">
                        <label class="field__label" for="reg-last-name">Last name</label>
                        <div class="input-line<?= isset($errors['last_name']) ? ' is-invalid' : '' ?>">
                            <input class="input" type="text" id="reg-last-name" name="last_name"
                                   autocomplete="family-name"
                                   value="<?= htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8') ?>"
                                   aria-describedby="reg-last-name-error"
                                  <?= isset($errors['last_name']) ? 'aria-invalid="true"' : '' ?>>
                        </div>
                        <p class="field__error" id="reg-last-name-error"<?= isset($errors['last_name']) ? '' : ' hidden' ?>><?= isset($errors['last_name']) ? htmlspecialchars($errors['last_name'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                    </div>
                </div>

                <div class="field">
                    <label class="field__label" for="reg-email">Email address</label>
                    <div class="input-line<?= isset($errors['email']) ? ' is-invalid' : '' ?>">
                        <input class="input" type="email" id="reg-email" name="email"
                               autocomplete="email" spellcheck="false" placeholder="you@example.com"
                               value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                               aria-describedby="reg-email-error"
                              <?= isset($errors['email']) ? 'aria-invalid="true"' : '' ?>>
                    </div>
                    <p class="field__error" id="reg-email-error"<?= isset($errors['email']) ? '' : ' hidden' ?>><?= isset($errors['email']) ? htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>

                <div class="field">
                    <label class="field__label" for="reg-phone">Phone number</label>
                    <div class="input-line<?= isset($errors['phone']) ? ' is-invalid' : '' ?>">
                        <input class="input" type="tel" id="reg-phone" name="phone"
                               autocomplete="tel" inputmode="tel" placeholder="+234 800 000 0000"
                               value="<?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') ?>"
                               aria-describedby="reg-phone-error"
                              <?= isset($errors['phone']) ? 'aria-invalid="true"' : '' ?>>
                    </div>
                    <p class="field__error" id="reg-phone-error"<?= isset($errors['phone']) ? '' : ' hidden' ?>><?= isset($errors['phone']) ? htmlspecialchars($errors['phone'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>

                <div class="field">
                    <label class="field__label" for="reg-account-type">Account type</label>
                    <div class="input-line<?= isset($errors['account_type']) ? ' is-invalid' : '' ?>">
                        <select class="input" id="reg-account-type" name="account_type"
                                aria-describedby="reg-account-type-error"
                               <?= isset($errors['account_type']) ? 'aria-invalid="true"' : '' ?>>
                            <option value="">Select an account type</option>
                            <?php foreach ($accountTypes as $type): ?>
                                <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"<?= $accountType === $type ? ' selected' : '' ?>><?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="field__error" id="reg-account-type-error"<?= isset($errors['account_type']) ? '' : ' hidden' ?>><?= isset($errors['account_type']) ? htmlspecialchars($errors['account_type'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>

                <div class="field">
                    <label class="field__label" for="reg-password">Password</label>
                    <div class="input-line<?= isset($errors['password']) ? ' is-invalid' : '' ?>">
                        <input class="input" type="password" id="reg-password" name="password"
                               autocomplete="new-password"
                               aria-describedby="reg-password-strength reg-password-error"
                              <?= isset($errors['password']) ? 'aria-invalid="true"' : '' ?>>
                        <button class="password-toggle" type="button"
                                aria-label="Show password" aria-controls="reg-password" aria-pressed="false">
                            <svg class="password-toggle__icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="password-toggle__icon password-toggle__icon--off" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                                <line x1="3" y1="3" x2="21" y2="21"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="strength" id="reg-password-strength">
                        <div class="strength__track">
                            <span class="strength__fill"></span>
                        </div>
                        <p class="strength__label">
                            <span>Password strength</span>
                            <span class="strength__level" aria-live="polite"></span>
                        </p>
                    </div>
                    <p class="field__error" id="reg-password-error"<?= isset($errors['password']) ? '' : ' hidden' ?>><?= isset($errors['password']) ? htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>

                <div class="field">
                    <label class="field__label" for="reg-confirm-password">Confirm password</label>
                    <div class="input-line<?= isset($errors['confirm_password']) ? ' is-invalid' : '' ?>">
                        <input class="input" type="password" id="reg-confirm-password" name="confirm_password"
                               autocomplete="new-password"
                               aria-describedby="reg-confirm-password-error"
                              <?= isset($errors['confirm_password']) ? 'aria-invalid="true"' : '' ?>>
                        <button class="password-toggle" type="button"
                                aria-label="Show password" aria-controls="reg-confirm-password" aria-pressed="false">
                            <svg class="password-toggle__icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="password-toggle__icon password-toggle__icon--off" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                                <line x1="3" y1="3" x2="21" y2="21"></line>
                            </svg>
                        </button>
                    </div>
                    <p class="field__error" id="reg-confirm-password-error"<?= isset($errors['confirm_password']) ? '' : ' hidden' ?>><?= isset($errors['confirm_password']) ? htmlspecialchars($errors['confirm_password'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>

                <div class="field register-form__terms">
                    <label class="checkbox<?= isset($errors['terms']) ? ' is-invalid' : '' ?>">
                        <input type="checkbox" id="reg-terms" name="terms" value="1"<?= $terms ? ' checked' : '' ?>
                               aria-describedby="reg-terms-error"<?= isset($errors['terms']) ? ' aria-invalid="true"' : '' ?>>
                        <span>I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a></span>
                    </label>
                    <p class="field__error" id="reg-terms-error"<?= isset($errors['terms']) ? '' : ' hidden' ?>><?= isset($errors['terms']) ? htmlspecialchars($errors['terms'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>

                <button class="btn btn--gold btn--block" type="submit">Create account</button>
            </form>

            <div class="login-form__divider" aria-hidden="true"></div>
            <p class="login-form__foot">
                Already have an account? <a href="<?= BASE_URL ?>login.php">Log in</a>
            </p>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php';