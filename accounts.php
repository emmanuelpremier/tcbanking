<?php
/**
 * T&C Banking - accounts page.
 * Pattern: set page vars, include shared data, include shared header,
 * output content, include shared footer.
 * Tab switching, search and account switching live in assets/js/accounts.js.
 */

$pageTitle  = 'Accounts';
$activePage = 'accounts';

require_once __DIR__ . '/includes/data.php';

// Small escape helper (unique name so it never clashes with other files).
if (!function_exists('acc_e')) {
    function acc_e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

// ---- Data ----------------------------------------------------------------
$accounts = get_all_accounts();
$activeId = $accounts !== [] ? $accounts[0]['id'] : '';

// ---- Summary figures (Naira accounts only) --------------------------------
$totalBalance   = 0.0;
$totalAvailable = 0.0;
$monthIn        = 0.0;

foreach ($accounts as $acc) {
    $cur = $acc['currency'] ?? '';
    if ($cur !== "\u{20A6}" && $cur !== 'NGN') {
        continue;
    }
    $totalBalance   += (float) ($acc['balance'] ?? 0);
    $totalAvailable += (float) ($acc['available'] ?? 0);
    foreach (($acc['transactions'] ?? []) as $tx) {
        if (($tx['direction'] ?? '') === 'in') {
            $monthIn += (float) ($tx['amount'] ?? 0);
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/accounts-styles.css">
<!-- ============ Accounts header ============ -->
<section class="accounts-header" aria-labelledby="accounts-heading">
    <div class="container">
        <p class="accounts-header__label">Your accounts</p>
        <h1 class="accounts-header__title" id="accounts-heading">Accounts</h1>
        <p class="accounts-header__lede">
            Review every account on your portfolio, check its balance and available
            funds, and browse the latest ledger entries.
        </p>

        <div class="accounts-header__actions">
            <a class="btn btn--outline" href="<?= BASE_URL ?>register.php">
                <span class="btn__icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                         stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </span>
                Open new account
            </a>
            <a class="btn btn--outline" href="#">
                <span class="btn__icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </span>
                Download statement
            </a>
        </div>
    </div>
</section>

<!-- ============ Summary strip ============ -->
<section class="accounts-summary" aria-label="Account summary">
    <div class="container">
        <div class="accounts-summary__inner">
            <div class="accounts-summary__figure">
                <p class="accounts-summary__label">Total balance (NGN)</p>
                <p class="accounts-summary__number">
                    <span class="accounts-summary__currency">&#8358;</span><?= number_format($totalBalance, 2) ?>
                </p>
            </div>

            <div class="accounts-summary__hairline" aria-hidden="true"></div>

            <div class="accounts-summary__figure">
                <p class="accounts-summary__label">Available to spend</p>
                <p class="accounts-summary__number">
                    <span class="accounts-summary__currency">&#8358;</span><?= number_format($totalAvailable, 2) ?>
                </p>
            </div>

            <div class="accounts-summary__hairline" aria-hidden="true"></div>

            <div class="accounts-summary__figure">
                <p class="accounts-summary__label">Money in this month</p>
                <p class="accounts-summary__number">
                    <span class="accounts-summary__currency">&#8358;</span><?= number_format($monthIn, 2) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============ Two-column body ============ -->
<div class="accounts-body container">

    <!-- Left: account picker -->
    <section class="accounts-picker" aria-labelledby="accounts-list-heading">
        <header class="accounts-picker__head">
            <h2 class="accounts-picker__title" id="accounts-list-heading">Select account</h2>
            <p class="accounts-picker__note">Click a row to open its panel.</p>
        </header>

        <div class="accounts-list">
            <?php foreach ($accounts as $acc): ?>
                <?php $isActive = ($acc['id'] === $activeId); ?>
                <button class="account-row<?= $isActive ? ' is-active' : '' ?>"
                        type="button"
                        data-account-target="<?= acc_e($acc['id']) ?>"
                        aria-pressed="<?= $isActive ? 'true' : 'false' ?>">
                    <span class="account-row__info">
                        <span class="account-row__name"><?= acc_e($acc['name']) ?></span>
                        <span class="account-row__meta"><?= acc_e($acc['type']) ?> &middot; <?= acc_e($acc['number_masked']) ?></span>
                    </span>
                    <span class="account-row__balance">
                        <?= acc_e($acc['currency']) ?><?= number_format((float) $acc['balance'], 2) ?>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Right: detail panels (one per account, only one visible) -->
    <div class="accounts-detail-wrap">
        <?php foreach ($accounts as $acc): ?>
            <?php $isActive = ($acc['id'] === $activeId); ?>
            <article class="accounts-detail<?= $isActive ? ' is-active' : ' is-hidden' ?>"
                     data-account="<?= acc_e($acc['id']) ?>"
                     <?= $isActive ? '' : 'hidden' ?>>

                <header class="accounts-detail__head">
                    <h2 class="accounts-detail__name"><?= acc_e($acc['name']) ?></h2>
                    <span class="badge badge--positive"><?= acc_e($acc['status']) ?></span>
                </header>

                <dl class="accounts-detail__facts">
                    <div class="accounts-detail__fact">
                        <dt class="accounts-detail__fact-label">Account type</dt>
                        <dd class="accounts-detail__fact-value"><?= acc_e($acc['type']) ?></dd>
                    </div>
                    <div class="accounts-detail__fact">
                        <dt class="accounts-detail__fact-label">Account number</dt>
                        <dd class="accounts-detail__fact-value"><?= acc_e($acc['number_masked']) ?></dd>
                    </div>
                    <div class="accounts-detail__fact">
                        <dt class="accounts-detail__fact-label">Opened</dt>
                        <dd class="accounts-detail__fact-value"><?= acc_e($acc['opened']) ?></dd>
                    </div>
                    <div class="accounts-detail__fact">
                        <dt class="accounts-detail__fact-label">Available balance</dt>
                        <dd class="accounts-detail__fact-value">
                            <span class="accounts-detail__currency"><?= acc_e($acc['currency']) ?></span><?= number_format((float) $acc['available'], 2) ?>
                        </dd>
                    </div>
                    <div class="accounts-detail__fact">
                        <dt class="accounts-detail__fact-label">Currency</dt>
                        <dd class="accounts-detail__fact-value"><?= acc_e($acc['currency']) ?></dd>
                    </div>
                    <?php if (!empty($acc['interest_rate'])): ?>
                        <div class="accounts-detail__fact">
                            <dt class="accounts-detail__fact-label">Interest rate</dt>
                            <dd class="accounts-detail__fact-value"><?= acc_e((float) $acc['interest_rate']) ?>% p.a.</dd>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($acc['maturity_date'])): ?>
                        <div class="accounts-detail__fact">
                            <dt class="accounts-detail__fact-label">Maturity date</dt>
                            <dd class="accounts-detail__fact-value"><?= acc_e($acc['maturity_date']) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <div class="accounts-detail__actions">
                    <a class="btn btn--ghost" href="#">Send money</a>
                    <a class="btn btn--ghost" href="#">Statement</a>
                    <a class="btn btn--ghost" href="#">Account settings</a>
                </div>

                <div class="accounts-detail__transactions">
                    <h3 class="accounts-detail__subtitle">Transactions</h3>

                    <div class="accounts-detail__tabs" role="tablist" aria-label="Transaction filter">
                        <button class="accounts-detail__tab is-active" type="button" role="tab"
                                aria-selected="true" data-tab="all">All</button>
                        <button class="accounts-detail__tab" type="button" role="tab"
                                aria-selected="false" data-tab="in">Money in</button>
                        <button class="accounts-detail__tab" type="button" role="tab"
                                aria-selected="false" data-tab="out">Money out</button>
                    </div>

                    <div class="accounts-detail__search">
                        <label class="sr-only" for="search-<?= acc_e($acc['id']) ?>">Search transactions</label>
                        <svg class="accounts-detail__search-icon" width="15" height="15"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input class="accounts-detail__search-input"
                               id="search-<?= acc_e($acc['id']) ?>"
                               type="search" placeholder="Search transactions">
                    </div>

                    <div class="accounts-detail__table-wrap">
                        <table class="ledger">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($acc['transactions'] as $tx): ?>
                                    <?php
                                    $isIn   = (($tx['direction'] ?? '') === 'in');
                                    $search = strtolower(
                                        ($tx['name'] ?? '') . ' ' . ($tx['subtitle'] ?? '') . ' ' . ($tx['date'] ?? '')
                                    );
                                    ?>
                                    <tr class="ledger__row"
                                        data-direction="<?= $isIn ? 'in' : 'out' ?>"
                                        data-search="<?= acc_e($search) ?>">
                                        <td class="ledger__cell ledger__cell--date"><?= acc_e($tx['date'] ?? '') ?></td>
                                        <td class="ledger__cell ledger__cell--desc">
                                            <p class="ledger__name"><?= acc_e($tx['name'] ?? '') ?></p>
                                            <p class="ledger__sub"><?= acc_e($tx['subtitle'] ?? '') ?></p>
                                        </td>
                                        <td class="ledger__cell ledger__cell--amount<?= $isIn ? ' is-in' : ' is-out' ?>">
                                            <span class="ledger__amount">
                                                <?= $isIn ? '+' : '&minus;' ?><?= acc_e($acc['currency']) ?><?= number_format((float) ($tx['amount'] ?? 0), 2) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="accounts-detail__empty" hidden>No transactions found</p>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

</div>

<script src="<?= BASE_URL ?>assets/js/accounts.js" defer></script>
<?php require __DIR__ . '/includes/footer.php'; ?>