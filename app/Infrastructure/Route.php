<?php

namespace App\Infrastructure;

use App\Http\Kernel;
use Slim\Routing\RouteCollectorProxy;

class Route
{
    public static RouteCollectorProxy $app;

    /**
     * Setup route app
     * @param RouteCollectorProxy $app
     * @return RouteCollectorProxy
     */
    public static function setup(RouteCollectorProxy &$app)
    {
        Self::$app = $app;
        return $app;
    }

    /**
     * Call static methods
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters = [])
    {
        $app = Self::$app;
        [$prefix, $callback, $options] = $parameters;


        if ($method == 'resource') {
            // Create resource routes group
            return Route::group($prefix, function (RouteCollectorProxy $group) use ($callback, $prefix) {
                Route::get('', [$callback, 'index'])->setName("{$prefix}.index");
                Route::post('', [$callback, 'store'])->setName("{$prefix}.store");
                Route::get('{id}', [$callback, 'show'])->setName("{$prefix}.show");
                Route::put('{id}', [$callback, 'update'])->setName("{$prefix}.update");
                Route::delete('{id}', [$callback, 'destroy'])->setName("{$prefix}.destroy");
            });
        }

        $path = $prefix ? "/{$prefix}" : '';

        if ($method == 'group') {
            // Create group routes
            $group = $app->group($path, function (RouteCollectorProxy $group) use ($callback, &$app) {
                Route::setup($group);
                $routing = $callback($group);
                Route::setup($app);
                return $routing;
            });

            // Extract middleware from options
            $aliases = $options['middleware'] ?? [];
            if (is_string($aliases)) {
                $aliases = [$aliases];
            }

            // Add middleware to group
            array_walk($aliases, function (string $alias) use (&$group) {
                $middleware = Kernel::$middlewareAliases[$alias] ?? $alias;
                $group->add($middleware);
            });

            return $group;
        }

        // Handle route
        return $app->$method($path, $callback);
    }
}
