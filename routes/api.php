<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Infrastructure\Route;

Route::get('/api', HomeController::class, 'index')->setName('home');
Route::resource('users', UserController::class);
