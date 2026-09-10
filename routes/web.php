<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Petugas\ReservationController as PetugasReservationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function(){

     Route::get(
        '/profile',
        [
            ProfileController::class,
            'edit'
        ]
    )
    ->name('profile.edit');


    Route::patch(
        '/profile',
        [
            ProfileController::class,
            'update'
        ]
    )
    ->name('profile.update');


    Route::delete(
        '/profile',
        [
            ProfileController::class,
            'destroy'
        ]
    )
    ->name('profile.destroy');

    // US 3
    Route::get(
        '/reservations/create',
        [
            ReservationController::class,
            'create'
        ]
    )
    ->name('reservations.create');


    Route::post(
        '/reservations',
        [
            ReservationController::class,
            'store'
        ]
    )
    ->name('reservations.store');



    // US 5
    Route::get(
        '/reservations/history',
        [
            ReservationController::class,
            'history'
        ]
    )
    ->name('reservations.history');



    Route::get(
        '/reservations/{reservation}',
        [
            ReservationController::class,
            'show'
        ]
    )
    ->name('reservations.show');



    // US 4
    Route::patch(
        '/reservations/{reservation}/cancel',
        [
            ReservationController::class,
            'cancel'
        ]
    )
    ->name('reservations.cancel');


});

Route::middleware('auth')
->prefix('petugas')
->group(function(){


    // Dashboard antrian reservasi
    Route::get(
        '/reservations',
        [
            PetugasReservationController::class,
            'queue'
        ]
    )
    ->name('petugas.reservations.queue');

    Route::get(
    '/petugas/reservations/{reservation}',
    [
    \App\Http\Controllers\Petugas\ReservationController::class, 'show'
    ]
    ) ->name('petugas.reservations.show');

    // US 9 Setujui
    Route::patch(
        '/reservations/{reservation}/approve',
        [
            PetugasReservationController::class,
            'approve'
        ]
    )
    ->name('petugas.reservations.approve');



    // US 9 Tolak
    Route::patch(
        '/reservations/{reservation}/reject',
        [
            PetugasReservationController::class,
            'reject'
        ]
    )
    ->name('petugas.reservations.reject');



    // US 10 Batalkan mendesak
    Route::patch(
        '/reservations/{reservation}/cancel',
        [
            PetugasReservationController::class,
            'cancel'
        ]
    )
    ->name('petugas.reservations.cancel');


});

require __DIR__.'/auth.php';
