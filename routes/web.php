<?php

use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Petugas\ReservationController as PetugasReservationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. BERANDA & DASHBOARD
|--------------------------------------------------------------------------
*/

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

    $aktivitasReservasi = Reservation::where('user_id', $user->id)->latest()->first();
    $aktivitasLaporan   = Report::where('user_id', $user->id)->latest()->first();

    return view('beranda', compact('daftarFasilitas', 'aktivitasReservasi', 'aktivitasLaporan'));
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| 2. FASILITAS (PUBLIK)
|--------------------------------------------------------------------------
*/

Route::get('/fasilitas', [FacilityController::class, 'index'])->name('fasilitas.index');
Route::get('/fasilitas/{facility}/slots', [FacilityController::class, 'slots'])->name('fasilitas.slots');
Route::get('/fasilitas/{facility}', [FacilityController::class, 'show'])->name('fasilitas.show');


/*
|--------------------------------------------------------------------------
| 3. USER LOGIN (Profil, Reservasi, Laporan)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // ----- Profil -----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ----- Reservasi (pengguna) -----
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/history', [ReservationController::class, 'history'])->name('reservations.history'); // sebelum {reservation}
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // ----- Pelaporan kerusakan (pengguna) -----
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // ----- Pelaporan kerusakan (petugas) -----
    Route::get('/reports/antrian', [ReportController::class, 'antrian'])->name('reports.antrian'); // sebelum {report}
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
});


/*
|--------------------------------------------------------------------------
| 4. PETUGAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('/dashboard', fn () => view('petugas.dashboard'))->name('dashboard');

    // ----- Reservasi -----
    Route::get('/reservations/queue', [PetugasReservationController::class, 'queue'])->name('reservations.queue'); 
    Route::get('/reservations', [PetugasReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [PetugasReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/approve', [PetugasReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{reservation}/reject', [PetugasReservationController::class, 'reject'])->name('reservations.reject');
    Route::patch('/reservations/{reservation}/cancel', [PetugasReservationController::class, 'cancel'])->name('reservations.cancel');
});


/*
|--------------------------------------------------------------------------
| 5. ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // ----- Pengguna -----
    Route::get('/pengguna', [UserController::class, 'indexUsers'])->name('pengguna.index');
    Route::get('/pengguna/create', [UserController::class, 'createUser'])->name('pengguna.create');
    Route::post('/pengguna', [UserController::class, 'storeUser'])->name('pengguna.store');
    Route::patch('/pengguna/{user}/verifikasi', [UserController::class, 'verify'])->name('pengguna.verify');
    Route::patch('/pengguna/{user}/tolak', [UserController::class, 'reject'])->name('pengguna.reject');

    // ----- Petugas -----
    Route::get('/petugas', [UserController::class, 'indexStaff'])->name('petugas.index');
    Route::get('/petugas/create', [UserController::class, 'createStaff'])->name('petugas.create');
    Route::post('/petugas', [UserController::class, 'storeStaff'])->name('petugas.store');

    // ----- Fasilitas -----
    Route::get('/fasilitas', [AdminFacilityController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/create', fn () => view('admin.facilities.form'))->name('facilities.create');
    Route::post('/fasilitas', [AdminFacilityController::class, 'store'])->name('facilities.store');
    Route::get('/fasilitas/{facility}/edit', [AdminFacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/fasilitas/{facility}', [AdminFacilityController::class, 'update'])->name('facilities.update');
    Route::patch('/fasilitas/{facility}/nonaktifkan', [AdminFacilityController::class, 'deactivate'])->name('facilities.deactivate');
    Route::patch('/fasilitas/{facility}/aktifkan', [AdminFacilityController::class, 'activate'])->name('facilities.activate');

    // ----- Rekap -----
    Route::get('/rekap', [AdminReportController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export/csv', [AdminReportController::class, 'exportCsv'])->name('rekap.export.csv');
    Route::get('/rekap/export/excel', [AdminReportController::class, 'exportExcel'])->name('rekap.export.excel');
    Route::get('/rekap/export/pdf', [AdminReportController::class, 'exportPdf'])->name('rekap.export.pdf');
});


require __DIR__ . '/auth.php';