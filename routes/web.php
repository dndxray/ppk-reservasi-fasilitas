<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Models\Facility;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Petugas\ReservationController as PetugasReservationController;

Route::get('/', function () {
    $daftarFasilitas = Facility::where('status', 'aktif')
        ->orderBy('nama_fasilitas')
        ->take(6)
        ->get();

    return view('beranda', compact('daftarFasilitas'));
})->name('beranda');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/fasilitas', [FacilityController::class, 'index'])->name('fasilitas.index');
Route::get('/fasilitas/{facility}', [FacilityController::class, 'show'])->name('fasilitas.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/history', [ReservationController::class, 'history'])->name('reservations.history');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

Route::middleware('auth')->prefix('petugas')->group(function () {
    // dashboard antrian reservasi
    Route::get('/reservations', [PetugasReservationController::class, 'queue'])->name('petugas.reservations.queue');
    Route::get('/reservations/{reservation}', [PetugasReservationController::class, 'show'])->name('petugas.reservations.show');

    // US 9 Setujui
    Route::patch('/reservations/{reservation}/approve', [PetugasReservationController::class, 'approve'])->name('petugas.reservations.approve');

    //tolak
    Route::patch('/reservations/{reservation}/reject', [PetugasReservationController::class, 'reject'])->name('petugas.reservations.reject');
    // Batalkan mendesak
    Route::patch('/reservations/{reservation}/cancel', [PetugasReservationController::class, 'cancel'])->name('petugas.reservations.cancel');
});

require __DIR__.'/auth.php';
