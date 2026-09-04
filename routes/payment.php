<?php

//payment routes

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'initiate'])
        ->name('payments.initiate');
});

Route::get('/payment/esewa/success', [PaymentController::class, 'success'])
    ->name('payments.esewa.success');

Route::get('/payment/esewa/failure', [PaymentController::class, 'failure'])
    ->name('payments.esewa.failure');
