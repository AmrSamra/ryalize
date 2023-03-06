<?php

use App\Http\Controllers\HomeController;
use App\Infrastructure\Route;

Route::get('/', HomeController::class, 'index')->setName('home');
