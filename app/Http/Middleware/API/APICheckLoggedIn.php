<?php

namespace App\Http\Middleware\API;

use Auth;
use Closure;
use Illuminate\Auth\AuthenticationException;

class APICheckLoggedIn
{

    public function handle($request, Closure $next)
    {

        $user = Auth::user();
        if (isset($user) && $user->is_active == 1 && $user->user_is_blocked == 0) {
            return $next($request);
        }

        throw new AuthenticationException();

    }
}
