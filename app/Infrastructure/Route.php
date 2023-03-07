<?php

namespace App\Infrastructure;

use App\Http\Kernel;
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
        [$prefix, $callback, $options] = $parameters;


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
            $group = $app->group($path, function (RouteCollectorProxy $group) use ($callback, &$app) {
                Route::setup($group);
                $routing = $callback($group);
                Route::setup($app);
                return $routing;
            });

            $aliases = $options['middleware'] ?? [];
            if (is_string($aliases)) {
                $aliases = [$aliases];
            }

            array_walk($aliases, function (string $alias) use ($group) {
                $middleware = Kernel::$middlewareAliases[$alias] ?? $alias;
                $group->add($middleware);
            });

            return $group;
        }

        return $app->$method($path, $callback);
    }
}
