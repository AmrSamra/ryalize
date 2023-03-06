<?php

try {
    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../bootstrap/app.php';
    $app->run();
} catch (\Throwable $th) {
    var_dump($th);
    die();
}
