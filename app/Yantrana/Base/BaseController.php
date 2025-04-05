<?php

namespace App\Yantrana\Base;

use App\Yantrana\__Laraware\Core\CoreController;
use Illuminate\Support\Facades\Route;
use JavaScript;
use Request;
use YesAuthority;
use YesSecurity;

abstract class BaseController extends CoreController
{
    /**
     * Prepare data for clideside.
     *
     * @return Response
     */
    public function prepareForBrowser()
    {
        // get all application routes.
        $routeCollection = Route::getRoutes();
        $routes = [];
        $index = 1;

        // if routes in application
        if (! empty($routeCollection)) {
            foreach ($routeCollection as $route) {
                if ($route->getName()) {
                    $routes[$route->getName()] = $route->uri();
                } else {
                    $routes['unnamed_'.$index] = $route->uri();
                }

                $index++;
            }
        }

        $auth_token = ! __isEmpty(config('app.yestoken.jti')) ? '&auth_token='.config('app.yestoken.jti') : '';

        $config = config('__tech');
        $themeColors = $config['theme_colors'];
        $default = [
            'background' => getConfigurationSettings('header_background_color'),
            'text' => getConfigurationSettings('header_text_link_color'),
        ];
        $themeColors = array_add($themeColors, 'default', $default);

        return [
            '__appImmutables' => [
                'public_encryption_token' => YesSecurity::getPublicRsaKey(),
                'form_security_id' => YesSecurity::getFormSecurityID(),
                'routes' => $routes,
                'static_assets' => [
                    'vendorlibs_first' => __yesset('dist/css/vendorlibs-first.css'),
                    'vendor_second' => __yesset('dist/css/vendor-second.css'),
                    'application_css' => __yesset('dist/css/application*.css'),
                    'vendorlibs_manage' => __yesset('dist/css/vendorlibs-manage.css'),
                ],
                'messages' => [
                    'validation' => trans('validation'),
                    'js_string' => trans('js-string'),
                ],
                'auth_info' => getUserAuthInfo(),
                'config' => [
                    'currency_format' => [
                        'raw' => getConfigurationSettings('currency_format'),
                        'full' => moneyFormat('__LABEL__', true, true),
                        'short' => moneyFormat('__LABEL__', true),
                    ],
                    'isZeroDecimalCurrency' => isZeroDecimalCurrency(),
                    'currencyDecimalRound' => getConfigurationSettings('currency_decimal_round'),
                    'roundZeroDecimalCurrency' => getConfigurationSettings('round_zero_decimal_currency'),
                    'zeroDecimalCurrencies' => configItem('currencies.zero_decimal'),
                    'theme_colors' => $themeColors,
                    'logo_image_url' => getConfigurationSettings('logo_image_url'),
                ],
                'restrict_user_email_update' => getConfigurationSettings('restrict_user_email_update'),
                'publicApp' => Request::route()->getName() != 'manage.app' ? true : false,
                'availableRoutes' => YesAuthority::availableRoutes(),
            ],
            '__appTemps' => [
                'stateViaRoute' => null,
            ],
            'appConfig' => [
                'debug' => env('APP_DEBUG', false),
                'appBaseURL' => asset(''),
            ],
        ];
    }

    public function loadPublicView($viewName, $data = [])
    {
        $browserData = $this->prepareForBrowser();

        JavaScript::put($browserData);

        $output = view('public-master', $data)->nest('pageRequested', $viewName, $data)->render();

        if (! env('APP_DEBUG', false)) {
            $filters = [
                '/<!--([^\[|(<!)].*)/' => '',  // Remove HTML Comments (breaks with HTML5 Boilerplate)
                '/(?<!\S)\/\/\s*[^\r\n]*/' => '',  // Remove comments in the form /* */
                '/\s{2,}/' => ' ', // Shorten multiple white spaces
                '/(\r?\n)/' => '',  // Collapse new lines
            ];

            return preg_replace(
                array_keys($filters),
                array_values($filters),
                $output
            );
        } else { // for clog
            $clogSessItemName = '__clog';

            if (isset($_SESSION[$clogSessItemName]) == true and ! empty($_SESSION[$clogSessItemName])) {
                $responseData = [];

                $responseData['__dd'] = true;
                $responseData['__clogType'] = 'NonAjax';
                $responseData[$clogSessItemName] = $clogSessItemName;
                // set for response
                $responseData[$clogSessItemName] = $_SESSION[$clogSessItemName];
                //reset the __clog items in session
                $_SESSION[$clogSessItemName] = [];

                $output = $output.'<script>__globals.clog('.json_encode($responseData).')</script>';
            }
        }

        return $output;
    }

    public function loadMailView($viewName, $data = [])
    {
        return view('emails.index', $data)->nest('pageMailRequested', $viewName, $data);
    }
}
