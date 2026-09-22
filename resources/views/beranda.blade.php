<x-app-layout>
    <div class="py-8 px-6 lg:px-10">
        <!-- search bar -->
        <div class="relative rounded-2xl overflow-hidden mb-6">
            <img src="{{ asset('images/login-bg.jpg') }}" alt="Kampus"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-[#511E1D] opacity-75"></div>

            <div class="relative z-10 p-8">
                <h1 class="text-3xl font-bold text-white mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                @auth
                    Selamat Datang, <span class="text-[#F4A896]">{{ Auth::user()->name }}!</span>
                    @else
                        Selamat Datang!
                    @endauth
                </h1>
                <p class="text-white/80 mb-6">Cari Fasilitas yang tersedia untuk direservasi disini!</p>
                <form method="GET" action="{{ route('fasilitas.index') }}"
                      class="flex flex-col sm:flex-row gap-3">

                    <div class="flex-1 flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2.5">
                        <svg class="w-4 h-4 text-white/70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <input type="text" name="lokasi" placeholder="Lokasi/Nama Fasilitas"
                               style="background-color: transparent;"
                               class="w-full border-0 focus:ring-0 text-sm p-0 text-white placeholder-white/70">
                    </div>

                    <div class="flex-1 flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2.5">
                        <svg class="w-4 h-4 text-white/70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <input type="text" placeholder="Tanggal dan Jam Reservasi" disabled
                               style="background-color: transparent;"
                               class="w-full border-0 focus:ring-0 text-sm p-0 text-white placeholder-white/70">
                    </div>

                    <div class="flex-1 flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2.5">
                        <svg class="w-4 h-4 text-white/70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input type="number" name="kapasitas_minimal" placeholder="Kapasitas Orang"
                               style="background-color: transparent;"
                               class="w-full border-0 focus:ring-0 text-sm p-0 text-white placeholder-white/70">
                    </div>

                    <button type="submit"
                            class="px-5 py-2.5 bg-[#B23A2E] text-white rounded-lg hover:bg-[#9a3025] transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        
        @guest
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-4 bg-white border border-gray-200 shadow-sm rounded-2xl px-5 py-3 mb-6 hover:bg-gray-50 transition">

                <svg class="w-9 h-9 flex-shrink-0 text-[#B23A2E]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2" />
                    <line x1="12" y1="8" x2="12" y2="12" stroke-linecap="round" />
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-linecap="round" />
                </svg>

                <div>
                    <div class="font-bold text-gray-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Anda belum login
                    </div>
                    <div class="text-sm text-[#B23A2E]">Login lebih dulu untuk melakukan reservasi</div>
                </div>

                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @endguest
        @auth
            @if(Auth::user()->isPengguna() && ($aktivitasReservasi || $aktivitasLaporan))
                <h2 class="font-semibold text-gray-800 mb-3">Aktivitas Terbaru</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

                    @if($aktivitasReservasi)
                        <a href="{{ route('reservations.show', $aktivitasReservasi) }}"
                           class="flex items-center justify-between bg-white shadow-sm rounded-xl p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">
                                        @if($aktivitasReservasi->status === 'disetujui') Reservasi fasilitas diverifikasi!
                                        @elseif($aktivitasReservasi->status === 'ditolak') Reservasi fasilitas ditolak
                                        @elseif($aktivitasReservasi->status === 'dibatalkan') Reservasi dibatalkan
                                        @else Reservasi menunggu diproses
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">Lihat detail reservasi Anda</div>
                                </div>
                            </div>
                            <span class="text-gray-400">›</span>
                        </a>
                    @endif

                    @if($aktivitasLaporan)
                        <a href="{{ route('reports.show', $aktivitasLaporan) }}"
                           class="flex items-center justify-between bg-white shadow-sm rounded-xl p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">
                                        @if($aktivitasLaporan->status === 'selesai') Laporan telah selesai ditangani
                                        @elseif($aktivitasLaporan->status === 'ditolak') Laporan ditolak
                                        @elseif($aktivitasLaporan->status === 'diproses') Laporan sedang diproses
                                        @else Laporan baru menunggu diproses
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">Lihat detail laporan Anda</div>
                                </div>
                            </div>
                            <span class="text-gray-400">›</span>
                        </a>
                    @endif

                </div>
            @endif
        @endauth
        <!--  Fasilitas paling banyak direservasi -->
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-semibold text-gray-800" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                Fasilitas Paling Banyak Direservasi
            </h2>
            <a href="{{ route('fasilitas.index') }}" class="text-sm text-[#B23A2E] font-medium hover:underline">
                Lihat Semua
            </a>
        </div>

        @if($daftarFasilitas->isEmpty())
            <div class="bg-white p-6 shadow-sm rounded-lg text-gray-600">
                Belum ada data fasilitas.
            </div>
        @else
            <div class="flex gap-4 overflow-x-auto pb-4">
                @foreach($daftarFasilitas as $fasilitas)
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
                                <h3 class="text-white font-semibold" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                    {{ $fasilitas->nama_fasilitas }}
                                </h3>
                                <p class="text-white/80 text-xs flex items-center gap-1">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $fasilitas->lokasi }}
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
        @endif

    </div>
</x-app-layout>