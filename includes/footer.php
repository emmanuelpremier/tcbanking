<?php
/**
 * T&C Banking - shared footer.
 * Every page ends with:  require __DIR__ . '/../includes/footer.php';
 */

// Footer quick links (slug => label).
$footerLinks = [
    'home' => 'Home',
    'accounts' => 'Accounts',
    'loans' => 'Loans',
    'about' => 'About',
    'contact' => 'Contact',
];
?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <p class="footer-logo"><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="footer-tagline">Secure, simple banking for everyone.</p>
        </div>

        <div class="footer-col">
            <h2 class="footer-heading">Quick Links</h2>
            <ul class="footer-links">
                <?php foreach ($footerLinks as $slug => $label): ?>
                    <li><a href="<?= BASE_URL . ($slug === 'home' ? 'index.php' : $slug . '.php') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h2 class="footer-heading">Contact</h2>
            <address class="footer-contact">
                <p>123 Banking Street, Suite 100</p>
                <p>Your City, ST 00000</p>
                <p><a href="tel:+10000000000">+1 (000) 000-0000</a></p>
                <p><a href="mailto:hello@example.com">hello@example.com</a></p>
            </address>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
