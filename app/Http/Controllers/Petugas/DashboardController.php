<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Report;
use App\Models\Facility;

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

        // Data for the 'Rangkuman Tabel' on the Petugas Dashboard (Top 5 Facilities by Reports/Reservations)
        $okupansi = Reservation::where('status', 'disetujui')
            ->selectRaw('facility_id, COUNT(*) as jumlah_reservasi')
            ->groupBy('facility_id')
            ->get();

        $kerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan')
            ->groupBy('facility_id')
            ->get();

        $fasilitas = Facility::take(5)->get(); // Get top 5 for the mini table
        $rekapDashboard = [];

        foreach ($fasilitas as $facility) {
            $jumlahReservasi = $okupansi->where('facility_id', $facility->id)->first();
            $jumlahLaporan = $kerusakan->where('facility_id', $facility->id)->first();

            $facility->total_reservasi = $jumlahReservasi ? $jumlahReservasi->jumlah_reservasi : 0;
            $facility->total_laporan = $jumlahLaporan ? $jumlahLaporan->jumlah_laporan : 0;

            $rekapDashboard[] = $facility;
        }

        return view('petugas.dashboard', compact(
            'reservasiDiterima',
            'reservasiMenunggu',
            'reservasiDitolak',
            'laporanSelesai',
            'laporanDiproses',
            'laporanDitolak',
            'rekapDashboard'
        ));
    }
}
