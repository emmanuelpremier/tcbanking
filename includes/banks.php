<?php
/**
 * T&C Banking - bank list for the Transfers bank picker.
 * Placeholder demo data only (not an official or complete register).
 *   n = bank name shown to the user
 *   k = extra words people may type (abbreviations, short names)
 *   p = 1 when the bank is listed first under "Popular banks"
 */
if (!function_exists('get_banks')) {
    function get_banks(): array
    {
        return [
            // Popular
            ['n' => 'Access Bank',                  'k' => 'access',                'p' => 1],
            ['n' => 'First Bank of Nigeria',        'k' => 'firstbank fbn',         'p' => 1],
            ['n' => 'Guaranty Trust Bank',          'k' => 'gtb gtbank gtco',       'p' => 1],
            ['n' => 'Kuda Microfinance Bank',       'k' => 'kuda',                  'p' => 1],
            ['n' => 'Moniepoint Microfinance Bank', 'k' => 'moniepoint mfb',        'p' => 1],
            ['n' => 'OPay',                         'k' => 'opay paycom',           'p' => 1],
            ['n' => 'PalmPay',                      'k' => 'palmpay',               'p' => 1],
            ['n' => 'United Bank for Africa',       'k' => 'uba',                   'p' => 1],
            ['n' => 'Zenith Bank',                  'k' => 'zenith',                'p' => 1],

            // Other banks
            ['n' => 'Carbon Microfinance Bank',     'k' => 'carbon',                'p' => 0],
            ['n' => 'Citibank Nigeria',             'k' => 'citi',                  'p' => 0],
            ['n' => 'Ecobank Nigeria',              'k' => 'eco',                   'p' => 0],
            ['n' => 'FairMoney Microfinance Bank',  'k' => 'fairmoney',             'p' => 0],
            ['n' => 'Fidelity Bank',                'k' => 'fidelity',              'p' => 0],
            ['n' => 'First City Monument Bank',     'k' => 'fcmb',                  'p' => 0],
            ['n' => 'Globus Bank',                  'k' => 'globus',                'p' => 0],
            ['n' => 'Heritage Bank',                'k' => 'heritage',              'p' => 0],
            ['n' => 'Jaiz Bank',                    'k' => 'jaiz',                  'p' => 0],
            ['n' => 'Keystone Bank',                'k' => 'keystone',              'p' => 0],
            ['n' => 'Lotus Bank',                   'k' => 'lotus',                 'p' => 0],
            ['n' => 'Optimus Bank',                 'k' => 'optimus',               'p' => 0],
            ['n' => 'Parallex Bank',                'k' => 'parallex',              'p' => 0],
            ['n' => 'Polaris Bank',                 'k' => 'polaris',               'p' => 0],
            ['n' => 'Premium Trust Bank',           'k' => 'premium',               'p' => 0],
            ['n' => 'Providus Bank',                'k' => 'providus',              'p' => 0],
            ['n' => 'Rubies Microfinance Bank',     'k' => 'rubies',                'p' => 0],
            ['n' => 'Signature Bank',               'k' => 'signature',             'p' => 0],
            ['n' => 'Sparkle Microfinance Bank',    'k' => 'sparkle',               'p' => 0],
            ['n' => 'Stanbic IBTC Bank',            'k' => 'stanbic ibtc',          'p' => 0],
            ['n' => 'Standard Chartered Bank',      'k' => 'stanchart sc',          'p' => 0],
            ['n' => 'Sterling Bank',                'k' => 'sterling',              'p' => 0],
            ['n' => 'SunTrust Bank',                'k' => 'suntrust',              'p' => 0],
            ['n' => 'TAJBank',                      'k' => 'taj',                   'p' => 0],
            ['n' => 'Titan Trust Bank',             'k' => 'titan',                 'p' => 0],
            ['n' => 'Union Bank of Nigeria',        'k' => 'union',                 'p' => 0],
            ['n' => 'Unity Bank',                   'k' => 'unity',                 'p' => 0],
            ['n' => 'VFD Microfinance Bank',        'k' => 'vfd vbank',             'p' => 0],
            ['n' => 'Wema Bank',                    'k' => 'wema alat',             'p' => 0],
        ];
    }
}
