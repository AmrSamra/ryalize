<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AuthorizedMiddleware;
use App\Infrastructure\Route;

Route::group('auth', function () {
    Route::post('login', [AuthController::class, 'login'])->setName('login');
    Route::post('register', [AuthController::class, 'register'])->setName('register');

    Route::get('logout', [AuthController::class, 'logout'])->setName('logout')->add(AuthorizedMiddleware::class);
});

Route::resource('users', UserController::class);
