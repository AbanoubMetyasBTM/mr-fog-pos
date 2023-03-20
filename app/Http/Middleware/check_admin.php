<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class check_admin
{

    public function handle(Request $request, Closure $next)
    {

        $user                     = Auth::user();
        $sessionPasswordChangedAt = $request->session()->get('password_changed_at');

        if (
            isset($user) && $user->is_active == 1 &&
            in_array($user->user_type, ["dev", "admin", "employee"]) &&
            $sessionPasswordChangedAt == $user->password_changed_at
        ) {
            return $next($request);
        }


        if (
            isset($user) &&
            in_array($user->user_type, ["dev", "admin", "employee"])
        ) {
            Auth::logout();
        }

        return redirect("/login?do_not_ajax=yes")->
        with("msg",
            "<div class='alert alert-info'>You should login first</div>"
        )->send();
    }
}
