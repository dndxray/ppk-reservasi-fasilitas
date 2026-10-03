<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Kelola Fasilitas</h2>
    </x-slot>

    @php
        $pesanSukses = session('success') ?? session('status');
    @endphp

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if ($pesanSukses)
            <div id="banner-sukses"
                 role="status"
                 class="mb-5 flex items-center justify-between gap-4 rounded-lg border border-[#166534] bg-[#DCFCE7] px-6 py-4 text-base font-semibold text-[#166534] transition-opacity duration-500">
                <span>{{ $pesanSukses }}</span>

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

            <form method="GET" class="flex flex-1 items-center gap-3">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari Nama/Lokasi..."
                       class="flex-1 min-w-0 px-4 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-[#511E1D]">

                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-[#5A2A27] text-white text-sm font-medium rounded-lg shadow-sm hover:bg-[#47201B] transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Filter
                </button>
            </form>

            <a href="{{ route('admin.facilities.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C0453F] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#A83B32] transition whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 9v6m3-3H9m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tambah Fasilitas
            </a>
        </div>

        {{-- kartu tabel utama --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            @if ($facilities->count() > 0)

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F5EBE9]">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">Fasilitas</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">Tipe</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">Kapasitas</th>
                                <th class="px-6 py-4 text-left font-bold text-[#A94438]">Status</th>
                                <th class="px-6 py-4 text-center font-bold text-[#A94438]">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($facilities as $facility)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- no --}}
                                    <td class="px-6 py-5 text-gray-700 font-medium">
                                        {{ $facilities->firstItem() + $loop->index }}.
                                    </td>

                                    {{-- nama & lokasi --}}
                                    <td class="px-6 py-5">
                                        <p class="font-medium text-gray-900">{{ $facility->nama_fasilitas }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $facility->lokasi }}</p>
                                    </td>

                                    {{-- tipe --}}
                                    <td class="px-6 py-5 text-gray-700 font-medium">
                                        {{ $facility->tipeLabel() }}
                                    </td>

                                    {{-- kapasitas --}}
                                    <td class="px-6 py-5 text-gray-700 font-medium">
                                        {{ $facility->kapasitas ?? '-' }}
                                    </td>

                                    {{-- status & warna --}}
                                    <td class="px-6 py-5">
                                        @php
                                            $statusText  = 'Nonaktif';
                                            $statusColor = 'text-gray-500';
                                            $dotColor    = 'bg-gray-400';

                                            if ($facility->status === 'aktif') {
                                                $statusText  = 'Tersedia';
                                                $statusColor = 'text-green-600';
                                                $dotColor    = 'bg-green-500';
                                            } elseif ($facility->status === 'dalam_perbaikan') {
                                                $statusText  = 'Dalam Perbaikan';
                                                $statusColor = 'text-yellow-600';
                                                $dotColor    = 'bg-yellow-500';
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                            <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                        </div>
                                    </td>

                                    {{-- tombol aksi: lihat detail (outline ghost) --}}
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('admin.facilities.show', $facility) }}"
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
                @include('partials.pagination-tabel', ['paginator' => $facilities])

            @else

                {{-- tampilan kalau datanya kosong / pencarian tidak cocok --}}
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F5EBE9] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h4m2-10h.01M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    @if (filled($search))
                        <h3 class="text-base font-semibold text-gray-800">Fasilitas Tidak Ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Tidak ada fasilitas yang sesuai dengan kata kunci pencarian Anda.
                        </p>
                        <a href="{{ route('admin.fasilitas.index') }}"
                           class="inline-flex mt-5 px-5 py-2.5 bg-[#4a1a24] text-white text-sm font-semibold rounded-lg hover:bg-[#3a141c] transition">
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base font-semibold text-gray-800">Belum Ada Fasilitas</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada fasilitas yang ditambahkan.
                        </p>
                    @endif
                </div>

            @endif
        </div>
    </div>
</x-app-layout>