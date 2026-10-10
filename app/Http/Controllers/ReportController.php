<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // buka form lapor kerusakan
    public function create(Request $request)
    {
        $facilities = Facility::where('status', 'aktif')
            ->orderBy('nama_fasilitas')
            ->get();

        $selectedFacilityId = null;
        if ($request->filled('facility_id')) {
            $facility = Facility::where('id', $request->facility_id)
                ->where('status', 'aktif')
                ->first();
            if ($facility) {
                $selectedFacilityId = $facility->id;
            }
        }

        return view('reports.create', compact('facilities', 'selectedFacilityId'));
    }

    // simpan laporan baru dari user
    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'kategori' => 'required|string|max:100',
            'kategori_lainnya' => 'required_if:kategori,Lainnya|nullable|string|max:100',
            'tanggal_ditemukan' => 'nullable|date',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|max:3072',
        ], [
            'facility_id.required' => 'Fasilitas wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak valid.',
            'kategori.required' => 'Kategori kerusakan wajib dipilih.',
            'kategori_lainnya.required_if' => 'Mohon sebutkan kategori kerusakan lainnya.',
            'deskripsi.required' => 'Deskripsi kerusakan wajib diisi.',
            'foto.required' => 'Foto bukti kerusakan wajib diunggah.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('reports', 'public');
        }

        $kategori = ($request->kategori === 'Lainnya')
            ? $request->kategori_lainnya
            : $request->kategori;

        Report::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'kategori' => $kategori,
            'tanggal_ditemukan' => $request->tanggal_ditemukan ?? now()->toDateString(),
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('reports.create')
            ->with('success', 'Laporan berhasil diajukan.');
    }

    // riwayat laporan user (ada fitur cari juga)
    public function index(Request $request)
    {
        $query = Report::where('user_id', Auth::id())
            ->with('facility');

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('kategori', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%")
                    ->orWhereHas('facility', function ($facility) use ($cari) {
                        $facility->where(
                            'nama_fasilitas',
                            'like',
                            "%{$cari}%"
                        );
                    });
            });
        }

        // filter status laporan
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'menunggu' || $status === 'baru') {
                $query->whereIn('status', ['menunggu', 'baru']);
            } elseif ($status === 'belum_selesai') {
                $query->whereIn('status', ['menunggu', 'baru', 'diproses']);
            } elseif (in_array($status, ['diproses', 'selesai', 'ditolak'])) {
                $query->where('status', $status);
            }
        }

        // filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // filter tanggal
        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->where(function ($q) use ($tanggal) {
                $q->whereDate('tanggal_ditemukan', $tanggal)
                  ->orWhereDate('created_at', $tanggal);
            });
        }

        $reports = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $daftarKategori = Report::distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('reports.index', compact('reports', 'daftarKategori'));
    }

    // lihat detail laporan
    public function show(Report $report)
    {
        $user = Auth::user();

        if ($report->user_id !== $user->id && $user->role !== 'petugas') {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    // update status laporan dan fasilitas dari petugas
    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        // laporan yang udah diproses atau selesai ga bisa ditolak lagi
        if (in_array($report->status, ['diproses', 'selesai']) && $request->status === 'ditolak') {
            return back()->withErrors(['status' => 'Laporan yang sudah diproses atau selesai tidak dapat ditolak.']);
        }

        $request->validate([
            'status' => 'required|in:menunggu,baru,diproses,selesai,ditolak',
            'catatan_resolusi' => 'required|string|max:1000',
            'status_fasilitas' => 'nullable|in:aktif,dalam_perbaikan',
        ], [
            'catatan_resolusi.required' => 'Catatan resolusi penanganan wajib diisi saat memperbarui status.',
            'catatan_resolusi.max' => 'Catatan resolusi penanganan maksimal 1000 karakter.',
        ]);

        $report->status = $request->status;
        $report->catatan_resolusi = $request->catatan_resolusi;
        $report->diproses_oleh = Auth::id();

        if ($request->status === 'selesai') {
            $report->diselesaikan_pada = now();
        } else {
            $report->diselesaikan_pada = null;
        }

        $report->save();

        if ($request->filled('status_fasilitas') && $report->facility) {
            $report->facility->update([
                'status' => $request->status_fasilitas
            ]);
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Status laporan dan fasilitas berhasil diperbarui.');
    }

    // antrian laporan masuk buat petugas
    public function antrian(Request $request)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $query = Report::with(['user', 'facility']);

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('kategori', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%")
                    ->orWhereHas('facility', function ($facility) use ($cari) {
                        $facility->where('nama_fasilitas', 'like', "%{$cari}%");
                    })
                    ->orWhereHas('user', function ($user) use ($cari) {
                        $user->where('name', 'like', "%{$cari}%");
                    });
            });
        }

        // filter status laporan
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'menunggu' || $status === 'baru') {
                $query->whereIn('status', ['menunggu', 'baru']);
            } elseif ($status === 'belum_selesai') {
                $query->whereIn('status', ['menunggu', 'baru', 'diproses']);
            } elseif (in_array($status, ['diproses', 'selesai', 'ditolak'])) {
                $query->where('status', $status);
            }
        }

        // filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // filter tanggal
        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->where(function ($q) use ($tanggal) {
                $q->whereDate('tanggal_ditemukan', $tanggal)
                  ->orWhereDate('created_at', $tanggal);
            });
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $daftarKategori = Report::distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('reports.antrian', compact('reports', 'daftarKategori'));
    }

    // rekap data laporan kerusakan buat petugas
    public function rekap(Request $request)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $queryKerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan');
        if ($bulan) {
            $queryKerusakan->where(function ($q) use ($bulan) {
                $q->whereMonth('created_at', $bulan)
                  ->orWhereMonth('tanggal_ditemukan', $bulan);
            });
        }
        if ($tahun) {
            $queryKerusakan->where(function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun)
                  ->orWhereYear('tanggal_ditemukan', $tahun);
            });
        }
        $kerusakan = $queryKerusakan->groupBy('facility_id')->get();

        $fasilitas = Facility::all();
        $rekap = [];

        foreach ($fasilitas as $facility) {
            $jumlahLaporan = $kerusakan->where('facility_id', $facility->id)->first();
            $facility->total_laporan = $jumlahLaporan ? $jumlahLaporan->jumlah_laporan : 0;
            $rekap[] = $facility;
        }

        $totalLaporanKerusakan = $kerusakan->sum('jumlah_laporan');

        if ($bulan && $tahun) {
            $daysInMonth = \Carbon\Carbon::createFromDate((int)$tahun, (int)$bulan, 1)->daysInMonth;
            $laporanHarianRaw = Report::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tanggal');

            $laporanPerHari = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                $laporanPerHari[$tanggal] = $laporanHarianRaw[$tanggal] ?? 0;
            }
        } else {
            $mulaiTanggal = now()->subDays(13)->startOfDay();
            $laporanHarianRaw = Report::where('created_at', '>=', $mulaiTanggal)
                ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tanggal');

            $laporanPerHari = [];
            for ($i = 13; $i >= 0; $i--) {
                $tanggal = now()->subDays($i)->format('Y-m-d');
                $laporanPerHari[$tanggal] = $laporanHarianRaw[$tanggal] ?? 0;
            }
        }

        $laporanBulanIni = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $laporanBulanLalu = Report::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $selisihLaporan = $laporanBulanIni - $laporanBulanLalu;

        $laporanDetailPerTanggal = Report::selectRaw('facility_id, DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->groupBy('facility_id', DB::raw('DATE(created_at)'))
            ->get();

        $detailTanggal = [];
        foreach ($laporanDetailPerTanggal as $item) {
            $tgl = $item->tanggal;
            if (!isset($detailTanggal[$tgl])) {
                $detailTanggal[$tgl] = [];
            }
            $detailTanggal[$tgl][$item->facility_id] = (int) $item->jumlah;
        }

        $daftarTahun = Report::selectRaw('YEAR(created_at) as thn')
            ->whereNotNull('created_at')
            ->distinct()
            ->pluck('thn')
            ->sortDesc()
            ->values();

        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([now()->year]);
        }

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('reports.rekap', compact(
            'rekap',
            'totalLaporanKerusakan',
            'laporanPerHari',
            'detailTanggal',
            'selisihLaporan',
            'bulan',
            'tahun',
            'daftarBulan',
            'daftarTahun'
        ));
    }

    // export data rekap ke file csv
    public function exportCsv(Request $request)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $queryKerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan');
        if ($bulan) {
            $queryKerusakan->where(function ($q) use ($bulan) {
                $q->whereMonth('created_at', $bulan)
                  ->orWhereMonth('tanggal_ditemukan', $bulan);
            });
        }
        if ($tahun) {
            $queryKerusakan->where(function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun)
                  ->orWhereYear('tanggal_ditemukan', $tahun);
            });
        }
        $laporan = $queryKerusakan->groupBy('facility_id')->pluck('jumlah_laporan', 'facility_id');

        $fasilitas = Facility::all();
        $filename = 'rekap-laporan-kerusakan.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($fasilitas, $laporan) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['Nama Fasilitas', 'Tipe', 'Lokasi', 'Status', 'Total Laporan Kerusakan']);

            foreach ($fasilitas as $facility) {
                $totalLap = $laporan[$facility->id] ?? 0;
                $statusRekap = $totalLap > 0 ? 'Dalam Perbaikan' : 'Aktif';
                fputcsv($file, [
                    $facility->nama_fasilitas,
                    $facility->tipe,
                    $facility->lokasi,
                    $statusRekap,
                    $totalLap,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // export data rekap ke file excel
    public function exportExcel(Request $request)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $queryKerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan');
        if ($bulan) {
            $queryKerusakan->where(function ($q) use ($bulan) {
                $q->whereMonth('created_at', $bulan)
                  ->orWhereMonth('tanggal_ditemukan', $bulan);
            });
        }
        if ($tahun) {
            $queryKerusakan->where(function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun)
                  ->orWhereYear('tanggal_ditemukan', $tahun);
            });
        }
        $laporan = $queryKerusakan->groupBy('facility_id')->pluck('jumlah_laporan', 'facility_id');

        $fasilitas = Facility::all();
        $filename = 'rekap-laporan-kerusakan.xls';

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body>
                <table border="1">
                    <tr style="background-color: #511E1D; color: white;">
                        <th>Nama Fasilitas</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Total Laporan Kerusakan</th>
                    </tr>
        ';

        foreach ($fasilitas as $facility) {
            $totalLap = $laporan[$facility->id] ?? 0;
            $statusRekap = $totalLap > 0 ? 'Dalam Perbaikan' : 'Aktif';
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($facility->nama_fasilitas) . '</td>
                        <td>' . htmlspecialchars($facility->tipe) . '</td>
                        <td>' . htmlspecialchars($facility->lokasi) . '</td>
                        <td>' . $statusRekap . '</td>
                        <td>' . $totalLap . '</td>
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

    // export data rekap ke file pdf
    public function exportPdf(Request $request)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $queryKerusakan = Report::selectRaw('facility_id, COUNT(*) as jumlah_laporan');
        if ($bulan) {
            $queryKerusakan->where(function ($q) use ($bulan) {
                $q->whereMonth('created_at', $bulan)
                  ->orWhereMonth('tanggal_ditemukan', $bulan);
            });
        }
        if ($tahun) {
            $queryKerusakan->where(function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun)
                  ->orWhereYear('tanggal_ditemukan', $tahun);
            });
        }
        $laporan = $queryKerusakan->groupBy('facility_id')->pluck('jumlah_laporan', 'facility_id');

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: sans-serif; font-size: 11px; color: #333; }
                    h2 { text-align: center; margin-bottom: 4px; color: #511E1D; }
                    .subtitle { text-align: center; color: #666; margin-bottom: 20px; font-size: 10px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th { background-color: #511E1D; color: white; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
                    td { border: 1px solid #e2e8f0; padding: 7px; }
                    .center { text-align: center; }
                    .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; display: inline-block; }
                    .badge-aktif { background-color: #d1fae5; color: #047857; }
                    .badge-perbaikan { background-color: #fef3c7; color: #b45309; }
                </style>
            </head>
            <body>
                <h2>REKAP LAPORAN KERUSAKAN FASILITAS</h2>
                <div class="subtitle">Laporan Rekapitulasi Kerusakan Fasilitas oleh Petugas | Tanggal Cetak: ' . date('d M Y') . '</div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Fasilitas</th>
                            <th>Tipe</th>
                            <th>Lokasi</th>
                            <th>Status Fasilitas</th>
                            <th style="text-align: center;">Total Laporan Kerusakan</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

        foreach ($fasilitas as $facility) {
            $totalLap = $laporan[$facility->id] ?? 0;
            $statusRekap = $totalLap > 0 ? 'Dalam Perbaikan' : 'Aktif';
            $badgeClass = $totalLap > 0 ? 'badge-perbaikan' : 'badge-aktif';
            $html .= '
                        <tr>
                            <td><strong>' . htmlspecialchars($facility->nama_fasilitas) . '</strong></td>
                            <td>' . htmlspecialchars($facility->tipe) . '</td>
                            <td>' . htmlspecialchars($facility->lokasi) . '</td>
                            <td><span class="badge ' . $badgeClass . '">' . $statusRekap . '</span></td>
                            <td class="center"><strong>' . $totalLap . '</strong></td>
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
            ->setPaper('a4', 'portrait')
            ->download('rekap-laporan-kerusakan.pdf');
    }
}