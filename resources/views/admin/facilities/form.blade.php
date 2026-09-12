<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $facility->exists ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form method="POST"
              action="{{ $facility->exists ? route('admin.facilities.update', $facility) : route('admin.facilities.store') }}"
              class="bg-white border border-stone-200 rounded-lg p-6 space-y-4">
            @csrf
            @if ($facility->exists) @method('PUT') @endif

            <div>
                <label class="block text-sm text-gray-600 mb-1">Nama Fasilitas</label>
                <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas', $facility->nama_fasilitas) }}"
                       class="w-full border-stone-300 rounded-md text-sm" required>
                @error('nama_fasilitas') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Tipe</label>
                <select name="tipe" class="w-full border-stone-300 rounded-md text-sm" required>
                    <option value="">Pilih tipe</option>
                    @foreach ($tipeOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('tipe', $facility->tipe) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('tipe') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $facility->lokasi) }}"
                       class="w-full border-stone-300 rounded-md text-sm" required>
                @error('lokasi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Kapasitas</label>
                <input type="number" name="kapasitas" value="{{ old('kapasitas', $facility->kapasitas) }}"
                       class="w-full border-stone-300 rounded-md text-sm">
                @error('kapasitas') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border-stone-300 rounded-md text-sm">{{ old('deskripsi', $facility->deskripsi) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-slate-800 text-white text-sm font-semibold rounded-md px-5 py-2 hover:bg-slate-900">
                    Simpan
                </button>
                <a href="{{ route('admin.facilities.index') }}" class="text-sm text-gray-500 self-center">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
