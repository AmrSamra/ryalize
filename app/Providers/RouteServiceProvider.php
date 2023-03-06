<?php

namespace App\Providers;

use App\Infrastructure\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Route::setup($this->app);
    }

    public function boot(): void
    {
        require routes_path('web.php');

        require routes_path('api.php');
    }
}
