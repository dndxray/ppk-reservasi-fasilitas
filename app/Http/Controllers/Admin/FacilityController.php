<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FacilityController extends Controller
{
    private function tipeOptions(): array
    {
        return [
            'ruangan' => 'Ruangan',
            'aula' => 'Aula',
            'laboratorium' => 'Laboratorium',
            'alat' => 'Alat',
            'lapangan' => 'Lapangan',
            'lainnya' => 'Lainnya',
        ];
    }

    private function rules(): array
    {
        $alat = request('tipe') === 'alat';

        return [
            'nama_fasilitas' => ['required', 'string', 'min:3', 'max:255'],
            'tipe' => ['required', 'string', Rule::in(array_keys($this->tipeOptions()))],
            'lokasi' => ['required', 'string', 'min:3', 'max:255'],
            'kapasitas' => $alat ? ['nullable'] : ['required', 'integer', 'min:1'],
            'kuantitas' => $alat ? ['required', 'integer', 'min:1'] : ['nullable'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    // Pesan error validasi dalam bahasa Indonesia
    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min.string' => ':attribute minimal :min karakter.',
            'max.string' => ':attribute maksimal :max karakter.',
            'kapasitas.min' => 'Kapasitas minimal 1 orang.',
            'kuantitas.min' => 'Kuantitas minimal 1 unit.',
            'tipe.in' => 'Tipe fasilitas tidak valid.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto tidak valid. Gunakan JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto terlalu besar. Maksimal 2 MB.',
            'foto.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya maksimal 2 MB.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nama_fasilitas' => 'Nama fasilitas',
            'tipe' => 'Tipe fasilitas',
            'lokasi' => 'Lokasi fasilitas',
            'kapasitas' => 'Kapasitas',
            'kuantitas' => 'Kuantitas',
            'deskripsi' => 'Deskripsi',
            'foto' => 'Foto',
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
        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());
        $validated = $this->bersihkanJumlah($validated);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('facilities', 'public');
        }

        $validated['status'] = 'aktif';

        Facility::create($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
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
        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());
        $validated = $this->bersihkanJumlah($validated);

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

        // Setelah tersimpan, kembali ke daftar fasilitas
        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['aktif', 'dalam_perbaikan', 'nonaktif'])],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
        ]);

        $facility->update(['status' => $validated['status']]);

        $label = [
            'aktif'           => 'Aktif',
            'dalam_perbaikan' => 'Dalam Perbaikan',
            'nonaktif'        => 'Nonaktif',
        ][$validated['status']];

        return redirect()
            ->route('admin.facilities.show', $facility)
            ->with('success', "Status fasilitas berhasil diubah menjadi {$label}.");
    }
    private function bersihkanJumlah(array $data): array
    {
        if (($data['tipe'] ?? null) === 'alat') {
            $data['kapasitas'] = null;
        } else {
            $data['kuantitas'] = null;
        }

        return $data;
    }
}