<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelImageController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

// Hotel ko lagi routes
Route::get('/', [HotelController::class, 'featured'])
    ->name('hotels.featured');

Route::get('/hotels', [HotelController::class, 'index'])
    ->name('hotels.index')
    ->middleware('auth', 'permission:view_hotels');

Route::get('/hotels/create', [HotelController::class, 'create'])
    ->name('hotels.create')
    ->middleware(['auth', 'permission:create_hotel']);

Route::get('/hotels/{hotel}/edit',[HotelController::class, 'edit'])
    ->name('hotel.edit')
    ->middleware(['auth', 'permission:update_hotel']);

Route::put('/hotels/{hotel}/update',[HotelController::class, 'update'])
    ->name('hotel.update')
    ->middleware('auth', 'permission:update_hotel');

Route::put('/hotels/{hotel}/deactivate',[HotelController::class, 'set_inactive'])
    ->name('hotel.deactivate')
    ->middleware('auth', 'permission:deactivate_hotel');

Route::put('/hotels/{hotel}/activate', [HotelController::class, 'set_active'])
    ->name('hotel.activate')
    ->middleware('auth', 'permission:activate_hotel');

Route::get('/hotels/{hotel}', [HotelController::class, 'show'])
    ->name('hotels.show')
    ->middleware(['auth', 'permission:view_hotels']);

Route::post('/hotels',[HotelController::class, 'store'])
    ->name('hotels.store')
    ->middleware(['auth', 'permission:create_hotel']);

Route::post('/hotels/{hotel}/images', [HotelImageController::class, 'store'])
    ->name('hotel-images.store')
    ->middleware(['auth', 'permission:upload_hotel_images']);

Route::delete('/hotel-images/{image}', [HotelImageController::class, 'destroy'])
    ->name('hotel-image.destroy')
    ->middleware('auth','permission:delete_hotel_images');

// Auth ko lagi routes
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

// Hotel Rooms ko lagi routes 
Route::middleware(['auth'])->group(function(){
    Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'index'])
        ->name('rooms.index')
        ->middleware('permission:view_rooms');
    
    Route::get('/hotels/{hotel}/rooms/create', [RoomController::class, 'create'])
        ->name('rooms.create')
        ->middleware('permission:create_room');
    
    Route::post('/hotels/{hotel}/rooms', [RoomController::class, 'store'])
        ->name('rooms.store')
        ->middleware('permission:create_room');
    
    Route::get('/hotels/{hotel}/rooms/{room}/edit', [RoomController::class, 'edit'])
        ->name('rooms.edit')
        ->middleware('permission:update_room');
    
    Route::put('/hotels/{hotel}/rooms/{room}', [RoomController::class, 'update'])
        ->name('rooms.update')
        ->middleware('permission:update_room');
    
    Route::delete('/hotels/{hotel}/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy')
        ->middleware('permission:delete_room');
});