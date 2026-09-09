<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/pengguna', [UserController::class, 'index'])
    ->name('admin.pengguna.index');

Route::post('/petugas', [UserController::class, 'storeStaff'])
    ->name('admin.petugas.store');

Route::post('/pengguna', [UserController::class, 'storeUser'])
    ->name('admin.pengguna.store');

Route::patch('/pengguna/{user}/verifikasi', [UserController::class, 'verify'])
    ->name('admin.pengguna.verify');

Route::patch('/pengguna/{user}/tolak', [UserController::class, 'reject'])
    ->name('admin.pengguna.reject');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/fasilitas', [FacilityController::class, 'index'])
        ->name('admin.fasilitas.index');

    Route::post('/fasilitas', [FacilityController::class, 'store'])
        ->name('admin.fasilitas.store');

    Route::put('/fasilitas/{facility}', [FacilityController::class, 'update'])
        ->name('admin.fasilitas.update');

    Route::patch('/fasilitas/{facility}/nonaktifkan', [FacilityController::class, 'deactivate'])
        ->name('admin.fasilitas.deactivate');
});



require __DIR__.'/auth.php';
