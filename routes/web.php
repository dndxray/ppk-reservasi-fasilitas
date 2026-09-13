<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // Pelaporan Kerusakan - Pengguna
    Route::get('/reports/create', [ReportController::class, 'create'])
        ->name('reports.create');

    Route::post('/reports', [ReportController::class, 'store'])
        ->name('reports.store');

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');


    // Pelaporan Kerusakan - Petugas
    // Harus diletakkan sebelum /reports/{report}
    Route::get('/reports/antrian', [ReportController::class, 'antrian'])
        ->name('reports.antrian');


    // Detail laporan
    Route::get('/reports/{report}', [ReportController::class, 'show'])
        ->name('reports.show');

    // Update status laporan oleh petugas
    Route::patch('/reports/{report}', [ReportController::class, 'updateStatus'])
        ->name('reports.updateStatus');
});

require __DIR__.'/auth.php';