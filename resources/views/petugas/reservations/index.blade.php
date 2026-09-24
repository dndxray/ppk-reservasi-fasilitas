<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-4 sm:px-8 py-6 sm:py-10 max-w-7xl mx-auto">

            {{-- tombol back & judul --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('petugas.dashboard') }}" class="text-[#47201B] hover:text-[#CA734D] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-[#47201B]">
                            Daftar Reservasi
                        </h1>
                    </div>
                </div>
            </div>

            {{-- search & filter --}}
            <form method="GET" action="{{ route('petugas.reservations.index') }}" class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-full sm:flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pemesan atau fasilitas..." class="w-full bg-[#F5EBE9] border-0 rounded-xl px-5 py-3.5 text-sm focus:ring-[#A94438] placeholder-gray-500">
                </div>
                
                <div class="w-full sm:w-52">
                    <select name="status" class="w-full bg-[#F5EBE9] border-0 rounded-xl px-4 py-3.5 text-sm focus:ring-[#A94438] text-gray-700 font-medium cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="menunggu" @selected(request('status')=='menunggu')>Menunggu</option>
                        <option value="disetujui" @selected(request('status')=='disetujui')>Disetujui</option>
                        <option value="ditolak" @selected(request('status')=='ditolak')>Ditolak</option>
                        <option value="dibatalkan" @selected(request('status')=='dibatalkan')>Dibatalkan</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-initial bg-[#511E1D] hover:bg-[#3B1514] transition text-white px-8 py-3.5 rounded-xl font-medium flex items-center justify-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    @if(request('search') || request('status'))
                        <a href="{{ route('petugas.reservations.index') }}" class="px-4 py-3.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium text-sm flex items-center justify-center transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- kartu tabel utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                @if($reservations->count() > 0)

                    {{-- MOBILE LIST (CARD) --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($reservations as $reservation)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <p class="font-bold text-gray-900 text-base">
                                            {{ $reservation->facility->nama_fasilitas ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $reservation->facility->lokasi ?? '-' }}
                                        </p>
                                    </div>
                                    @php
                                        $statusText = 'Menunggu';
                                        $statusColor = 'text-yellow-600';
                                        $dotColor = 'bg-yellow-500';

                                        if ($reservation->status === 'disetujui') {
                                            $statusText = 'Disetujui';
                                            $statusColor = 'text-green-600';
                                            $dotColor = 'bg-green-500';
                                        } elseif ($reservation->status === 'ditolak') {
                                            $statusText = 'Ditolak';
                                            $statusColor = 'text-red-600';
                                            $dotColor = 'bg-red-500';
                                        } elseif ($reservation->status === 'dibatalkan') {
                                            $statusText = 'Dibatalkan';
                                            $statusColor = 'text-gray-500';
                                            $dotColor = 'bg-gray-400';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-1.5 shrink-0 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                                        <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                        <span class="text-xs font-semibold {{ $statusColor }}">{{ $statusText }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-600 space-y-1 bg-[#F5EBE9]/40 p-3 rounded-xl border border-[#F5EBE9]">
                                    <p><span class="font-semibold text-gray-700">Pemesan:</span> {{ $reservation->user->name ?? '-' }} ({{ $reservation->user->email ?? '-' }})</p>
                                    <p><span class="font-semibold text-gray-700">Tanggal & Waktu:</span> {{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }} ({{ substr($reservation->waktu_mulai, 0, 5) }} - {{ substr($reservation->waktu_selesai, 0, 5) }})</p>
                                    <p><span class="font-semibold text-gray-700">Diajukan:</span> {{ $reservation->created_at?->translatedFormat('d M Y H:i') }} WIB</p>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-1">
                                    @if($reservation->status === 'menunggu')
                                        <form method="POST" action="{{ route('petugas.reservations.approve', $reservation->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded bg-green-100 text-green-600 hover:bg-green-200 transition" title="Setujui Reservasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('petugas.reservations.reject', $reservation->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded bg-red-100 text-red-600 hover:bg-red-200 transition" title="Tolak Reservasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('petugas.reservations.show', $reservation->id) }}" class="w-8 h-8 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 transition" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- DESKTOP TABLE --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-[#F5EBE9]">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Nama Fasilitas</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Nama Pemesan</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Waktu Penggunaan</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Tanggal Diajukan</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Status</th>
                                    <th class="px-6 py-4 text-center font-bold text-[#A94438]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($reservations as $index => $reservation)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            {{ $index + 1 }}.
                                        </td>
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-gray-900">
                                                {{ $reservation->facility->nama_fasilitas ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $reservation->facility->lokasi ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-gray-900">
                                                {{ $reservation->user->name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $reservation->user->email ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            <p class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ substr($reservation->waktu_mulai, 0, 5) }} - {{ substr($reservation->waktu_selesai, 0, 5) }} WIB
                                            </p>
                                        </td>
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            <p class="font-medium text-gray-900">
                                                {{ $reservation->created_at?->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $reservation->created_at?->format('H:i') }} WIB
                                            </p>
                                        </td>
                                        <td class="px-6 py-5">
                                            @php
                                                $statusText = 'Menunggu';
                                                $statusColor = 'text-yellow-600';
                                                $dotColor = 'bg-yellow-500';

                                                if ($reservation->status === 'disetujui') {
                                                    $statusText = 'Disetujui';
                                                    $statusColor = 'text-green-600';
                                                    $dotColor = 'bg-green-500';
                                                } elseif ($reservation->status === 'ditolak') {
                                                    $statusText = 'Ditolak';
                                                    $statusColor = 'text-red-600';
                                                    $dotColor = 'bg-red-500';
                                                } elseif ($reservation->status === 'dibatalkan') {
                                                    $statusText = 'Dibatalkan';
                                                    $statusColor = 'text-gray-500';
                                                    $dotColor = 'bg-gray-400';
                                                }
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                                <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($reservation->status === 'menunggu')
                                                    <form method="POST" action="{{ route('petugas.reservations.approve', $reservation->id) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded bg-green-100 text-green-600 hover:bg-green-200 transition" title="Setujui Reservasi">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('petugas.reservations.reject', $reservation->id) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded bg-red-100 text-red-600 hover:bg-red-200 transition" title="Tolak Reservasi">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('petugas.reservations.show', $reservation->id) }}" class="w-8 h-8 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 transition" title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else

                    {{-- KOSONG --}}
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F5EBE9] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">
                            Belum Ada Reservasi
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada data reservasi fasilitas yang ditemukan.
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>