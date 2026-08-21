<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\HotelController as OwnerHotelController;
use App\Http\Controllers\HotelImageController;

Route::middleware(['auth', 'role:hotel_owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerHotelController::class, 'dashboard'])
        ->name('owner.index')
        ->middleware('permission:view_dashboard');

    // Owner hotel list
    Route::get('/owner/hotels', [OwnerHotelController::class, 'index'])
        ->name('owner.hotels.index')
        ->middleware('permission:view_hotels');


    // Create hotel form
    Route::get('/owner/hotels/create', [OwnerHotelController::class, 'create'])
        ->name('owner.hotels.create')
        ->middleware('permission:create_hotel');


    // Store hotel
    Route::post('/owner/hotels', [OwnerHotelController::class, 'store'])
        ->name('owner.hotels.store')
        ->middleware('permission:create_hotel');


    // Owner hotel details
    Route::get('/owner/hotels/{hotel}', [OwnerHotelController::class, 'show'])
        ->name('owner.hotels.show')
        ->middleware('permission:view_hotels');


    // Edit hotel
    Route::get('/owner/hotels/{hotel}/edit', [OwnerHotelController::class, 'edit'])
        ->name('owner.hotels.edit')
        ->middleware('permission:update_hotel');


    // Update hotel
    Route::put('/owner/hotels/{hotel}', [OwnerHotelController::class, 'update'])
        ->name('owner.hotels.update')
        ->middleware('permission:update_hotel');


    // Deactivate hotel
    Route::put('/owner/hotels/{hotel}/deactivate', [OwnerHotelController::class, 'set_inactive'])
        ->name('owner.hotels.deactivate')
        ->middleware('permission:deactivate_hotel');


    // Activate hotel
    Route::put('/owner/hotels/{hotel}/activate', [OwnerHotelController::class, 'set_active'])
        ->name('owner.hotels.activate')
        ->middleware('permission:activate_hotel');


    // Upload hotel image
    Route::post('/owner/hotels/{hotel}/images', [HotelImageController::class, 'store'])
        ->name('owner.hotel-images.store')
        ->middleware('permission:upload_hotel_images');


    // Delete hotel image
    Route::delete('/owner/hotel-images/{image}', [HotelImageController::class, 'destroy'])
        ->name('owner.hotel-image.destroy')
        ->middleware('permission:delete_hotel_images');
});
