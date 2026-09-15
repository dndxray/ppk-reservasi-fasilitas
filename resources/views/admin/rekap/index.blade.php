<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Rekap & Ekspor</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">

        <div class="flex justify-end gap-2 mb-4">
            <a href="{{ route('admin.rekap.export.csv') }}"
               class="text-xs font-semibold border border-stone-300 rounded-md px-3 py-2 hover:bg-stone-50">Export CSV</a>
            <a href="{{ route('admin.rekap.export.excel') }}"
               class="text-xs font-semibold border border-stone-300 rounded-md px-3 py-2 hover:bg-stone-50">Export Excel</a>
            <a href="{{ route('admin.rekap.export.pdf') }}"
               class="text-xs font-semibold border border-stone-300 rounded-md px-3 py-2 hover:bg-stone-50">Export PDF</a>
        </div>

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
                            <td class="px-4 py-3">{{ $facility->statusLabel() }}</td>
                            <td class="px-4 py-3">{{ $facility->total_reservasi }}</td>
                            <td class="px-4 py-3">{{ $facility->total_laporan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
