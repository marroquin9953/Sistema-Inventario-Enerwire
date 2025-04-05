<?php

// WARNING!! DO NOT CHANGE HERE, It may break auto update etc.
$lwSystemConfig = [
    'product_name' => 'Inventario',
    'product_uid' => '84dfd8d5-fadc-4194-8a9b-c2993884a4f0',
    'your_email' => '',
    'registration_id' => '',
    'name' => 'Inventario',
    'version' => '1.5.0',
    'app_update_url' => env('APP_UPDATE_URL', 'https://sararobotics.org/'),
];
$versionInfo = [];

if (file_exists(config_path('.lw_registration.php'))) {
    $versionInfo = require config_path('.lw_registration.php');
}

return array_merge($lwSystemConfig, $versionInfo);
