<?php

// Response Codes & other global configurations
$techConfig = require app_path('Yantrana/__Laraware/Config/tech-config.php');

$techAppConfig = [

    /* Paths - required item will be replaced in custom tech config
    ------------------------------------------------------------------------- */
    'storage_paths' => [
        '/media_storage' => [
            'other/no_thumb_image.jpg' => 'key@no_thumb',
            'other/no-user-thumb-icon.png' => 'key@no_user_thumb',
            'users' => [
                '{_uid}' => [
                    'profile' => 'key@user_photo',
                    'temp_uploads' => 'key@user_temp_uploads',
                ],
            ],
            'logo' => 'key@logo',
            'small_logo' => 'key@small_logo',
            'favicon' => 'key@favicon',
            'file-manager-assets/{userUid}/' => 'key@user_file_manager',
            'file-manager-assets/{userUid}/thumbnails/' => 'key@user_file_manager_thumb',
        ],
    ],
    // 'ignore_storage_paths' => '',

    /* Media extensions
    ------------------------------------------------------------------------- */
    'media' => [
        'extensions' => [
            1 => ['jpg', 'png', 'gif', 'jpeg'],
            2 => ['3gp', 'mp4', 'flv'],
            3 => [],
            4 => ['mp3', 'wav'],
            5 => ['pdf', 'doc', 'docx', 'xls', 'ppt', 'txt'],
            6 => ['pdf', 'jpg', 'png', 'gif', 'jpeg'],
            7 => ['png'], // for logo & profile
            8 => ['pdf', 'doc', 'docx', 'xls', 'ppt', 'txt', 'jpg', 'png', 'gif', 'jpeg'],
            9 => ['ico'],
            10 => ['png', 'ico'],
        ],
    ],

    'login_url' => '/#!/login',
    'reset_password_url' => '/#!/reset-password/',

    /* if demo mode is on then set theme color
    ------------------------------------------------------------------------- */
    'theme_colors' => [
        'purple-white' => [
            'background' => '6900b9',
            'text' => 'ffffff',
        ],
        'blue-white' => [
            'background' => '005bb9',
            'text' => 'ffffff',
        ],
        'green-white' => [
            'background' => '008428',
            'text' => 'ffffff',
        ],
        'brown-white' => [
            'background' => '842500',
            'text' => 'ffffff',
        ],
        'gray-white' => [
            'background' => '424242',
            'text' => 'ffffff',
        ],
        'light-blue-white' => [
            'background' => '43a8ff',
            'text' => 'ffffff',
        ],
        'pink-white' => [
            'background' => 'e185e2',
            'text' => 'ffffff',
        ],
        'chocolate-white' => [
            'background' => 'a06000',
            'text' => 'ffffff',
        ],
    ],

    /* Status Code Multiple Uses
    ------------------------------------------------------------------------- */

    'status_codes' => [
        1 => 'Active',
        2 => 'Inactive',
        3 => 'Banned',
        4 => 'Never Activated',
        5 => 'Deleted',
        6 => 'Suspended',
        7 => 'On Hold',
        8 => 'Completed',
        9 => 'Invite',
    ],

    /* Durations Value
    ------------------------------------------------------------------------*/
    'durations' => [
        1 => 'Current Month',
        2 => 'Last Month',
        3 => 'Current Week',
        4 => 'Last Week',
        5 => 'Today',
        6 => 'Yesterday',
        7 => 'Last Year',
        8 => 'Current Year',
        9 => 'Last 30 Days',
        10 => 'Custom',
    ],

    /* Email Config
    ------------------------------------------------------------------------- */

    'mail_from' => [
        env('MAIL_FROM_ADD', 'your@domain.com'),
        env('MAIL_FROM_NAME', 'E-Mail Service'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_PUBLIC_KEY', ''),
        'secret_key' => env('RECAPTCHA_PRIVATE_KEY', ''),
    ],

    /* Description String limit
    ------------------------------------------------------------------------- */
    'string_limit' => 30,

    'account_activation' => (60 * 60 * 48),

    /* logo name & landing page image
    ------------------------------------------------------------------------- */

    'logoName' => 'logo.png',

    /* Small Logo Name
    ------------------------------------------------------------------------- */

    'smallLogoName' => 'small_logo.png',

    /* favicon name
    ------------------------------------------------------------------------- */

    'faviconName' => 'favicon.ico',

    /* Account related
    ------------------------------------------------------------------------- */

    'account' => [
        'activation_expiry' => 24 * 2, // hours
        'change_email_expiry' => 24 * 2, // hours
        'password_reminder_expiry' => 24 * 2, // hours
        'passwordless_login_expiry' => 5, // minutes
    ],

    /* User
    ------------------------------------------------------------------------- */
    'user' => [
        'statuses' => [
            1,			// Active
            2,			// InActive
            5, 			// Deleted
            12,			// Never Activated
        ],
        'roles' => [
            1 => 'Admin',
        ],
        'permission_status' => [
            1 => 'Role Inheritance',
            2 => 'Allow',
            3 => 'Deny',
        ],
        'chart_color' => [
            1 => '#064FF8', // Active
            2 => '#ffad20', // Inactive
        ],
    ],

    /* tax
    ------------------------------------------------------------------------- */
    'tax' => [
        'type' => [
            1 => ('Flat'),
            2 => ('Percentage'),
        ],
    ],

    /* Activity log status
    ------------------------------------------------------------------------- */
    'activity_log' => [
        'entity_type' => [
            1 => 'User',
            2 => 'Profile',
            3 => 'Role Permission',
            4 => 'User Login Log',
            5 => 'Password',
            6 => 'User Permission(s)',
            7 => 'Inventory',
            8 => 'Category',
            9 => 'Product',
            10 => 'Product Option Label',
            11 => 'Product Option Value',
            12 => 'Option Label / Value',
            13 => 'Supplier',
            14 => 'Location',
            15 => 'Email',
            16 => 'Barcode',
            17 => 'Bill',
            18 => 'Tax Preset',
            19 => 'Tax',
            20 => 'Customer',
            21 => 'Stock Transaction',
            22 => 'Combination',
        ],
        'action_type' => [
            1 => 'Created',
            2 => 'Updated',
            3 => 'Deleted',
            4 => 'Soft Deleted',
            5 => 'Restored',
            6 => 'Logged in',
            7 => 'Logged Out',
            8 => 'Removed',
            9 => 'Assign',
        ],
    ],

    /* Location status
    ------------------------------------------------------------------------- */
    'location' => [
        1 => 'Active',
        2 => 'Inactive',
    ],

    /* Category status
    ------------------------------------------------------------------------- */
    'categories' => [
        1 => 'Active',
        2 => 'Inactive',
    ],

    'products' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
            3 => 'Deleted',
        ],
        'measure_types' => [
            1 => 'Length',
            2 => 'Area',
            3 => 'Weight',
        ],
    ],

    /* Inventory Stock Transaction Related Config Values
    --------------------------------------------------------------------------*/
    'stock_transactions' => [
        'types' => [
            1 => 'Debit',
            2 => 'Credit',
        ],
        'sub_types' => [
            1 => 'Purchase',
            2 => 'Sale',
            3 => 'Purchase Return',
            4 => 'Wastage',
            5 => 'Move In',
            6 => 'Move Out',
            7 => 'Sale Return',
        ],
    ],

    /* Inventory Related Config Values
    --------------------------------------------------------------------------*/
    'invetory' => [
        1 => 'In Stock',
        2 => 'Out of Stock',
    ],

    /* Bill Statuses
    --------------------------------------------------------------------------*/
    'bill_statuses' => [
        1 => 'Draft',
        2 => 'Paid',
    ],

    /* tax status
    ------------------------------------------------------------------------- */
    'tax_status' => [
        1 => 'Active',
        2 => 'Inactive',
    ],

    /* tax preset status
    ------------------------------------------------------------------------- */
    'tax_preset_status' => [
        1 => 'Active',
        2 => 'Inactive',
    ],

    /* Store Related Config Values
    --------------------------------------------------------------------------*/

    'currencies' => [
        /* Zero-decimal currencies
        ----------------------------------------------------------------------*/
        'zero_decimal' => [
            'BIF' => 'Burundian Franc',
            'CLP' => 'Chilean Peso',
            'DJF' => 'Djiboutian Franc',
            'GNF' => 'Guinean Franc',
            'JPY' => 'Japanese Yen',
            'KMF' => 'Comorian Franc',
            'KRW' => 'South Korean Won',
            'MGA' => 'Malagasy Ariary',
            'PYG' => 'Paraguayan Guaraní',
            'RWF' => 'Rwandan Franc',
            'VND' => 'Vietnamese Đồng',
            'VUV' => 'Vanuatu Vatu',
            'XAF:' => 'Central African Cfa Franc',
            'XOF' => 'West African Cfa Franc',
            'XPF' => 'Cfp Franc',
            // Paypal zero-decimal currencies
            'HUF' => 'Hungarian Forint',
            'TWD' => 'New Taiwan Dollar',
        ],
        /* Mostly PayPal Supported currencies
        ----------------------------------------------------------------------*/
        'options' => [
            'AUD' => ('Australian Dollar'),
            'CAD' => ('Canadian Dollar'),
            'EUR' => ('Euro'),
            'GBP' => ('British Pound'),
            'USD' => ('U.S. Dollar'),
            'NZD' => ('New Zealand Dollar'),
            'CHF' => ('Swiss Franc'),
            'HKD' => ('Hong Kong Dollar'),
            'SGD' => ('Singapore Dollar'),
            'SEK' => ('Swedish Krona'),
            'DKK' => ('Danish Krone'),
            'PLN' => ('Polish Zloty'),
            'NOK' => ('Norwegian Krone'),
            'HUF' => ('Hungarian Forint'),
            'CZK' => ('Czech Koruna'),
            'ILS' => ('Israeli New Shekel'),
            'MXN' => ('Mexican Peso'),
            'BRL' => ('Brazilian Real (only for Brazilian members)'),
            'MYR' => ('Malaysian Ringgit (only for Malaysian members)'),
            'PHP' => ('Philippine Peso'),
            'TWD' => ('New Taiwan Dollar'),
            'THB' => ('Thai Baht'),
            'JPY' => ('Japanese Yen'),
            'TRY' => ('Turkish Lira (only for Turkish members)'),
            '' => ('Other'),
        ],

        /* Currencies options with details
        ----------------------------------------------------------------------*/
        'details' => [

            'AUD' => [
                'name' => ('Australian Dollar'),
                'symbol' => 'A$',
                'ASCII' => 'A&#36;',
            ],

            'CAD' => [
                'name' => ('Canadian Dollar'),
                'symbol' => '$',
                'ASCII' => '&#36;',
            ],

            'CZK' => [
                'name' => ('Czech Koruna'),
                'symbol' => 'Kč',
                'ASCII' => 'K&#x10d;',
            ],

            'DKK' => [
                'name' => ('Danish Krone'),
                'symbol' => 'Kr',
                'ASCII' => 'K&#x72;',
            ],

            'EUR' => [
                'name' => ('Euro'),
                'symbol' => '€',
                'ASCII' => '&euro;',
            ],

            'HKD' => [
                'name' => ('Hong Kong Dollar'),
                'symbol' => '$',
                'ASCII' => '&#36;',
            ],

            'HUF' => [
                'name' => ('Hungarian Forint'),
                'symbol' => 'Ft',
                'ASCII' => 'F&#x74;',
            ],

            'ILS' => [
                'name' => ('Israeli New Sheqel'),
                'symbol' => '₪',
                'ASCII' => '&#8361;',
            ],

            'JPY' => [
                'name' => ('Japanese Yen'),
                'symbol' => '¥',
                'ASCII' => '&#165;',
            ],

            'MXN' => [
                'name' => ('Mexican Peso'),
                'symbol' => '$',
                'ASCII' => '&#36;',
            ],

            'NOK' => [
                'name' => ('Norwegian Krone'),
                'symbol' => 'Kr',
                'ASCII' => 'K&#x72;',
            ],

            'NZD' => [
                'name' => ('New Zealand Dollar'),
                'symbol' => '$',
                'ASCII' => '&#36;',
            ],

            'PHP' => [
                'name' => ('Philippine Peso'),
                'symbol' => '₱',
                'ASCII' => '&#8369;',
            ],

            'PLN' => [
                'name' => ('Polish Zloty'),
                'symbol' => 'zł',
                'ASCII' => 'z&#x142;',
            ],

            'GBP' => [
                'name' => ('Pound Sterling'),
                'symbol' => '£',
                'ASCII' => '&#163;',
            ],

            'SGD' => [
                'name' => ('Singapore Dollar'),
                'symbol' => '$',
                'ASCII' => '&#36;',
            ],

            'SEK' => [
                'name' => ('Swedish Krona'),
                'symbol' => 'kr',
                'ASCII' => 'K&#x72;',
            ],

            'CHF' => [
                'name' => ('Swiss Franc'),
                'symbol' => 'CHF',
                'ASCII' => '&#x43;&#x48;&#x46;',
            ],

            'TWD' => [
                'name' => ('Taiwan New Dollar'),
                'symbol' => 'NT$',
                'ASCII' => 'NT&#36;',
            ],

            'THB' => [
                'name' => ('Thai Baht'),
                'symbol' => '฿',
                'ASCII' => '&#3647;',
            ],

            'USD' => [
                'name' => ('U.S. Dollar'),
                'symbol' => '$',
                'ASCII' => '&#36;',
            ],

            'INR' => [
                'name' => ('Indian Rupee'),
                'symbol' => '₹',
                'ASCII' => '&#8377;',
            ],

            'TRY' => [
                'name' => ('Turkish Lira'),
                'symbol' => '₺',
                'ASCII' => '&#x20BA;',
            ],
        ],
    ],

    /* Technical Items Codes
    ------------------------------------------------------------------------- */

    'tech_items' => [
        1 => [
            'title' => 'Active',
            'id' => 1,
            'action' => 'Activate',
            'state' => 'Activated',
        ],
        2 => [
            'title' => 'Inactive',
            'id' => 2,
            'action' => 'Inactivate',
            'state' => 'Inactivated',
        ],
        3 => [
            'title' => 'Deleted',
            'id' => 5,
            'action' => 'Delete',
            'state' => 'Deleted',
        ],
        4 => [
            'title' => 'Unban',
            'id' => 4,
            'action' => 'Unban',
            'state' => 'Unbanned',
        ],
        5 => [
            'title' => 'Ban',
            'id' => 3,
            'action' => 'Ban',
            'state' => 'Banned',
        ],
        6 => [
            'title' => 'Suspend',
            'id' => 6,
            'action' => 'Suspend',
            'state' => 'Suspended',
        ],
        7 => [
            'title' => 'On Hold',
            'id' => 7,
            'action' => 'On Hold',
            'state' => 'On Hold',
        ],
        8 => [
            'title' => 'Complete',
            'id' => 8,
            'action' => 'Complete',
            'state' => 'Completed',
        ],
        9 => [
            'title' => 'Invite',
            'id' => 9,
            'action' => 'Invite',
            'state' => 'Invited',
        ],
        10 => [
            'title' => 'Publish',
            'id' => 10,
            'action' => 'Publish',
            'state' => 'Published',
        ],
        11 => [
            'title' => 'Unpublish',
            'id' => 11,
            'action' => 'Unpublish',
            'state' => 'Unpublished',
        ],
        12 => [
            'title' => 'Never Activate',
            'id' => 12,
            'action' => 'Never Activate',
            'state' => 'Never Activated',
        ],
    ],
    // WARNING!! Do not change, it may break application
    'entity_ownership_id' => 'a28462ee-416a-411e-8e62-93ddf21a181f',

    /* Security configurations for encrypting/decrypting form values
     * one can generate these keys using like given in below example:

        $ openssl genrsa -out rsa_1024_priv.pem 1024
        $ openssl rsa -pubout -in rsa_1024_priv.pem -out rsa_1024_pub.pem

        ---------- OR ------------

        $ openssl genrsa -out rsa_aes256_priv.pem -aes256
        $ openssl rsa -pubout -in rsa_aes256_priv.pem -out rsa_aes256_pub.pem

    ------------------------------------------------------------------------- */
    'form_encryption' => [

        /* Passphrse for RSA Key
        --------------------------------------------------------------------- */
        'default_rsa_passphrase' => '0jVuVXNLudMoP4TnGBVY5V8AsADcrLyTcnLDKkPHLXY',

        /* Default Public RSA Key
        --------------------------------------------------------------------- */

        'default_rsa_public_key' => '-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBANH4TV5bVWUoyd7pL3YKDjAe3LMD4Sl5
s6WB7OUrF5vMgJ0Zv81urTlZSaUssHhpGPObV3X331zAdOv/OEN3KRUCAwEAAQ==
-----END PUBLIC KEY-----',

        /* Default Private RSA Key
        --------------------------------------------------------------------- */

        'default_rsa_private_key' => '-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-256-CBC,F0B5CDA2B9B44AF819B17D051F13D178

iJEe4XiPDyxRfFZSrRkogDKw0ApjPnRlodHM7LivDw7dQ7IlG2Uwpl18/5AJ2F5k
byVpf/gKWfWwt5mIqyWarFKsXsjUnncg70EezDHWaGYB/7Aj9YbYDBuCzzewK/de
T/m6gMb40N0iFzkhpNkFbxPQPK9wMEkpQ4MuJBpMcCe9/bl9tT4tTiCBcukq92Gj
YWyi48iLJmd13BAVsRsQ1ToNJIg3KF6qH29Cs7JJS1oRKdiH7YvDja0IFFLwTzlQ
Se/cZ7nJ23q8ozsgNiNXJ4gRmdVBmgUfB0ZIOzC+UiOmaql9BgJrqPtFE/4u+E8k
+6v5lDA0bHqoCFNnghnF5XuyMK5wivSmRqIfT4xYQxa+DzJtcLzVrOetg+Y8y+m9
8SJAmVhEumuysbwikcZuYfrY+XG6ut9H3orL0Uzhucg=
-----END RSA PRIVATE KEY-----',
    ],
];

$appTechConfig = [];
if (file_exists(base_path('user-tech-config.php'))) {
    $appTechConfig = require base_path('user-tech-config.php');
}

return array_merge($techConfig, $techAppConfig, $appTechConfig);
