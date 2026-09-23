<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Rekap & Ekspor</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">

        <div class="flex justify-end gap-2 mb-6">
            <a href="{{ route('admin.rekap.export.csv') }}"
               class="text-xs font-semibold border border-stone-300 rounded-md px-3 py-2 hover:bg-stone-50">Export CSV</a>
            <a href="{{ route('admin.rekap.export.excel') }}"
               class="text-xs font-semibold border border-stone-300 rounded-md px-3 py-2 hover:bg-stone-50">Export Excel</a>
            <a href="{{ route('admin.rekap.export.pdf') }}"
               class="text-xs font-semibold border border-stone-300 rounded-md px-3 py-2 hover:bg-stone-50">Export PDF</a>
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
                    @foreach ($rekap as $facility)
                        <tr class="border-t border-stone-100">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $facility->nama_fasilitas }}</td>
                            <td class="px-4 py-3">{{ $facility->lokasi }}</td>
                            <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $facility->status)) }}</td>
                            <td class="px-4 py-3">{{ $facility->total_reservasi }}</td>
                            <td class="px-4 py-3">{{ $facility->total_laporan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
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
                    backgroundColor: '#be123c',
                    borderRadius: 4,
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
                    backgroundColor: '#be123c',
                    borderRadius: 4,
                }]
            },
            options: opsiChartDasar
        });
    </script>
    @endpush
</x-app-layout>