<?php

namespace App\Providers;

use App\Http\Handlers\JsonErrorRenderer;

class ErrorMiddlewareServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->addErrorMiddleware(
            env('APP_DEBUG', false) == 'true',
            env('LOG_ERRORS', false) == 'true',
            env('LOG_ERROR_DETAILS', false) == 'true'
        )
            ->getDefaultErrorHandler()
            ->registerErrorRenderer('application/json', JsonErrorRenderer::class);
    }

    public function boot(): void
    {
        //
    }
}
