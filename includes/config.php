<?php
// Redirect the old http://localhost/tcbanking/ address to the virtual host.
if (isset($_SERVER['HTTP_HOST']) && stripos($_SERVER['HTTP_HOST'], 'localhost') === 0
    && stripos($_SERVER['REQUEST_URI'], '/tcbanking/') === 0) {
    $newPath = substr($_SERVER['REQUEST_URI'], strlen('/tcbanking'));
    header('Location: http://tcbanking' . $newPath, true, 301);
    exit;
}
/**
 * T&C Banking - global configuration.
 * Loaded by includes/header.php on every page.
 */

// Site is served from the web root of the tcbanking virtual host.
define('SITE_NAME', 'T&C Banking');
define('BASE_URL', '/');
