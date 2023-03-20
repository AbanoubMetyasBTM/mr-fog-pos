<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckLoggedIn
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (isset($user) && $user->is_active == 1) {
            return $next($request);
        }

        return redirect(pureLangUrl("/login?do_not_ajax=yes"))->
        with("msg",
            "<div class='alert alert-info'>" . showContent("authentication.please_login") . "</div>"
        )->send();

    }
}
