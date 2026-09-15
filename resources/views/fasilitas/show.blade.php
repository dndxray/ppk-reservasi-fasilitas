<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Fasilitas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-start">
                    <h1 class="text-xl font-bold text-gray-800">
                        {{ $fasilitas->nama_fasilitas }}
                    </h1>

                    @if($fasilitas->sedangAktif())
                        <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Aktif</span>
                    @elseif($fasilitas->dalamPerbaikan())
                        <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-700">Dalam Perbaikan</span>
                    @else
                        <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">Nonaktif</span>
                    @endif
                </div>

                <dl class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Tipe</dt>
                        <dd class="text-gray-800">{{ $fasilitas->tipe }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Lokasi</dt>
                        <dd class="text-gray-800">{{ $fasilitas->lokasi }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Kapasitas</dt>
                        <dd class="text-gray-800">{{ $fasilitas->kapasitas ?? '-' }} orang</dd>
                    </div>
                </dl>

                @if($fasilitas->deskripsi)
                    <div class="mt-5">
                        <p class="text-gray-500 text-sm">Deskripsi</p>
                        <p class="mt-1 text-gray-800 text-sm">{{ $fasilitas->deskripsi }}</p>
                    </div>
                @endif
            </div>

            {{-- Ketersediaan slot 30 menit, jam operasional 07.00 - 20.00 --}}
            <div class="mt-6 bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex flex-wrap justify-between items-end gap-3">
                    <div>
                        <h2 class="font-semibold text-gray-800">Ketersediaan Jadwal</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Slot 30 menit, jam operasional 07.00 &ndash; 20.00.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('fasilitas.show', $fasilitas) }}" class="flex items-end gap-2">
                        <div>
                            <label for="tanggal" class="block text-sm text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                                   class="mt-1 block rounded border-gray-300 text-sm">
                        </div>
                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white text-sm rounded hover:bg-gray-700">
                            Cek
                        </button>
                    </form>
                </div>

                @if($fasilitas->dalamPerbaikan())
                    <p class="mt-4 text-sm text-yellow-700 bg-yellow-50 p-3 rounded">
                        Fasilitas sedang dalam perbaikan, seluruh jadwal belum dapat direservasi.
                    </p>
                @elseif(!$fasilitas->sedangAktif())
                    <p class="mt-4 text-sm text-gray-700 bg-gray-100 p-3 rounded">
                        Fasilitas sedang nonaktif.
                    </p>
                @endif

                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2">
                    @foreach($daftarSlot as $slot)
                        <div class="border rounded px-3 py-2 text-sm
                                    {{ $slot['terisi'] ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700' }}">
                            <div>{{ $slot['mulai'] }} &ndash; {{ $slot['selesai'] }}</div>
                            <div class="text-xs">{{ $slot['terisi'] ? 'Terisi' : 'Tersedia' }}</div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Jadwal tanggal {{ $tanggal }}. Slot terisi termasuk reservasi yang menunggu persetujuan.
                </p>
            </div>

            <div class="mt-6">
                <a href="{{ route('fasilitas.index') }}" class="text-sm text-blue-600 hover:underline">
                    &larr; Kembali ke daftar fasilitas
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
