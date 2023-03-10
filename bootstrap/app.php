<?php

use App\Http\Kernel;
use DI\Container;
use Slim\Factory\AppFactory;

// Create Container using PHP-DI
$container = new Container();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);

$app = AppFactory::create();

require __DIR__ . '/../bootstrap/env.php';

// Load configuration
$kernel = new Kernel($app);
$kernel->boot();


return $app;
