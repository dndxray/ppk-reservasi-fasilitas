<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Report;
use App\Models\Facility;

class ReportController extends Controller
{
    public function index()
    {
        $okupansi = Reservation::where('status', 'disetujui')
            ->selectRaw('facility_id, COUNT(*) as jumlah_reservasi')
            ->groupBy('facility_id')
            ->get();

        $kerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan')
            ->groupBy('facility_id')
            ->get();

        $fasilitas = Facility::all();

        $rekap = [];

        foreach ($fasilitas as $facility) {
            $jumlahReservasi = $okupansi
                ->where('facility_id', $facility->id)
                ->first();

            $jumlahLaporan = $kerusakan
                ->where('facility_id', $facility->id)
                ->first();

            $facility->total_reservasi = $jumlahReservasi
                ? $jumlahReservasi->jumlah_reservasi
                : 0;

            $facility->total_laporan = $jumlahLaporan
                ? $jumlahLaporan->jumlah_laporan
                : 0;

            $rekap[] = $facility;
        }

        // Total fasilitas yang pernah direservasi 
        $totalFasilitasDireservasi = $okupansi->count();

        // Total laporan kerusakan (seluruh fasilitas)
        $totalLaporanKerusakan = $kerusakan->sum('jumlah_laporan');

        // Data reservasi per hari (14) untuk grafik
        $mulaiTanggal = now()->subDays(13)->startOfDay();

        $reservasiHarianRaw = Reservation::where('status', 'disetujui')
            ->where('created_at', '>=', $mulaiTanggal)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->groupByRaw('DATE(created_at)')
            ->pluck('jumlah', 'tanggal');

        $reservasiPerHari = [];
        for ($i = 13; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $reservasiPerHari[$tanggal] = $reservasiHarianRaw[$tanggal] ?? 0;
        }

        // Laporan kerusakan per hari (14) untuk grafik batang kedua
        $laporanHarianRaw = Report::where('created_at', '>=', $mulaiTanggal)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->groupByRaw('DATE(created_at)')
            ->pluck('jumlah', 'tanggal');

        $laporanPerHari = [];
        for ($i = 13; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $laporanPerHari[$tanggal] = $laporanHarianRaw[$tanggal] ?? 0;
        }

        // Selisih dibanding bulan lalu, untuk badge naik/turun di card
        $fasilitasDireservasiBulanIni = Reservation::where('status', 'disetujui')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('facility_id')
            ->count('facility_id');

        $fasilitasDireservasiBulanLalu = Reservation::where('status', 'disetujui')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->distinct('facility_id')
            ->count('facility_id');

        $selisihFasilitas = $fasilitasDireservasiBulanIni - $fasilitasDireservasiBulanLalu;

        $laporanBulanIni = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $laporanBulanLalu = Report::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $selisihLaporan = $laporanBulanIni - $laporanBulanLalu;

        return view('admin.rekap.index', compact(
            'rekap',
            'totalFasilitasDireservasi',
            'totalLaporanKerusakan',
            'reservasiPerHari',
            'laporanPerHari',
            'selisihFasilitas',
            'selisihLaporan'
        ));
    }
}