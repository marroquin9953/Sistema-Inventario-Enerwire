<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            Auth::logout();

            return __apiResponse([
                'message' => __tr('Please login to complete request.'),
                'auth_info' => getUserAuthInfo(),
            ], 4);
        } else {
            return route('manage.app');
        }
    }
}
