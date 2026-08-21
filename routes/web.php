<?php

use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/hotel_owner.php';
require __DIR__.'/room.php';
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Public / Customer Hotel Routes
|--------------------------------------------------------------------------
*/

// Homepage



// Customer hotel listing
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