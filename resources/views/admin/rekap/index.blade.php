<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Rekap & Ekspor</h2>
    </x-slot>

    <div x-data="{ currentPage: 1, perPage: 10, get totalPages() { return Math.ceil({{ count($rekap) }} / this.perPage) || 1 }, isInPage(i) { return i >= (this.currentPage - 1) * this.perPage && i < this.currentPage * this.perPage } }" class="py-8 max-w-6xl mx-auto px-4">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            {{-- Filter Periode Bulan & Tahun --}}
            <form method="GET" action="{{ route('admin.rekap.index') }}" class="flex flex-wrap items-end gap-3 flex-1">
                <div>
                    <label for="bulan" class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="h-10 rounded-xl bg-stone-100 border-0 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-[#511E1D]">
                        <option value="">Semua Bulan</option>
                        @foreach(($daftarBulan ?? []) as $num => $namaBulan)
                            <option value="{{ $num }}" {{ (string)($bulan ?? '') === (string)$num ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun" class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih Tahun</label>
                    <select name="tahun" id="tahun" class="h-10 rounded-xl bg-stone-100 border-0 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-[#511E1D]">
                        <option value="">Semua Tahun</option>
                        @foreach(($daftarTahun ?? []) as $thn)
                            <option value="{{ $thn }}" {{ (string)($tahun ?? '') === (string)$thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="h-10 px-5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M9 12h6M11 16h2"/></svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->filled('bulan') || request()->filled('tahun'))
                        <a href="{{ route('admin.rekap.index') }}" class="h-10 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold text-xs rounded-xl transition flex items-center justify-center border border-stone-200">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.rekap.export.csv', request()->query()) }}"
                   class="text-xs font-semibold border border-stone-300 rounded-xl px-3 py-2.5 hover:bg-stone-50 transition">Export CSV</a>
                <a href="{{ route('admin.rekap.export.excel', request()->query()) }}"
                   class="text-xs font-semibold border border-stone-300 rounded-xl px-3 py-2.5 hover:bg-stone-50 transition">Export Excel</a>
                <a href="{{ route('admin.rekap.export.pdf', request()->query()) }}"
                   class="text-xs font-semibold border border-stone-300 rounded-xl px-3 py-2.5 hover:bg-stone-50 transition">Export PDF</a>
            </div>
        </div>

        {{--  Card Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            {{-- Card: Total Fasilitas Direservasi --}}
            <div class="rounded-2xl border border-rose-100 bg-gradient-to-br from-rose-50 to-white p-5 flex items-start justify-between">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-800 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V9l8-6 8 6v12M9 21v-6h6v6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">Total Fasilitas Direservasi</p>
                        <p class="text-xs text-stone-500 mt-1 max-w-xs">Jumlah fasilitas yang pernah direservasi di sistem.</p>
                        <p class="text-3xl font-extrabold text-rose-900 mt-3">{{ $totalFasilitasDireservasi }}</p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-full flex items-center gap-1 whitespace-nowrap
                    {{ $selisihFasilitas >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $selisihFasilitas >= 0 ? '↑' : '↓' }} {{ $selisihFasilitas >= 0 ? '+' : '' }}{{ $selisihFasilitas }}
                    <span class="font-normal">dari bulan lalu</span>
                </span>
            </div>

            {{-- Card: Total Laporan Kerusakan --}}
            <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 flex items-start justify-between">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-700 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">Total Laporan Kerusakan</p>
                        <p class="text-xs text-stone-500 mt-1 max-w-xs">Jumlah laporan kerusakan fasilitas yang masuk.</p>
                        <p class="text-3xl font-extrabold text-emerald-800 mt-3">{{ $totalLaporanKerusakan }}</p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-full flex items-center gap-1 whitespace-nowrap
                    {{ $selisihLaporan >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $selisihLaporan >= 0 ? '↑' : '↓' }} {{ $selisihLaporan >= 0 ? '+' : '' }}{{ $selisihLaporan }}
                    <span class="font-normal">dari bulan lalu</span>
                </span>
            </div>
        </div>

        {{--  Grafik Batang --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <div class="bg-white border border-stone-200 rounded-2xl p-5">
                <p class="text-sm font-semibold text-slate-800 mb-3">Fasilitas Direservasi per Hari (14 Hari Terakhir)</p>
                <canvas id="grafikFasilitasHarian" height="180"></canvas>
            </div>

            <div class="bg-white border border-stone-200 rounded-2xl p-5">
                <p class="text-sm font-semibold text-slate-800 mb-3">Laporan Kerusakan per Hari (14 Hari Terakhir)</p>
                <canvas id="grafikLaporanHarian" height="180"></canvas>
            </div>
        </div>

        {{--  Tabel Rekap --}}
        <div class="bg-white border border-stone-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white text-xs uppercase">
                    <tr>
                        <th class="text-left px-4 py-3">Fasilitas</th>
                        <th class="text-left px-4 py-3">Lokasi</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Total Reservasi</th>
                        <th class="text-left px-4 py-3">Total Laporan Kerusakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $index => $facility)
                        <tr x-show="isInPage({{ $index }})" class="border-t border-stone-100">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $facility->nama_fasilitas }}</td>
                            <td class="px-4 py-3">{{ $facility->lokasi }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $facility->total_laporan > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $facility->total_laporan > 0 ? 'Dalam Perbaikan' : 'Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $facility->total_reservasi }}</td>
                            <td class="px-4 py-3">{{ $facility->total_laporan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination Footer --}}
            <div class="px-6 py-4 bg-white border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-800">
                <p class="text-xs sm:text-sm text-stone-600">
                    Menampilkan 
                    <span class="font-bold text-slate-900" x-text="{{ count($rekap) }} > 0 ? ((currentPage - 1) * perPage + 1) : 0"></span>
                    sampai 
                    <span class="font-bold text-slate-900" x-text="Math.min(currentPage * perPage, {{ count($rekap) }})"></span>
                    dari 
                    <span class="font-bold text-slate-900">{{ count($rekap) }}</span> data fasilitas
                </p>

                <div class="flex items-center gap-1" x-show="totalPages > 1">
                    <button type="button" 
                            @click="if(currentPage > 1) currentPage--" 
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-30 cursor-not-allowed text-stone-400' : 'hover:bg-stone-100 text-slate-800 cursor-pointer'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 font-bold transition">
                        ‹
                    </button>

                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p"
                                :class="currentPage === p ? 'bg-slate-800 text-white font-bold' : 'hover:bg-stone-100 text-stone-700 border border-transparent'"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs transition cursor-pointer"
                                x-text="p">
                        </button>
                    </template>

                    <button type="button" 
                            @click="if(currentPage < totalPages) currentPage++" 
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-30 cursor-not-allowed text-stone-400' : 'hover:bg-stone-100 text-slate-800 cursor-pointer'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 font-bold transition">
                        ›
                    </button>
                </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function buatLabelTanggal(dataHarian) {
            return Object.keys(dataHarian).map(tanggal => {
                const d = new Date(tanggal);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            });
        }

        const opsiChartDasar = {
            responsive: true,
            animation: {
                duration: 1600,
                easing: 'easeOutQuart',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#511E1D',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        };

        // Grafik batang: fasilitas direservasi per hari
        const dataFasilitas = @json($reservasiPerHari);
        new Chart(document.getElementById('grafikFasilitasHarian'), {
            type: 'bar',
            data: {
                labels: buatLabelTanggal(dataFasilitas),
                datasets: [{
                    label: 'Fasilitas Direservasi',
                    data: Object.values(dataFasilitas),
                    backgroundColor: '#BA3D34',
                    hoverBackgroundColor: '#9E3129',
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: opsiChartDasar
        });

        // Grafik batang: laporan kerusakan per hari
        const dataLaporan = @json($laporanPerHari);
        new Chart(document.getElementById('grafikLaporanHarian'), {
            type: 'bar',
            data: {
                labels: buatLabelTanggal(dataLaporan),
                datasets: [{
                    label: 'Laporan Kerusakan',
                    data: Object.values(dataLaporan),
                    backgroundColor: '#BA3D34',
                    hoverBackgroundColor: '#9E3129',
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: opsiChartDasar
        });
    </script>
    @endpush
</x-app-layout>