<?php

namespace App\Providers;

use Slim\App;

abstract class ServiceProvider
{

    /**
     * Create a new service provider instance.
     * @param App $app
     */
    final public function __construct(public App &$app)
    {
        //
    }

    /**
     * Register any application services.
     * @return void
     */
    abstract public function register(): void;


    /**
     * Bootstrap any application services.
     * @return void
     */
    abstract public function boot(): void;


    /**
     * Setup the application's service providers.
     * @param App $app
     * @param array $providerClasses
     * @return void
     */
    final public static function setup(App &$app, array $providerClasses): void
    {
        $providers = array_map(function ($class) use (&$app): ServiceProvider {
            return new $class($app);
        }, $providerClasses);

        array_walk($providers, function (ServiceProvider $provider) {
            $provider->register();
            $provider->boot();
        });
    }
}
