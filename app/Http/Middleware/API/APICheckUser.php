<?php

namespace App\Http\Middleware\API;

use Closure;
use Auth;
use Illuminate\Auth\AuthenticationException;

class APICheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user                     = Auth::user();
        $sessionPasswordChangedAt = $request->session()->get('password_changed_at');

        if (
            isset($user) &&
            $user->user_type == "user" &&
            $user->is_active == 1 &&
            $user->user_is_blocked == 0 &&
            $sessionPasswordChangedAt == $user->password_changed_at &&
            $user->user_role != "suspended"
        ) {
            return $next($request);
        }

        throw new AuthenticationException();

    }

}
