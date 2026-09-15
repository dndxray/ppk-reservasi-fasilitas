<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        $facilities = collect();

        return view('reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|max:2048'
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
            'status' => 'baru'
        ]);

        return redirect()->route('reports.index');
    }

    public function index()
    {
        $reports = Report::where('user_id', Auth::id())->get();

        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        if ($report->user_id != Auth::id() && Auth::user()->role != 'petugas') {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }

        $request->validate([
            'status' => 'required',
            'catatan_resolusi' => 'nullable'
        ]);

        $report->status = $request->status;
        $report->catatan_resolusi = $request->catatan_resolusi;
        $report->diproses_oleh = Auth::id();

        if ($request->status == 'selesai') {
            $report->diselesaikan_pada = now();
        }

        $report->save();

        return redirect()->route('reports.show', $report);
    }

    public function antrian()
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }

        $reports = Report::latest()->get();

        return view('reports.antrian', compact('reports'));
    }
}