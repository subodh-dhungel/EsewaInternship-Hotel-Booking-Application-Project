<?php


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('show.login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('user.register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('user.create');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('user.logout');

