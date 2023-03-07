<?php

namespace App\Providers;


class ErrorMiddlewareServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->addErrorMiddleware(
            env('APP_DEBUG', false) == 'true',
            env('LOG_ERRORS', false) == 'true',
            env('LOG_ERROR_DETAILS', false) == 'true'
        );
    }

    public function boot(): void
    {
        //
    }
}
