<?php

use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

require __DIR__.'/hotel_owner.php';
require __DIR__.'/room.php';
require __DIR__.'/auth.php';
require __DIR__.'/room_types.php';
require __DIR__.'/customer_booking.php';
require __DIR__.'/payment.php';


// customer routes...
Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/', [HotelController::class, 'featured'])
    ->name('hotels.featured');

    Route::get('/hotels', [HotelController::class, 'index'])
        ->name('hotels.index')
        ->middleware('permission:view_hotels');


    // Customer hotel details
    Route::get('/hotels/{hotel}', [HotelController::class, 'show'])
        ->name('hotels.show')
        ->middleware('permission:view_hotels');

});

