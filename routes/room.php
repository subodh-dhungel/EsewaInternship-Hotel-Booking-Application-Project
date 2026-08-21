<?php
/*
|--------------------------------------------------------------------------
| Hotel Room Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

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