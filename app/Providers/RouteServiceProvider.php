<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */

    protected $namespace    = 'App\Http\Controllers';
    protected $apiNamespace = 'App\Http\Controllers\API';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {

        #region set timezone config

        $getDefaultTimezone = getSettingsValue('timezone','Asia/Riyadh');
        date_default_timezone_set($getDefaultTimezone);

        #endregion

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        Route::group(['namespace' => $this->namespace,'middleware' => ['web']],function(){
            require base_path('routes/admin_routes.php');
            require base_path('routes/dashboard_routes.php');
            require base_path('routes/dev_routes.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {

        Route::group([
            'middleware' => ['web'],
            'namespace'  => "{$this->namespace}",
        ], function ($router) {
            require base_path('routes/front/web.php');
            require base_path('routes/front/authentication.php');
        });

        Route::group([
            'middleware' => ['web'],
            'namespace'  => "{$this->namespace}",
            'prefix'     => '{lang_title?}',
            'where'      => ["lang_title" => "([a-z-]{5})*"],
        ], function ($router) {
            require base_path('routes/front/web.php');
            require base_path('routes/front/authentication.php');
        });

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {

    }
}
