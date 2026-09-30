<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $query->whereDate('tanggal_ditemukan', $request->tanggal);
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
            $query->whereDate('tanggal_ditemukan', $request->tanggal);
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
}