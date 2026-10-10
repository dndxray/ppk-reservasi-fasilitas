<x-app-layout>

    <div x-data="{ showProfileModal: false, zoomImage: null, showConfirmModal: false }" class="min-h-screen bg-[#F8F7F7]">

        <div class="px-6 py-8 max-w-7xl mx-auto">

            {{-- tombol back & judul --}}
            <div class="flex items-center mb-6">
                @php
                    $fallbackUrl = auth()->user()->role === 'petugas' ? route('petugas.dashboard') : route('reports.index');
                @endphp
                <a href="{{ $fallbackUrl }}" onclick="if (document.referrer && document.referrer !== window.location.href) { history.back(); return false; }" class="mr-4 text-[#47201B] hover:text-[#CA734D] transition p-1.5 rounded-lg hover:bg-white/60" title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-[#47201B]">
                    Detail Laporan
                </h1>
            </div>

            {{-- alert sukses --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(auth()->user()->role === 'petugas')
            
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    
                    {{-- info laporan (kiri) --}}
                    <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-[#1A1A1A] mb-8">Informasi Laporan</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="w-1/3 flex items-center text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Nama Fasilitas
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->facility->nama_fasilitas ?? '-' }}
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-1/3 flex items-center text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Kategori
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->kategori }}
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-1/3 flex items-center text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal Ditemukan
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->tanggal_ditemukan ? \Carbon\Carbon::parse($report->tanggal_ditemukan)->translatedFormat('d F Y') : $report->created_at?->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-1/3 flex items-center text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Deskripsi Kerusakan
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium whitespace-pre-line">
                                    {{ $report->deskripsi }}
                                </div>
                            </div>
                            
                            <div class="flex items-center mt-6">
                                <div class="w-1/3 flex items-center text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Nama Pelapor
                                </div>
                                <div class="w-2/3 flex items-center gap-3">
                                    <span class="text-sm text-gray-900 font-medium">{{ $report->user->name ?? '-' }}</span>
                                    <button type="button" @click="showProfileModal = true" class="bg-[#C84F4F] text-white px-3 py-1 rounded-md text-xs font-semibold hover:bg-[#A94438] transition flex items-center gap-1 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Profil
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-1/3 flex items-center text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Nomor Kontak
                                </div>
                                <div class="w-2/3 text-sm text-gray-900 font-medium">
                                    {{ $report->user->no_telepon ?? $report->user->phone ?? '08122xxxxxx' }}
                                </div>
                            </div>

                            <div class="pt-6">
                                <h3 class="text-md font-bold text-[#1A1A1A] mb-4">Foto Kerusakan</h3>
                                <div class="flex gap-4">
                                    @if($report->foto)
                                        <div class="relative group cursor-pointer overflow-hidden rounded-xl border border-gray-200 shadow-xs" @click="zoomImage = '{{ asset('storage/' . $report->foto) }}'">
                                            <img src="{{ asset('storage/' . $report->foto) }}" class="w-36 h-36 rounded-xl object-cover group-hover:scale-105 transition duration-300">
                                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                                                <svg class="w-7 h-7 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-32 h-32 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200 text-xs">Tak ada foto</div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- update status (kanan) --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-8 shadow-sm h-fit">
                        <h2 class="text-xl font-bold text-[#1A1A1A] mb-6">Perbarui Status</h2>
                        
                        <div class="mb-6">
                            <p class="text-sm font-bold text-[#1A1A1A] mb-2">Status saat ini</p>
                            @php
                                $statusColors = [
                                    'menunggu' => 'bg-yellow-500',
                                    'baru' => 'bg-yellow-500',
                                    'diproses' => 'bg-blue-500',
                                    'selesai' => 'bg-green-500',
                                    'ditolak' => 'bg-red-500',
                                ];
                                $statusTextColors = [
                                    'menunggu' => 'text-yellow-600',
                                    'baru' => 'text-yellow-600',
                                    'diproses' => 'text-blue-600',
                                    'selesai' => 'text-green-600',
                                    'ditolak' => 'text-red-600',
                                ];
                                $statusLabels = [
                                    'menunggu' => 'Menunggu',
                                    'baru' => 'Menunggu',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ];
                                $dotColor = $statusColors[$report->status] ?? 'bg-yellow-500';
                                $textColor = $statusTextColors[$report->status] ?? 'text-yellow-600';
                                $statusText = $statusLabels[$report->status] ?? 'Menunggu';
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full {{ $dotColor }}"></div>
                                <span class="text-sm font-medium {{ $textColor }}">{{ $statusText }}</span>
                            </div>
                        </div>

                        <form x-ref="updateForm" action="{{ route('reports.updateStatus', $report) }}" method="POST" @submit.prevent="showConfirmModal = true">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-5">
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-2">Pilih Status Baru</label>
                                <select name="status" class="w-full rounded-xl border-gray-300 bg-[#F8F7F7] focus:border-[#A94438] focus:ring-[#A94438] text-sm py-3 px-4">
                                    <option value="menunggu" {{ ($report->status === 'menunggu' || $report->status === 'baru') ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            
                            <div class="mb-5">
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-2">Catatan</label>
                                <textarea name="catatan_resolusi" rows="4" placeholder="Ketik Catatan..." class="w-full rounded-xl border-gray-300 bg-[#F8F7F7] focus:border-[#A94438] focus:ring-[#A94438] text-sm p-4">{{ old('catatan_resolusi', $report->catatan_resolusi) }}</textarea>
                            </div>
                            
                            <input type="hidden" name="status_fasilitas" value="{{ $report->facility?->status ?? 'aktif' }}">
                            
                            <button type="submit" class="w-full mt-2 bg-[#C84F4F] hover:bg-[#A94438] text-white py-3.5 rounded-xl font-bold text-sm transition cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Perbarui Status</span>
                            </button>
                        </form>
                    </div>

                </div>
            @else
            
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- info laporan pengguna --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- detail laporan --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-8">
                            
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl font-bold text-[#1A1A1A]">Detail Laporan</h2>
                                
                                {{-- status badge --}}
                                @php
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-500',
                                        'baru' => 'bg-yellow-500',
                                        'diproses' => 'bg-blue-500',
                                        'selesai' => 'bg-green-500',
                                        'ditolak' => 'bg-red-500',
                                    ];
                                    $statusTextColors = [
                                        'menunggu' => 'text-yellow-600',
                                        'baru' => 'text-yellow-600',
                                        'diproses' => 'text-blue-500',
                                        'selesai' => 'text-green-500',
                                        'ditolak' => 'text-red-500',
                                    ];
                                    $statusLabels = [
                                        'menunggu' => 'Menunggu',
                                        'baru' => 'Menunggu',
                                        'diproses' => 'Diproses',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak',
                                    ];
                                    $dotColor = $statusColors[$report->status] ?? 'bg-yellow-500';
                                    $textColor = $statusTextColors[$report->status] ?? 'text-yellow-600';
                                    $label = $statusLabels[$report->status] ?? 'Menunggu';
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

                    {{-- fasilitas terkait (kanan) --}}
                    <div class="space-y-6">
                        
                        {{-- kartu fasilitas & foto --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            
                            @if($report->foto)
                                <div class="mb-4 overflow-hidden rounded-xl h-48 w-full bg-gray-100 border border-gray-100 relative group cursor-pointer" @click="zoomImage = '{{ asset('storage/' . $report->foto) }}'">
                                    <img
                                        src="{{ asset('storage/' . $report->foto) }}"
                                        alt="Foto kondisi kerusakan"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    >
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white font-medium text-xs gap-1.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                        Klik untuk memperbesar
                                    </div>
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

                        {{-- proses status laporan --}}
                        @if(auth()->user()->role === 'petugas')
                            
                            <div class="bg-white rounded-xl border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-[#1A1A1A] mb-4">Proses Laporan</h3>
                                
                                <form x-ref="updateFormSecondary" action="{{ route('reports.updateStatus', $report) }}" method="POST" @submit.prevent="showConfirmModal = true">
                                    @csrf
                                    @method('PATCH')

                                    <div class="space-y-4">
                                        
                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Laporan</label>
                                            <select name="status" id="status" required class="w-full rounded-lg border-gray-300 bg-[#F8F7F7] focus:border-[#A94438] focus:ring-[#A94438] text-sm">
                                                <option value="menunggu" {{ ($report->status === 'menunggu' || $report->status === 'baru') ? 'selected' : '' }}>Menunggu</option>
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

            @endif

        </div>

        {{-- Modal Detail Profil Pelapor --}}
        <div x-show="showProfileModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="showProfileModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative border border-gray-100">
                <button type="button" @click="showProfileModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                
                <div class="flex flex-col items-center text-center pb-4 border-b border-gray-100 mb-5">
                    <div class="w-16 h-16 rounded-full bg-[#511E1D] text-white text-2xl font-bold flex items-center justify-center mb-3 shadow-md">
                        {{ strtoupper(substr($report->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $report->user->name ?? '-' }}</h3>
                    <span class="mt-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 capitalize">
                        {{ $report->user->role ?? 'Pengguna' }}
                    </span>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Email</span>
                        <span class="text-gray-900 font-semibold">{{ $report->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">NIM / NIP</span>
                        <span class="text-gray-900 font-semibold">{{ $report->user->nim_nip ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">No. Telepon / WA</span>
                        <span class="text-gray-900 font-semibold">{{ $report->user->no_telepon ?? $report->user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-gray-500 font-medium">Status Akun</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ ucfirst($report->user->status_verifikasi ?? 'Terverifikasi') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="button" @click="showProfileModal = false" class="w-full bg-[#511E1D] hover:bg-[#3d1615] text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Perbarui Status --}}
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="showConfirmModal = false" class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl text-center border border-gray-100">
                <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Perubahan</h3>
                <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin memperbarui status laporan ini?</p>
                <div class="flex gap-3">
                    <button type="button" @click="showConfirmModal = false" class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="button" @click="if($refs.updateForm) { $refs.updateForm.submit(); } else if($refs.updateFormSecondary) { $refs.updateFormSecondary.submit(); }" class="w-1/2 py-2.5 bg-[#C84F4F] hover:bg-[#A94438] text-white font-semibold rounded-xl text-sm transition">
                        Ya, Perbarui
                    </button>
                </div>
            </div>
        </div>

        {{-- Lightbox Modal Zoom Foto Kerusakan --}}
        <div x-show="zoomImage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="zoomImage = null">
            <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.stop>
                <button type="button" @click="zoomImage = null" class="absolute -top-12 right-0 text-white hover:text-gray-300 bg-white/10 hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img :src="zoomImage" class="max-w-full max-h-[80vh] rounded-2xl shadow-2xl object-contain border border-white/20">
                <p class="text-white/80 text-xs mt-3">Klik di luar gambar atau tombol X untuk menutup</p>
            </div>
        </div>

    </div>

</x-app-layout>