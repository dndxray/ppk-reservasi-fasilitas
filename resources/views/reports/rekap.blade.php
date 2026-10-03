<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div x-data="rekapFilterData()" @filter-date.window="selectDate($event.detail)" class="px-4 sm:px-8 py-6 sm:py-8 max-w-7xl mx-auto space-y-6">

            {{-- Header Judul, Tombol Kembali & Export Buttons --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div class="flex items-center gap-3">
                    <a href="{{ route('petugas.dashboard') }}" class="text-[#47201B] hover:text-[#CA734D] p-1.5 rounded-lg hover:bg-white/60 transition" title="Kembali ke Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-[#47201B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Rekap Fasilitas & Kerusakan
                    </h1>
                </div>

                {{-- Export Buttons --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.rekap.export.excel') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold border border-emerald-300 text-emerald-800 bg-emerald-50/90 hover:bg-emerald-100 rounded-xl px-4 py-2.5 transition shadow-xs">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </a>
                    <a href="{{ route('reports.rekap.export.pdf') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold border border-rose-300 text-rose-800 bg-rose-50/90 hover:bg-rose-100 rounded-xl px-4 py-2.5 transition shadow-xs">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Export PDF
                    </a>
                </div>
            </div>

        {{-- Active Filter Notification Banner --}}
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

        {{-- Card Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">

            {{-- Card: Total Laporan Kerusakan --}}
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

        {{-- Grafik Batang --}}
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-xs">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-sm font-semibold text-slate-800">Laporan Kerusakan per Hari (14 Hari Terakhir)</p>
                    <span class="text-xs text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 font-medium">💡 Klik batang diagram untuk memfilter tabel & total</span>
                </div>
                <canvas id="grafikLaporanHarian" height="100" class="cursor-pointer"></canvas>
            </div>
        </div>

        {{-- Tabel Rekap --}}
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
                    @foreach ($rekap as $facility)
                        <tr class="transition hover:bg-stone-50/60" :class="{ 'opacity-40': selectedDate && getFacilityCount({{ $facility->id }}) === 0 }">
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $facility->nama_fasilitas }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $facility->lokasi }}</td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold
                                    {{ $facility->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $facility->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold text-slate-900" x-text="getFacilityCount({{ $facility->id }})"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                },
                
                resetFilter() {
                    this.selectedDate = null;
                    this.selectedDateFormatted = '';
                    this.selectedDateTotal = 0;
                    this.displayTotal = defaultTotalOverall;
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

        function initRekapChart() {
            const chartCanvas = document.getElementById('grafikLaporanHarian');
            if (!chartCanvas) return;

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

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initRekapChart);
        } else {
            initRekapChart();
        }
    </script>
    @endpush

        </div>
    </div>
</x-app-layout>
