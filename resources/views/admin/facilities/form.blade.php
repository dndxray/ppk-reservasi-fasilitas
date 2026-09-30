<x-app-layout>
    @php
        $fotoLama = $facility?->foto ? asset('storage/' . $facility->foto) : null;
    @endphp

    <div class="min-h-screen bg-[#F8F7F7]">

        {{-- header bar --}}
        <div class="flex items-center gap-4 bg-[#F5F0ED] px-6 sm:px-10 py-5">
            <a href="{{ route('admin.fasilitas.index') }}" class="text-[#47201B] hover:opacity-70 transition" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-[#47201B]">
                {{ $facility?->exists ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}
            </h1>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ $facility?->exists
                        ? route('admin.facilities.update', $facility)
                        : route('admin.facilities.store') }}"
                  x-data="{ preview: @js($fotoLama), fotoAwal: @js($fotoLama) }"
                  class="bg-white border border-[#E6D6CE] rounded-2xl p-6 sm:p-8 space-y-5">

                @csrf
                @if ($facility?->exists)
                    @method('PUT')
                @endif

                {{-- Nama Fasilitas --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Nama Fasilitas</label>
                    <input type="text" name="nama_fasilitas"
                           value="{{ old('nama_fasilitas', $facility?->nama_fasilitas ?? '') }}"
                           placeholder="Contoh: Ruang Seminar"
                           class="w-full px-4 py-3 bg-[#F5EFE9] border border-[#E6D6CE] rounded-lg text-sm placeholder-gray-400 focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]"
                           required>
                    @error('nama_fasilitas')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Tipe</label>
                    <select name="tipe"
                            class="w-full px-4 py-3 bg-[#F5EFE9] border border-[#E6D6CE] rounded-lg text-sm focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]"
                            required>
                        <option value="">Pilih tipe</option>
                        @foreach ($tipeOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('tipe', $facility?->tipe ?? '') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipe')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi + Kapasitas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-gray-900 mb-2">Lokasi</label>
                        <input type="text" name="lokasi"
                               value="{{ old('lokasi', $facility?->lokasi ?? '') }}"
                               placeholder="Contoh: Gedung B"
                               class="w-full px-4 py-3 bg-[#F5EFE9] border border-[#E6D6CE] rounded-lg text-sm placeholder-gray-400 focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]"
                               required>
                        @error('lokasi')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Kapasitas</label>
                        <input type="number" name="kapasitas" min="1"
                               value="{{ old('kapasitas', $facility?->kapasitas ?? '') }}"
                               placeholder="50"
                               class="w-full px-4 py-3 bg-[#F5EFE9] border border-[#E6D6CE] rounded-lg text-sm placeholder-gray-400 focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]">
                        @error('kapasitas')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                              placeholder="Contoh: Ruangan untuk seminar dan kegiatan akademik"
                              class="w-full px-4 py-3 bg-[#F5EFE9] border border-[#E6D6CE] rounded-lg text-sm placeholder-gray-400 focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]">{{ old('deskripsi', $facility?->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        Foto Fasilitas
                        <span class="text-[10px] font-normal text-[#B23A2E]">(opsional)</span>
                    </label>

                    <label for="foto"
                           class="flex flex-col items-center justify-center gap-2 min-h-[130px] px-4 py-6 bg-[#F5EFE9] border border-[#E6D6CE] rounded-lg cursor-pointer hover:bg-[#EFE6DF] transition">

                        {{-- preview --}}
                        <template x-if="preview">
                            <img :src="preview" alt="Preview foto"
                                 class="w-full max-w-xs h-44 rounded-xl object-cover">
                        </template>

                        {{-- placeholder --}}
                        <template x-if="!preview">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                <span class="text-xs">Klik untuk memilih gambar</span>
                            </div>
                        </template>

                        <span class="text-[10px] text-gray-400">Hanya menerima JPG, PNG, WEBP (maks. 2 MB)</span>
                    </label>

                    <input id="foto" type="file" name="foto" accept="image/png,image/jpeg,image/webp"
                           class="hidden"
                           @change="const f = $event.target.files[0]; preview = f ? URL.createObjectURL(f) : fotoAwal">

                    @error('foto')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex items-center justify-end gap-5 pt-2">
                    <button type="reset" @click="preview = fotoAwal"
                            class="text-sm font-bold text-gray-900 hover:opacity-70 transition">
                        Reset
                    </button>

                    <button type="submit"
                            class="px-8 py-3 bg-[#C0453F] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#A83B32] transition">
                        {{ $facility?->exists ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>