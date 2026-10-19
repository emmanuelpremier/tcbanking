<?php
/**
 * T&C Banking - home page (premium dark dashboard).
 * Pattern: set page vars, include shared header, output content, include shared footer.
 * All sample data comes from includes/data.php and is rendered with foreach loops.
 */

$pageTitle = 'Home';
$activePage = 'home';

require_once __DIR__ . '/includes/data.php';

// ---- Sample data ---------------------------------------------------------
$accounts     = get_accounts();
$account      = $accounts[0];
$transactions = get_transactions();
$giftCards    = get_gift_cards();
$contacts     = get_contacts();
$weekly       = get_weekly_spending();

// ---- Time-based greeting -------------------------------------------------
$hour     = (int) date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
$today    = date('l, j F Y');

// ---- Weekly chart helpers ------------------------------------------------
$peakAmount = 0.0;
foreach ($weekly as $day) {
    if ($day['amount'] > $peakAmount) {
        $peakAmount = $day['amount'];
    }
}

require __DIR__ . '/includes/header.php';
?>
<section class="greeting">
    <div class="container">
        <p class="greeting__date"><?= htmlspecialchars($today, ENT_QUOTES, 'UTF-8') ?></p>
        <h1 class="greeting__title"><?= htmlspecialchars($greeting, ENT_QUOTES, 'UTF-8') ?>, Emmanuel</h1>
    </div>
</section>

