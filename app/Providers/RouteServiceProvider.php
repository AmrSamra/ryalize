<?php

namespace App\Providers;

use App\Infrastructure\Route;
use Slim\Routing\RouteCollectorProxy;

class RouteServiceProvider extends ServiceProvider
{
    const DEFAULT_HOME = '/home';

    public function register(): void
    {
        Route::setup($this->app);
    }

    public function boot(): void
    {
        $this->app->redirect('/', Self::DEFAULT_HOME);

        if (file_exists(routes_path('web.php'))) {
            Route::group('', function (RouteCollectorProxy $group) {
                require routes_path('web.php');
            });
        }

        if (file_exists(routes_path('api.php'))) {
            Route::group('api', function (RouteCollectorProxy $group) {
                require routes_path('api.php');
            }, ['middleware' => 'api']);
        }
    }
}
