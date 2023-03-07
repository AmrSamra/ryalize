<?php

use App\Http\Kernel;
use DI\Container;
use DI\Bridge\Slim\Bridge as AppFactory;
use Dotenv\Dotenv;

// Create PHP-DI Container instance
$app = AppFactory::create(new Container);


// Load environment variables
$environment = Dotenv::createImmutable(base_path());
$environment->load();

// Load configuration
$kernel = new Kernel($app);
$kernel->boot();


return $app;
