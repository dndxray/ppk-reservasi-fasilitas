<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\FacilityControllerStaff;
use App\Models\Facility;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Petugas\ReservationController as PetugasReservationController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $daftarFasilitas = Facility::where(
        'status',
        'aktif'
    )
    ->orderBy('nama_fasilitas')
    ->take(6)
    ->get();

    return view(
        'beranda',
        compact('daftarFasilitas')
    );

})->name('beranda');


Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware(['auth', 'verified'])
->name('dashboard');


// Fasilitas
Route::get(
    '/fasilitas',
    [
        FacilityController::class,
        'index'
    ]
)
->name('fasilitas.index');


Route::get(
    '/fasilitas/{facility}',
    [
        FacilityController::class,
        'show'
    ]
)
->name('fasilitas.show');


Route::middleware('auth')->group(function () {

    // Profile
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


    // Reservasi user
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


    Route::patch(
        '/reservations/{reservation}/cancel',
        [
            ReservationController::class,
            'cancel'
        ]
    )
    ->name('reservations.cancel');


    // Pelaporan Kerusakan - Pengguna
    Route::get(
        '/reports/create',
        [
            ReportController::class,
            'create'
        ]
    )
    ->name('reports.create');


    Route::post(
        '/reports',
        [
            ReportController::class,
            'store'
        ]
    )
    ->name('reports.store');


    Route::get(
        '/reports',
        [
            ReportController::class,
            'index'
        ]
    )
    ->name('reports.index');


    // Pelaporan Kerusakan - Petugas
    // Harus diletakkan sebelum /reports/{report}
    Route::get(
        '/reports/antrian',
        [
            ReportController::class,
            'antrian'
        ]
    )
    ->name('reports.antrian');


    // Detail laporan
    Route::get(
        '/reports/{report}',
        [
            ReportController::class,
            'show'
        ]
    )
    ->name('reports.show');


    // Update status laporan oleh petugas
    Route::patch(
        '/reports/{report}',
        [
            ReportController::class,
            'updateStatus'
        ]
    )
    ->name('reports.updateStatus');

});


// Petugas
Route::middleware('auth')
->prefix('petugas')
->group(function () {

    Route::get(
        '/reservations',
        [
            PetugasReservationController::class,
            'queue'
        ]
    )
    ->name('petugas.reservations.queue');


    Route::get(
        '/reservations/{reservation}',
        [
            PetugasReservationController::class,
            'show'
        ]
    )
    ->name('petugas.reservations.show');


    Route::patch(
        '/reservations/{reservation}/approve',
        [
            PetugasReservationController::class,
            'approve'
        ]
    )
    ->name('petugas.reservations.approve');


    Route::patch(
        '/reservations/{reservation}/reject',
        [
            PetugasReservationController::class,
            'reject'
        ]
    )
    ->name('petugas.reservations.reject');


    Route::patch(
        '/reservations/{reservation}/cancel',
        [
            PetugasReservationController::class,
            'cancel'
        ]
    )
    ->name('petugas.reservations.cancel');

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