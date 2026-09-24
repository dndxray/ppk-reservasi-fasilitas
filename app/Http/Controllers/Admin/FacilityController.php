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

    // Menampilkan form tambah fasilitas
    public function create()
    {
        $tipeOptions = [
            'ruangan' => 'Ruangan',
            'aula' => 'Aula',
            'laboratorium' => 'Laboratorium',
            'lapangan' => 'Lapangan',
            'lainnya' => 'Lainnya',
        ];

        return view('admin.facilities.form', [
            'facility' => new Facility(),
            'tipeOptions' => $tipeOptions,
        ]);
    }

    // Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['status'] = 'aktif';

        Facility::create($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Facility $facility)
    {
        $tipeOptions = [
            'ruangan' => 'Ruangan',
            'aula' => 'Aula',
            'laboratorium' => 'Laboratorium',
            'lapangan' => 'Lapangan',
            'lainnya' => 'Lainnya',
        ];

        return view('admin.facilities.form', compact('facility', 'tipeOptions'));
    }

    // Mengubah fasilitas
    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $facility->update($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    // Menonaktifkan fasilitas
    public function deactivate(Facility $facility)
    {
        $facility->update([
            'status' => 'nonaktif',
        ]);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil dinonaktifkan.');
    }

    // Mengaktifkan kembali fasilitas
    public function activate(Facility $facility)
    {
        $facility->update([
            'status' => 'aktif',
        ]);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diaktifkan.');
    }
}