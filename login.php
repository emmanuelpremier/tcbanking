<?php
/**
 * T&C Banking - login page.
 * Pattern: set page vars, include shared data/header, output content,
 * include shared footer.
 * The form posts back to this page for server-side validation only;
 * there is no authentication or database yet.
 */

$pageTitle = 'Log in';
$activePage = 'login';

require_once __DIR__ . '/includes/data.php';

// ---- Server-side validation (demo only; nothing is authenticated) --------
$errors         = [];
$emailOrAccount = '';
$remember       = false;
$showDemoNotice = false;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $showDemoNotice = true;

    $emailOrAccount = isset($_POST['login_id']) ? trim((string) $_POST['login_id']) : '';
    $password       = isset($_POST['password']) ? (string) $_POST['password'] : '';
    $remember       = !empty($_POST['remember']);

    if ($emailOrAccount === '') {
        $errors['login_id'] = 'Enter your email or account number.';
    } elseif (filter_var($emailOrAccount, FILTER_VALIDATE_EMAIL) === false
              && preg_match('/^[0-9]{6,20}$/', $emailOrAccount) !== 1) {
        $errors['login_id'] = 'Enter a valid email address or account number.';
    }

    if ($password === '') {
        $errors['password'] = 'Enter your password.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }
}

require __DIR__ . '/includes/header.php';
?>
<!-- ============ Login: two-panel section ============ -->
<section class="login" aria-labelledby="login-heading">
    <div class="container login__inner">
        <!-- Left panel: forest, trust signals -->
        <div class="login__panel login__panel--brand">
            <p class="login__label">Secure access</p>
            <h1 class="login__title" id="login-heading">Welcome back to T&amp;C Banking</h1>
            <p class="login__lede">Sign in to see your balances, send money and follow every transaction in one calm, private place.</p>

            <ul class="login__trust">
                <li>
                    <svg class="login__trust-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Encrypted sessions</span>
                </li>
                <li>
                    <svg class="login__trust-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span>Instant transfer alerts</span>
                </li>
                <li>
                    <svg class="login__trust-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"></path>
                        <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                    </svg>
                    <span>24/7 support</span>
                </li>
            </ul>
        </div>
        <!-- Right panel: paper, login form -->
        <div class="login__panel login__panel--form">
            <?php if ($showDemoNotice): ?>
                <p class="login__notice" role="status">Login is not connected yet (demo)</p>
            <?php endif; ?>

            <p class="login__label login__label--brass">Account login</p>
            <h2 class="login__subtitle">Log in to your account</h2>

            <form class="login-form" method="post" action="<?= BASE_URL ?>login.php" novalidate>
                <div class="field">
                    <label class="field__label" for="login-id">Email or account number</label>
                    <div class="input-line<?= isset($errors['login_id']) ? ' is-invalid' : '' ?>">
                        <input class="input" type="text" id="login-id" name="login_id"
                               autocomplete="username" spellcheck="false" placeholder="you@example.com"
                               value="<?= htmlspecialchars($emailOrAccount, ENT_QUOTES, 'UTF-8') ?>"
                               aria-describedby="login-id-error"
                              <?= isset($errors['login_id']) ? 'aria-invalid="true"' : '' ?>>
                    </div>
                    <p class="field__error" id="login-id-error"<?= isset($errors['login_id']) ? '' : ' hidden' ?>><?= isset($errors['login_id']) ? htmlspecialchars($errors['login_id'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>
                <div class="field">
                    <label class="field__label" for="login-password">Password</label>
                    <div class="input-line<?= isset($errors['password']) ? ' is-invalid' : '' ?>">
                        <input class="input" type="password" id="login-password" name="password"
                               autocomplete="current-password"
                               aria-describedby="login-password-error"
                              <?= isset($errors['password']) ? 'aria-invalid="true"' : '' ?>>
                        <button class="password-toggle" type="button"
                                aria-label="Show password" aria-controls="login-password" aria-pressed="false">
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
                    <p class="field__error" id="login-password-error"<?= isset($errors['password']) ? '' : ' hidden' ?>><?= isset($errors['password']) ? htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') : '' ?></p>
                </div>
                <div class="login-form__row">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" value="1"<?= $remember ? ' checked' : '' ?>>
                        <span>Remember me</span>
                    </label>
                    <a class="login-form__link" href="#">Forgot password?</a>
                </div>

                <button class="btn btn--gold btn--block" type="submit">Log in</button>
            </form>

            <div class="login-form__divider" aria-hidden="true"></div>
            <p class="login-form__foot">
                New to T&amp;C Banking? <a href="<?= BASE_URL ?>register.php">Open an account</a>
            </p>
        </div>




    </div>
</section>
<?php require __DIR__ . '/includes/footer.php';
