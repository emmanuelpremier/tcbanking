<?php
/**
 * T&C Banking - shared footer.
 * Every page ends with:  require __DIR__ . '/../includes/footer.php';
 * Placeholder links only - no addresses, phone numbers, licences
 * or regulator claims.
 */

// Footer "Banking" links (slug => label); home maps to index.php.
$footerBankingLinks = [
    'home' => 'Home',
    'accounts' => 'Accounts',
    'loans' => 'Loans',
];

// Placeholder link groups (slug => label, href '#').
$footerCompanyLinks = [
    'about' => 'About',
    'careers' => 'Careers',
    'press' => 'Press',
];

$footerSupportLinks = [
    'help' => 'Help centre',
    'security' => 'Security',
    'contact' => 'Contact',
];
?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <a class="footer-logo" href="<?= BASE_URL ?>index.php" aria-label="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?> &mdash; home">
                <span class="logo-mark" aria-hidden="true">T&amp;C</span>
                <span class="logo-text" aria-hidden="true">Banking</span>
            </a>
            <p class="footer-tagline">Private banking, quietly considered. Accounts, cards and transfers brought together in one calm place.</p>
        </div>

        <div class="footer-col">
            <h2 class="footer-heading">Banking</h2>
            <ul class="footer-links">
                <?php foreach ($footerBankingLinks as $slug => $label): ?>
                    <li><a href="<?= BASE_URL . ($slug === 'home' ? 'index.php' : $slug . '.php') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h2 class="footer-heading">Company</h2>
            <ul class="footer-links">
                <?php foreach ($footerCompanyLinks as $label): ?>
                    <li><a href="#"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h2 class="footer-heading">Support</h2>
            <ul class="footer-links">
                <?php foreach ($footerSupportLinks as $label): ?>
                    <li><a href="#"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom__inner">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>. All rights reserved.</p>
            <p class="footer-disclaimer">Sample interface for demonstration purposes.</p>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
