<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Kelola Fasilitas</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('status'))
            <div class="bg-emerald-50 text-emerald-700 text-sm rounded-md px-4 py-2 mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4 gap-3">
            <form method="GET" class="flex-1">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari nama atau lokasi fasilitas"
                       class="w-full max-w-sm border-stone-300 rounded-md text-sm">
            </form>
            <a href="{{ route('admin.facilities.create') }}"
               class="bg-slate-800 text-white text-sm font-semibold rounded-md px-4 py-2 hover:bg-slate-900 whitespace-nowrap">
                + Tambah Fasilitas
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#F5EBE8]">
                        <tr>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Fasilitas</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Tipe</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Kapasitas</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Status</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($facilities as $facility)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-5">
                                    <p class="font-medium text-gray-900">{{ $facility->nama_fasilitas }}</p>
                                    <p class="text-xs text-gray-400">{{ $facility->lokasi }}</p>
                                </td>
                                <td class="px-6 py-5 text-gray-700">{{ $facility->tipeLabel() }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $facility->kapasitas ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    @if ($facility->status === 'aktif')
                                        <span class="inline-flex items-center gap-2 font-semibold text-green-500">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>Tersedia
                                        </span>
                                    @elseif ($facility->status === 'dalam_perbaikan')
                                        <span class="inline-flex items-center gap-2 font-semibold text-yellow-500">
                                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>Dalam Perbaikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 font-semibold text-gray-500">
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.facilities.edit', $facility) }}"
                                           class="px-3 py-2 rounded-lg bg-gray-100 text-slate-700 text-xs font-semibold hover:bg-gray-200 transition">
                                            Edit
                                        </a>

                                        @if ($facility->status !== 'nonaktif')
                                            <form action="{{ route('admin.facilities.deactivate', $facility) }}" method="POST"
                                                  onsubmit="return confirm('Nonaktifkan {{ $facility->nama_fasilitas }}?')">
                                                @csrf @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg bg-red-100 text-red-600 text-xs font-semibold hover:bg-red-200 transition">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.facilities.activate', $facility) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg bg-green-100 text-green-600 text-xs font-semibold hover:bg-green-200 transition">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $facilities->links() }}</div>
    </div>
</x-app-layout>