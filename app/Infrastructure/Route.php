<?php

namespace App\Infrastructure;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class Route
{
    public static RouteCollectorProxy $app;

    public static function setup(RouteCollectorProxy &$app)
    {
        Self::$app = $app;
        return $app;
    }

    public static function __callStatic(string $method, array $parameters = [])
    {
        $app = Self::$app;
        [$prefix, $callback] = $parameters;


        if ($method == 'resource') {
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
            return $app->group($path, function (RouteCollectorProxy $group) use ($callback, &$app) {
                Route::setup($group);
                $routing = $callback($group);
                Route::setup($app);
                return $routing;
            });
        }

        return $app->$method($path, $callback);
    }
}
