<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-8 py-10 max-w-7xl mx-auto">

            {{-- tombol back & judul --}}
            <div class="mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('reports.antrian') }}" class="text-[#47201B] hover:text-[#CA734D] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-[#47201B]">
                        Daftar Laporan
                    </h1>
                </div>
            </div>

            {{-- search & tombol filter --}}
            <form method="GET" action="{{ route('reports.antrian') }}" class="flex flex-col sm:flex-row items-center gap-4 mb-8">
                <div class="w-full sm:flex-1 relative">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari Nama Fasilitas" class="w-full bg-[#F5EBE9] border-0 rounded-xl px-5 py-3.5 text-sm focus:ring-[#A94438] placeholder-gray-500">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-[#511E1D] hover:bg-[#3B1514] transition text-white px-8 py-3.5 rounded-xl font-medium flex items-center justify-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </form>

            {{-- kartu tabel utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                @if($reports->count() > 0)

                    {{-- tabel antrean laporan --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-[#F5EBE9]">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Nama Fasilitas</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Nama Pelapor</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Kategori</th>
                                    <th class="px-6 py-4 text-left font-bold text-[#A94438]">Tanggal</th>
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
                                                 {{ $report->facility->nama_fasilitas ?? '-' }}
                                            </p>
                                        </td>

                                        {{-- nama & email pelapor --}}
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-gray-900">
                                                {{ $report->user->name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $report->user->email ?? '-' }}
                                            </p>
                                        </td>

                                        {{-- kategori --}}
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            {{ $report->kategori }}
                                        </td>

                                        {{-- tanggal laporan --}}
                                        <td class="px-6 py-5 text-gray-700 font-medium">
                                            {{ $report->created_at?->format('d M Y') }}
                                        </td>

                                        {{-- status & warna badge --}}
                                        <td class="px-6 py-5">
                                            @php
                                                // status default
                                                $statusText = 'Menunggu';
                                                $statusColor = 'text-yellow-500';
                                                $dotColor = 'bg-yellow-500';

                                                // status kalau selesai atau ditolak
                                                if ($report->status === 'selesai') {
                                                    $statusText = 'Selesai';
                                                    $statusColor = 'text-green-500';
                                                    $dotColor = 'bg-green-500';
                                                } elseif ($report->status === 'ditolak') {
                                                    $statusText = 'Ditolak';
                                                    $statusColor = 'text-red-500';
                                                    $dotColor = 'bg-red-500';
                                                }
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                 <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                                <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                            </div>
                                        </td>

                                        {{-- tombol aksi --}}
                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-center gap-2">
                                                
                                                @if(in_array($report->status, ['baru', 'diproses']))
                                                    {{-- tombol terima --}}
                                                    <a href="{{ route('reports.show', $report) }}" class="w-8 h-8 flex items-center justify-center rounded bg-green-100 text-green-600 hover:bg-green-200 transition" title="Terima / Proses Laporan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                    {{-- tombol tolak --}}
                                                    <a href="{{ route('reports.show', $report) }}" class="w-8 h-8 flex items-center justify-center rounded bg-red-100 text-red-600 hover:bg-red-200 transition" title="Tolak Laporan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    {{-- tombol hapus --}}
                                                    <a href="{{ route('reports.show', $report) }}" class="w-8 h-8 flex items-center justify-center rounded bg-red-50 text-red-400 hover:bg-red-100 transition" title="Hapus Laporan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                {{-- tombol detail --}}
                                                <a href="{{ route('reports.show', $report) }}" class="w-8 h-8 flex items-center justify-center rounded text-gray-400 hover:bg-gray-100 transition" title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                      <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{-- nomor halaman --}}
                        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-[#A94438]">
                            <p>Menampilkan {{ $reports->count() }} dari {{ $reports->count() }} data</p>
                            
                            <div class="flex items-center gap-2">
                                <button class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600">&lt;</button>
                                <button class="w-7 h-7 flex items-center justify-center rounded bg-[#8A3B3B] text-white">1</button>
                                <button class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded">2</button>
                                <button class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded">3</button>
                                <button class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600">&gt;</button>
                            </div>
                        </div>
                    </div>

                @else

                    {{-- tampilan kalau datanya kosong --}}
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F5EBE9] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h4m2-10h.01M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">
                            Belum Ada Laporan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada laporan kerusakan yang masuk.
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>