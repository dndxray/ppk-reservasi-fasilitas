<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\FacilityControllerStaff;
use App\Models\Facility;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Petugas\ReservationController as PetugasReservationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    $daftarFasilitas = Facility::where('status', 'aktif')
        ->orderBy('nama_fasilitas')
        ->take(6)
        ->get();

    return view('beranda', compact('daftarFasilitas'))
        ->with('aktivitasReservasi', null)
        ->with('aktivitasLaporan', null);

})->name('beranda');


Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isPetugas()) {
        return redirect()->route('petugas.dashboard');
    }

    $daftarFasilitas = Facility::where('status', 'aktif')
        ->orderBy('nama_fasilitas')
        ->take(6)
        ->get();

    $aktivitasReservasi = \App\Models\Reservation::where('user_id', $user->id)
        ->latest()
        ->first();

    $aktivitasLaporan = \App\Models\Report::where('user_id', $user->id)
        ->latest()
        ->first();

    return view('beranda', compact('daftarFasilitas', 'aktivitasReservasi', 'aktivitasLaporan'));
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
Route::get('/fasilitas/{facility}/slots', [FacilityController::class, 'slots'])
    ->name('fasilitas.slots');


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
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
        })
    ->name('petugas.dashboard');

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

Route::middleware('auth')->prefix('admin')->group(function () {

    // =========================
    // PENGGUNA
    // =========================
    Route::get('/dashboard', function () {
        return view('admin.dashboard');})
        ->name('admin.dashboard');
    Route::get('/pengguna', [UserController::class, 'indexUsers'])
        ->name('admin.pengguna.index');
    
    Route::get('/pengguna/create', [UserController::class, 'createUser'])
        ->name('admin.pengguna.create');

    Route::post('/pengguna', [UserController::class, 'storeUser'])
        ->name('admin.pengguna.store');

    Route::patch('/pengguna/{user}/verifikasi', [UserController::class, 'verify'])
        ->name('admin.pengguna.verify');

    Route::patch('/pengguna/{user}/tolak', [UserController::class, 'reject'])
        ->name('admin.pengguna.reject');


    // =========================
    // PETUGAS
    // =========================
    Route::get('/petugas', [UserController::class, 'indexStaff'])
        ->name('admin.petugas.index');
    
    Route::get('/petugas/create', [UserController::class, 'createStaff'])
        ->name('admin.petugas.create');

    Route::post('/petugas', [UserController::class, 'storeStaff'])
        ->name('admin.petugas.store');


    // =========================
    // FASILITAS
    // =========================
    Route::get('/fasilitas', [AdminFacilityController::class, 'index'])
        ->name('admin.fasilitas.index');

    Route::get('/fasilitas/create', function () {
        return view('admin.facilities.form');
    })->name('admin.facilities.create');

    Route::post('/fasilitas', [AdminFacilityController::class, 'store'])
        ->name('admin.facilities.store');

    Route::get('/fasilitas/{facility}/edit', [AdminFacilityController::class, 'edit'])
        ->name('admin.facilities.edit');

    Route::put('/fasilitas/{facility}', [AdminFacilityController::class, 'update'])
        ->name('admin.facilities.update');

    Route::patch('/fasilitas/{facility}/nonaktifkan', [AdminFacilityController::class, 'deactivate'])
        ->name('admin.facilities.deactivate');

    Route::patch('/fasilitas/{facility}/aktifkan', [AdminFacilityController::class, 'activate'])
        ->name('admin.facilities.activate');

    Route::get('/rekap', [AdminReportController::class, 'index'])
        ->name('admin.rekap.index');
});



require __DIR__.'/auth.php';