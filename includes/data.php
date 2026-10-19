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
