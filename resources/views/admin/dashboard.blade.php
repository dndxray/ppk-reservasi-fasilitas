<x-app-layout>

    @php
        // ---- Data dari controller (semua punya nilai default supaya halaman tidak error) ----
        $fasilitasStatus  = $fasilitasStatus ?? ['aktif' => 0, 'nonaktif' => 0];
        $jumlahTersedia   = (int) ($fasilitasStatus['aktif'] ?? 0);
        $jumlahNonaktif   = (int) ($fasilitasStatus['nonaktif'] ?? 0);
        $totalStatus      = max(1, $jumlahTersedia + $jumlahNonaktif);

        $fasilitasTerpopuler = collect($fasilitasTerpopuler ?? []);
        $maksPopuler         = max(1, (int) $fasilitasTerpopuler->max('reservations_count'));
    @endphp

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-4 sm:px-8 py-6 sm:py-8 max-w-7xl mx-auto space-y-6">

            {{-- Banner Sambutan Admin (sama dengan dashboard petugas) --}}
            <div class="relative rounded-2xl overflow-hidden shadow-xs">
                <img src="{{ asset('images/login-bg.jpg') }}" alt="Kampus LOKA"
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-[#511E1D]/85 backdrop-blur-[1px]"></div>

                <div class="relative z-10 py-7 px-6 sm:px-8 flex flex-col justify-center">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight"
                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Selamat Datang, <span class="text-[#F4A896]">Admin!</span>
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm font-normal mt-1.5">
                        Pantau pengguna, petugas, dan fasilitas kampus dalam satu tempat.
                    </p>
                </div>
            </div>

            {{-- 4 Kartu Ringkasan --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

                {{-- Pengguna --}}
                <a href="{{ route('admin.pengguna.index') }}"
                   class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-xl bg-[#DCEEE2] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#247D4C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-[#47201B] leading-none">{{ $totalPengguna ?? 0 }}</p>
                    <p class="mt-1.5 text-sm text-gray-500">Total pengguna</p>
                    <p class="mt-3 text-xs font-semibold text-[#BA3D34] opacity-0 group-hover:opacity-100 transition">Kelola pengguna &rarr;</p>
                </a>

                {{-- Petugas --}}
                <a href="{{ route('admin.petugas.index') }}"
                   class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-xl bg-[#FCF2DB] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#B7791F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-[#47201B] leading-none">{{ $totalPetugas ?? 0 }}</p>
                    <p class="mt-1.5 text-sm text-gray-500">Total petugas</p>
                    <p class="mt-3 text-xs font-semibold text-[#BA3D34] opacity-0 group-hover:opacity-100 transition">Kelola petugas &rarr;</p>
                </a>

                {{-- Fasilitas --}}
                <a href="{{ route('admin.fasilitas.index') }}"
                   class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-xl bg-[#FADBD9] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#A62B2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-[#47201B] leading-none">{{ $totalFasilitas ?? 0 }}</p>
                    <p class="mt-1.5 text-sm text-gray-500">Total fasilitas</p>
                    <p class="mt-3 text-xs font-semibold text-[#BA3D34] opacity-0 group-hover:opacity-100 transition">Kelola fasilitas &rarr;</p>
                </a>

                {{-- Reservasi bulan ini (kartu gelap sebagai aksen) --}}
                <a href="{{ route('admin.rekap.index') }}"
                   class="group bg-[#511E1D] rounded-2xl shadow-sm p-5 hover:bg-[#431816] transition">
                    <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-white leading-none">{{ $reservasiBulanIni ?? 0 }}</p>
                    <p class="mt-1.5 text-sm text-white/70">Reservasi bulan ini</p>
                    <p class="mt-3 text-xs font-semibold text-[#F4A896] opacity-0 group-hover:opacity-100 transition">Lihat rekap &rarr;</p>
                </a>
            </div>

            {{-- Aksi cepat (dipindah ke atas, tepat di bawah kartu ringkasan) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-[#47201B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Aksi Cepat
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Tambah data dengan sekali klik</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach ([
                        ['Tambah Pengguna',  route('admin.pengguna.create')],
                        ['Tambah Petugas',   route('admin.petugas.create')],
                        ['Tambah Fasilitas', route('admin.facilities.create')],
                    ] as [$label, $url])
                        <a href="{{ $url }}"
                           class="group flex items-center justify-between rounded-xl border border-[#A94438]/30 px-4 py-3 text-sm font-semibold text-[#A94438] hover:bg-[#A94438] hover:text-white transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v6m3-3H9m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $label }}
                            </span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Kondisi fasilitas + Terpopuler --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Kondisi fasilitas: tersedia & nonaktif --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-[#47201B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Kondisi Fasilitas
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5 mb-5">Perbandingan fasilitas tersedia dan nonaktif</p>

                    <div class="flex h-3 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="bg-green-500" style="width: {{ $jumlahTersedia / $totalStatus * 100 }}%"></div>
                        <div class="bg-gray-400"  style="width: {{ $jumlahNonaktif / $totalStatus * 100 }}%"></div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div class="rounded-xl bg-[#DCEEE2] px-4 py-4">
                            <p class="flex items-center gap-2 text-sm text-[#247D4C]">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>Tersedia
                            </p>
                            <p class="mt-1 text-2xl font-extrabold text-[#247D4C]">{{ $jumlahTersedia }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-100 px-4 py-4">
                            <p class="flex items-center gap-2 text-sm text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>Nonaktif
                            </p>
                            <p class="mt-1 text-2xl font-extrabold text-gray-700">{{ $jumlahNonaktif }}</p>
                        </div>
                    </div>
                </div>

                {{-- Fasilitas terpopuler --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-[#47201B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Fasilitas Terpopuler
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5 mb-5">Berdasarkan jumlah reservasi</p>

                    @forelse ($fasilitasTerpopuler as $f)
                        <a href="{{ route('admin.facilities.show', $f) }}" class="block {{ $loop->last ? '' : 'mb-4' }} group">
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="flex items-center gap-2 min-w-0">
                                    <span class="shrink-0 w-5 h-5 rounded-md bg-[#F5EBE9] text-[#A94438] text-[11px] font-bold flex items-center justify-center">{{ $loop->iteration }}</span>
                                    <span class="truncate font-medium text-gray-800 group-hover:text-[#BA3D34] transition">{{ $f->nama_fasilitas }}</span>
                                </span>
                                <span class="shrink-0 text-xs font-semibold text-gray-500">{{ $f->reservations_count }}x</span>
                            </div>
                            <div class="mt-2 h-1.5 w-full rounded-full bg-gray-100">
                                <div class="h-1.5 rounded-full bg-[#BA3D34]" style="width: {{ $f->reservations_count / $maksPopuler * 100 }}%"></div>
                            </div>
                        </a>
                    @empty
                        <p class="py-6 text-center text-sm text-gray-500">Belum ada data reservasi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Pendaftar terbaru (lebar penuh) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 pt-6 pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[#47201B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                            Pendaftar Terbaru
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Akun pengguna yang baru dibuat</p>
                    </div>
                    <a href="{{ route('admin.pengguna.index') }}" class="text-xs font-semibold text-[#BA3D34] hover:underline">Lihat semua</a>
                </div>

                <div class="divide-y divide-gray-100 border-t border-gray-100">
                    @forelse (($penggunaTerbaru ?? []) as $u)
                        @php
                            $statusText  = 'Menunggu';
                            $statusColor = 'text-yellow-600';
                            $dotColor    = 'bg-yellow-500';

                            if ($u->status_verifikasi === 'terverifikasi') {
                                $statusText = 'Aktif'; $statusColor = 'text-green-600'; $dotColor = 'bg-green-500';
                            } elseif ($u->status_verifikasi === 'nonaktif') {
                                $statusText = 'Nonaktif'; $statusColor = 'text-gray-500'; $dotColor = 'bg-gray-400';
                            } elseif ($u->status_verifikasi === 'ditolak') {
                                $statusText = 'Ditolak'; $statusColor = 'text-red-600'; $dotColor = 'bg-red-500';
                            }
                        @endphp
                        <a href="{{ route('admin.pengguna.show', $u) }}"
                           class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 shrink-0 rounded-full bg-[#B83C30] text-white text-sm font-bold flex items-center justify-center">
                                {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                            </div>

                            <p class="hidden sm:block w-36 shrink-0 text-sm text-gray-500">
                                {{ $u->created_at?->locale('id')->translatedFormat('d M Y') }}
                            </p>

                            <div class="flex w-28 shrink-0 items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                            </div>

                            <svg class="hidden sm:block w-4 h-4 shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-gray-500">Belum ada pengguna.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</x-app-layout>