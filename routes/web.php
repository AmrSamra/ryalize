<?php

use App\Http\Controllers\Controller;
use App\Infrastructure\Route;

Route::get('home', Controller::class)->setName('home');
