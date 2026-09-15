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

        <div class="bg-white border border-stone-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white text-xs uppercase">
                    <tr>
                        <th class="text-left px-4 py-3">Fasilitas</th>
                        <th class="text-left px-4 py-3">Tipe</th>
                        <th class="text-left px-4 py-3">Kapasitas</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facilities as $facility)
                        <tr class="border-t border-stone-100">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800">{{ $facility->nama_fasilitas }}</p>
                                <p class="text-xs text-gray-400">{{ $facility->lokasi }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $facility->tipeLabel() }}</td>
                            <td class="px-4 py-3">{{ $facility->kapasitas ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($facility->status === 'aktif')
                                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Tersedia</span>
                                @elseif ($facility->status === 'dalam_perbaikan')
                                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">Dalam Perbaikan</span>
                                @else
                                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}"
                                       class="text-slate-600 text-xs font-semibold hover:underline">Edit</a>

                                    @if ($facility->status !== 'nonaktif')
                                        <form action="{{ route('admin.facilities.deactivate', $facility) }}" method="POST"
                                              onsubmit="return confirm('Nonaktifkan {{ $facility->nama_fasilitas }}?')">
                                            @csrf @method('PATCH')
                                            <button class="text-red-600 text-xs font-semibold hover:underline">Nonaktifkan</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.facilities.activate', $facility) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="text-emerald-600 text-xs font-semibold hover:underline">Aktifkan</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $facilities->links() }}</div>
    </div>
</x-app-layout>
