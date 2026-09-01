<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:create_booking'])->group(function () {
    // Booking form dekhauna
    Route::get('/hotels/{hotel}/room-types/{room_type}/book', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::get('/my-bookings', [BookingController::class, 'show'])
        ->name('bookings.history');

    // Booking lai database ma store garna
    Route::post('/hotels/{hotel}/room-types/{room_type}/book', [BookingController::class, 'store'])
        ->name('bookings.store');
});