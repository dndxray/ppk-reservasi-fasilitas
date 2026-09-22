<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // US6 - Form laporan kerusakan
    public function create()
    {
        $facilities = Facility::where('status', 'aktif')
            ->orderBy('nama_fasilitas')
            ->get();

        return view('reports.create', compact('facilities'));
    }

    // US6 - Menyimpan laporan
    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'status' => 'baru',
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    // US7 - Riwayat laporan milik pengguna
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

        $reports = $query
            ->latest()
            ->get();

        return view('reports.index', compact('reports'));
    }

    // US7 - Detail laporan
    // US8 - Petugas melihat detail laporan
    public function show(Report $report)
    {
        $user = Auth::user();

        if ($report->user_id !== $user->id && $user->role !== 'petugas') {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    // US11 - Petugas mengubah status laporan
    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
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

        if ($request->has('status_fasilitas')) {
            $report->facility->update([
                'status' => $request->status_fasilitas
            ]);
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    // US8 - Antrian laporan untuk petugas
    public function antrian()
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403);
        }

        $reports = Report::with(['user', 'facility'])
            ->latest()
            ->get();

        return view('reports.antrian', compact('reports'));
    }
}