<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-6 py-8 max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="flex items-center mb-6">
                @if(auth()->user()->role === 'petugas')
                    <a href="{{ route('reports.antrian') }}" class="mr-4 text-[#47201B] hover:text-[#CA734D] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('reports.index') }}" class="mr-4 text-[#47201B] hover:text-[#CA734D] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif
                <h1 class="text-2xl font-bold text-[#47201B]">
                    Detail Laporan
                </h1>
            </div>

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Detail Laporan Card --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-8">
                        
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-xl font-bold text-[#1A1A1A]">Detail Laporan</h2>
                            
                            {{-- Status Badge --}}
                            @php
                                $statusColors = [
                                    'baru' => 'bg-blue-500',
                                    'diproses' => 'bg-yellow-500',
                                    'selesai' => 'bg-green-500',
                                    'ditolak' => 'bg-red-500',
                                ];
                                $statusTextColors = [
                                    'baru' => 'text-blue-500',
                                    'diproses' => 'text-yellow-600',
                                    'selesai' => 'text-green-500',
                                    'ditolak' => 'text-red-500',
                                ];
                                $statusLabels = [
                                    'baru' => 'Baru',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ];
                                $dotColor = $statusColors[$report->status] ?? 'bg-gray-500';
                                $textColor = $statusTextColors[$report->status] ?? 'text-gray-500';
                                $label = $statusLabels[$report->status] ?? ucfirst($report->status);
                            @endphp
                            
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full {{ $dotColor }}"></div>
                                <span class="text-sm font-medium {{ $textColor }}">{{ $label }}</span>
                            </div>
                        </div>

                        <div class="space-y-6">
                            
                            <div class="flex items-start">
                                <div class="w-1/3 flex items-center text-sm font-medium text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Pelapor
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->user->name ?? '-' }}
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-1/3 flex items-center text-sm font-medium text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Kategori Kerusakan
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->kategori }}
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-1/3 flex items-center text-sm font-medium text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal Laporan
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->created_at?->format('d F Y') }}
                                </div>
                            </div>

                            @if($report->diselesaikan_pada)
                            <div class="flex items-start">
                                <div class="w-1/3 flex items-center text-sm font-medium text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Diselesaikan Pada
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->diselesaikan_pada->format('d F Y, H:i') }}
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-10">
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-4">Deskripsi Kerusakan</h3>
                            <div class="p-6 bg-[#F8F7F7] rounded-xl border border-gray-100">
                                <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
                                    {{ $report->deskripsi }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">
                    
                    {{-- Fasilitas Card --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        
                        @if($report->foto)
                            <div class="mb-4 overflow-hidden rounded-xl h-48 w-full bg-gray-100 border border-gray-100">
                                <img
                                    src="{{ asset('storage/' . $report->foto) }}"
                                    alt="Foto kondisi kerusakan"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                        @else
                            <div class="mb-4 rounded-xl h-48 w-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                                <span class="text-sm text-gray-400">Tidak ada foto</span>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-[#1A1A1A] mb-3">
                            {{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}
                        </h3>

                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Fasilitas Terkait
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($report->facility->status === 'aktif')
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-md bg-[#64413E] text-white">
                                    Fasilitas Aktif
                                </span>
                            @elseif($report->facility->status === 'dalam_perbaikan')
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-md bg-yellow-100 text-yellow-800">
                                    Dalam Perbaikan
                                </span>
                            @endif
                        </div>

                    </div>

                    {{-- Form Proses Laporan (Petugas) / Catatan (Pengguna) --}}
                    @if(auth()->user()->role === 'petugas')
                        
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-4">Proses Laporan</h3>
                            
                            <form action="{{ route('reports.updateStatus', $report) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-4">
                                    
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Laporan</label>
                                        <select name="status" id="status" required class="w-full rounded-lg border-gray-300 bg-[#F8F7F7] focus:border-[#A94438] focus:ring-[#A94438] text-sm">
                                            <option value="baru" {{ $report->status === 'baru' ? 'selected' : '' }}>Baru</option>
                                            <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="status_fasilitas" class="block text-sm font-medium text-gray-700 mb-1">Status Fasilitas</label>
                                        <select name="status_fasilitas" id="status_fasilitas" class="w-full rounded-lg border-gray-300 bg-[#F8F7F7] focus:border-[#A94438] focus:ring-[#A94438] text-sm">
                                            <option value="aktif" {{ $report->facility->status === 'aktif' ? 'selected' : '' }}>Aktif (Tersedia)</option>
                                            <option value="dalam_perbaikan" {{ $report->facility->status === 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="catatan_resolusi" class="block text-sm font-medium text-gray-700 mb-1">Catatan Resolusi</label>
                                        <textarea name="catatan_resolusi" id="catatan_resolusi" rows="4" placeholder="Ketik catatan penyelesaian..." class="w-full rounded-lg border-gray-300 bg-[#F8F7F7] focus:border-[#A94438] focus:ring-[#A94438] text-sm">{{ old('catatan_resolusi', $report->catatan_resolusi) }}</textarea>
                                    </div>

                                </div>

                                <button type="submit" class="w-full mt-6 bg-[#A94438] hover:bg-[#8F3A2F] text-white py-3 rounded-xl font-semibold text-sm transition">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>

                    @else
                        
                        @if($report->catatan_resolusi)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-4">Catatan Petugas</h3>
                            
                            <div class="p-4 bg-[#F8F7F7] rounded-xl border border-gray-100">
                                <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
                                    {{ $report->catatan_resolusi }}
                                </p>
                            </div>
                        </div>
                        @endif

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>