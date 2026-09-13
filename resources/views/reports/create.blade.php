<x-app-layout>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden" x-data="{ confirmOpen: false }">

                <div class="bg-[#47201B] px-6 py-4 flex items-center gap-3">
                    <a href="{{ route('reports.index') }}" class="text-white hover:text-[#E1D3C4]">
                        &larr;
                    </a>
                    <h2 class="text-white font-semibold text-lg">
                        Laporkan Kerusakan Fasilitas
                    </h2>
                </div>

                <form id="form-lapor" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <div class="mb-4">
                        <label for="facility_id" class="block font-medium text-sm text-[#47201B] mb-1">Nama Fasilitas</label>
                        <select name="facility_id" id="facility_id" required
                            class="block w-full border-[#996561]/40 rounded-md shadow-sm bg-[#F8F7F7] focus:border-[#CA734D] focus:ring-[#CA734D]">
                            <option value="">-- Pilih fasilitas --</option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}" @selected(old('facility_id') == $facility->id)>
                                    {{ $facility->nama_fasilitas }} ({{ $facility->lokasi }})
                                </option>
                            @endforeach
                        </select>
                        @error('facility_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori" class="block font-medium text-sm text-[#47201B] mb-1">Kategori Kerusakan</label>
                            <input type="text" name="kategori" id="kategori" required maxlength="100" value="{{ old('kategori') }}"
                                placeholder="mis. Listrik, AC, Furniture"
                                class="block w-full border-[#996561]/40 rounded-md shadow-sm bg-[#F8F7F7] focus:border-[#CA734D] focus:ring-[#CA734D]">
                            @error('kategori') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tanggal_ditemukan" class="block font-medium text-sm text-[#47201B] mb-1">Tanggal Ditemukan</label>
                            <input type="date" name="tanggal_ditemukan" id="tanggal_ditemukan" value="{{ old('tanggal_ditemukan') }}"
                                class="block w-full border-[#996561]/40 rounded-md shadow-sm bg-[#F8F7F7] focus:border-[#CA734D] focus:ring-[#CA734D]">
                            @error('tanggal_ditemukan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block font-medium text-sm text-[#47201B] mb-1">Deskripsi Kerusakan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required maxlength="1000"
                            placeholder="Jelaskan kerusakan yang ditemukan..."
                            class="block w-full border-[#996561]/40 rounded-md shadow-sm bg-[#F8F7F7] focus:border-[#CA734D] focus:ring-[#CA734D]">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="foto" class="block font-medium text-sm text-[#47201B] mb-1">Foto Kondisi Kerusakan</label>
                        <label for="foto"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-[#996561]/40 rounded-md bg-[#F8F7F7] cursor-pointer hover:bg-[#F3EFE8] transition">
                            <span class="text-sm text-[#996561]" x-data="{ fileName: '' }" x-text="fileName || 'Klik atau seret foto ke sini'"
                                @change.window="fileName = $event.target.files && $event.target.files[0] ? $event.target.files[0].name : ''"></span>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="hidden"
                            @change="$el.closest('.mb-6').querySelector('span').innerText = $el.files[0]?.name || 'Klik atau seret foto ke sini'">
                        <p class="text-xs text-[#996561] mt-1">Maks. 2MB, format gambar ya.</p>
                        @error('foto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="reset" class="px-4 py-2 bg-[#E1D3C4] text-[#47201B] rounded-md hover:bg-[#996561]/30">
                            Reset
                        </button>
                        <button type="button" @click="confirmOpen = true"
                            class="px-4 py-2 bg-[#47201B] text-white rounded-md hover:bg-[#511E1D]">
                            Kirim Laporan
                        </button>
                    </div>
                </form>

                {{-- modal konfirmasi --}}
                <div x-show="confirmOpen" x-cloak
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                    <div @click.outside="confirmOpen = false" class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm relative">
                        <button @click="confirmOpen = false" class="absolute top-3 right-3 text-[#996561] hover:text-[#47201B]">
                            &times;
                        </button>
                        <p class="text-center font-medium text-[#47201B] mb-6">
                            Apa Anda yakin akan mengirim laporan?
                        </p>
                        <div class="flex justify-center gap-3">
                            <button type="button" @click="confirmOpen = false"
                                class="px-6 py-2 bg-[#E1D3C4] text-[#47201B] rounded-md hover:bg-[#996561]/30">
                                Batal
                            </button>
                            <button type="button" @click="document.getElementById('form-lapor').submit()"
                                class="px-6 py-2 bg-[#47201B] text-white rounded-md hover:bg-[#511E1D]">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>