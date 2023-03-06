<?php

use App\Http\Kernel;
use DI\Container;
use DI\Bridge\Slim\Bridge as AppFactory;

$app = AppFactory::create(new Container);


$kernel = new Kernel($app);

$kernel->boot();

return $app;
