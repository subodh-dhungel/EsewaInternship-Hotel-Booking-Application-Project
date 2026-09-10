<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:create_booking'])->group(function () {
    // Booking form dekhauna
    Route::get('/hotels/{hotel}/room-types/{room_type}/book', [BookingController::class, 'create'])
        ->name('bookings.create');
    
    //cancel booking
    Route::delete('/bookings/{booking}/delete',[BookingController::class, 'destroy'])
        ->name('bookings.destroy');
    
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])
        ->name('bookings.show');

    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings.history');

    // Booking lai database ma store garna
    Route::post('/hotels/{hotel}/room-types/{room_type}/book', [BookingController::class, 'store'])
        ->name('bookings.store');

    // availability checking
    Route::post('/hotels/{hotel}/room-types/{room_type}/availability',[BookingController::class, 'checkAvailability'])
        ->name('bookings.checkAvailability');


    // Route::post('/bookings/{hotel}/{room_type}', [BookingController::class, 'store'])
    //     ->name('bookings.store');

});