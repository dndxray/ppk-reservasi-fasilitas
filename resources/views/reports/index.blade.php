<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-6 py-8">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[#47201B]">
                    Laporkan Kerusakan Fasilitas
                </h1>
                <p class="mt-1 text-sm text-[#996561]">
                    Laporkan kerusakan fasilitas yang kamu temukan dan pantau status laporannya di sini.
                </p>
            </div>

            {{-- kartu "Lapor Disini" sesuai wireframe, klik -> ke form lapor --}}
            <a href="{{ route('reports.create') }}"
               class="flex items-center gap-4 bg-white rounded-xl shadow-sm border border-[#996561]/20 p-5 mb-8 hover:bg-[#F3EFE8] transition">
                <div class="w-11 h-11 rounded-full bg-[#E1D3C4] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#47201B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v6m3-3H9m9 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-[#47201B]">Lapor Disini</p>
                    <p class="text-sm text-[#996561]">Temukan fasilitas rusak? Laporkan sekarang biar cepat ditindaklanjuti petugas.</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#996561] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <h2 class="text-lg font-semibold text-[#47201B] mb-3">
                Riwayat Laporan Saya
            </h2>

            {{-- search & filter --}}
            <form method="GET" action="{{ route('reports.index') }}" class="flex gap-3 mb-4">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari fasilitas/tanggal..."
                    class="flex-1 border-[#996561]/40 rounded-md shadow-sm bg-white focus:border-[#CA734D] focus:ring-[#CA734D]">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-[#996561]/40 text-[#47201B] text-sm font-medium rounded-md hover:bg-[#F3EFE8] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4h18M6 8h12M9 12h6M11 16h2"/>
                    </svg>
                    Filter
                </button>
            </form>

            <div class="bg-white rounded-xl shadow-sm border border-[#996561]/20 overflow-hidden">

                @if($reports->count() > 0)

                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">

                            <thead class="bg-[#F3EFE8]">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-[#47201B]">No</th>
                                    <th class="px-6 py-4 text-left font-semibold text-[#47201B]">Fasilitas</th>
                                    <th class="px-6 py-4 text-left font-semibold text-[#47201B]">Kategori</th>
                                    <th class="px-6 py-4 text-left font-semibold text-[#47201B]">Tanggal Ditemukan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-[#47201B]">Foto</th>
                                    <th class="px-6 py-4 text-left font-semibold text-[#47201B]">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold text-[#47201B]">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#996561]/10">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-[#F8F7F7] transition">

                                        <td class="px-6 py-4 text-[#996561]">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="font-medium text-[#47201B]">
                                                {{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4 text-[#996561]">
                                            {{ $report->kategori }}
                                        </td>

                                        {{-- pakai created_at karena kolom tanggal_ditemukan belum ada di tabel reports --}}
                                        <td class="px-6 py-4 text-[#996561]">
                                            {{ $report->created_at?->format('d/m/Y') }}
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($report->foto)
                                                <img src="{{ asset('storage/' . $report->foto) }}" alt="Foto kerusakan"
                                                     class="w-10 h-10 rounded-md object-cover border border-[#996561]/20">
                                            @else
                                                <span class="text-[#996561] text-xs">-</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($report->status === 'baru')
                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Baru</span>
                                            @elseif($report->status === 'diproses')
                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Diproses</span>
                                            @elseif($report->status === 'selesai')
                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Selesai</span>
                                            @elseif($report->status === 'ditolak')
                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Ditolak</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('reports.show', $report) }}"
                                               class="inline-flex items-center px-4 py-2 text-xs font-semibold text-[#47201B] border border-[#996561]/40 rounded-lg hover:bg-[#F3EFE8] transition">
                                                Lihat Detail
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    {{-- Mobile Card --}}
                    <div class="md:hidden divide-y divide-[#996561]/10">
                        @foreach($reports as $report)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs text-[#996561] mb-1">Laporan #{{ $loop->iteration }}</p>
                                        <h3 class="font-semibold text-[#47201B]">
                                            {{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}
                                        </h3>
                                        <p class="text-sm text-[#996561] mt-1">{{ $report->kategori }}</p>
                                    </div>

                                    @if($report->status === 'baru')
                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Baru</span>
                                    @elseif($report->status === 'diproses')
                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Diproses</span>
                                    @elseif($report->status === 'selesai')
                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Selesai</span>
                                    @elseif($report->status === 'ditolak')
                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Ditolak</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mt-4">
                                    <p class="text-xs text-[#996561]">
                                        {{ $report->created_at?->format('d/m/Y') }}
                                    </p>
                                    <a href="{{ route('reports.show', $report) }}"
                                       class="text-sm font-semibold text-[#47201B] hover:text-[#511E1D]">
                                        Lihat Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F3EFE8] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#996561]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12h6m-6 4h4m2 5H9a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-[#47201B]">Belum Ada Laporan</h3>
                        <p class="mt-1 text-sm text-[#996561]">Kamu belum pernah membuat laporan kerusakan fasilitas.</p>
                        <a href="{{ route('reports.create') }}"
                           class="inline-flex mt-5 px-5 py-2.5 bg-[#47201B] text-white text-sm font-semibold rounded-lg hover:bg-[#511E1D] transition">
                            Buat Laporan
                        </a>
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>