<div class="container dashboard">

    <!-- 1. Balance card -->
    <section class="card balance-card" aria-labelledby="balance-heading">
        <p class="card__eyebrow" id="balance-heading">Total balance</p>
        <p class="balance-card__amount">
            <?= htmlspecialchars($account['currency'], ENT_QUOTES, 'UTF-8') ?><?= number_format($account['balance'], 2) ?>
        </p>
        <p class="balance-card__change">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
            </svg>
            <?= htmlspecialchars($account['change'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <div class="balance-card__actions">
            <button class="action-btn" type="button">
                <span class="action-btn__icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </span>
                Send
            </button>
            <button class="action-btn" type="button">
                <span class="action-btn__icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <polyline points="19 12 12 19 5 12"></polyline>
                    </svg>
                </span>
                Receive
            </button>
            <button class="action-btn" type="button">
                <span class="action-btn__icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 12 20 22 4 22 4 12"></polyline>
                        <rect x="2" y="7" width="20" height="5"></rect>
                        <line x1="12" y1="22" x2="12" y2="7"></line>
                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                    </svg>
                </span>
                Buy Gift Card
            </button>
            <button class="action-btn" type="button">
                <span class="action-btn__icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                </span>
                Top Up
            </button>
        </div>
    </section>

    <!-- 2. Bank card -->
    <section class="bank-card-wrap" aria-labelledby="bank-card-heading">
        <h2 class="sr-only" id="bank-card-heading">Your bank card</h2>
        <div class="bank-card">
            <div class="bank-card__top">
                <span class="bank-card__tier"><?= htmlspecialchars($account['card']['tier'], ENT_QUOTES, 'UTF-8') ?></span>
                <span class="bank-card__brand"><?= htmlspecialchars($account['card']['brand'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="bank-card__chip" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
            <p class="bank-card__number"><?= htmlspecialchars($account['card']['number_masked'], ENT_QUOTES, 'UTF-8') ?></p>
            <div class="bank-card__bottom">
                <div class="bank-card__field">
                    <span class="bank-card__label">Card holder</span>
                    <span class="bank-card__value"><?= htmlspecialchars($account['card']['holder'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="bank-card__field bank-card__field--right">
                    <span class="bank-card__label">Expires</span>
                    <span class="bank-card__value"><?= htmlspecialchars($account['card']['expiry'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Gift cards -->
    <section class="card gift-card" aria-labelledby="gift-heading">
        <div class="card__head">
            <h2 class="card__title" id="gift-heading">Gift Cards</h2>
            <div class="gift-tabs" role="tablist" aria-label="Gift card actions">
                <button class="gift-tab is-active" type="button" role="tab" id="gift-tab-send"
                        aria-selected="true" aria-controls="gift-panel-send" data-gift-tab="send">Send</button>
                <button class="gift-tab" type="button" role="tab" id="gift-tab-receive"
                        aria-selected="false" aria-controls="gift-panel-receive" data-gift-tab="receive" tabindex="-1">Receive</button>
                <button class="gift-tab" type="button" role="tab" id="gift-tab-redeem"
                        aria-selected="false" aria-controls="gift-panel-redeem" data-gift-tab="redeem" tabindex="-1">Redeem</button>
            </div>
        </div>

        <div class="gift-panel is-active" role="tabpanel" id="gift-panel-send"
             aria-labelledby="gift-tab-send" data-gift-panel="send">
            <div class="gift-grid">
                <?php foreach ($giftCards as $cardItem): ?>
                    <button class="gift-tile" type="button">
                        <span class="gift-tile__logo" aria-hidden="true"><?= htmlspecialchars($cardItem['symbol'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="gift-tile__brand"><?= htmlspecialchars($cardItem['brand'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="gift-tile__value">$<?= number_format($cardItem['value']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="gift-panel" role="tabpanel" id="gift-panel-receive"
             aria-labelledby="gift-tab-receive" data-gift-panel="receive" hidden>
            <p class="gift-panel__note">Share your personal gift link and incoming gift cards will appear here for approval.</p>
            <button class="btn btn--ghost" type="button">Copy gift link</button>
        </div>

        <div class="gift-panel" role="tabpanel" id="gift-panel-redeem"
             aria-labelledby="gift-tab-redeem" data-gift-panel="redeem" hidden>
            <form class="gift-redeem" data-placeholder>
                <label class="sr-only" for="redeem-code">Gift card code</label>
                <input class="input" type="text" id="redeem-code" name="redeem_code" placeholder="Enter gift card code">
                <button class="btn btn--gold" type="submit">Redeem</button>
            </form>
        </div>
    </section>

    <!-- 4. Recent activity -->
    <section class="card activity-card" aria-labelledby="activity-heading">
        <div class="card__head">
            <h2 class="card__title" id="activity-heading">Recent activity</h2>
            <a class="card__link" href="#">View all</a>
        </div>

        <ul class="activity-list">
            <?php foreach ($transactions as $tx): ?>
                <?php $isIn = ($tx['direction'] === 'in'); ?>
                <li class="activity-item<?= $isIn ? ' activity-item--in' : '' ?>">
                    <span class="activity-item__arrow" aria-hidden="true">
                        <?php if ($isIn): ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <polyline points="19 12 12 19 5 12"></polyline>
                            </svg>
                        <?php else: ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="19" x2="12" y2="5"></line>
                                <polyline points="5 12 12 5 19 12"></polyline>
                            </svg>
                        <?php endif; ?>
                    </span>
                    <span class="activity-item__meta">
                        <span class="activity-item__name"><?= htmlspecialchars($tx['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="activity-item__sub"><?= htmlspecialchars($tx['subtitle'], ENT_QUOTES, 'UTF-8') ?></span>
                    </span>
                    <span class="activity-item__amount">
                        <?= $isIn ? '+' : '−' ?>₦<?= number_format($tx['amount'], 2) ?>
                        <span class="sr-only"><?= $isIn ? 'received' : 'sent' ?></span>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <!-- 5. Quick transfer -->
    <section class="card transfer-card" aria-labelledby="transfer-heading">
        <h2 class="card__title" id="transfer-heading">Quick transfer</h2>

        <ul class="contacts">
            <?php foreach ($contacts as $contact): ?>
                <li>
                    <button class="contact" type="button">
                        <span class="contact__avatar" aria-hidden="true"><?= htmlspecialchars($contact['initials'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="contact__name"><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?></span>
                    </button>
                </li>
            <?php endforeach; ?>
            <li>
                <button class="contact contact--add" type="button" aria-label="Add new recipient">
                    <span class="contact__avatar contact__avatar--add" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </span>
                </button>
            </li>
        </ul>

        <form class="transfer-form" data-placeholder>
            <div class="field">
                <label class="field__label" for="account-number">Account number</label>
                <input class="input" type="text" id="account-number" name="account_number"
                       inputmode="numeric" autocomplete="off" placeholder="0000000000">
            </div>
            <div class="field">
                <label class="field__label" for="transfer-amount">Amount</label>
                <input class="input" type="text" id="transfer-amount" name="amount"
                       inputmode="decimal" autocomplete="off" placeholder="₦0.00">
            </div>
            <button class="btn btn--gold btn--block" type="submit">Send money</button>
        </form>
    </section>

    <!-- 6. Spending chart -->
    <section class="card spending-card" aria-labelledby="spending-heading">
        <div class="card__head">
            <h2 class="card__title" id="spending-heading">Spending this week</h2>
            <span class="spending-card__legend">
                <span class="spending-card__legend-dot" aria-hidden="true"></span> Highest day
            </span>
        </div>

        <div class="chart" role="img"
             aria-label="Weekly spending from Monday to Sunday. Peak spending is Friday at ₦<?= number_format($peakAmount, 0) ?>.">
            <?php foreach ($weekly as $day): ?>
                <?php
                $isPeak    = ($day['amount'] === $peakAmount);
                $heightPct = $peakAmount > 0 ? (int) round($day['amount'] / $peakAmount * 100) : 0;
                ?>
                <div class="chart__col<?= $isPeak ? ' chart__col--peak' : '' ?>">
                    <span class="chart__value">₦<?= number_format($day['amount'] / 1000, 1) ?>k</span>
                    <div class="chart__bar" style="height: <?= $heightPct ?>%"></div>
                    <span class="chart__day"><?= htmlspecialchars($day['day'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</div>
<?php
require __DIR__ . '/includes/footer.php';
