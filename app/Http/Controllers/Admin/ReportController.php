<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const NAMA_BULAN = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * Hitung rekap per fasilitas dengan filter bulan/tahun.
     * Dipakai oleh index dan semua export supaya hasilnya konsisten.
     */
    private function hitungRekap($bulan, $tahun): array
    {
        $queryOkupansi = Reservation::where('status', 'disetujui')
            ->selectRaw('facility_id, COUNT(*) as jumlah_reservasi');

        $queryKerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan');

        if ($bulan) {
            $queryOkupansi->whereMonth('tanggal', $bulan);
            $queryKerusakan->where(function ($q) use ($bulan) {
                $q->whereMonth('created_at', $bulan)
                  ->orWhereMonth('tanggal_ditemukan', $bulan);
            });
        }

        if ($tahun) {
            $queryOkupansi->whereYear('tanggal', $tahun);
            $queryKerusakan->where(function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun)
                  ->orWhereYear('tanggal_ditemukan', $tahun);
            });
        }

        $okupansi  = $queryOkupansi->groupBy('facility_id')->get();
        $kerusakan = $queryKerusakan->groupBy('facility_id')->get();

        $rekap = Facility::all()->map(function ($facility) use ($okupansi, $kerusakan) {
            $r = $okupansi->firstWhere('facility_id', $facility->id);
            $l = $kerusakan->firstWhere('facility_id', $facility->id);

            $facility->total_reservasi = $r ? (int) $r->jumlah_reservasi : 0;
            $facility->total_laporan   = $l ? (int) $l->jumlah_laporan : 0;

            return $facility;
        });

        return [$rekap, $okupansi, $kerusakan];
    }

    private function labelPeriode($bulan, $tahun): string
    {
        $parts = [];
        if ($bulan) {
            $parts[] = self::NAMA_BULAN[(int) $bulan] ?? $bulan;
        }
        if ($tahun) {
            $parts[] = $tahun;
        }

        return $parts ? implode(' ', $parts) : 'Semua Periode';
    }

    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        [$rekap, $okupansi, $kerusakan] = $this->hitungRekap($bulan, $tahun);

        $totalFasilitasDireservasi = $okupansi->count();
        $totalLaporanKerusakan     = $kerusakan->sum('jumlah_laporan');

        // ---- Data grafik harian ----
        if ($bulan && $tahun) {
            $daysInMonth = Carbon::createFromDate((int) $tahun, (int) $bulan, 1)->daysInMonth;

            $reservasiHarianRaw = Reservation::where('status', 'disetujui')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->selectRaw('DATE(tanggal) as tgl, COUNT(*) as jumlah')
                ->groupByRaw('DATE(tanggal)')
                ->pluck('jumlah', 'tgl');

            $laporanHarianRaw = Report::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->selectRaw('DATE(created_at) as tgl, COUNT(*) as jumlah')
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tgl');

            $reservasiPerHari = [];
            $laporanPerHari   = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $t = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                $reservasiPerHari[$t] = $reservasiHarianRaw[$t] ?? 0;
                $laporanPerHari[$t]   = $laporanHarianRaw[$t] ?? 0;
            }

            $judulGrafik = $this->labelPeriode($bulan, $tahun);
        } else {
            $mulaiTanggal = now()->subDays(13)->startOfDay();

            $reservasiHarianRaw = Reservation::where('status', 'disetujui')
                ->where('created_at', '>=', $mulaiTanggal)
                ->selectRaw('DATE(created_at) as tgl, COUNT(*) as jumlah')
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tgl');

            $laporanHarianRaw = Report::where('created_at', '>=', $mulaiTanggal)
                ->selectRaw('DATE(created_at) as tgl, COUNT(*) as jumlah')
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tgl');

            $reservasiPerHari = [];
            $laporanPerHari   = [];
            for ($i = 13; $i >= 0; $i--) {
                $t = now()->subDays($i)->format('Y-m-d');
                $reservasiPerHari[$t] = $reservasiHarianRaw[$t] ?? 0;
                $laporanPerHari[$t]   = $laporanHarianRaw[$t] ?? 0;
            }

            $judulGrafik = '14 Hari Terakhir';
        }

        // ---- Perbandingan dengan bulan lalu ----
        $bulanLalu = now()->subMonthNoOverflow();

        $fasilitasBulanIni = Reservation::where('status', 'disetujui')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct()
            ->count('facility_id');

        $fasilitasBulanLalu = Reservation::where('status', 'disetujui')
            ->whereMonth('created_at', $bulanLalu->month)
            ->whereYear('created_at', $bulanLalu->year)
            ->distinct()
            ->count('facility_id');

        $selisihFasilitas = $fasilitasBulanIni - $fasilitasBulanLalu;

        $laporanBulanIni = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $laporanBulanLalu = Report::whereMonth('created_at', $bulanLalu->month)
            ->whereYear('created_at', $bulanLalu->year)
            ->count();

        $selisihLaporan = $laporanBulanIni - $laporanBulanLalu;

        // ---- Daftar tahun untuk dropdown filter ----
        // Catatan: alias SELECT tidak boleh dipakai di WHERE (MySQL), jadi pakai kolom asli.
        $daftarTahun = Reservation::query()
            ->selectRaw('YEAR(tanggal) as thn')
            ->whereNotNull('tanggal')
            ->union(
                Report::query()
                    ->selectRaw('YEAR(created_at) as thn')
                    ->whereNotNull('created_at')
            )
            ->pluck('thn')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([now()->year]);
        }

        $daftarBulan = self::NAMA_BULAN;

        return view('admin.rekap.index', compact(
            'rekap',
            'totalFasilitasDireservasi',
            'totalLaporanKerusakan',
            'reservasiPerHari',
            'laporanPerHari',
            'judulGrafik',
            'selisihFasilitas',
            'selisihLaporan',
            'bulan',
            'tahun',
            'daftarBulan',
            'daftarTahun'
        ));
    }

    public function exportCsv(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        [$rekap] = $this->hitungRekap($bulan, $tahun);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rekap-fasilitas.csv"',
        ];

        $callback = function () use ($rekap) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Nama Fasilitas',
                'Tipe',
                'Lokasi',
                'Kapasitas',
                'Total Reservasi',
                'Total Laporan',
            ]);

            foreach ($rekap as $facility) {
                fputcsv($file, [
                    $facility->nama_fasilitas,
                    $facility->tipe,
                    $facility->lokasi,
                    $facility->kapasitas,
                    $facility->total_reservasi,
                    $facility->total_laporan,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        [$rekap] = $this->hitungRekap($bulan, $tahun);

        $html = '<html><head><meta charset="UTF-8"></head><body>'
            . '<h3>Rekap Fasilitas - ' . e($this->labelPeriode($bulan, $tahun)) . '</h3>'
            . '<table border="1">'
            . '<tr>'
            . '<th>Nama Fasilitas</th><th>Tipe</th><th>Lokasi</th>'
            . '<th>Kapasitas</th><th>Total Reservasi</th><th>Total Laporan</th>'
            . '</tr>';

        foreach ($rekap as $facility) {
            $html .= '<tr>'
                . '<td>' . e($facility->nama_fasilitas) . '</td>'
                . '<td>' . e($facility->tipe) . '</td>'
                . '<td>' . e($facility->lokasi) . '</td>'
                . '<td>' . e($facility->kapasitas) . '</td>'
                . '<td>' . $facility->total_reservasi . '</td>'
                . '<td>' . $facility->total_laporan . '</td>'
                . '</tr>';
        }

        $html .= '</table></body></html>';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="rekap-fasilitas.xls"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        [$rekap] = $this->hitungRekap($bulan, $tahun);

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: sans-serif; font-size: 11px; }
                    h2 { text-align: center; margin-bottom: 5px; }
                    .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    th { background-color: #be123c; color: white; padding: 8px; text-align: center; }
                    td { border: 1px solid #ddd; padding: 7px; }
                    .center { text-align: center; }
                </style>
            </head>
            <body>
                <h2>Rekap Fasilitas</h2>
                <div class="subtitle">
                    Rekapitulasi Reservasi dan Laporan Kerusakan &mdash; ' . e($this->labelPeriode($bulan, $tahun)) . '
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
                    <tbody>';

        foreach ($rekap as $facility) {
            $html .= '
                        <tr>
                            <td>' . e($facility->nama_fasilitas) . '</td>
                            <td>' . e($facility->tipe) . '</td>
                            <td>' . e($facility->lokasi) . '</td>
                            <td class="center">' . e($facility->kapasitas) . '</td>
                            <td class="center">' . $facility->total_reservasi . '</td>
                            <td class="center">' . $facility->total_laporan . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </body>
            </html>';

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('rekap-fasilitas.pdf');
    }
}