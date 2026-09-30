<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]" x-data="{ showFilter: false }">

        <div class="px-4 sm:px-8 py-6 sm:py-10 max-w-7xl mx-auto">

            {{-- tombol back & judul --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('fasilitas.index') }}" class="text-[#47201B] hover:text-[#CA734D] transition" aria-label="Kembali">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-[#47201B]">
                            Riwayat Reservasi
                        </h1>
                        <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                            Lihat seluruh riwayat pengajuan reservasi fasilitas Anda.
                        </p>
                    </div>
                </div>
            </div>

            {{-- search & filter (gaya index fasilitas) --}}
            <div class="flex items-start gap-3 mb-6 sm:mb-8">

                {{-- search --}}
                <form method="GET" action="{{ route('reservations.history') }}" class="flex-1 min-w-0">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari Fasilitas..."
                           class="w-full px-4 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm focus:ring-2 focus:ring-[#511E1D]">
                </form>

                {{-- tombol filter + dropdown --}}
                <div class="relative shrink-0">
                    <button type="button" @click="showFilter = !showFilter"
                            class="inline-flex items-center gap-2 px-5 py-3 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4h18M6 8h12M9 12h6M11 16h2" />
                        </svg>
                        Filter
                    </button>

                    <div x-show="showFilter" x-transition x-cloak @click.outside="showFilter = false"
                         class="absolute right-0 top-full mt-2 z-20 w-72 bg-white shadow-lg rounded-lg p-4">
                        <form method="GET" action="{{ route('reservations.history') }}" class="space-y-3">
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Status Reservasi</label>
                                <select name="status" class="w-full rounded-lg border-gray-300 text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                                    <option value="disetujui" @selected(request('status') == 'disetujui')>Disetujui</option>
                                    <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                                    <option value="dibatalkan" @selected(request('status') == 'dibatalkan')>Dibatalkan</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-[#4a1a24] text-white text-sm rounded-lg hover:bg-[#3a141c]">
                                    Terapkan
                                </button>
                                <a href="{{ route('reservations.history') }}" class="px-4 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- kartu tabel utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                @if($reservations->count() > 0)

                    {{-- MOBILE LIST (CARD) --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($reservations as $reservation)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-base break-words">
                                            {{ $reservation->facility->nama_fasilitas ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500 break-words">
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
                                    <p><span class="font-semibold text-gray-700">Tanggal & Waktu:</span> {{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }} ({{ substr($reservation->waktu_mulai, 0, 5) }} - {{ substr($reservation->waktu_selesai, 0, 5) }})</p>
                                    <p><span class="font-semibold text-gray-700">Diajukan:</span> {{ $reservation->created_at?->translatedFormat('d M Y H:i') }} WIB</p>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-1">
                                    @if($reservation->status === 'menunggu' || $reservation->status === 'disetujui')
                                        <button type="button" onclick="openCancelModal({{ $reservation->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded bg-red-100 text-red-600 hover:bg-red-200 transition" title="Batalkan Reservasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('reservations.show', $reservation->id) }}" class="w-8 h-8 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 transition" title="Lihat Detail">
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
                                        {{-- tombol aksi --}}
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('reservations.show', $reservation->id) }}"
                                                   class="group inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-[#A94438] text-[#A94438] hover:bg-[#A94438] hover:text-white active:bg-[#8F352B] active:border-[#8F352B] text-xs font-semibold rounded-xl shadow-xs hover:shadow transition duration-150 ease-in-out whitespace-nowrap">
                                                    <span>Lihat Detail</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-3.5 h-3.5 transition-transform duration-150 group-hover:translate-x-0.5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M9 5l7 7-7 7" />
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
                            Belum Ada Riwayat Reservasi
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada riwayat reservasi yang ditemukan.
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- MODAL BATALKAN RESERVASI --}}
    <div id="cancelModal"
         class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">

        <div class="bg-white w-full max-w-[390px] rounded-3xl p-8 text-center relative">

            <button type="button" onclick="closeCancelModal()"
                    class="absolute top-3 right-5 text-3xl text-black leading-none">
                ×
            </button>

            <h2 class="text-2xl font-bold text-black leading-tight mb-8">
                Apakah Anda yakin<br>
                ingin membatalkan<br>
                reservasi?
            </h2>

            <div class="flex gap-3">
                <button type="button" onclick="closeCancelModal()"
                        class="flex-1 py-3 rounded-xl bg-[#6F3835] text-white font-semibold">
                    Tidak
                </button>

                <button type="button" onclick="submitCancel()"
                        class="flex-1 py-3 rounded-xl bg-[#F5F0ED] border border-[#D5C6BD] text-black font-semibold">
                    Ya, Batalkan
                </button>
            </div>

        </div>

    </div>

    <form id="cancelForm" method="POST">
        @csrf
        @method('PATCH')
    </form>


    <script>
        let cancelId;

        function openCancelModal(id) {
            cancelId = id;
            const modal = document.getElementById('cancelModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitCancel() {
            const form = document.getElementById('cancelForm');
            form.action = "/reservations/" + cancelId + "/cancel";
            form.submit();
        }
    </script>

</x-app-layout>