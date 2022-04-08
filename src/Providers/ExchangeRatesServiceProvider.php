<?php

namespace Alfacash\ExchangeRates\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ExchangeRatesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'alfacash-exchange-rates');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'alfacash-exchange-rates');
    }
}
