<?php

use App\Http\Kernel;
use DI\Container;
use DI\Bridge\Slim\Bridge as AppFactory;

// Create PHP-DI Container instance
$app = AppFactory::create(new Container);

// Load configuration
$kernel = new Kernel($app);
$kernel->boot();


return $app;
