<?php
/**
 * T&C Banking - sample data providers.
 * Each function returns a plain PHP array.
 * All names, numbers and amounts below are fictional sample data.
 *
 * Usage (from a page, after including config via header or directly):
 *   $accounts = get_accounts();
 */

/**
 * @return array Bank accounts sample data (primary account first).
 */
function get_accounts(): array
{
    return [
        [
            'id' => 1,
            'name' => 'T&C Black',
            'type' => 'Savings',
            'balance' => 4820500.75,
            'currency' => '₦',
            'change' => '+12.4% this month',
            'card' => [
                'brand' => 'Visa',
                'tier' => 'T&C Black',
                'number_masked' => '•••• •••• •••• 4821',
                'holder' => 'EMMANUEL PREMIER',
                'expiry' => '09/29',
            ],
        ],
    ];
}

/**
 * @return array Recent transactions sample data.
 * Each item: name, subtitle, amount (float), direction ('in'|'out').
 */
function get_transactions(): array
{
    return [
        ['name' => 'Airtel Data Bundle', 'subtitle' => 'Airtime & data · Today, 09:24', 'amount' => 5000.0, 'direction' => 'out'],
        ['name' => 'Chidinma Okafor', 'subtitle' => 'Transfer received · Today, 08:02', 'amount' => 85000.0, 'direction' => 'in'],
        ['name' => 'Shoprite Lekki', 'subtitle' => 'Card purchase · Yesterday', 'amount' => 23750.0, 'direction' => 'out'],
        ['name' => 'Salary Deposit', 'subtitle' => 'Acme Studios · Oct 12', 'amount' => 750000.0, 'direction' => 'in'],
        ['name' => 'Power Bill', 'subtitle' => 'Bill payment · Oct 11', 'amount' => 18200.0, 'direction' => 'out'],
    ];
}

/**
 * @return array Gift card sample data.
 * Each item: brand, symbol (display letter), value (USD amount).
 */
function get_gift_cards(): array
{
    return [
        ['brand' => 'Amazon', 'symbol' => 'A', 'value' => 100],
        ['brand' => 'Apple', 'symbol' => '', 'value' => 50],
        ['brand' => 'Steam', 'symbol' => 'S', 'value' => 75],
    ];
}

/**
 * @return array Frequent transfer contacts sample data.
 * Each item: initials, name.
 */
function get_contacts(): array
{
    return [
        ['initials' => 'AO', 'name' => 'Adaeze O.'],
        ['initials' => 'TB', 'name' => 'Tunde B.'],
        ['initials' => 'CE', 'name' => 'Chidi E.'],
        ['initials' => 'FN', 'name' => 'Fatima N.'],
    ];
}

/**
 * @return array Weekly spending sample data (Mon–Sun).
 * Each item: day label, amount spent that day.
 */
function get_weekly_spending(): array
{
    return [
        ['day' => 'Mon', 'amount' => 42000.0],
        ['day' => 'Tue', 'amount' => 38500.0],
        ['day' => 'Wed', 'amount' => 61200.0],
        ['day' => 'Thu', 'amount' => 29800.0],
        ['day' => 'Fri', 'amount' => 74500.0],
        ['day' => 'Sat', 'amount' => 58300.0],
        ['day' => 'Sun', 'amount' => 21400.0],
    ];
}

/**
 * @return array News / announcements sample data.
 */
function get_news(): array
{
    return [];
}

/**
 * @return array Branch / ATM locations sample data.
 */
function get_locations(): array
{
    return [];
}
/**
 * Sample data for the Accounts page: four accounts, each with its own
 * transactions. Fields per account: id, name, type, number_masked, currency,
 * balance, available, status, opened, interest_rate, maturity_date,
 * transactions[] (name, subtitle, date, amount, direction).
 */
