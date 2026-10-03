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
    // buka form buat lapor kerusakan
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
            'foto' => 'nullable|image|max:2048',
        ], [
            'facility_id.required' => 'Fasilitas wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak valid.',
            'kategori.required' => 'Kategori kerusakan wajib dipilih.',
            'kategori_lainnya.required_if' => 'Mohon sebutkan kategori kerusakan lainnya.',
            'deskripsi.required' => 'Deskripsi kerusakan wajib diisi.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
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

    // riwayat laporan user (ada fitur search juga)
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

        // Filter status proses laporan
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

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->where(function ($q) use ($tanggal) {
                $q->whereDate('tanggal_ditemukan', $tanggal)
                  ->orWhereDate('created_at', $tanggal);
            });
        }

        $reports = $query
            ->latest()
            ->get();

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

    // update status laporan & fasilitas oleh petugas
    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:menunggu,baru,diproses,selesai,ditolak',
            'catatan_resolusi' => 'nullable|string',
            'status_fasilitas' => 'nullable|in:aktif,dalam_perbaikan',
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
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    // antrean laporan masuk buat petugas
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

        // Filter status proses laporan
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

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->where(function ($q) use ($tanggal) {
                $q->whereDate('tanggal_ditemukan', $tanggal)
                  ->orWhereDate('created_at', $tanggal);
            });
        }

        $reports = $query->latest()->get();

        $daftarKategori = Report::distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('reports.antrian', compact('reports', 'daftarKategori'));
    }

    // rekap laporan kerusakan untuk petugas
    public function rekap()
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

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
            $jumlahReservasi = $okupansi->where('facility_id', $facility->id)->first();
            $jumlahLaporan = $kerusakan->where('facility_id', $facility->id)->first();

            $facility->total_reservasi = $jumlahReservasi ? $jumlahReservasi->jumlah_reservasi : 0;
            $facility->total_laporan = $jumlahLaporan ? $jumlahLaporan->jumlah_laporan : 0;

            $rekap[] = $facility;
        }

        $totalFasilitasDireservasi = $okupansi->count();
        $totalLaporanKerusakan = $kerusakan->sum('jumlah_laporan');

        $mulaiTanggal = now()->subDays(13)->startOfDay();

        // Reservasi per hari (14 hari terakhir)
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

        // Laporan per hari (14 hari terakhir)
        $laporanHarianRaw = Report::where('created_at', '>=', $mulaiTanggal)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->groupByRaw('DATE(created_at)')
            ->pluck('jumlah', 'tanggal');

        $laporanPerHari = [];
        for ($i = 13; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $laporanPerHari[$tanggal] = $laporanHarianRaw[$tanggal] ?? 0;
        }

        // Selisih bulan ini vs bulan lalu
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

        return view('reports.rekap', compact(
            'rekap',
            'totalFasilitasDireservasi',
            'totalLaporanKerusakan',
            'reservasiPerHari',
            'laporanPerHari',
            'detailTanggal',
            'selisihFasilitas',
            'selisihLaporan'
        ));
    }

    // ekspor rekap laporan kerusakan ke Excel (.xls)
    public function exportExcel()
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $fasilitas = Facility::all();
        $laporan = Report::selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

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
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($facility->nama_fasilitas) . '</td>
                        <td>' . htmlspecialchars($facility->tipe) . '</td>
                        <td>' . htmlspecialchars($facility->lokasi) . '</td>
                        <td>' . ucfirst(str_replace('_', ' ', $facility->status)) . '</td>
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

    // ekspor rekap laporan kerusakan ke PDF (.pdf)
    public function exportPdf()
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $fasilitas = Facility::all();
        $laporan = Report::selectRaw('facility_id, COUNT(*) as jumlah')
            ->groupBy('facility_id')
            ->pluck('jumlah', 'facility_id');

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
            $badgeClass = $facility->status === 'aktif' ? 'badge-aktif' : 'badge-perbaikan';
            $html .= '
                        <tr>
                            <td><strong>' . htmlspecialchars($facility->nama_fasilitas) . '</strong></td>
                            <td>' . htmlspecialchars($facility->tipe) . '</td>
                            <td>' . htmlspecialchars($facility->lokasi) . '</td>
                            <td><span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $facility->status)) . '</span></td>
                            <td class="center"><strong>' . ($laporan[$facility->id] ?? 0) . '</strong></td>
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