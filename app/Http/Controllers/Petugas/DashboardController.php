<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $reservasiDiterima = Reservation::where('status', 'disetujui')->count();
        $reservasiMenunggu = Reservation::where('status', 'menunggu')->count();
        $reservasiDitolak = Reservation::where('status', 'ditolak')->count();

        $laporanSelesai = Report::where('status', 'selesai')->count();
        $laporanDiproses = Report::where('status', 'diproses')->count();
        $laporanDitolak = Report::where('status', 'ditolak')->count();

        return view('petugas.dashboard', compact(
            'reservasiDiterima',
            'reservasiMenunggu',
            'reservasiDitolak',
            'laporanSelesai',
            'laporanDiproses',
            'laporanDitolak'
        ));
    }
}
