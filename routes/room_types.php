<?php

use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    // Room type list
    Route::get('/hotels/{hotel}/room-types', [RoomTypeController::class, 'index'])
        ->name('room-types.index')
        ->middleware('permission:view_rooms');


    // Create room type form
    Route::get('/hotels/{hotel}/room-types/create', [RoomTypeController::class, 'create'])
        ->name('room-types.create')
        ->middleware('permission:create_room');


    // Store room type
    Route::post('/hotels/{hotel}/room-types', [RoomTypeController::class, 'store'])
        ->name('room-types.store')
        ->middleware('permission:create_room');


    // Edit room type
    Route::get('/hotels/{hotel}/room-types/{room_type}', [RoomTypeController::class, 'edit'])
        ->name('room-types.edit')
        ->middleware('permission:update_room');


    // Update room type
    Route::put('/hotels/{hotel}/room-types/{room_type}', [RoomTypeController::class, 'update'])
        ->name('room-types.update')
        ->middleware('permission:update_room');


    // Delete room type
    Route::delete('/hotels/{hotel}/room-types/{room_type}', [RoomTypeController::class, 'destroy'])
        ->name('room-types.destroy')
        ->middleware('permission:delete_room');

});