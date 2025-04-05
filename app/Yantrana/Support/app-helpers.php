<?php

use App\Yantrana\Components\User\Models\UserAuthorityModel;
use Carbon\Carbon;

/*
    |--------------------------------------------------------------------------
    | App Helpers
    |--------------------------------------------------------------------------
    |
    */

/**
 * Get the technical items from tech items
 *
 * @param  string  $key
 * @param  mixed  $requireKeys
 * @return mixed
 *-------------------------------------------------------- */
if (! function_exists('configItem')) {
    function configItem($key, $requireKeys = null)
    {
        if (! __isEmpty($requireKeys) and ! is_array($requireKeys)) {
            return config('__tech.'.$key.'.'.$requireKeys);
        }

        $geItem = array_get(config('__tech'), $key);

        if (! __isEmpty($requireKeys) and is_array($requireKeys)) {
            return array_intersect_key($geItem, array_flip($requireKeys));
        }

        return $geItem;
    }
}

/**
 * Get the auth id
 *
 * @return mixed
 *-------------------------------------------------------- */
if (! function_exists('authUID')) {
    function authUID()
    {
        return Auth::user()->_uid;
    }
}

/*
    * Check if access available
    *
    * @param string $accessId
    *
    * @return bool.
    *-------------------------------------------------------- */

if (! function_exists('canAccess')) {
    function canAccess($accessId = null)
    {
        if (
            YesAuthority::check($accessId) === true
            or YesAuthority::isPublicAccess($accessId)
        ) {
            return true;
        }

        return false;
    }
}

/*
    * Check if access available
    *
    * @param string $accessId
    *
    * @return bool.
    *-------------------------------------------------------- */
if (! function_exists('canPublicAccess')) {
    function canPublicAccess($accessId = null)
    {
        return YesAuthority::isPublicAccess($accessId);
    }
}

/*
    * Customized GetText string
    *
    * @param string $string
    * @param array $replaceValues
    *
    * @return string.
    *-------------------------------------------------------- */

if (! function_exists('__tr')) {
    function __tr($string, $replaceValues = [])
    {
        if (configItem('gettext_fallback')) {
            $string = T_gettext($string);
        }

        // Check if replaceValues exist
        if (! empty($replaceValues) and is_array($replaceValues)) {
            $string = strtr($string, $replaceValues);
        }

        return $string;
    }
}

/**
 * Get user ID
 *
 * @return number.
 *-------------------------------------------------------- */
if (! function_exists('getUserID')) {
    function getUserID()
    {
        return Auth::id();
    }
}

/**
 * Get user Authority Id
 *
 * @return number.
 *-------------------------------------------------------- */
if (! function_exists('getUserAuthorityId')) {
    function getUserAuthorityId()
    {
        $userAuthInfo = getUserAuthInfo();

        if ($userAuthInfo['authorized']) {
            return $userAuthInfo['user_authority_id'];
        }

        return null;
    }
}

/**
 * Get user authentication
 *
 * @return array
 *---------------------------------------------------------------- */
if (! function_exists('getUserAuthInfo')) {
    function getUserAuthInfo($itemKey = null)
    {
        $userAuthInfo = [
            'authorized' => false,
            'reaction_code' => 9,
        ];

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->_id;
            $userAuthority = UserAuthorityModel::where('users__id', $userId)->first();

            $authenticationToken = md5(uniqid(true));

            $userAuthInfo = [
                'authorization_token' => $authenticationToken,
                'authorized' => true,
                'reaction_code' => (! __isEmpty($itemKey) and is_numeric($itemKey))
                    ? $itemKey : 10,
                'profile' => [
                    'full_name' => $user->first_name.' '.$user->last_name,
                    'email' => $user->email,
                    'username' => $user->username,
                ],
                'personnel' => $userId,
                'designation' => (isset($userAuthority->user_roles__id))
                    ? $userAuthority->user_roles__id : null,
                'user_authority_id' => $userAuthority->_id,
            ];

            if ($itemKey and array_key_exists($itemKey, $userAuthInfo)) {
                return $userAuthInfo[$itemKey];
            }
        }

        return $userAuthInfo;
    }
}