if (!function_exists('get_all_accounts')) {
    function get_all_accounts(): array
    {
        $naira = "\u{20A6}";
        $dots  = "\u{2022}\u{2022}\u{2022}\u{2022} \u{2022}\u{2022}\u{2022}\u{2022} \u{2022}\u{2022}\u{2022}\u{2022} ";

        $tx = static function (string $name, string $subtitle, string $date, float $amount, string $direction): array {
            return [
                'name'      => $name,
                'subtitle'  => $subtitle,
                'date'      => $date,
                'amount'    => $amount,
                'direction' => $direction,
            ];
        };

        return [
            [
                'id'             => 'premier-savings',
                'name'           => 'Premier Savings',
                'type'           => 'Savings',
                'number_masked'  => $dots . '4821',
                'currency'       => $naira,
                'balance'        => 4820500.75,
                'available'      => 4720500.75,
                'status'         => 'Active',
                'opened'         => '14 Mar 2021',
                'interest_rate'  => null,
                'maturity_date'  => null,
                'transactions'   => [
                    $tx('Airtel Data Bundle', 'Airtime & data', '21 Oct 2026', 5000.00, 'out'),
                    $tx('Chidinma Okafor', 'Transfer received', '21 Oct 2026', 85000.00, 'in'),
                    $tx('Shoprite Lekki', 'Card purchase', '20 Oct 2026', 23750.00, 'out'),
                    $tx('Salary Deposit', 'Acme Studios', '20 Oct 2026', 750000.00, 'in'),
                    $tx('Power Bill', 'Utility payment', '11 Oct 2026', 18200.00, 'out'),
                    $tx('Interest Credit', 'Monthly interest', '30 Sep 2026', 12450.75, 'in'),
                    $tx('Netflix', 'Subscription', '28 Sep 2026', 4400.00, 'out'),
                    $tx('Tunde Balogun', 'Transfer received', '25 Sep 2026', 120000.00, 'in'),
                ],
            ],
            [
                'id'             => 'current-account',
                'name'           => 'Current Account',
                'type'           => 'Current',
                'number_masked'  => $dots . '1096',
                'currency'       => $naira,
                'balance'        => 1265300.00,
                'available'      => 1265300.00,
                'status'         => 'Active',
                'opened'         => '02 Aug 2022',
                'interest_rate'  => null,
                'maturity_date'  => null,
                'transactions'   => [
                    $tx('Office Rent', 'Standing order', '19 Oct 2026', 250000.00, 'out'),
                    $tx('Invoice #2041', 'Payment received', '18 Oct 2026', 480000.00, 'in'),
                    $tx('Jumia Business', 'Card purchase', '17 Oct 2026', 36500.00, 'out'),
                    $tx('Fuel Station', 'Card purchase', '16 Oct 2026', 22000.00, 'out'),
                    $tx('Client Payment', 'Transfer received', '14 Oct 2026', 310000.00, 'in'),
                    $tx('Internet Subscription', 'Direct debit', '12 Oct 2026', 35000.00, 'out'),
                    $tx('Refund - Order 8821', 'Merchant refund', '09 Oct 2026', 18900.00, 'in'),
                    $tx('Bank Charges', 'Monthly fee', '01 Oct 2026', 1250.00, 'out'),
                ],
            ],
            [
                'id'             => 'fixed-deposit',
                'name'           => 'Fixed Deposit',
                'type'           => 'Fixed Deposit',
                'number_masked'  => $dots . '5584',
                'currency'       => $naira,
                'balance'        => 10000000.00,
                'available'      => 0.00,
                'status'         => 'Active',
                'opened'         => '01 Feb 2026',
                'interest_rate'  => 14.5,
                'maturity_date'  => '01 Feb 2027',
                'transactions'   => [
                    $tx('Interest Accrual', 'Monthly accrual', '30 Sep 2026', 120833.33, 'in'),
                    $tx('Interest Accrual', 'Monthly accrual', '31 Aug 2026', 120833.33, 'in'),
                    $tx('Interest Accrual', 'Monthly accrual', '31 Jul 2026', 120833.33, 'in'),
                    $tx('Interest Accrual', 'Monthly accrual', '30 Jun 2026', 120833.33, 'in'),
                    $tx('Interest Accrual', 'Monthly accrual', '31 May 2026', 120833.33, 'in'),
                    $tx('Interest Accrual', 'Monthly accrual', '30 Apr 2026', 120833.33, 'in'),
                    $tx('Interest Accrual', 'Monthly accrual', '31 Mar 2026', 120833.33, 'in'),
                    $tx('Deposit', 'Initial placement', '01 Feb 2026', 10000000.00, 'in'),
                ],
            ],
            [
                'id'             => 'dollar-account',
                'name'           => 'Dollar Account',
                'type'           => 'Domiciliary',
                'number_masked'  => $dots . '7730',
                'currency'       => '$',
                'balance'        => 3450.20,
                'available'      => 3450.20,
                'status'         => 'Active',
                'opened'         => '02 Mar 2024',
                'interest_rate'  => null,
                'maturity_date'  => null,
                'transactions'   => [
                    $tx('Upwork Payout', 'Freelance income', '20 Oct 2026', 850.00, 'in'),
                    $tx('Amazon', 'Card purchase', '18 Oct 2026', 64.99, 'out'),
                    $tx('Spotify', 'Subscription', '15 Oct 2026', 11.99, 'out'),
                    $tx('Wise Transfer', 'Transfer received', '12 Oct 2026', 400.00, 'in'),
                    $tx('Airbnb', 'Card purchase', '08 Oct 2026', 210.50, 'out'),
                    $tx('Dividend', 'Investment income', '30 Sep 2026', 38.20, 'in'),
                    $tx('ATM Withdrawal', 'Cash', '27 Sep 2026', 100.00, 'out'),
                    $tx('Client Invoice', 'Payment received', '22 Sep 2026', 1200.00, 'in'),
                ],
            ],
        ];
    }
}