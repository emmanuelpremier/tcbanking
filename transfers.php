<?php
/**
 * T&C Banking - transfers page.
 * Pattern: set page vars, include shared data, include shared header,
 * output content, include shared footer.
 * Behaviour lives in assets/js/transfers.js.
 */

$pageTitle  = 'Transfers';
$activePage = 'transfers';

require_once __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/transfers.css">
<!-- ============ Transfers header ============ -->
<section class="transfers-header" aria-labelledby="transfers-heading">
    <div class="container">
        <p class="transfers-label">Move money</p>
        <h1 class="transfers-title" id="transfers-heading">Transfers</h1>
        <p class="transfers-lede">Send funds to a T&amp;C account or another bank in a few simple steps.</p>
    </div>
</section>

<!-- ============ Progress indicator ============ -->
<nav class="progress" aria-label="Transfer progress">
    <ol class="progress__list">
        <li class="progress__step progress__step--active" data-step="1">
            <span class="progress__circle">1</span>
            <span class="progress__label">Details</span>
        </li>
        <li class="progress__step" data-step="2">
            <span class="progress__line"></span>
            <span class="progress__circle">2</span>
            <span class="progress__label">Review</span>
        </li>
        <li class="progress__step" data-step="3">
            <span class="progress__line"></span>
            <span class="progress__circle">3</span>
            <span class="progress__label">Done</span>
        </li>
    </ol>
</nav>

