<?php

namespace App\Http;

use Slim\App;

class Kernel
{
    /**
     * Create a new HTTP kernel instance.
     *
     * @param App $app
     */
    public function __construct(protected App &$app)
    {
        //
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [],

        'api' => [],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [];


    /**
     * The application's service providers.
     *
     * These service providers are registered when the application boots.
     *
     * @var array<string, class-string|string>
     */
    protected $providers = [
        \App\Providers\RouteServiceProvider::class,
        \App\Providers\ErrorMiddlewareServiceProvider::class,
    ];


    public function boot()
    {
        \App\Providers\ServiceProvider::setup($this->app, $this->providers);
    }
}
