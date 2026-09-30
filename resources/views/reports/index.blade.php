<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        @if(session('success'))
            <div id="successToast"
                class="
                fixed
                bottom-8
                left-1/2
                -translate-x-1/2
                z-[100]
                flex
                items-center
                gap-3
                bg-[#22C55E]
                text-white
                px-6
                py-4
                rounded-2xl
                shadow-2xl
                font-semibold
                transition-all
                duration-500
                transform
                translate-y-0
                opacity-100
                "
            >
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>

            <script>
            setTimeout(() => {
                const toast = document.getElementById('successToast');
                if (toast) {
                    toast.style.opacity = "0";
                    toast.style.transform = "translate(-50%, 20px)";
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }
            }, 4000);
            </script>
        @endif

        <div class="px-4 sm:px-8 py-8 sm:py-10 max-w-7xl mx-auto">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[#47201B]">
                    Laporkan Kerusakan Fasilitas
                </h1>
                <p class="mt-1 text-sm text-[#996561]">
                    Laporkan kerusakan fasilitas yang kamu temukan dan pantau status laporannya di sini.
                </p>
            </div>

            {{-- tombol lapor disini buat ke form --}}
            <a href="{{ route('reports.create') }}"
               class="flex items-center gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8 hover:bg-gray-50/80 transition">
                <div class="w-11 h-11 rounded-full bg-[#F5EBE9] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v6m3-3H9m9 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Lapor Disini</p>
                    <p class="text-sm text-gray-500">Temukan fasilitas rusak? Laporkan sekarang biar cepat ditindaklanjuti petugas.</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <h2 class="text-lg font-bold text-[#47201B] mb-3">
                Riwayat Laporan Saya
            </h2>

            {{-- search + filter button with popover --}}
            <div x-data="{ showFilter: false }" class="mb-6 relative">
                <div class="flex items-center gap-3">
                    {{-- Search Input Form --}}
                    <form method="GET" action="{{ route('reports.index') }}" class="flex-1">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                        <input type="text" name="cari" value="{{ request('cari') }}"
                               placeholder="Cari Nama/Fasilitas/Kategori/..."
                               class="w-full px-4 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm focus:ring-2 focus:ring-[#511E1D]">
                    </form>

                    {{-- Tombol Filter + Popover Floating --}}
                    <div class="relative">
                        <button type="button" @click="showFilter = !showFilter"
                                class="inline-flex items-center gap-2 px-5 py-3 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 4h18M6 8h12M9 12h6M11 16h2" />
                            </svg>
                            Filter
                            @if(request()->filled('status') || request()->filled('kategori') || request()->filled('tanggal'))
                                <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                            @endif
                        </button>

                        {{-- Floating Popover Card --}}
                        <div x-show="showFilter" x-transition x-cloak @click.outside="showFilter = false"
                             class="absolute right-0 top-full mt-2 z-30 w-72 sm:w-80 bg-white shadow-2xl rounded-2xl p-5 border border-gray-100">
                            <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                                <input type="hidden" name="cari" value="{{ request('cari') }}">

                                {{-- Status Proses --}}
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1 font-medium">Status</label>
                                    <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]">
                                        <option value="">Semua Status</option>
                                        <option value="baru" @selected(request('status') === 'baru')>Baru</option>
                                        <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                                        <option value="diproses" @selected(request('status') === 'diproses')>Diproses</option>
                                        <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                                    </select>
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1 font-medium">Kategori</label>
                                    <select name="kategori" class="w-full rounded-lg border-gray-300 text-sm focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]">
                                        <option value="">Semua Kategori</option>
                                        @foreach(($daftarKategori ?? []) as $kat)
                                            <option value="{{ $kat }}" @selected(request('kategori') === $kat)>
                                                {{ $kat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tanggal Ditemukan --}}
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1 font-medium">Tanggal Ditemukan</label>
                                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]">
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="flex items-center gap-2 pt-1">
                                    <button type="submit" class="px-5 py-2.5 bg-[#4a1a24] text-white text-sm font-medium rounded-xl hover:bg-[#3a141c] transition">
                                        Terapkan
                                    </button>
                                    <a href="{{ route('reports.index') }}" class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 transition text-gray-700">
                                        Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                @if($reports->count() > 0)

                    {{-- tabel laporan versi desktop --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">

                            <thead class="bg-[#F5EBE9]">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Nama Fasilitas</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Kategori</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Foto</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Status</th>
                                    <th class="px-6 py-4 text-center font-bold text-[#A94438]">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-50 transition">

                                        {{-- no --}}
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            {{ $loop->iteration }}.
                                        </td>

                                        {{-- nama fasilitas --}}
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-gray-900">
                                                {{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}
                                            </p>
                                        </td>

                                        {{-- kategori --}}
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            {{ $report->kategori }}
                                        </td>

                                        {{-- tanggal --}}
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                             {{ $report->tanggal_ditemukan ? \Carbon\Carbon::parse($report->tanggal_ditemukan)->format('d M Y') : $report->created_at?->format('d M Y') }}
                                        </td>

                                        {{-- foto --}}
                                        <td class="px-6 py-5">
                                            @if($report->foto)
                                                <img src="{{ asset('storage/' . $report->foto) }}" alt="Foto kerusakan"
                                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200 shadow-xs">
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>

                                        {{-- status & warna badge --}}
                                        <td class="px-6 py-5">
                                            @php
                                                $statusText = 'Baru';
                                                $statusColor = 'text-blue-600';
                                                $dotColor = 'bg-blue-500';

                                                if ($report->status === 'diproses') {
                                                    $statusText = 'Diproses';
                                                    $statusColor = 'text-yellow-600';
                                                    $dotColor = 'bg-yellow-500';
                                                } elseif ($report->status === 'selesai') {
                                                    $statusText = 'Selesai';
                                                    $statusColor = 'text-green-600';
                                                    $dotColor = 'bg-green-500';
                                                } elseif ($report->status === 'ditolak') {
                                                    $statusText = 'Ditolak';
                                                    $statusColor = 'text-red-600';
                                                    $dotColor = 'bg-red-500';
                                                }
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                                <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                            </div>
                                        </td>

                                        {{-- tombol aksi: lihat detail (outline ghost) --}}
                                        <td class="px-6 py-5 text-center">
                                            <a href="{{ route('reports.show', $report) }}"
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

                        {{-- nomor halaman --}}
                        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-[#A94438]">
                            <p>Menampilkan {{ $reports->count() }} dari {{ $reports->count() }} data</p>
                            
                            <div class="flex items-center gap-2">
                                <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600">&lt;</button>
                                <button type="button" class="w-7 h-7 flex items-center justify-center rounded bg-[#A94438] text-white">1</button>
                                <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded">2</button>
                                <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded">3</button>
                                <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600">&gt;</button>
                            </div>
                        </div>
                    </div>

                    {{-- list laporan versi mobile --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($reports as $report)
                            @php
                                $statusText = 'Baru';
                                $statusColor = 'text-blue-600';
                                $dotColor = 'bg-blue-500';

                                if ($report->status === 'diproses') {
                                    $statusText = 'Diproses';
                                    $statusColor = 'text-yellow-600';
                                    $dotColor = 'bg-yellow-500';
                                } elseif ($report->status === 'selesai') {
                                    $statusText = 'Selesai';
                                    $statusColor = 'text-green-600';
                                    $dotColor = 'bg-green-500';
                                } elseif ($report->status === 'ditolak') {
                                    $statusText = 'Ditolak';
                                    $statusColor = 'text-red-600';
                                    $dotColor = 'bg-red-500';
                                }
                            @endphp
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs text-gray-400 mb-1">Laporan #{{ $loop->iteration }}</p>
                                        <h3 class="font-semibold text-gray-900">
                                            {{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-0.5">{{ $report->kategori }}</p>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                        <span class="text-xs font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                                    <p class="text-xs text-gray-500">
                                        {{ $report->tanggal_ditemukan ? \Carbon\Carbon::parse($report->tanggal_ditemukan)->format('d M Y') : $report->created_at?->format('d M Y') }}
                                    </p>
                                    <a href="{{ route('reports.show', $report) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-[#A94438] hover:text-[#8F352B]">
                                        <span>Lihat Detail</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else

                    {{-- tampilan kalau tidak ada laporan / filter tidak cocok --}}
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F5EBE9] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12h6m-6 4h4m2 5H9a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        @if(request()->hasAny(['cari', 'status', 'kategori', 'tanggal']))
                            <h3 class="text-base font-semibold text-gray-800">Laporan Tidak Ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Tidak ada laporan yang sesuai dengan kriteria filter pencarian Anda.</p>
                            <a href="{{ route('reports.index') }}"
                               class="inline-flex mt-5 px-5 py-2.5 bg-[#4a1a24] text-white text-sm font-semibold rounded-lg hover:bg-[#3a141c] transition">
                                Reset Filter
                            </a>
                        @else
                            <h3 class="text-base font-semibold text-gray-800">Belum Ada Laporan</h3>
                            <p class="mt-1 text-sm text-gray-500">Kamu belum pernah membuat laporan kerusakan fasilitas.</p>
                            <a href="{{ route('reports.create') }}"
                               class="inline-flex mt-5 px-5 py-2.5 bg-[#4a1a24] text-white text-sm font-semibold rounded-lg hover:bg-[#3a141c] transition">
                                Buat Laporan
                            </a>
                        @endif
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>