<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div x-data="rekapFilterData()" @filter-date.window="selectDate($event.detail)" class="px-4 sm:px-8 py-6 sm:py-8 max-w-7xl mx-auto space-y-6">

            {{-- bagian header dan tombol export --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div class="flex items-center gap-3">
                    <a href="{{ route('petugas.dashboard') }}" class="text-[#47201B] hover:text-[#CA734D] p-1.5 rounded-lg hover:bg-white/60 transition" title="Kembali ke Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-[#47201B]" style="font-family: 'Poppins', sans-serif;">
                        Rekap Fasilitas & Kerusakan
                    </h1>
                </div>

                {{-- tombol export file --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.rekap.export.csv', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold border border-blue-300 text-blue-800 bg-blue-50/90 hover:bg-blue-100 rounded-xl px-4 py-2.5 transition shadow-xs">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export CSV
                    </a>
                    <a href="{{ route('reports.rekap.export.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold border border-emerald-300 text-emerald-800 bg-emerald-50/90 hover:bg-emerald-100 rounded-xl px-4 py-2.5 transition shadow-xs">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </a>
                    <a href="{{ route('reports.rekap.export.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold border border-rose-300 text-rose-800 bg-rose-50/90 hover:bg-rose-100 rounded-xl px-4 py-2.5 transition shadow-xs">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Export PDF
                    </a>
                </div>
            </div>

            {{-- filter bulan dan tahun --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-xs mb-2">
                <form method="GET" action="{{ route('reports.rekap') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    
                    {{-- pilihan bulan --}}
                    <div class="w-full sm:w-56">
                        <label for="bulan" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Pilih Bulan</label>
                        <select name="bulan" id="bulan" class="w-full h-11 rounded-xl bg-[#F5EFE9] border-0 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-[#511E1D]">
                            <option value="">Semua Bulan</option>
                            @foreach(($daftarBulan ?? []) as $num => $namaBulan)
                                <option value="{{ $num }}" {{ (string)($bulan ?? '') === (string)$num ? 'selected' : '' }}>
                                    {{ $namaBulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- pilihan tahun --}}
                    <div class="w-full sm:w-44">
                        <label for="tahun" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Pilih Tahun</label>
                        <select name="tahun" id="tahun" class="w-full h-11 rounded-xl bg-[#F5EFE9] border-0 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-[#511E1D]">
                            <option value="">Semua Tahun</option>
                            @foreach(($daftarTahun ?? []) as $thn)
                                <option value="{{ $thn }}" {{ (string)($tahun ?? '') === (string)$thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- tombol filter --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-initial h-11 px-6 bg-[#511E1D] hover:bg-[#3d1615] text-white font-semibold text-sm rounded-xl transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M9 12h6M11 16h2"/></svg>
                            <span>Filter Periode</span>
                        </button>

                        @if(request()->filled('bulan') || request()->filled('tahun'))
                            <a href="{{ route('reports.rekap') }}" class="flex-1 sm:flex-initial h-11 px-4 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold text-sm rounded-xl transition flex items-center justify-center gap-1 border border-stone-200">
                                Reset
                            </a>
                        @endif
                    </div>

                </form>
            </div>

        {{-- notif filter aktif --}}
        <div x-show="selectedDate" x-cloak class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2 text-sm font-medium">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M9 12h6M11 16h2"/></svg>
                <span>Filter aktif berdasarkan tanggal: <strong x-text="selectedDateFormatted"></strong> (<span x-text="selectedDateTotal"></span> Laporan Kerusakan)</span>
            </div>
            <button type="button" @click="resetFilter()" class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset Filter
            </button>
        </div>

        {{-- kartu ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">

            {{-- kartu total laporan --}}
            <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 flex items-start justify-between shadow-xs">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-700 flex items-center justify-center shrink-0 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">Total Laporan Kerusakan</p>
                        <p class="text-xs text-stone-500 mt-1 max-w-xs" x-text="selectedDate ? 'Jumlah laporan kerusakan pada tanggal ini.' : 'Jumlah laporan kerusakan fasilitas yang masuk.'"></p>
                        <p class="text-3xl font-extrabold text-emerald-800 mt-3" x-text="displayTotal"></p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-full flex items-center gap-1 whitespace-nowrap
                    {{ $selisihLaporan >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $selisihLaporan >= 0 ? '↑' : '↓' }} {{ $selisihLaporan >= 0 ? '+' : '' }}{{ $selisihLaporan }}
                    <span class="font-normal">dari bulan lalu</span>
                </span>
            </div>
        </div>

        {{-- grafik laporan per hari --}}
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-xs">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-sm font-semibold text-slate-800">Laporan Kerusakan per Hari (14 Hari Terakhir)</p>
                    <span class="text-xs text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 font-medium">💡 Klik batang diagram untuk memfilter tabel & total</span>
                </div>
                <canvas id="grafikLaporanHarian" height="100" class="cursor-pointer"></canvas>
            </div>
        </div>

        {{-- tabel rekap fasilitas --}}
        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-xs">
            <table class="w-full text-sm">
                <thead class="bg-[#511E1D] text-white text-xs uppercase">
                    <tr>
                        <th class="text-left px-4 py-3.5">Fasilitas</th>
                        <th class="text-left px-4 py-3.5">Lokasi</th>
                        <th class="text-left px-4 py-3.5">Status</th>
                        <th class="text-center px-4 py-3.5">Total Laporan Kerusakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($rekap as $index => $facility)
                        <tr x-show="isInPage({{ $index }})" class="transition hover:bg-stone-50/60" :class="{ 'opacity-40': selectedDate && getFacilityCount({{ $facility->id }}) === 0 }">
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $facility->nama_fasilitas }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $facility->lokasi }}</td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold"
                                    :class="getFacilityCount({{ $facility->id }}) > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'"
                                    x-text="getFacilityCount({{ $facility->id }}) > 0 ? 'Dalam Perbaikan' : 'Aktif'">
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold text-slate-900" x-text="getFacilityCount({{ $facility->id }})"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination Footer --}}
            <div class="px-6 py-4 bg-white border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-[#511E1D]">
                <p class="text-xs sm:text-sm text-stone-600">
                    Menampilkan 
                    <span class="font-bold text-[#511E1D]" x-text="{{ count($rekap) }} > 0 ? ((currentPage - 1) * perPage + 1) : 0"></span>
                    sampai 
                    <span class="font-bold text-[#511E1D]" x-text="Math.min(currentPage * perPage, {{ count($rekap) }})"></span>
                    dari 
                    <span class="font-bold text-[#511E1D]">{{ count($rekap) }}</span> data fasilitas
                </p>

                <div class="flex items-center gap-1" x-show="totalPages > 1">
                    <button type="button" 
                            @click="if(currentPage > 1) currentPage--" 
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-30 cursor-not-allowed text-stone-400' : 'hover:bg-stone-100 text-[#511E1D] cursor-pointer'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 font-bold transition">
                        ‹
                    </button>

                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p"
                                :class="currentPage === p ? 'bg-[#511E1D] text-white font-bold' : 'hover:bg-stone-100 text-stone-700 border border-transparent'"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs transition cursor-pointer"
                                x-text="p">
                        </button>
                    </template>

                    <button type="button" 
                            @click="if(currentPage < totalPages) currentPage++" 
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-30 cursor-not-allowed text-stone-400' : 'hover:bg-stone-100 text-[#511E1D] cursor-pointer'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 font-bold transition">
                        ›
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rawLaporanData = @json($laporanPerHari);
        const rawDetailTanggal = @json($detailTanggal ?? []);
        const rawDefaultFacilityTotals = @json(array_column($rekap, 'total_laporan', 'id'));
        const defaultTotalOverall = {{ $totalLaporanKerusakan }};
        const datesList = Object.keys(rawLaporanData);

        function rekapFilterData() {
            return {
                selectedDate: null,
                selectedDateFormatted: '',
                selectedDateTotal: 0,
                displayTotal: defaultTotalOverall,
                currentPage: 1,
                perPage: 10,

                get totalPages() {
                    return Math.ceil({{ count($rekap) }} / this.perPage) || 1;
                },

                isInPage(index) {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    return index >= start && index < end;
                },

                selectDate(dateStr) {
                    if (this.selectedDate === dateStr) {
                        this.resetFilter();
                        return;
                    }
                    this.selectedDate = dateStr;
                    const d = new Date(dateStr);
                    this.selectedDateFormatted = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                    this.selectedDateTotal = rawLaporanData[dateStr] || 0;
                    this.displayTotal = this.selectedDateTotal;
                    this.currentPage = 1;
                },
                
                resetFilter() {
                    this.selectedDate = null;
                    this.selectedDateFormatted = '';
                    this.selectedDateTotal = 0;
                    this.displayTotal = defaultTotalOverall;
                    this.currentPage = 1;
                },
                
                getFacilityCount(facilityId) {
                    if (!this.selectedDate) {
                        return rawDefaultFacilityTotals[facilityId] || 0;
                    }
                    const tglData = rawDetailTanggal[this.selectedDate] || {};
                    return tglData[facilityId] || 0;
                }
            };
        }

        function buatLabelTanggal(dataHarian) {
            return Object.keys(dataHarian).map(tanggal => {
                const d = new Date(tanggal);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            });
        }

        const chartCanvas = document.getElementById('grafikLaporanHarian');
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'bar',
                data: {
                    labels: buatLabelTanggal(rawLaporanData),
                    datasets: [{
                        label: 'Laporan Kerusakan',
                        data: Object.values(rawLaporanData),
                        backgroundColor: '#BA3D34',
                        hoverBackgroundColor: '#9E3129',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1600,
                        easing: 'easeOutQuart',
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const targetDate = datesList[index];
                            window.dispatchEvent(new CustomEvent('filter-date', { detail: targetDate }));
                        }
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
                }
            });
        }
    </script>
    @endpush

        </div>
    </div>
</x-app-layout>
