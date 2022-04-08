<?php

namespace Alfacash\ExchangeRates\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        Route::middleware('web')->group(__DIR__ . '/../routes/web.php');
    }
}
