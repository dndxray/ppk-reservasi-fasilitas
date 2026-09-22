<x-app-layout>
    <div class="py-8 px-6 lg:px-10">

        <h1 class="text-2xl font-bold text-gray-900">Cari Fasilitas</h1>
        <p class="text-[#B23A2E] text-sm mt-1 mb-6">Temukan Fasilitas yang ingin Anda reservasi.</p>

        <!-- search + filter -->
        <div x-data="{ showFilter: false }" class="mb-8">
            <form method="GET" action="{{ route('fasilitas.index') }}" class="flex gap-3">
                <input type="text" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari Nama/Lokasi/..."
                       class="flex-1 px-4 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm focus:ring-2 focus:ring-[#511E1D]">

                <button type="button" @click="showFilter = !showFilter"
                        class="flex items-center gap-2 px-5 py-3 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4h18M6 8h12M9 12h6M11 16h2" />
                    </svg>
                    Filter
                </button>
            </form>

            
            <div x-show="showFilter" x-transition x-cloak
                 class="mt-4 bg-white shadow-sm rounded-lg p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <form method="GET" action="{{ route('fasilitas.index') }}" class="contents">
                    <input type="hidden" name="cari" value="{{ request('cari') }}">

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Tipe</label>
                        <select name="tipe" class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="">Semua Tipe</option>
                            @foreach($daftarTipe as $t)
                                <option value="{{ $t }}" @selected($tipe == $t)>
                                    {{ ucfirst(str_replace('_', ' ', $t)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                               list="daftarLokasi" placeholder="Contoh: Gedung A"
                               class="w-full rounded-lg border-gray-300 text-sm">
                        <datalist id="daftarLokasi">
                            @foreach($daftarLokasi as $lokasi)
                                <option value="{{ $lokasi }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Kapasitas Minimal</label>
                        <input type="number" name="kapasitas_minimal" value="{{ request('kapasitas_minimal') }}"
                               min="1" placeholder="Contoh: 30" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div class="sm:col-span-3 flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-[#4a1a24] text-white text-sm rounded-lg hover:bg-[#3a141c]">
                            Terapkan
                        </button>
                        <a href="{{ route('fasilitas.index') }}" class="px-4 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($daftarFasilitas->isEmpty())
            <div class="bg-white p-6 shadow-sm rounded-lg text-gray-600">
                Fasilitas tidak ditemukan. Coba ubah kata kunci atau filter.
            </div>
        @else

            <!-- paling banyak di reserv -->
            <h2 class="font-semibold text-gray-800 mb-3">Paling Banyak Direservasi</h2>
            <div class="flex gap-4 overflow-x-auto pb-4 mb-8">
                @foreach($daftarFasilitas->take(3) as $fasilitas)
                    <div class="min-w-[300px] bg-white rounded-xl shadow-sm overflow-hidden flex-shrink-0">
                        <div class="relative h-40">
                            <img src="{{ asset('images/login-bg.jpg') }}" alt="{{ $fasilitas->nama_fasilitas }}"
                                 class="w-full h-full object-cover">
                            <span class="absolute top-3 right-3 text-xs font-medium px-3 py-1 rounded-full
                                @if($fasilitas->sedangAktif()) bg-emerald-200 text-emerald-800
                                @elseif($fasilitas->dalamPerbaikan()) bg-yellow-200 text-yellow-800
                                @else bg-gray-300 text-gray-700
                                @endif">
                                @if($fasilitas->sedangAktif()) Tersedia
                                @elseif($fasilitas->dalamPerbaikan()) Diperbaiki
                                @else Nonaktif
                                @endif
                            </span>
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                <h3 class="text-white font-semibold">{{ $fasilitas->nama_fasilitas }}</h3>
                                <p class="text-white/80 text-xs flex items-center gap-1">
                                    📍 {{ $fasilitas->lokasi }}
                                </p>
                            </div>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Kapasitas {{ $fasilitas->kapasitas ?? '-' }} orang</span>
                            <a href="{{ route('fasilitas.show', $fasilitas) }}"
                               class="text-xs font-medium px-3 py-1.5 bg-[#4a1a24] text-white rounded-full hover:bg-[#3a141c]">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- daftar semua fasilitas -->
            <h2 class="font-semibold text-gray-800 mb-3">Semua Fasilitas</h2>
            <div class="space-y-4">
                @foreach($daftarFasilitas as $fasilitas)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden flex items-center">
                        <div class="flex-1 p-4">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-semibold text-gray-800">{{ $fasilitas->nama_fasilitas }}</h3>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                    @if($fasilitas->sedangAktif()) bg-emerald-100 text-emerald-700
                                    @elseif($fasilitas->dalamPerbaikan()) bg-yellow-100 text-yellow-700
                                    @else bg-gray-200 text-gray-700
                                    @endif">
                                    @if($fasilitas->sedangAktif()) Tersedia
                                    @elseif($fasilitas->dalamPerbaikan()) Diperbaiki
                                    @else Nonaktif
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                {{ $fasilitas->lokasi }} &middot; {{ ucfirst(str_replace('_',' ',$fasilitas->tipe)) }} &middot; Kapasitas {{ $fasilitas->kapasitas ?? '-' }} orang
                            </p>
                        </div>
                        <div class="w-40 h-28 flex-shrink-0 relative">
                            <img src="{{ asset('images/login-bg.jpg') }}" alt="{{ $fasilitas->nama_fasilitas }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <a href="{{ route('fasilitas.show', $fasilitas) }}"
                               class="text-xs font-medium px-3 py-1.5 bg-white border border-[#4a1a24] text-[#4a1a24] rounded-full hover:bg-[#4a1a24] hover:text-white transition whitespace-nowrap">
                                Lihat Detail →
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
</x-app-layout>