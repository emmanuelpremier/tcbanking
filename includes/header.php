<?php
/**
 * T&C Banking - shared header.
 * Every page starts with:  require __DIR__ . '/../includes/header.php';
 * after setting $pageTitle and $activePage.
 */

require_once __DIR__ . '/config.php';

// Page-level defaults so header never fails on missing variables.
$pageTitle = $pageTitle ?? SITE_NAME;
$activePage = $activePage ?? '';

// Navigation items: slug => label.
$navItems = [
    'home' => 'Home',
    'accounts' => 'Accounts',
    'loans' => 'Loans',
    'about' => 'About',
    'contact' => 'Contact',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?><?= $pageTitle !== SITE_NAME ? ' | ' . htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') : '' ?></title>
    <meta name="description" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?> &mdash; secure banking for everyone.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>

<header class="site-header">
    <!-- Tier 1: thin top strip -->
    <div class="top-strip">
        <div class="container top-strip__inner">
            <p class="top-strip__note">Secure online banking</p>
            <ul class="top-strip__links">
                <li><a href="#">Help</a></li>
                <li><a href="<?= BASE_URL ?>login.php">Log in</a></li>
                <li><a href="#">Log out</a></li>
            </ul>
        </div>
    </div>

    <!-- Tier 2: masthead -->
    <div class="masthead">
        <div class="container masthead__inner">
            <a class="logo" href="<?= BASE_URL ?>index.php" aria-label="T&amp;C Banking &mdash; home">
                <span class="logo-mark" aria-hidden="true">T&amp;C</span>
                <span class="logo-text" aria-hidden="true">Banking</span>
            </a>

            <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="primary-nav">
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
            </button>

            <nav class="primary-nav" id="primary-nav" aria-label="Primary">
                <ul class="nav-list">
                    <?php foreach ($navItems as $slug => $label): ?>
                        <?php $isActive = ($slug === $activePage); ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $isActive ? ' active' : '' ?>"
                               href="<?= BASE_URL . ($slug === 'home' ? 'index.php' : $slug . '.php') ?>"
                              <?= $isActive ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="header-actions">
                <form class="header-search" role="search" data-placeholder>
                    <svg class="header-search__icon" width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" name="q" placeholder="Search transactions" aria-label="Search transactions">
                </form>

                <button class="header-bell" type="button" aria-label="Notifications (1 unread)">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="header-bell__dot" aria-hidden="true"></span>
                </button>

                <a class="header-avatar" href="<?= BASE_URL ?>login.php" aria-label="Log in to your account">E</a>
            </div>
        </div>
    </div>
</header>

<main id="main-content">
