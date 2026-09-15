<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Petugas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- kartu ringkasan, biar petugas langsung tau ada berapa yang nunggu --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Laporan Kerusakan Pending</p>
                    <p class="text-3xl font-bold mt-1">{{ $laporanPending }}</p>
                    <a href="{{ route('reports.antrian') }}" class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                        Lihat antrian &rarr;
                    </a>
                </div>

                {{-- placeholder buat antrian reservasi punya Anggota B, biar dashboard-nya sesuai US 8 (reservasi & laporan) --}}
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Reservasi Pending</p>
                    <p class="text-3xl font-bold mt-1">{{ $reservasiPending ?? '-' }}</p>
                    <a href="#" class="text-sm text-gray-400 mt-2 inline-block">
                        Menunggu modul reservasi (B)
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Laporan Terbaru</h3>

                @if ($laporanTerbaru->isEmpty())
                    <p class="text-gray-500 text-sm">Nggak ada laporan pending saat ini.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="border-b text-gray-600">
                            <tr>
                                <th class="py-2">Pelapor</th>
                                <th class="py-2">Fasilitas</th>
                                <th class="py-2">Kategori</th>
                                <th class="py-2">Status</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporanTerbaru as $laporan)
                                <tr class="border-b">
                                    <td class="py-2">{{ $laporan->user->name }}</td>
                                    <td class="py-2">{{ $laporan->facility->nama_fasilitas }}</td>
                                    <td class="py-2">{{ $laporan->kategori }}</td>
                                    <td class="py-2">{{ ucfirst($laporan->status) }}</td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('reports.show', $laporan) }}" class="text-blue-600 hover:underline">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>