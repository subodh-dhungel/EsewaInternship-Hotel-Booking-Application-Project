<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Hotel ko lagi routes
Route::get('/', [HotelController::class, 'featured'])->name('hotels.featured');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{id}', [HotelController::class, 'show'])->name('hotels.show');

// Auth ko lagi routes
Route::get('/login', function(){return view('auth.login');})->name('user.login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [AuthController::class, 'register'])->name('user.create');