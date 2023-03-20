<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class check_dev
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=Auth::user();
        if(isset($user)&&$user->user_type=="dev"){
            return $next($request);
        }

        return redirect("/login?do_not_ajax=yes")->
        with("msg",
            "<div class='alert alert-info'>You should login first</div>"
        )->send();
    }
}
