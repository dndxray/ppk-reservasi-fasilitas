<x-app-layout>
    <div class="min-h-screen bg-[#F8F7F7]">

        {{-- header bar --}}
        <div class="flex items-center gap-4 bg-[#F5F0ED] px-6 sm:px-10 py-5">
            <a href="{{ route('admin.fasilitas.index') }}" class="text-[#47201B] hover:opacity-70 transition" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-[#47201B]">Detail Fasilitas</h1>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">

            @if (session('status'))
                <div class="bg-emerald-50 text-emerald-700 text-sm rounded-md px-4 py-2">
                    {{ session('status') }}
                </div>
            @endif

            {{-- kartu detail fasilitas --}}
            <div class="flex flex-col sm:flex-row gap-5 bg-[#F5F0ED] rounded-2xl p-5 sm:p-7">
                <img src="{{ $facility->foto_url }}" alt="{{ $facility->nama_fasilitas }}"
                     class="w-full sm:w-60 h-48 sm:h-44 rounded-xl object-cover flex-shrink-0">

                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $facility->nama_fasilitas }}</h2>

                            <div class="mt-2">
                                @if ($facility->status === 'aktif')
                                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-green-600">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>Tersedia
                                    </span>
                                @elseif ($facility->status === 'dalam_perbaikan')
                                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-yellow-600">
                                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>Dalam Perbaikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('admin.facilities.edit', $facility) }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C0453F] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#A83B32] transition whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2.414a2 2 0 01.586-1.414z" />
                            </svg>
                            Edit Fasilitas
                        </a>
                    </div>

                    <div class="mt-3 space-y-1 text-gray-600">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $facility->lokasi }}
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $facility->kapasitas ?? '-' }} Orang
                        </p>
                        <p class="text-xs text-gray-500">Tipe: {{ $facility->tipeLabel() }}</p>
                    </div>

                    <p class="mt-3 text-gray-900">
                        {{ $facility->deskripsi ?: 'Belum ada deskripsi.' }}
                    </p>
                </div>
            </div>

            {{-- riwayat reservasi fasilitas ini --}}
            <div>
                <h2 class="text-lg font-bold text-[#47201B] mb-3">Riwayat Reservasi</h2>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-[#F5EBE8]">
                                <tr>
                                    <th class="text-left px-6 py-5 font-bold text-[#A83B32]">No</th>
                                    <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Pemesan</th>
                                    <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Jadwal</th>
                                    <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($reservations as $reservation)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $reservations->firstItem() + $loop->index }}.
                                        </td>
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-gray-900">{{ $reservation->user?->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-400">{{ $reservation->user?->email }}</p>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <p class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ substr($reservation->waktu_mulai, 0, 5) }} - {{ substr($reservation->waktu_selesai, 0, 5) }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-5">
                                            @if ($reservation->status === 'menunggu')
                                                <span class="inline-flex items-center gap-2 font-semibold text-yellow-500">
                                                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>Menunggu
                                                </span>
                                            @elseif ($reservation->status === 'disetujui')
                                                <span class="inline-flex items-center gap-2 font-semibold text-green-500">
                                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>Disetujui
                                                </span>
                                            @elseif ($reservation->status === 'dibatalkan')
                                                <span class="inline-flex items-center gap-2 font-semibold text-gray-500">
                                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>Dibatalkan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-2 font-semibold text-red-500">
                                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>{{ ucfirst($reservation->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada reservasi untuk fasilitas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">{{ $reservations->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>