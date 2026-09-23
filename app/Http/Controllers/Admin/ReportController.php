<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Report;
use App\Models\Facility;
use Barryvdh\DomPDF\Facade\Pdf;


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
    public function exportCsv()
    {
        $fasilitas = Facility::all();

        $reservasi = Reservation::where('status', 'disetujui')
            ->selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

        $laporan = Report::selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

        $filename = 'rekap-fasilitas.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($fasilitas, $reservasi, $laporan) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Nama Fasilitas',
                'Tipe',
                'Lokasi',
                'Kapasitas',
                'Total Reservasi',
                'Total Laporan'
            ]);

            foreach ($fasilitas as $facility) {
                fputcsv($file, [
                    $facility->nama_fasilitas,
                    $facility->tipe,
                    $facility->lokasi,
                    $facility->kapasitas,
                    $reservasi[$facility->id] ?? 0,
                    $laporan[$facility->id] ?? 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function exportExcel()
    {
        $fasilitas = Facility::all();

        $reservasi = Reservation::where('status', 'disetujui')
            ->selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

        $laporan = Report::selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

        $filename = 'rekap-fasilitas.xls';

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
            </head>

            <body>

                <table border="1">
                    <tr>
                        <th>Nama Fasilitas</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Total Reservasi</th>
                        <th>Total Laporan</th>
                    </tr>
        ';

        foreach ($fasilitas as $facility) {
            $html .= '
                    <tr>
                        <td>' . $facility->nama_fasilitas . '</td>
                        <td>' . $facility->tipe . '</td>
                        <td>' . $facility->lokasi . '</td>
                        <td>' . $facility->kapasitas . '</td>
                        <td>' . ($reservasi[$facility->id] ?? 0) . '</td>
                        <td>' . ($laporan[$facility->id] ?? 0) . '</td>
                    </tr>
            ';
        }

        $html .= '
                </table>

            </body>
            </html>
        ';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    public function exportPdf()
    {
        $fasilitas = Facility::all();

        $reservasi = Reservation::where('status', 'disetujui')
            ->selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

        $laporan = Report::selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">

                <style>
                    body {
                        font-family: sans-serif;
                        font-size: 11px;
                    }

                    h2 {
                        text-align: center;
                        margin-bottom: 5px;
                    }

                    .subtitle {
                        text-align: center;
                        color: #666;
                        margin-bottom: 20px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th {
                        background-color: #be123c;
                        color: white;
                        padding: 8px;
                        text-align: center;
                    }

                    td {
                        border: 1px solid #ddd;
                        padding: 7px;
                    }

                    .center {
                        text-align: center;
                    }
                </style>
            </head>

            <body>

                <h2>Rekap Fasilitas</h2>

                <div class="subtitle">
                    Rekapitulasi Reservasi dan Laporan Kerusakan
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Fasilitas</th>
                            <th>Tipe</th>
                            <th>Lokasi</th>
                            <th>Kapasitas</th>
                            <th>Total Reservasi</th>
                            <th>Total Laporan</th>
                        </tr>
                    </thead>

                    <tbody>
        ';

        foreach ($fasilitas as $facility) {
            $html .= '
                        <tr>
                            <td>' . $facility->nama_fasilitas . '</td>
                            <td>' . $facility->tipe . '</td>
                            <td>' . $facility->lokasi . '</td>
                            <td class="center">' . $facility->kapasitas . '</td>
                            <td class="center">' . ($reservasi[$facility->id] ?? 0) . '</td>
                            <td class="center">' . ($laporan[$facility->id] ?? 0) . '</td>
                        </tr>
            ';
        }

        $html .= '
                    </tbody>
                </table>

            </body>
            </html>
        ';

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('rekap-fasilitas.pdf');
    }
}