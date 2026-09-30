<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-4 sm:px-8 py-6 sm:py-8 max-w-7xl mx-auto space-y-6">

            {{-- Banner Sambutan Petugas --}}
            <div class="relative rounded-2xl overflow-hidden shadow-xs">
                <img src="{{ asset('images/login-bg.jpg') }}" alt="Kampus LOKA"
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-[#511E1D]/85 backdrop-blur-[1px]"></div>

                <div class="relative z-10 py-7 px-6 sm:px-8 flex flex-col justify-center">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight"
                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Selamat Datang, <span class="text-[#F4A896]">Petugas!</span>
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm font-normal mt-1.5">
                        Cari Fasilitas yang tersedia untuk direservasi disini!
                    </p>
                </div>
            </div>

            {{-- 2 Kartu Utama: Reservasi & Laporan Kerusakan --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-7">

                {{-- KARTU 1: RESERVASI --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition">
                    
                    <div>
                        {{-- Header Kartu --}}
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-[#511E1D] flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    <circle cx="16" cy="16" r="3" stroke-width="1.5" />
                                    <path stroke-linecap="round" stroke-width="1.5" d="M16 14.5v1.5l1 1" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-[#47201B] tracking-tight"
                                    style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                    Reservasi
                                </h2>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                                    Kelola reservasi yang masuk.
                                </p>
                            </div>
                        </div>

                        {{-- 3 Kotak Statistik --}}
                        <div class="grid grid-cols-3 gap-2.5 sm:gap-3.5 my-4">
                            
                            {{-- Reservasi diterima (Hijau) --}}
                            <a href="{{ route('petugas.reservations.queue', ['status' => 'disetujui']) }}"
                               class="bg-[#DCEEE2] rounded-xl p-3 sm:p-4 flex flex-col justify-center transition hover:bg-[#cde4d4] hover:shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#247D4C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 16l2 2 4-4" />
                                    </svg>
                                    <span class="text-lg sm:text-xl font-bold text-[#247D4C] leading-none">
                                        {{ $reservasiDiterima ?? 0 }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#247D4C] font-medium mt-1.5 leading-tight">
                                    Reservasi diterima
                                </p>
                            </a>

                            {{-- Reservasi menunggu (Kuning / Amber) --}}
                            <a href="{{ route('petugas.reservations.queue', ['status' => 'menunggu']) }}"
                               class="bg-[#FCF2DB] rounded-xl p-3 sm:p-4 flex flex-col justify-center transition hover:bg-[#fae8c4] hover:shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#B7791F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        <circle cx="15.5" cy="15.5" r="2.5" stroke-width="1.8" />
                                        <path stroke-linecap="round" stroke-width="1.6" d="M15.5 14.5v1l.7.7" />
                                    </svg>
                                    <span class="text-lg sm:text-xl font-bold text-[#B7791F] leading-none">
                                        {{ $reservasiMenunggu ?? 0 }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#B7791F] font-medium mt-1.5 leading-tight">
                                    Reservasi menunggu
                                </p>
                            </a>

                            {{-- Reservasi ditolak (Merah / Pink) --}}
                            <a href="{{ route('petugas.reservations.queue', ['status' => 'ditolak']) }}"
                               class="bg-[#FADBD9] rounded-xl p-3 sm:p-4 flex flex-col justify-center transition hover:bg-[#f8c9c6] hover:shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#A62B2B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13.5 13.5l3.5 3.5m0-3.5l-3.5 3.5" />
                                    </svg>
                                    <span class="text-lg sm:text-xl font-bold text-[#A62B2B] leading-none">
                                        {{ $reservasiDitolak ?? 0 }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#A62B2B] font-medium mt-1.5 leading-tight">
                                    Reservasi ditolak
                                </p>
                            </a>

                        </div>
                    </div>

                    {{-- Tombol Lihat Semua --}}
                    <div class="flex justify-end pt-3">
                        <a href="{{ route('petugas.reservations.queue') }}"
                           class="px-6 py-2.5 bg-[#BA3D34] hover:bg-[#9E3129] active:bg-[#782c23] text-white text-sm font-semibold rounded-xl transition duration-150 shadow-xs hover:shadow text-center">
                            Lihat Semua
                        </a>
                    </div>

                </div>

                {{-- KARTU 2: LAPORAN KERUSAKAN --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition">
                    
                    <div>
                        {{-- Header Kartu --}}
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-[#511E1D] flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-[#47201B] tracking-tight"
                                    style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                    Laporan Kerusakan
                                </h2>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                                    Kelola data dan akses pengguna sistem.
                                </p>
                            </div>
                        </div>

                        {{-- 3 Kotak Statistik --}}
                        <div class="grid grid-cols-3 gap-2.5 sm:gap-3.5 my-4">
                            
                            {{-- Laporan selesai (Hijau) --}}
                            <a href="{{ route('reports.antrian', ['status' => 'selesai']) }}"
                               class="bg-[#DCEEE2] rounded-xl p-3 sm:p-4 flex flex-col justify-center transition hover:bg-[#cde4d4] hover:shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#247D4C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M9 12h6m-6 4h4m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 16l1.5 1.5 3-3" />
                                    </svg>
                                    <span class="text-lg sm:text-xl font-bold text-[#247D4C] leading-none">
                                        {{ $laporanSelesai ?? 0 }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#247D4C] font-medium mt-1.5 leading-tight">
                                    Laporan selesai
                                </p>
                            </a>

                            {{-- Laporan diproses (Kuning / Amber) --}}
                            <a href="{{ route('reports.antrian', ['status' => 'diproses']) }}"
                               class="bg-[#FCF2DB] rounded-xl p-3 sm:p-4 flex flex-col justify-center transition hover:bg-[#fae8c4] hover:shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#B7791F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M9 12h6m-6 4h3m3 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        <circle cx="16" cy="16" r="2.5" stroke-width="1.8" />
                                        <path stroke-linecap="round" stroke-width="1.6" d="M16 14.8v1.2l.8.8" />
                                    </svg>
                                    <span class="text-lg sm:text-xl font-bold text-[#B7791F] leading-none">
                                        {{ $laporanDiproses ?? 0 }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#B7791F] font-medium mt-1.5 leading-tight">
                                    Laporan diproses
                                </p>
                            </a>

                            {{-- Laporan ditolak (Merah / Pink) --}}
                            <a href="{{ route('reports.antrian', ['status' => 'ditolak']) }}"
                               class="bg-[#FADBD9] rounded-xl p-3 sm:p-4 flex flex-col justify-center transition hover:bg-[#f8c9c6] hover:shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#A62B2B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M9 12h6m-6 4h3m3 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        <rect x="13.5" y="13.5" width="5" height="5" rx="1" stroke-width="1.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.5 14.5l3 3m0-3l-3 3" />
                                    </svg>
                                    <span class="text-lg sm:text-xl font-bold text-[#A62B2B] leading-none">
                                        {{ $laporanDitolak ?? 0 }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#A62B2B] font-medium mt-1.5 leading-tight">
                                    Laporan ditolak
                                </p>
                            </a>

                        </div>
                    </div>

                    {{-- Tombol Lihat Semua --}}
                    <div class="flex justify-end pt-3">
                        <a href="{{ route('reports.antrian') }}"
                           class="px-6 py-2.5 bg-[#BA3D34] hover:bg-[#9E3129] active:bg-[#782c23] text-white text-sm font-semibold rounded-xl transition duration-150 shadow-xs hover:shadow text-center">
                            Lihat Semua
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
