<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelImageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Owner\HotelController as OwnerHotelController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public / Customer Hotel Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HotelController::class, 'featured'])
    ->name('hotels.featured');


// Customer hotel listing
Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/hotels', [HotelController::class, 'index'])
        ->name('hotels.index')
        ->middleware('permission:view_hotels');


    // Customer hotel details
    Route::get('/hotels/{hotel}', [HotelController::class, 'show'])
        ->name('hotels.show')
        ->middleware('permission:view_hotels');

});


/*
|--------------------------------------------------------------------------
| Hotel Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:hotel_owner'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Hotel Management
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Hotel Images
    |--------------------------------------------------------------------------
    */

    // Upload hotel image
    Route::post('/owner/hotels/{hotel}/images', [HotelImageController::class, 'store'])
        ->name('owner.hotel-images.store')
        ->middleware('permission:upload_hotel_images');


    // Delete hotel image
    Route::delete('/owner/hotel-images/{image}', [HotelImageController::class, 'destroy'])
        ->name('owner.hotel-image.destroy')
        ->middleware('permission:delete_hotel_images');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Hotel Room Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Room list
    Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'index'])
        ->name('rooms.index')
        ->middleware('permission:view_rooms');


    // Create room form
    Route::get('/hotels/{hotel}/rooms/create', [RoomController::class, 'create'])
        ->name('rooms.create')
        ->middleware('permission:create_room');


    // Store room
    Route::post('/hotels/{hotel}/rooms', [RoomController::class, 'store'])
        ->name('rooms.store')
        ->middleware('permission:create_room');


    // Edit room
    Route::get('/hotels/{hotel}/rooms/{room}/edit', [RoomController::class, 'edit'])
        ->name('rooms.edit')
        ->middleware('permission:update_room');


    // Update room
    Route::put('/hotels/{hotel}/rooms/{room}', [RoomController::class, 'update'])
        ->name('rooms.update')
        ->middleware('permission:update_room');


    // Delete room
    Route::delete('/hotels/{hotel}/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy')
        ->middleware('permission:delete_room');

});