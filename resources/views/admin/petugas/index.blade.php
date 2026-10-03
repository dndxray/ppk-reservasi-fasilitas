<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Kelola Petugas
        </h2>
    </x-slot>

    @php
        $jumlahFilter = collect([$status, $tanggal])->filter()->count();
    @endphp

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('success'))
            <div id="banner-sukses"
                 role="status"
                 class="mb-5 flex items-center justify-between gap-4 rounded-lg border border-[#166534] bg-[#DCFCE7] px-6 py-4 text-base font-semibold text-[#166534] transition-opacity duration-500">
                <span>{{ session('success') }}</span>

                <button type="button"
                        onclick="document.getElementById('banner-sukses').remove()"
                        class="text-[#166534] hover:text-[#14532D]"
                        aria-label="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <script>
                // Hilang otomatis setelah 4 detik
                setTimeout(function () {
                    const el = document.getElementById('banner-sukses');
                    if (!el) return;
                    el.classList.add('opacity-0');
                    setTimeout(function () { el.remove(); }, 500);
                }, 4000);
            </script>
        @endif

        {{-- search + filter + tambah --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">

            <form method="GET" action="{{ route('admin.petugas.index') }}" class="flex flex-1 items-center gap-3">

                {{-- Search --}}
                <div class="relative flex-1 min-w-0">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama, email, no. telepon, status..."
                           autocomplete="off"
                           class="w-full pl-4 pr-12 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-[#511E1D]">

                    <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#5A2A27] transition"
                            aria-label="Cari">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                {{-- Filter + popup --}}
                <div class="relative">
                    <button type="button"
                            id="btn-filter"
                            aria-haspopup="true"
                            aria-expanded="false"
                            class="inline-flex items-center gap-2 px-5 py-3 bg-[#5A2A27] text-white text-sm font-medium rounded-lg shadow-sm hover:bg-[#47201B] transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter

                        @if ($jumlahFilter > 0)
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white text-xs font-bold text-[#5A2A27]">
                                {{ $jumlahFilter }}
                            </span>
                        @endif
                    </button>

                    <div id="panel-filter"
                         class="hidden absolute right-0 z-20 mt-2 w-72 sm:w-80 rounded-2xl border border-gray-100 bg-white p-5 shadow-xl">

                        <div class="mb-4">
                            <label for="filter-status" class="block text-base text-gray-600 mb-2">
                                Status Akun
                            </label>

                            <select id="filter-status"
                                    name="status"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-base text-gray-900 focus:border-[#5A2A27] focus:ring-[#5A2A27]">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label for="filter-tanggal" class="block text-base text-gray-600 mb-2">
                                Tanggal Bergabung
                            </label>

                            <input type="date"
                                   id="filter-tanggal"
                                   name="tanggal"
                                   value="{{ $tanggal }}"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3 text-base text-gray-900 focus:border-[#5A2A27] focus:ring-[#5A2A27]">
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="rounded-xl bg-[#4A1A20] px-6 py-3 text-base font-medium text-white hover:bg-[#35121A] transition">
                                Terapkan
                            </button>

                            {{-- Reset hanya menghapus filter, kata kunci pencarian tetap dipertahankan --}}
                            <a href="{{ route('admin.petugas.index', array_filter(['search' => $search])) }}"
                               class="rounded-xl border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <a href="{{ route('admin.petugas.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C0453F] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#A83B32] transition whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 9v6m3-3H9m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tambah Petugas
            </a>
        </div>

        {{-- Keterangan hasil pencarian / filter --}}
        @if ($search !== '' || $jumlahFilter > 0)
            <p class="mb-4 text-base font-semibold text-gray-900">
                @if ($search !== '')
                    Hasil pencarian untuk "<span class="text-[#A83B32]">{{ $search }}</span>"
                @else
                    Hasil filter
                @endif
                <span class="text-sm font-normal text-gray-500">({{ $staff->total() }} petugas)</span>
            </p>
        @endif

        {{-- kartu tabel utama --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            @if ($staff->count() > 0)

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F5EBE9]">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">Petugas</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">No. Telepon</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">Status</th>
                                <th class="px-6 py-4 text-center font-bold text-[#A94438]">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($staff as $user)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- no --}}
                                    <td class="px-6 py-5 text-gray-700 font-medium">
                                        {{ $staff->firstItem() + $loop->index }}.
                                    </td>

                                    {{-- nama & email --}}
                                    <td class="px-6 py-5">
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                                    </td>

                                    {{-- no. telepon --}}
                                    <td class="px-6 py-5 text-gray-700 font-medium">
                                        {{ $user->no_telepon ?? '-' }}
                                    </td>

                                    {{-- status & warna --}}
                                    <td class="px-6 py-5">
                                        @php
                                            $statusText  = 'Menunggu';
                                            $statusColor = 'text-yellow-600';
                                            $dotColor    = 'bg-yellow-500';

                                            if ($user->status_verifikasi === 'terverifikasi') {
                                                $statusText  = 'Aktif';
                                                $statusColor = 'text-green-600';
                                                $dotColor    = 'bg-green-500';
                                            } elseif ($user->status_verifikasi === 'nonaktif') {
                                                $statusText  = 'Nonaktif';
                                                $statusColor = 'text-gray-500';
                                                $dotColor    = 'bg-gray-400';
                                            } elseif ($user->status_verifikasi === 'ditolak') {
                                                $statusText  = 'Ditolak';
                                                $statusColor = 'text-red-600';
                                                $dotColor    = 'bg-red-500';
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                            <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                        </div>
                                    </td>

                                    {{-- tombol aksi: lihat detail (outline ghost) --}}
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('admin.petugas.show', $user) }}"
                                           class="group inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-[#A94438] text-[#A94438] hover:bg-[#A94438] hover:text-white active:bg-[#8F352B] active:border-[#8F352B] text-xs font-semibold rounded-xl shadow-xs hover:shadow transition duration-150 ease-in-out whitespace-nowrap">
                                            <span>Lihat Detail</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-150 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- jumlah data + nomor halaman --}}
                @include('partials.pagination-tabel', ['paginator' => $staff])

            @else

                {{-- tampilan kalau datanya kosong / filter tidak cocok --}}
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F5EBE9] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h4m2-10h.01M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    @if ($search !== '' || $jumlahFilter > 0)
                        <h3 class="text-base font-semibold text-gray-800">Petugas Tidak Ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Tidak ada petugas yang sesuai dengan kriteria pencarian atau filter Anda.
                        </p>
                        <a href="{{ route('admin.petugas.index') }}"
                           class="inline-flex mt-5 px-5 py-2.5 bg-[#4a1a24] text-white text-sm font-semibold rounded-lg hover:bg-[#3a141c] transition">
                            Reset Filter
                        </a>
                    @else
                        <h3 class="text-base font-semibold text-gray-800">Belum Ada Petugas</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada petugas yang terdaftar.
                        </p>
                    @endif
                </div>

            @endif
        </div>

    </div>

    <script>
        (function () {
            const btn   = document.getElementById('btn-filter');
            const panel = document.getElementById('panel-filter');

            function tutup() {
                panel.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const terbuka = !panel.classList.contains('hidden');
                if (terbuka) { tutup(); return; }
                panel.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            });

            // Klik di luar popup = tutup
            document.addEventListener('click', function (e) {
                if (!panel.contains(e.target) && !btn.contains(e.target)) tutup();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') tutup();
            });
        })();
    </script>
</x-app-layout>