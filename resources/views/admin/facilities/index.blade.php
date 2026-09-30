<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Kelola Fasilitas</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('status'))
            <div class="bg-emerald-50 text-emerald-700 text-sm rounded-md px-4 py-2 mb-4">
                {{ session('status') }}
            </div>
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

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#F5EBE8]">
                        <tr>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Fasilitas</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Tipe</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Kapasitas</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Status</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($facilities as $facility)
                            {{-- baris bisa diklik -> halaman detail --}}
                            <tr class="hover:bg-slate-50 transition cursor-pointer"
                                onclick="window.location='{{ route('admin.facilities.show', $facility) }}'">
                                <td class="px-6 py-5">
                                    <p class="font-medium text-gray-900">{{ $facility->nama_fasilitas }}</p>
                                    <p class="text-xs text-gray-400">{{ $facility->lokasi }}</p>
                                </td>
                                <td class="px-6 py-5 text-gray-700">{{ $facility->tipeLabel() }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $facility->kapasitas ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    @if ($facility->status === 'aktif')
                                        <span class="inline-flex items-center gap-2 font-semibold text-green-500">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>Tersedia
                                        </span>
                                    @elseif ($facility->status === 'dalam_perbaikan')
                                        <span class="inline-flex items-center gap-2 font-semibold text-yellow-500">
                                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>Dalam Perbaikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 font-semibold text-gray-500">
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                {{-- sel aksi: klik tombol tidak ikut membuka detail --}}
                                <td class="px-6 py-5" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.facilities.edit', $facility) }}"
                                           class="px-3 py-2 rounded-lg bg-gray-100 text-slate-700 text-xs font-semibold hover:bg-gray-200 transition">
                                            Edit
                                        </a>

                                        @if ($facility->status !== 'nonaktif')
                                            <form action="{{ route('admin.facilities.deactivate', $facility) }}" method="POST"
                                                  onsubmit="return confirm('Nonaktifkan {{ $facility->nama_fasilitas }}?')">
                                                @csrf @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg bg-red-100 text-red-600 text-xs font-semibold hover:bg-red-200 transition">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.facilities.activate', $facility) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg bg-green-100 text-green-600 text-xs font-semibold hover:bg-green-200 transition">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $facilities->links() }}</div>
    </div>
</x-app-layout>