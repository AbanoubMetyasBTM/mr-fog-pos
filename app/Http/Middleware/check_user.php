<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class check_user
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
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

        Auth::logout();

        return redirect(pureLangUrl("/login?do_not_ajax=yes"))->
        with("msg",
            "<div class='alert alert-info'>" . showContent("authentication.please_login") . "</div>"
        )->send();
    }
}
