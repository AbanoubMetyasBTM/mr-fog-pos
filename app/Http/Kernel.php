<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        //        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\WebLocalization::class
        ],

        'api' => [
            'throttle:120,1',
            'bindings',
            'APIGate',
            'APILocalization',
            'APILogger',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                     => \App\Http\Middleware\Authenticate::class,
        'auth.basic'               => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'                 => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers'            => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                      => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'                    => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed'                   => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'                 => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'                 => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'check_admin'              => \App\Http\Middleware\check_admin::class,
        'check_dev'                => \App\Http\Middleware\check_dev::class,
        'check_user'               => \App\Http\Middleware\check_user::class,
        'CheckLoggedIn'            => \App\Http\Middleware\CheckLoggedIn::class,
        'APICheckLoggedIn'         => \App\Http\Middleware\API\APICheckLoggedIn::class,
        'APIGate'                  => \App\Http\Middleware\API\APIGate::class,
        'APILocalization'          => \App\Http\Middleware\API\APILocalization::class,
        'APILogger'                => \App\Http\Middleware\API\APILogger::class,
        'shouldUseApi'             => \App\Http\Middleware\API\shouldUseApi::class,
        'WebLocalization'          => \App\Http\Middleware\WebLocalization::class,
        'APICheckUser'             => \App\Http\Middleware\API\APICheckUser::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
