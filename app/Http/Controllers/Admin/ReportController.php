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

        return view('admin.rekap.index', compact('rekap'));
    }
}