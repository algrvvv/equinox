<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

use Imissher\Equinox\app\controllers\LoginController;
use Imissher\Equinox\app\controllers\ProfileController;
use Imissher\Equinox\app\controllers\RegisterController;
use Imissher\Equinox\app\controllers\TestController;
use Imissher\Equinox\app\core\http\Route;

/*
|--------------------------------------------------------------------------
| Веб-маршруты
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать веб-маршруты для своего приложения.
|
*/

Route::get('/', 'home');

Route::get('/profile', [ProfileController::class, 'profile'])->middleware('auth');

Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class,'store'])->middleware('guest');

Route::get('/login', [LoginController::class, 'index'])->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/test/{id}/{name}', [TestController::class, 'index']);
