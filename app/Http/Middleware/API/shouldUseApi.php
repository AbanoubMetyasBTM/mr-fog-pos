<?php

namespace App\Http\Middleware\API;


use Closure;
use Config;
use Illuminate\Http\Request;


class shouldUseApi {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        \Auth::shouldUse('api');

        return $next($request);
    }

}
