<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Booking\BookingController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('auth.logout');
});

Route::middleware(['auth:sanctum'])->prefix('booking')->group(function () {
    Route::get('/available-seats', [BookingController::class, 'availableSeats'])->name('booking.availableSeats');
    Route::post('/book-seat', [BookingController::class, 'bookSeat'])->name('booking.bookSeat');
});
