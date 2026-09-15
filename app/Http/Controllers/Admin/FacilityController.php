<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $facilities = Facility::when($search, function ($query) use ($search) {
            $query->where('nama_fasilitas', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('admin.facilities.index', compact('facilities', 'search'));
    }

    //Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,dalam_perbaikan,nonaktif',
        ]);

        $facility = Facility::create($validated);

        return response()->json([
            'message' => 'Fasilitas berhasil ditambahkan',
            'data' => $facility,
        ], 201);
    }

    //Mengubah fasilitas
    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,dalam_perbaikan,nonaktif',
        ]);

        $facility->update($validated);

        return response()->json([
            'message' => 'Fasilitas berhasil diperbarui',
            'data' => $facility,
        ]);
    }

    // Menonaktifkan fasilitas
    public function deactivate(Facility $facility)
    {
        $facility->update([
            'status' => 'nonaktif'
        ]);

        return response()->json([
            'message' => 'Fasilitas berhasil dinonaktifkan',
            'data' => $facility,
        ]);
    }
    // Menampilkan form edit
    public function edit(Facility $facility)
    {
        $tipeOptions = [
            'Ruangan',
            'Laboratorium',
            'Aula',
            'Lapangan',
            'Peralatan',
        ];

        return view('admin.facilities.form', compact('facility', 'tipeOptions'));
    }

    // Mengaktifkan kembali fasilitas
    public function activate(Facility $facility)
    {
        $facility->update([
            'status' => 'aktif'
        ]);

        return redirect()->route('admin.fasilitas.index');
    }
}
