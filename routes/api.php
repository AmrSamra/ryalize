<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\AuthorizedMiddleware;
use App\Infrastructure\Route;

Route::group('auth', function () {
    Route::post('login', [AuthController::class, 'login'])->setName('login');
    Route::post('register', [AuthController::class, 'register'])->setName('register');

    Route::group('', function () {
        Route::get('logout', [AuthController::class, 'logout'])->setName('logout');
        Route::get('profile', [AuthController::class, 'profile'])->setName('profile');
        Route::put('update', [AuthController::class, 'update'])->setName('update');
    })->add(AuthorizedMiddleware::class);
});
