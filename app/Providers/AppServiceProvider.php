<?php

namespace App\Providers;

use App\Adapters\API\CurrenciesRates\ICurrenciesRatesAdapter;
use App\Adapters\API\CurrenciesRates\Implementation\CurrenciesRatesAdapter;
use App\helpers\utility;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {

        $this->bindServices();

        $this->bindAdapters();

    }

    public function bindServices()
    {

        $this->app->bind('App\Services\IAuthService', 'App\Services\Implementation\AuthService');
        $this->app->bind(ICurrenciesRatesAdapter::class, CurrenciesRatesAdapter::class);

    }

    public function bindAdapters()
    {


    }


    public function boot()
    {

        \Queue::failing(function (JobFailed $event) {
            $header = "--canda-pos-Website-- source[failed-job] critical issue need to be fixed #" . date("Y-m-d H:i");
            utility::sendErrorLogEmail($header,$event->exception);
        });

    }
}
