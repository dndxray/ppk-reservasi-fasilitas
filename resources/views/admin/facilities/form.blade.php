<x-app-layout>
    @php
        $fotoLama = $facility?->foto ? asset('storage/' . $facility->foto) : null;
        $inputClass = 'w-full px-5 py-4 bg-[#F5EFE9] border border-[#E6D6CE] rounded-xl text-base placeholder-gray-400 focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]';
    @endphp

    <div class="min-h-screen bg-white px-6 sm:px-10 lg:px-12 py-8">

        {{-- judul + panah kembali --}}
        <div class="flex items-center gap-5 mb-6">
            <a href="{{ $facility?->exists ? route('admin.facilities.show', $facility) : route('admin.fasilitas.index') }}"
               class="text-[#47201B] hover:opacity-70 transition" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-black">
                {{ $facility?->exists ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}
            </h1>
        </div>

        <form method="POST"
              enctype="multipart/form-data"
              action="{{ $facility?->exists
                    ? route('admin.facilities.update', $facility)
                    : route('admin.facilities.store') }}"
              x-data="{ preview: @js($fotoLama), fotoAwal: @js($fotoLama) }"
              class="w-full bg-white border border-[#E0D5D0] rounded-3xl p-6 sm:p-10 space-y-7">

            @csrf
            @if ($facility?->exists)
                @method('PUT')
            @endif

            {{-- Nama Fasilitas --}}
            <div>
                <label class="block text-lg font-bold text-black mb-3">Nama Fasilitas</label>
                <input type="text" name="nama_fasilitas"
                       value="{{ old('nama_fasilitas', $facility?->nama_fasilitas ?? '') }}"
                       placeholder="Contoh: Ruang Seminar"
                       class="{{ $inputClass }}" required>
                @error('nama_fasilitas')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipe --}}
            <div>
                <label class="block text-lg font-bold text-black mb-3">Tipe</label>
                <select name="tipe" class="{{ $inputClass }}" required>
                    <option value="">Pilih tipe</option>
                    @foreach ($tipeOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('tipe', $facility?->tipe ?? '') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('tipe')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Lokasi --}}
            <div>
                <label class="block text-lg font-bold text-black mb-3">Lokasi Fasilitas</label>
                <input type="text" name="lokasi"
                       value="{{ old('lokasi', $facility?->lokasi ?? '') }}"
                       placeholder="Contoh: Gedung B"
                       class="{{ $inputClass }}" required>
                @error('lokasi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kapasitas --}}
            <div>
                <label class="block text-lg font-bold text-black mb-3">Kapasitas</label>
                <input type="number" name="kapasitas" min="1"
                       value="{{ old('kapasitas', $facility?->kapasitas ?? '') }}"
                       placeholder="Contoh: 50"
                       class="{{ $inputClass }}">
                @error('kapasitas')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-lg font-bold text-black mb-3">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          placeholder="Contoh: Ruangan untuk seminar dan kegiatan akademik"
                          class="{{ $inputClass }}">{{ old('deskripsi', $facility?->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto --}}
            <div>
                <label class="block text-lg font-bold text-black mb-3">
                    Foto Fasilitas
                    <span class="text-xs font-normal text-[#B23A2E]">(opsional)</span>
                </label>

                <label for="foto"
                       class="flex flex-col items-center justify-center gap-2 min-h-[160px] px-4 py-6 bg-[#F5EFE9] border border-[#E6D6CE] rounded-xl cursor-pointer hover:bg-[#EFE6DF] transition">

                    <template x-if="preview">
                        <img :src="preview" alt="Preview foto"
                             class="w-full max-w-md h-52 rounded-xl object-cover">
                    </template>

                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span class="text-sm">Klik untuk memilih gambar</span>
                        </div>
                    </template>

                    <span class="text-xs text-gray-400">Hanya menerima JPG, PNG, WEBP (maks. 2 MB)</span>
                </label>

                <input id="foto" type="file" name="foto" accept="image/png,image/jpeg,image/webp"
                       class="hidden"
                       @change="const f = $event.target.files[0]; preview = f ? URL.createObjectURL(f) : fotoAwal">

                @error('foto')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-6 pt-2">
                <button type="reset" @click="preview = fotoAwal"
                        class="text-base font-bold text-black hover:opacity-70 transition">
                    Reset
                </button>

                <button type="submit"
                        class="px-10 py-4 bg-[#C0453F] text-white text-base font-semibold rounded-xl shadow-sm hover:bg-[#A83B32] transition">
                    {{ $facility?->exists ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
                </button>
            </div>

        </form>
    </div>
</x-app-layout>