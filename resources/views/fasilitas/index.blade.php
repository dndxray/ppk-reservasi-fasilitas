<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Fasilitas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Form filter / pencarian fasilitas --}}
            <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('fasilitas.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                        <div>
                            <label for="tipe" class="block text-sm text-gray-700">Tipe</label>
                            <select name="tipe" id="tipe" class="mt-1 block w-full rounded border-gray-300 text-sm">
                                <option value="">Semua Tipe</option>
                                @foreach($daftarTipe as $tipe)
                                    <option value="{{ $tipe }}" @selected(request('tipe') == $tipe)>
                                        {{ $tipe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="lokasi" class="block text-sm text-gray-700">Lokasi</label>
                            <input type="text" name="lokasi" id="lokasi" value="{{ request('lokasi') }}"
                                   list="daftarLokasi" placeholder="Contoh: Gedung A"
                                   class="mt-1 block w-full rounded border-gray-300 text-sm">
                            <datalist id="daftarLokasi">
                                @foreach($daftarLokasi as $lokasi)
                                    <option value="{{ $lokasi }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label for="kapasitas_minimal" class="block text-sm text-gray-700">Kapasitas Minimal</label>
                            <input type="number" name="kapasitas_minimal" id="kapasitas_minimal"
                                   value="{{ request('kapasitas_minimal') }}" min="1" placeholder="Contoh: 30"
                                   class="mt-1 block w-full rounded border-gray-300 text-sm">
                        </div>

                        <div>
                            <label for="cari" class="block text-sm text-gray-700">Nama Fasilitas</label>
                            <input type="text" name="cari" id="cari" value="{{ request('cari') }}"
                                   placeholder="Contoh: Aula"
                                   class="mt-1 block w-full rounded border-gray-300 text-sm">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded hover:bg-gray-700">
                                Cari
                            </button>
                            <a href="{{ route('fasilitas.index') }}"
                               class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Hasil --}}
            <div class="mt-6">
                <p class="text-sm text-gray-600 mb-3">
                    Menampilkan {{ $daftarFasilitas->count() }} dari {{ $daftarFasilitas->total() }} fasilitas.
                </p>

                @if($daftarFasilitas->isEmpty())
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg text-gray-600">
                        Fasilitas tidak ditemukan. Coba ubah kata kunci atau filter.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($daftarFasilitas as $fasilitas)
                            <div class="bg-white p-5 shadow-sm sm:rounded-lg flex flex-col">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $fasilitas->nama_fasilitas }}
                                    </h3>

                                    @if($fasilitas->sedangAktif())
                                        <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Aktif</span>
                                    @elseif($fasilitas->dalamPerbaikan())
                                        <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-700">Dalam Perbaikan</span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">Nonaktif</span>
                                    @endif
                                </div>

                                <p class="mt-2 text-sm text-gray-600">
                                    {{ $fasilitas->tipe }} &middot; {{ $fasilitas->lokasi }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Kapasitas: {{ $fasilitas->kapasitas ?? '-' }} orang
                                </p>

                                @if($fasilitas->deskripsi)
                                    <p class="mt-2 text-sm text-gray-500">
                                        {{ Str::limit($fasilitas->deskripsi, 90) }}
                                    </p>
                                @endif

                                <div class="mt-auto pt-4">
                                    <a href="{{ route('fasilitas.show', $fasilitas) }}"
                                       class="text-sm text-blue-600 hover:underline">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $daftarFasilitas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
