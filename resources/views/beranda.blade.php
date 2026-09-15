<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Loka &mdash; Sistem Reservasi &amp; Pelaporan Fasilitas Kampus
                    </h1>
                    <p class="mt-3 text-gray-600">
                        Cek ketersediaan ruang kelas, aula, laboratorium, alat, dan lapangan kampus,
                        ajukan reservasi, serta laporkan kerusakan fasilitas lewat satu aplikasi.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('fasilitas.index') }}"
                           class="px-4 py-2 bg-gray-800 text-white text-sm rounded hover:bg-gray-700">
                            Lihat Daftar Fasilitas
                        </a>

                        @guest
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                                Daftar
                            </a>
                        @endguest
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">
                    Fasilitas Tersedia
                </h2>

                @if($daftarFasilitas->isEmpty())
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg text-gray-600">
                        Belum ada data fasilitas.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($daftarFasilitas as $fasilitas)
                            <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $fasilitas->nama_fasilitas }}
                                    </h3>

                                    @if($fasilitas->sedangAktif())
                                        <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Aktif</span>
                                    @elseif($fasilitas->dalamPerbaikan())
                                        <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-700">Dalam Perbaikan</span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">Nonaktif</span>
                                    @endif
                                </div>

                                <p class="mt-2 text-sm text-gray-600">
                                    {{ $fasilitas->tipe }} &middot; {{ $fasilitas->lokasi }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Kapasitas: {{ $fasilitas->kapasitas ?? '-' }} orang
                                </p>

                                <a href="{{ route('fasilitas.show', $fasilitas) }}"
                                   class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                                    Lihat Detail
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('fasilitas.index') }}" class="text-sm text-blue-600 hover:underline">
                            Lihat semua fasilitas
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