<!-- ============ Body: two-column grid ============ -->
<div class="container transfers-body">
    <!-- Left column: multi-step form (~8/12) -->
    <div class="transfers-form-col">
        <form class="transfers-form" id="transfers-form" novalidate>

            <!-- STEP 1: Details -->
            <div class="step step--active" id="step-1">
                <h2 class="step__title">Step 1 &mdash; Details</h2>

                <!-- From account -->
                <div class="field">
                    <label class="field__label" for="from-account">From account</label>
                    <div class="input-line">
                        <select class="input" id="from-account" name="from_account">
                            <?php foreach (get_all_accounts() as $acc): ?>
                                <option
                                    value="<?= htmlspecialchars($acc['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-available="<?= htmlspecialchars(number_format((float) ($acc['available'] ?? 0), 2), ENT_QUOTES, 'UTF-8') ?>"
                                    data-currency="<?= htmlspecialchars($acc['currency'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <?= htmlspecialchars($acc['name'], ENT_QUOTES, 'UTF-8') ?>
                                    &middot;
                                    <?= htmlspecialchars($acc['number_masked'], ENT_QUOTES, 'UTF-8') ?>
                                    &mdash;
                                    <?= htmlspecialchars($acc['currency'], ENT_QUOTES, 'UTF-8') ?>
                                    <?= number_format((float) ($acc['balance'] ?? 0), 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="balance-line">Available balance:
                        <span id="balance-value"></span>
                    </p>
                </div>

                <!-- Transfer type tabs -->
                <div class="field">
                    <label class="field__label" for="transfer-type">Transfer type</label>
                    <div class="gift-tabs" role="tablist" aria-label="Transfer type">
                        <button
                            class="gift-tab is-active"
                            type="button"
                            role="tab"
                            aria-selected="true"
                            data-type="tc"
                            id="tab-tc"
                        >To a T&amp;C account</button>
                        <button
                            class="gift-tab"
                            type="button"
                            role="tab"
                            aria-selected="false"
                            data-type="other"
                            id="tab-other"
                        >To another bank</button>
                    </div>
                </div>
                <!-- Recipient contacts -->
                <div class="field">
                    <label class="field__label" for="recipient">Recipient</label>
                    <ul class="contacts" id="contacts-list">
                        <?php foreach (get_contacts() as $contact): ?>
                            <li>
                                <button
                                    class="contact"
                                    type="button"
                                    data-account="<?= htmlspecialchars($contact['account_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-name="<?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <span class="contact__avatar" aria-hidden="true"><?= htmlspecialchars($contact['initials'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="contact__name"><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                </button>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <button class="contact contact--add" type="button" aria-label="Add new recipient">
                                <span class="contact__avatar contact__avatar--add" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
                <!-- Bank (shown only for "another bank") -->
                <div class="field bank-field" id="bank-field" hidden>
                    <label class="field__label" for="bank">Bank</label>
                    <div class="input-line">
                        <select class="input" id="bank" name="bank">
                            <option value="access">Access Bank</option>
                            <option value="firstbank">FirstBank</option>
                            <option value="gtbank">Guaranty Trust Bank</option>
                            <option value="uba">United Bank for Africa</option>
                            <option value="zenith">Zenith Bank</option>
                            <option value="fidelity">Fidelity Bank</option>
                            <option value="sterling">Sterling Bank</option>
                            <option value="ecobank">Ecobank Nigeria</option>
                        </select>
                    </div>
                </div>

                <!-- Account number -->
                <div class="field">
                    <label class="field__label" for="account-number">Account number</label>
                    <div class="input-line">
                        <input
                            class="input"
                            type="text"
                            id="account-number"
                            name="account_number"
                            inputmode="numeric"
                            autocomplete="off"
                            maxlength="10"
                            placeholder="0000000000"
                        >
                    </div>
                    <p class="field__error" id="account-error" hidden>Account number must be exactly 10 digits.</p>
                </div>

                <!-- Recipient name -->
                <div class="field">
                    <label class="field__label" for="recipient-name">Recipient name</label>
                    <div class="input-line">
                        <input
                            class="input"
                            type="text"
                            id="recipient-name"
                            name="recipient_name"
                            autocomplete="off"
                            placeholder="Recipient full name"
                        >
                    </div>
                    <p class="field__error" id="recipient-error" hidden>Recipient name is required.</p>
                </div>

                <!-- Amount -->
                <div class="field">
                    <label class="field__label" for="amount">Amount</label>
                    <div class="input-line">
                        <input
                            class="input"
                            type="text"
                            id="amount"
                            name="amount"
                            inputmode="decimal"
                            autocomplete="off"
                            placeholder="₦0.00"
                        >
                    </div>
                    <p class="field__error" id="amount-error" hidden>Amount must be greater than 0 and within your available balance.</p>
                </div>

                <!-- Quick amount chips -->
                <div class="field">
                    <label class="field__label" style="margin-bottom: var(--s1);">Quick amount</label>
                    <div class="amount-chips">
                        <button class="chip" type="button" data-amount="5000">₦5,000</button>
                        <button class="chip" type="button" data-amount="10000">₦10,000</button>
                        <button class="chip" type="button" data-amount="50000">₦50,000</button>
                        <button class="chip" type="button" data-amount="100000">₦100,000</button>
                    </div>
                </div>

                <!-- Narration -->
                <div class="field">
                    <label class="field__label" for="narration">Narration (optional)</label>
                    <div class="input-line">
                        <input
                            class="input"
                            type="text"
                            id="narration"
                            name="narration"
                            autocomplete="off"
                            placeholder="e.g. Rent payment"
                        >
                    </div>
                </div>

                <button class="btn btn--gold btn--block" type="button" id="continue-btn">Continue</button>
            </div>
            <!-- STEP 2: Review -->
            <div class="step" id="step-2" hidden>
                <h2 class="step__title">Step 2 &mdash; Review</h2>

                <table class="ledger">
                    <tbody>
                        <tr class="review-row">
                            <td class="review-label">From</td>
                            <td class="review-value" id="review-from"></td>
                        </tr>
                        <tr class="review-row">
                            <td class="review-label">To</td>
                            <td class="review-value" id="review-to"></td>
                        </tr>
                        <tr class="review-row bank-review-row" id="review-bank-row" hidden>
                            <td class="review-label">Bank</td>
                            <td class="review-value" id="review-bank"></td>
                        </tr>
                        <tr class="review-row">
                            <td class="review-label">Account number</td>
                            <td class="review-value" id="review-account"></td>
                        </tr>
                        <tr class="review-row">
                            <td class="review-label">Amount</td>
                            <td class="review-value" id="review-amount"></td>
                        </tr>
                        <tr class="review-row">
                            <td class="review-label">Fee</td>
                            <td class="review-value" id="review-fee">₦0.00</td>
                        </tr>
                        <tr class="review-row">
                            <td class="review-label">Total</td>
                            <td class="review-value" id="review-total"></td>
                        </tr>
                        <tr class="review-row narration-review-row" id="review-narration-row" hidden>
                            <td class="review-label">Narration</td>
                            <td class="review-value" id="review-narration"></td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn--ghost" type="button" id="edit-btn">Edit</button>

                <!-- Transaction PIN -->
                <div class="field">
                    <label class="field__label" for="pin">Transaction PIN</label>
                    <div class="input-line">
                        <input
                            class="input"
                            type="password"
                            id="pin"
                            name="pin"
                            maxlength="4"
                            autocomplete="off"
                            placeholder="1234"
                        >
                    </div>
                    <p class="field__error" id="pin-error" hidden>PIN must be 4 digits.</p>
                </div>
                <p class="sr-only" id="pin-notice">Demo only: the PIN is never stored or sent.</p>

                <button class="btn btn--gold btn--block" type="button" id="confirm-btn">Confirm transfer</button>
            </div>
            <!-- STEP 3: Done -->
            <div class="step" id="step-3" hidden>
                <div class="done-panel">
                    <div class="check-icon" aria-hidden="true">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <h2 class="done-title">Transfer submitted (demo)</h2>
                    <p class="done-ref"><span class="ref-label">Reference:</span> <span class="ref-number" id="ref-number"></span></p>
                    <p class="done-amount" id="done-amount"></p>
                    <p class="done-recipient" id="done-recipient"></p>

                    <div class="done-actions">
                        <button class="btn btn--gold btn--block" type="button" id="another-btn">Make another transfer</button>
                        <a class="btn btn--ghost btn--block" href="<?= BASE_URL ?>accounts.php">Back to accounts</a>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <!-- Right column: sticky panel (~4/12) -->
    <div class="transfers-panel-col">
        <div class="transfers-panel transfers-panel--sticky">

            <!-- Daily limit -->
            <div class="card">
                <div class="card__head">
                    <p class="card__eyebrow">Daily limit</p>
                    <h2 class="card__title">₦5,000,000</h2>
                </div>
                <div class="limit-panel">
                    <div class="limit-head">
                        <span class="limit-label">Used</span>
                        <span class="limit-value">₦850,000</span>
                    </div>
                    <div class="limit-bar">
                        <div class="limit-bar__track"></div>
                        <div class="limit-bar__fill"></div>
                    </div>
                    <p class="limit-text">₦4,150,000 remaining today</p>
                </div>
            </div>

            <!-- Recent recipients -->
            <div class="card">
                <div class="card__head">
                    <h2 class="card__title">Recent recipients</h2>
                </div>
                <ul class="recipients">
                    <?php
                    $recent = array_slice(get_contacts(), 0, 3);
                    foreach ($recent as $contact):
                    ?>
                        <li class="recipient-item">
                            <span class="recipient-avatar"><?= htmlspecialchars($contact['initials'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="recipient-name"><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Note -->
            <div class="note">
                <span class="note__rule"></span>
                <p class="note__text">Always confirm recipient details before sending.</p>
            </div>

        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/transfers.js" defer></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
