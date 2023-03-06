<?php

namespace App\Providers;


class ErrorMiddlewareServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->addErrorMiddleware(
            config('middleware.errors.display'),
            config('middleware.errors.log'),
            config('middleware.errors.log_details')
        );
    }

    public function boot(): void
    {
        //
    }
}
