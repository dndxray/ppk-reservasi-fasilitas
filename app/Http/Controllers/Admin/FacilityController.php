<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();
    }

    //Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskipsi' => 'nullable|string',
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
}
