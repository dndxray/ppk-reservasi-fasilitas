<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    private function tipeOptions(): array
    {
        return [
            'ruangan' => 'Ruangan',
            'aula' => 'Aula',
            'laboratorium' => 'Laboratorium',
            'lapangan' => 'Lapangan',
            'lainnya' => 'Lainnya',
        ];
    }

    private function rules(): array
    {
        return [
            'nama_fasilitas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $facilities = Facility::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_fasilitas', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        })->paginate(10)->withQueryString();

        return view('admin.facilities.index', compact('facilities', 'search'));
    }

    // Detail fasilitas + riwayat reservasi khusus fasilitas ini
    public function show(Facility $facility)
    {
        $reservations = $facility->reservations()
            ->with('user')
            ->latest('tanggal')
            ->latest('waktu_mulai')
            ->paginate(10);

        return view('admin.facilities.show', compact('facility', 'reservations'));
    }

    // Menampilkan form tambah fasilitas
    public function create()
    {
        return view('admin.facilities.form', [
            'facility' => new Facility(),
            'tipeOptions' => $this->tipeOptions(),
        ]);
    }

    // Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('facilities', 'public');
        }

        $validated['status'] = 'aktif';

        Facility::create($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('status', 'Fasilitas berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Facility $facility)
    {
        return view('admin.facilities.form', [
            'facility' => $facility,
            'tipeOptions' => $this->tipeOptions(),
        ]);
    }

    // Mengubah fasilitas
    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            // hapus foto lama kalau ada
            if ($facility->foto) {
                Storage::disk('public')->delete($facility->foto);
            }
            $validated['foto'] = $request->file('foto')->store('facilities', 'public');
        } else {
            // tidak upload baru: jangan timpa foto lama
            unset($validated['foto']);
        }

        $facility->update($validated);

        return redirect()
            ->route('admin.facilities.show', $facility)
            ->with('status', 'Fasilitas berhasil diperbarui.');
    }

    // Menonaktifkan fasilitas
    public function deactivate(Facility $facility)
    {
        $facility->update(['status' => 'nonaktif']);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('status', 'Fasilitas berhasil dinonaktifkan.');
    }

    // Mengaktifkan kembali fasilitas
    public function activate(Facility $facility)
    {
        $facility->update(['status' => 'aktif']);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('status', 'Fasilitas berhasil diaktifkan.');
    }
}