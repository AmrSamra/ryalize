<?php

namespace App\Infrastructure;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class Route
{
    public static App $app;

    public static function setup(App &$app)
    {
        Self::$app = $app;
        return $app;
    }

    public static function __callStatic(string $method, array $parameters = [])
    {
        $app = Self::$app;

        if ($method == 'resource') {
            [$prefix, $controller] = $parameters;
            return $app->group('/' . $prefix, function (RouteCollectorProxy $group) use ($controller) {
                $group->get('', $controller, 'index')->setName('index');
                $group->get('/{model}', $controller, 'show')->setName('show');
                $group->post('', $controller, 'store')->setName('store');
                $group->put('/{model}', $controller, 'update')->setName('update');
                $group->delete('/{model}', $controller, 'destroy')->setName('destroy');
            });
        }

        return $app->$method(...$parameters);
    }
}
