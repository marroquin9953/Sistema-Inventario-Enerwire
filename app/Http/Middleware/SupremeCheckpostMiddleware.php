<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use YesTokenAuth;

class SupremeCheckpostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $isVerified = YesTokenAuth::verifyToken();
        /*
        if($isVerified['error'] === false) {
            Auth::loginUsingId($isVerified['aud']);
        }*/

        // if user guest
        if (Auth::guard($guard)->guest()) {
            return __apiResponse([
                'message' => __tr('Please login to complete request.'),
                'auth_info' => getUserAuthInfo(9),
            ], 21);
        }

        return $next($request);
    }
}