/**
 * Check if logged in user is admin
 *
 * @return bool
 *-------------------------------------------------------- */
if (! function_exists('isAdmin')) {
    function isAdmin()
    {
        // Check if user looged in
        if (isLoggedIn()) {
            $userRole = UserAuthorityModel::where('users__id', '=', getUserID())->first();
            if ($userRole->user_roles__id === 1) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Get user ID
 *
 * @return number.
 *-------------------------------------------------------- */
if (! function_exists('isActiveUser')) {
    function isActiveUser()
    {
        if (! empty(Auth::user())) {
            if (Auth::user()->status != 1) {
                Session::flash(
                    'invalidUserMessage',
                    __tr('Invalid request please contact administrator.')
                );

                Auth::logout();

                return true;
            }
        }

        return false;
    }
}

/**
 * Check if user logged in application
 *
 * @return bool
 *-------------------------------------------------------- */
if (! function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        isActiveUser();

        return Auth::check();
    }
}

/**
 * Convert date with setting time zone
 *
 * @param  string  $rawDate
 * @return date
 *-------------------------------------------------------- */
if (! function_exists('storeTimezone')) {
    function storeTimezone($rawDate)
    {
        $carbonDate = Carbon::parse($rawDate);

        $storeTimezone = getConfigurationSettings('timezone');

        if (! __isEmpty($storeTimezone)) {
            $carbonDate->timezone = $storeTimezone;
        }

        return $carbonDate;
    }
}

/**
 * Get formatted date time from passed raw date using timezone
 *
 * @param  string  $rawDateTime
 * @return date
 *-------------------------------------------------------- */
if (! function_exists('formatDate')) {
    function formatDate($rawDateTime, $format = 'l jS F Y')
    {
        $dateUpdatedTimezone = storeTimezone($rawDateTime);

        // if format set as false then return carbon object
        if ($format === false) {
            return $dateUpdatedTimezone;
        }

        return $dateUpdatedTimezone->format($format);
    }
}

/**
 * Get formatted date time from passed raw date using timezone
 *
 * @param  string  $rawDateTime
 * @return date
 *-------------------------------------------------------- */
if (! function_exists('formatDateTime')) {
    function formatDateTime($rawDateTime, $format = 'l jS F Y g:i:s a')
    {
        $dateUpdatedTimezone = storeTimezone($rawDateTime);

        // if format set as false then return carbon object
        if ($format === false) {
            return $dateUpdatedTimezone;
        }

        return $dateUpdatedTimezone->format($format);
    }
}

/*
    * Add activity log entry
    *
    * @param string $activity
    *
    * @return void.
    *-------------------------------------------------------- */

if (! function_exists('activityLog')) {
    function activityLog($entityType, $entityId, $actiontype, $itemName = null, $description = '')
    {
        $userAuthInfo = getUserAuthInfo();
        $userId = Auth::user();

        if (! __isEmpty($userId)) {
            $userAuthority = UserAuthorityModel::where('users__id', $userId->_id)->first();

            $activity = [
                'user_info' => [
                    'id' => getUserID(),
                    'full_name' => (isset($userAuthInfo['profile']['full_name']))
                        ? $userAuthInfo['profile']['full_name']
                        : null,
                    'email' => (isset($userAuthInfo['profile']['email']))
                        ? $userAuthInfo['profile']['email']
                        : null,
                    'username' => $userAuthInfo['profile']['username'],
                ],
                'ip' => Request::ip(),
                'itemName' => $itemName,
                'description' => $description,
            ];

            App\Yantrana\Components\ActivityLog\Models\ActivityLogModel::create([
                '__data' => $activity,
                'created_at' => Carbon::now(),
                'action_type' => $actiontype,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'user_id' => getUserID(),
                'user_role_id' => $userAuthority->user_roles__id,
                'eo_uid' => entityOwnershipId(),
            ]);
        }
    }
}

/**
 * Get no thumb image URL
 *
 * @return string
 *-------------------------------------------------------- */
if (! function_exists('noUserThumbImageURL')) {
    function noUserThumbImageURL()
    {
        return url('/dist/imgs/no-user-thumb-icon.png');
    }
}

/**
 * Get the technical items from tech items
 *
 * @param  string  $key
 * @param  array  $requireKeys
 * @param  array  $options.
 * @return mixed
 *-------------------------------------------------------- */
if (! function_exists('techItem')) {
    function techItem($key, $requireKeys, $options = [])
    {
        $techItems = config('__tech.tech_items');

        $requestedItems = config('__tech.'.$key);

        // if requested items key not exist then return blank array
        if (__isEmpty($requestedItems)) {
            return [];
        }

        $requestedTechItems = array_only($techItems, $requestedItems);

        if (
            ! __isEmpty($options)
            and array_key_exists('only', $options)
            and is_array($options['only'])
        ) {
            $requestedTechItems = array_only($requestedTechItems, $options['only']);
        }

        $items = [];

        if (! __isEmpty($requestedTechItems)) {
            foreach ($requestedTechItems as $key => $item) {
                $items[] = array_only($item, $requireKeys);
            }
        }

        return $items;
    }
}

/**
 * Get the technical item string using passed item
 *
 * @param  string  $itemKey
 * @param  string  $stringKey
 * @return mixed
 *-------------------------------------------------------- */
if (! function_exists('techItemString')) {
    function techItemString($itemKey, $stringKey = 'title')
    {
        $techItem = config('__tech.tech_items.'.$itemKey);

        return array_get($techItem, $stringKey);

        // if requested item not found then return blank string
        //  return !__isEmpty($techItem) ? $techItem->get($stringKey) : '';
    }
}

/**
 * get timezone list
 *
 * @param  string  $timezone
 * @return string
 *---------------------------------------------------------------- */
if (! function_exists('getTimeZone')) {
    function getTimeZone()
    {
        $timezoneCollection = [];

        $timezoneList = timezone_identifiers_list();

        foreach ($timezoneList as $timezone) {
            $timezoneCollection[] = [
                'value' => $timezone,
                'text' => $timezone,
            ];
        }

        return $timezoneCollection;
    }
}

/**
 * Get shop items media storage path
 *
 * @return string path.
 *-------------------------------------------------------- */
if (! function_exists('mediaStorage')) {
    function mediaStorage($item, $dynamicItems = null, $generateUrl = false)
    {
        $storagePaths = __nestedKeyValues(
            config('__tech.storage_paths'),
            '/'
        );

        $itemPath = $storagePaths[$item];

        if ($itemPath) {
            if ($dynamicItems and ! is_array($dynamicItems)) {
                $itemPath = strtr($itemPath, ['{_uid}' => $dynamicItems]);
            } elseif ($dynamicItems and is_array($dynamicItems)) {
                $itemPath = strtr($itemPath, $dynamicItems);
            }

            if ($generateUrl) {
                // return str_replace(config('__tech.ignore_storage_paths'),'', url($itemPath));
                return url($itemPath);
            }

            return public_path($itemPath);
        }
    }
}

/**
 * Get shop items media storage URL
 *
 * @return string path.
 *-------------------------------------------------------- */
if (! function_exists('mediaUrl')) {
    function mediaUrl($item, $dynamicItems = null, $generateUrl = false)
    {
        return mediaStorage($item, $dynamicItems, true);
    }
}

/**
 * Get Static Assets Path
 *
 * @param  string  $fileName
 * @param  string  $folderName
 * @return string path.
 *-------------------------------------------------------- */
if (! function_exists('getStaticAssetsPath')) {
    function getStaticAssetsPath($fileName = null, $folderName = null)
    {
        $staticAssetsUrl = url('static-assets/imgs/');

        if (__isEmpty($fileName) and __isEmpty($folderName)) {
            return $staticAssetsUrl;
        }

        if ($folderName) {
            return $staticAssetsUrl.'/'.$folderName.'/'.$fileName;
        }

        return $staticAssetsUrl.'/'.$fileName;
    }
}

/*
    * get setting items
    *
    * @param string $name
    *
    * @return void
    *---------------------------------------------------------------- */

if (! function_exists('getConfigurationSettings')) {
    function getConfigurationSettings($name, $details = false)
    {
        $configurationNames = config('__settings.items');
        $logoDirUrl = mediaUrl('logo');
        $mediaLogoStoragePath = mediaStorage('logo');
        $smallLogoUrl = mediaUrl('small_logo');
        $mediaSmallLogoStoragePath = mediaStorage('small_logo');
        $faviconDirUrl = mediaUrl('favicon');
        $medaFaviconStoragePath = mediaStorage('favicon');

        $settings = Cache::rememberForever('cache.all.configurations', function () {
            $getSettings = App\Yantrana\Components\Configuration\Models\Configuration::all();

            $storeSettings = [];

            foreach ($getSettings as $setting) {
                $value = getDataType($setting);

                $storeSettings[$setting->name] = $value;
            }

            unset($checkoutMethods, $getSettings);

            return $storeSettings;
        });

        $settings['selected_background_theme_color'] = session('background_theme_color');
        $settings['selected_text_theme_color'] = session('text_theme_color');

        // Set here dynamic logo
        if (__ifIsset($settings['logo_image']) and File::exists($mediaLogoStoragePath.'/'.$settings['logo_image'])) {
            $logoImage = $settings['logo_image'];

            $settings['logo_image_url'] = $logoDirUrl.'/'.$logoImage.'?logover='.@filemtime($mediaLogoStoragePath.'/'.$logoImage);
        }

        // Set here dynamic small logo
        if (__ifIsset($settings['small_logo_image']) and File::exists($mediaSmallLogoStoragePath.'/'.$settings['small_logo_image'])) {
            $smallLogoImage = $settings['small_logo_image'];

            $settings['small_logo_image_url'] = $smallLogoUrl.'/'.$smallLogoImage.'?smalllogover='.@filemtime($mediaSmallLogoStoragePath.'/'.$smallLogoImage);
        }

        // Set here dynamic favicon
        if (__ifIsset($settings['favicon_image']) and File::exists($medaFaviconStoragePath.'/'.$settings['favicon_image'])) {
            $faviconImage = $settings['favicon_image'];

            // $settings['favicon_image']     = $faviconDirUrl.'/'.$value;

            $settings['favicon_image_url'] = $faviconDirUrl.'/'.$faviconImage.'?faviconver='.@filemtime($mediaLogoStoragePath.'/'.$faviconImage);
        }

        if (array_key_exists($name, $settings)) {
            return $settings[$name];
        }

        // For Default Logo
        if (($name == 'logo_image') or ($name == 'logo_image_url')) {
            $logoName = $configurationNames['logo_image']['default'];

            $fullLogoPath = getStaticAssetsPath($logoName);

            $defaultSettings['logo_image'] = $fullLogoPath;
            $defaultSettings['logo_image_url'] = $fullLogoPath.'?logover='.@filemtime($fullLogoPath);

            return $defaultSettings[$name];
        }

        // For Default small Logo
        if (($name == 'small_logo_image') or ($name == 'small_logo_image_url')) {
            $smallLogoName = $configurationNames['small_logo_image']['default'];

            $fullLogoPath = getStaticAssetsPath($smallLogoName);

            $defaultSettings['small_logo_image'] = $fullLogoPath;
            $defaultSettings['small_logo_image_url'] = $fullLogoPath.'?smalllogover='.@filemtime($fullLogoPath);

            return $defaultSettings[$name];
        }

        // For Default Favicon
        if (($name == 'favicon_image') or ($name == 'favicon_image_url')) {
            $faviconName = $configurationNames['favicon_image']['default'];

            $fullFaviconPath = getStaticAssetsPath($faviconName);

            $defaultSettings['favicon_image'] = $fullFaviconPath;
            $defaultSettings['favicon_image_url'] = $fullFaviconPath.'?logover='.@filemtime($fullFaviconPath);

            return $defaultSettings[$name];
        }

        return $configurationNames[$name]['default'];
    }
}

/*
    * Check if access social account is valid
    *
    * @param string $providerKey
    *
    * @return bool.
    *-------------------------------------------------------- */

if (! function_exists('getSocialProviderName')) {
    function getSocialProviderName($providerKey)
    {
        if (! __ifIsset($providerKey)) {
            return false;
        }

        $socialLoginDriver = Config('__tech.social_login_driver');

        if (array_key_exists($providerKey, $socialLoginDriver) !== false) {
            return $socialLoginDriver[$providerKey];
        }

        return false;
    }
}

/*
    * Check if access social account is valid
    *
    * @param string $providerKey
    *
    * @return bool.
    *-------------------------------------------------------- */

if (! function_exists('getSocialProviderKey')) {
    function getSocialProviderKey($providerKey)
    {
        if (! __ifIsset($providerKey)) {
            return false;
        }

        $socialLoginDriver = Config('__tech.social_login_driver_keys');

        if (array_key_exists($providerKey, $socialLoginDriver)) {
            return $socialLoginDriver[$providerKey];
        }

        return false;
    }
}

/**
 * get profile path of user
 *
 * @return string
 *---------------------------------------------------------------- */
if (! function_exists('getProfileImage')) {
    function getProfileImage($profileFile, $uid = null)
    {
        $userUid = isset($uid) ? $uid : Auth::user()->_uid;

        if (__isEmpty($profileFile)) {
            return noUserThumbImageURL();
        }

        $profileMedia = mediaStorage('user_photo', [
            '{_uid}' => $userUid,
        ]).'/'.$profileFile;

        return (file_exists($profileMedia) === true)
            ? mediaUrl('user_photo', ['{_uid}' => $userUid]).'/'.$profileFile
            : noUserThumbImageURL();
    }
}

/**
 * Get demo mode for Demo of site
 *
 * @return boolean.
 *-------------------------------------------------------- */
if (! function_exists('isDemo')) {
    function isDemo()
    {
        return (env('IS_DEMO_MODE', false)) ? true : false;
    }
}

/**
 * Get entity ownership id
 *
 * @return string.
 *-------------------------------------------------------- */
if (! function_exists('entityOwnershipId')) {
    function entityOwnershipId()
    {
        return configItem('entity_ownership_id');
    }
}

/**
 * Get the data-type of each item
 *
 * @param  int  $itemId
 * @return string path.
 *-------------------------------------------------------- */
if (! function_exists('getDataType')) {
    function getDataType($setting)
    {
        $configurationNames = config('__settings.items');
        $name = $setting->name;
        $value = $setting->value;

        if (! __isEmpty($name) and array_key_exists($name, $configurationNames)) {
            $datTypeId = $configurationNames[$name]['data_type'];

            switch ($datTypeId) {
                case 1:
                    return (string) $value;
                    break;
                case 2:
                    return (bool) $value;
                    break;
                case 3:
                    return (int) $value;
                    break;
                case 4:
                    return json_decode($value, true);
                    break;
                default:
                    return $value;
            }
        }
    }
}

/**
 * get set currency
 *
 * @return string
 *---------------------------------------------------------------- */
if (! function_exists('getCurrency')) {
    function getCurrency()
    {
        return html_entity_decode(getConfigurationSettings('currency_value'));
    }
}

/**
 * get set currency Symbol
 *
 * @return string
 *---------------------------------------------------------------- */
if (! function_exists('getCurrencySymbol')) {
    function getCurrencySymbol()
    {
        return html_entity_decode(getConfigurationSettings('currency_symbol'));
    }
}

/**
 * Check if Zero Decimal Currency
 *
 * @param string - $currency  - currency
 * @return void
 *-----------------------------------------------------------------------*/
if (! function_exists('isZeroDecimalCurrency')) {
    function isZeroDecimalCurrency($currency = null)
    {
        if (! $currency) {
            $currency = getCurrency();
        }

        return array_key_exists($currency, configItem('currencies.zero_decimal'));
    }
}

/**
 * Handle Currency Amount
 *
 * @param string - $currency  - currency
 * @return void
 *-----------------------------------------------------------------------*/
if (! function_exists('handleCurrencyAmount')) {
    function handleCurrencyAmount($amount, $currency = null)
    {
        if (! $amount) {
            return 0;
        }

        if (! $currency) {
            $currency = getCurrency();
        }
        // Round Zero Decimal Currency
        if ((isZeroDecimalCurrency($currency) === true)
            and (getConfigurationSettings('round_zero_decimal_currency')) === true
        ) {
            return round($amount);
        }

        return round((float) $amount, getConfigurationSettings('currency_decimal_round'));
    }
}

/*
    * return formated price
    *
    * @param float $amount
    *
    * @return float
    *---------------------------------------------------------------- */

if (! function_exists('moneyFormat')) {
    function moneyFormat($amount, $currencyCode = false, $currencySymbol = false, $options = [])
    {
        if ($currencyCode === true) {
            $currencyCode = getCurrency();
        }

        if (! is_string($currencyCode) and $currencySymbol === true) {
            $currencySymbol = getCurrencySymbol();
        }

        if (
            is_string($currencyCode)
            and ! __isEmpty($currencyCode)
            and __isEmpty($currencySymbol)
        ) {
            $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
            $currencyCode = '';
        } elseif (
            is_string($currencySymbol)
            and ! __isEmpty($currencySymbol)
        ) {
            $currencySymbol = $currencySymbol;
        }

        // If currency code and symbol have string
        if (
            is_string($currencyCode)
            and ! __isEmpty($currencyCode)
            and is_string($currencySymbol)
            and ! __isEmpty($currencySymbol)
        ) {
            $currencyCode = $currencyCode;
            $currencySymbol = $currencySymbol;
        }

        if (
            is_string($currencyCode)
            and ! __isEmpty($currencyCode)
            and $currencySymbol == 'true'
        ) {
            $currencyCode = $currencyCode;
            $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
        }

        if (
            is_string($currencyCode)
            and ! __isEmpty($currencyCode)
            and $currencySymbol == 'false'
        ) {
            $currencyCode = '';
            $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
        }

        // Check if currency is zero decimal
        // If it is zero decimal currency then remove ".00" from amount
        if (
            isZeroDecimalCurrency($currencyCode)
            and (getConfigurationSettings('round_zero_decimal_currency')) === true
        ) {
            $price = number_format(handleCurrencyAmount($amount, $currencyCode));
        } else {
            $price = number_format(handleCurrencyAmount($amount, $currencyCode), getConfigurationSettings('currency_decimal_round'));
        }

        // Check if options array exist
        if (
            ! __isEmpty($options)
            and array_key_exists('format', $options)
        ) {
            if ($options['format'] == 'SHORT') {
                $currencyCode = '';
            }
        }

        return trim(strtr(strip_tags(getConfigurationSettings('currency_format')), [
            '{__currencySymbol__}' => ($currencySymbol === '__SYMBOL__') ? '{__currencySymbol__}' : $currencySymbol,
            '{__amount__}' => ($amount === '__LABEL__') ? '{__amount__}' : $price,
            '{__currencyCode__}' => $currencyCode,
        ]));
    }
}

/**
 * Get account by subdomain/domain
 *
 * @return bool
 *---------------------------------------------------------------- */
if (! function_exists('accountDomainId')) {
    function accountDomainId()
    {
        if (isset($_SERVER['HTTP_HOST']) === false) {
            return null;
        }

        $fullDomain = trim(strip_tags($_SERVER['HTTP_HOST']));
        $apiDomain = env('API_DOMAIN', '');

        if ($apiDomain and strpos($fullDomain, $apiDomain) !== false) {
            $domainParts = explode('.', $fullDomain);

            return $domainParts[0];
        }

        return $fullDomain;
    }
}

/**
 * sets the Authentication token (jwt)
 *
 * @return boolean.
 *-------------------------------------------------------- */
if (! function_exists('setAuthToken')) {
    function setAuthToken($token)
    {
        if ($token == '') {
            $minutes = time() - 3600;
        } else {
            $minutes = config('yes-token-auth.expiration');
        }

        Cookie::queue(Cookie::make('auth_access_token', $token, $minutes));
    }
}
