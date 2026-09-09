<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FacilityController;
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
