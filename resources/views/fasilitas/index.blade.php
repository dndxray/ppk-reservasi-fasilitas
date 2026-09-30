<x-app-layout>
    @php
        // Ada search / filter aktif? (cookie filter lama tidak dihitung, hanya input dari URL)
        $modeHasil = request()->filled('cari')
            || request()->filled('tipe')
            || request()->filled('lokasi')
            || request()->filled('kapasitas_minimal');
    @endphp

    <div class="py-8 px-6 lg:px-10" x-data="{ showFilter: false }">

        <h1 class="text-2xl font-bold text-gray-900">Reservasi Fasilitas</h1>
        <p class="text-[#B23A2E] text-sm mt-1 mb-6">Temukan Fasilitas yang ingin Anda reservasi.</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================= KOLOM KIRI ================= --}}
            <div class="lg:col-span-2">

                {{-- search --}}
                <form method="GET" action="{{ route('fasilitas.index') }}" class="mb-6">
                    <input type="hidden" name="tipe" value="{{ request('tipe') }}">
                    <input type="hidden" name="lokasi" value="{{ request('lokasi') }}">
                    <input type="hidden" name="kapasitas_minimal" value="{{ request('kapasitas_minimal') }}">
                    <input type="text" name="cari" value="{{ request('cari') }}"
                           placeholder="Cari Fasilitas..."
                           class="w-full px-4 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm focus:ring-2 focus:ring-[#511E1D]">
                </form>

                @if($daftarFasilitas->isEmpty())
                    <div class="bg-white p-6 shadow-sm rounded-lg text-gray-600">
                        Fasilitas tidak ditemukan. Coba ubah kata kunci atau filter.
                    </div>
                @else

                    @if($modeHasil)

                        {{-- ===== MODE HASIL: hanya hasil search / filter ===== --}}
                        <h2 class="font-semibold text-gray-800 mb-3">
                            Hasil Pencarian
                            @if(request()->filled('cari'))
                                "{{ request('cari') }}"
                            @endif
                            <span class="text-xs font-normal text-gray-500">({{ $daftarFasilitas->total() }} fasilitas)</span>
                        </h2>

                    @else

                        {{-- ===== MODE NORMAL: paling banyak direservasi ===== --}}
                        <h2 class="font-semibold text-gray-800 mb-3">Paling Banyak Direservasi</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                            @foreach($fasilitasPopuler as $fasilitas)
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                                    <div class="relative h-32">
                                        <img src="{{ asset('images/login-bg.jpg') }}" alt="{{ $fasilitas->nama_fasilitas }}"
                                             class="w-full h-full object-cover">
                                        <span class="absolute top-2 right-2 text-[10px] font-medium px-3 py-0.5 rounded-full
                                            @if($fasilitas->sedangAktif()) bg-emerald-200 text-emerald-800
                                            @elseif($fasilitas->dalamPerbaikan()) bg-yellow-200 text-yellow-800
                                            @else bg-gray-300 text-gray-700
                                            @endif">
                                            @if($fasilitas->sedangAktif()) Tersedia
                                            @elseif($fasilitas->dalamPerbaikan()) Diperbaiki
                                            @else Nonaktif
                                            @endif
                                        </span>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $fasilitas->nama_fasilitas }}</h3>
                                        <p class="text-xs text-gray-600 flex items-center gap-1 mt-1 mb-3">
                                            <svg class="w-3 h-3 text-[#B23A2E]" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $fasilitas->lokasi }}
                                        </p>
                                        <a href="{{ route('fasilitas.show', $fasilitas) }}"
                                           class="flex items-center justify-center gap-1 w-full text-xs font-medium py-1.5 bg-[#4a1a24] text-white rounded-md hover:bg-[#3a141c] transition">
                                            Lihat Detail →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <h2 class="font-semibold text-gray-800 mb-3">Fasilitas Lainnya</h2>

                    @endif

                    {{-- ===== DAFTAR FASILITAS (dipakai kedua mode) ===== --}}
                    <div class="space-y-3">
                        @foreach($daftarFasilitas as $fasilitas)
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-3 flex gap-3">
                                <img src="{{ asset('images/login-bg.jpg') }}" alt="{{ $fasilitas->nama_fasilitas }}"
                                     class="w-20 h-16 rounded-md object-cover flex-shrink-0">

                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $fasilitas->nama_fasilitas }}</h3>
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-[#B23A2E]" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $fasilitas->lokasi }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-medium px-3 py-0.5 rounded-full whitespace-nowrap
                                            @if($fasilitas->sedangAktif()) bg-emerald-200 text-emerald-800
                                            @elseif($fasilitas->dalamPerbaikan()) bg-yellow-200 text-yellow-800
                                            @else bg-gray-300 text-gray-700
                                            @endif">
                                            @if($fasilitas->sedangAktif()) Tersedia
                                            @elseif($fasilitas->dalamPerbaikan()) Diperbaiki
                                            @else Nonaktif
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex items-end justify-between gap-2 mt-2">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                {{ ucfirst(str_replace('_', ' ', $fasilitas->tipe)) }}
                                            </span>
                                            <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                Kapasitas {{ $fasilitas->kapasitas ?? '-' }} Orang
                                            </span>
                                            {{-- TODO: tag tambahan (AC, LCD, dll) kalau ada relasi/kolomnya --}}
                                        </div>
                                        <a href="{{ route('fasilitas.show', $fasilitas) }}"
                                           class="text-xs font-medium px-4 py-1 bg-[#4a1a24] text-white rounded-md hover:bg-[#3a141c] transition whitespace-nowrap">
                                            Lihat Detail →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $daftarFasilitas->links() }}
                    </div>
                @endif
            </div>

            {{-- ================= KOLOM KANAN ================= --}}
            <div>

                {{-- tombol filter + dropdown --}}
                <div class="relative mb-6">
                    <button type="button" @click="showFilter = !showFilter"
                            class="inline-flex items-center gap-2 px-5 py-3 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4h18M6 8h12M9 12h6M11 16h2" />
                        </svg>
                        Filter
                    </button>

                    <div x-show="showFilter" x-transition x-cloak @click.outside="showFilter = false"
                         class="absolute left-0 top-full mt-2 z-20 w-72 bg-white shadow-lg rounded-lg p-4">
                        <form method="GET" action="{{ route('fasilitas.index') }}" class="space-y-3">
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
                                    @foreach($daftarLokasi as $item)
                                        <option value="{{ $item }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Kapasitas Minimal</label>
                                <input type="number" name="kapasitas_minimal" value="{{ request('kapasitas_minimal') }}"
                                       min="1" placeholder="Contoh: 30" class="w-full rounded-lg border-gray-300 text-sm">
                            </div>

                            <div class="flex gap-2">
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

                {{-- riwayat reservasi --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="flex items-center gap-2 font-semibold text-gray-800 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Reservasi
                        </h2>
                        <a href="{{ Route::has('reservations.history') ? route('reservations.history') : '#' }}" class="text-[10px] font-medium text-[#B23A2E] hover:underline">Lihat Semua →</a>
                    </div>

                    <div class="space-y-3">
                        @forelse(($riwayatReservasi ?? collect()) as $reservasi)
                            @php
                                // "Selesai" = sudah disetujui dan waktunya sudah lewat
                                $sudahLewat = \Carbon\Carbon::parse($reservasi->tanggal . ' ' . $reservasi->waktu_selesai)->isPast();

                                if ($reservasi->status === 'disetujui' && $sudahLewat) {
                                    $labelStatus = 'Selesai';   $warnaStatus = 'bg-emerald-500';
                                } elseif ($reservasi->status === 'disetujui') {
                                    $labelStatus = 'Disetujui'; $warnaStatus = 'bg-blue-500';
                                } elseif ($reservasi->status === 'menunggu') {
                                    $labelStatus = 'Menunggu';  $warnaStatus = 'bg-yellow-500';
                                } elseif ($reservasi->status === 'dibatalkan') {
                                    $labelStatus = 'Dibatalkan'; $warnaStatus = 'bg-gray-400';
                                } else {
                                    $labelStatus = ucfirst($reservasi->status); $warnaStatus = 'bg-red-500';
                                }
                            @endphp
                            <a href="{{ route('reservations.show', $reservasi) }}"
                               class="border border-gray-200 rounded-lg p-2 flex gap-3 hover:bg-gray-50 transition">
                                <img src="{{ asset('images/login-bg.jpg') }}" alt=""
                                     class="w-14 h-12 rounded-md object-cover flex-shrink-0">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $reservasi->facility?->nama_fasilitas ?? 'Fasilitas dinonaktifkan' }}
                                    </p>
                                    <p class="text-[10px] text-[#B23A2E] mt-0.5">
                                        {{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('d M Y') }}
                                        &nbsp; {{ substr($reservasi->waktu_mulai, 0, 5) }} - {{ substr($reservasi->waktu_selesai, 0, 5) }}
                                    </p>
                                    <p class="text-[10px] text-gray-600 flex items-center gap-1 mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $warnaStatus }}"></span>
                                        {{ $labelStatus }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-gray-500">Belum ada riwayat reservasi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>