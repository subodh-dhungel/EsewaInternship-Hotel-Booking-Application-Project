<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/hotels/{id}', [HotelController::class, 'show'])
    ->name('hotels.show')
    ->middleware(['auth', 'permission:view_hotels']);

Route::post('/hotels',[HotelController::class, 'store'])
    ->name('hotels.store')
    ->middleware(['auth', 'permission:create_hotel']);

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

//fake or test route
Route::get('/test-permission', function(){
    return 'You have the permission!';
})->middleware('permission:view_dashboard');

Route::get('/current-user', function(){
    $user = Auth::user();

    return [
        'id'=>$user->id,
        'name'=>$user->name,
        'email'=>$user->email,
    ];
})->middleware('auth');

Route::get('/debug-user', function(){
    $user = Auth::user();

    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'roles'=>$user->roles,
        'permissions'=>$user->roles
            ->flatMap->permissions->pluck('name')
            ->unique()
            ->values(),
    ];

});