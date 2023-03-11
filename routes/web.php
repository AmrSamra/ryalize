<?php

use App\Http\Controllers\HomeController;
use App\Infrastructure\Route;

Route::get('home', [HomeController::class, 'index'])->setName('home');
