<?php

namespace App\Http\Middleware;

use App\Yantrana\Components\User\Models\User;
use App\Yantrana\Services\YesTokenAuth\TokenRegistry\Models\TokenRegistryModel;
use App\Yantrana\Services\YesTokenAuth\TokenRegistry\Repositories\TokenRegistryRepository;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Route;
use YesAuthority;
use YesTokenAuth;

class YesAuthorityCheckpostMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Token Registry Model.
     *
     *-----------------------------------------*/
    protected $tokenRegistryModel;

    /**
     * Token Registry Repository.
     *
     *-----------------------------------------*/
    protected $tokenRegistryRepository;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @param  ItemCommentRepository  $itemCommentRepository  - ItemComment Repository
     * @param  TokenRegistryModel  $tokenRegistryModel     - Token Registry Model
     * @param  TokenRegistryRepository  $tokenRegistryRepository     - Token Registry Repository
     */
    public function __construct(
        Guard $auth,
        TokenRegistryModel $tokenRegistryModel,
        TokenRegistryRepository $tokenRegistryRepository
    ) {
        $this->auth = $auth;
        $this->tokenRegistryModel = $tokenRegistryModel;
        $this->tokenRegistryRepository = $tokenRegistryRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        config([
            '__tech.auth_info' => [
                'authorized' => false,
                'reaction_code' => 9,
            ],
        ]);

        $isVerified = YesTokenAuth::verifyToken();

        $tokenRefreshed = array_get($isVerified, 'refreshed_token', null);

        if ($tokenRefreshed) {
            /*set refreshed token */
            setAuthToken($tokenRefreshed);
        }

        if ($isVerified['error'] === false) {
            $userInfo = User::where('_id', $isVerified['aud'])->first();

            if (! __isEmpty($userInfo) && $userInfo->status == 2 || $userInfo->status == 5) {
                return __apiResponse([
                    'message' => __tr('Your Account seems to Inactive or Deleted, Please contact Administrator.'),
                    'auth_info' => getUserAuthInfo(9),
                ], 9);
            } elseif (! __isEmpty($userInfo) && $userInfo->status == 1) {
                Auth::loginUsingId($isVerified['aud']);

                if (Route::currentRouteName() != 'base_data') {
                    $authority = YesAuthority::withDetails()->check();

                    if (($authority->is_access() === false) || (Auth::user()->status !== 1)) {
                        if ($authority->response_code() === 403) { // Authentication Required
                            Auth::logout();
                            if ($request->ajax()) {
                                return __apiResponse([
                                    'message' => __tr('Please login to complete request.'),
                                    'auth_info' => getUserAuthInfo(9),
                                ], 9);
                            }

                            return redirect()->route('user.login')
                                         ->with([
                                             'error' => true,
                                             'message' => __tr('Please login to complete request.'),
                                         ]);
                        }

                        // When it loggedIn But not permission to access route
                        if ($authority->response_code() === 401) { // Unauthorized
                            if ($request->ajax()) {
                                return __apiResponse([
                                    'message' => __tr('Unauthorized Access.'),
                                    'auth_info' => getUserAuthInfo(11),
                                ], 11);
                            }
                        }

                        return redirect()->route('manage.app')
                                     ->with([
                                         'error' => true,
                                         'message' => __tr('Unauthorized.'),
                                     ]);
                    }
                }

                config([
                    'app.yestoken.jti' => $isVerified['jti'],
                ]);
            }
        } else {
            if (Route::currentRouteName() != 'base_data') {
                Auth::logout();
                if ($request->ajax()) {
                    return __apiResponse([
                        'message' => __tr('Please login to complete request.'),
                        'auth_info' => getUserAuthInfo(9),
                    ], 9);
                }

                return redirect()->route('manage.app')
                                     ->with([
                                         'error' => true,
                                         'message' => __tr('Unauthorized.'),
                                     ]);
            }
        }

        return $next($request);
    }
}
