<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Kelola Pengguna
        </h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('status'))
            <div class="bg-emerald-50 text-emerald-700 text-sm rounded-md px-4 py-2 mb-4">
                {{ session('status') }}
            </div>
        @endif

        {{-- search + filter + tambah --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">

            <form method="GET" class="flex flex-1 items-center gap-3">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari Nama/Email..."
                       class="flex-1 min-w-0 px-4 py-3 bg-[#F5EFE9] border-0 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-[#511E1D]">

                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-[#5A2A27] text-white text-sm font-medium rounded-lg shadow-sm hover:bg-[#47201B] transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Filter
                </button>
            </form>

            <a href="{{ route('admin.pengguna.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C0453F] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#A83B32] transition whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 9v6m3-3H9m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tambah Pengguna
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-[#F5EBE8]">
                        <tr>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Pengguna</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">NIM/NIP</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">No. Telepon</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Status</th>
                            <th class="text-left px-6 py-5 font-bold text-[#A83B32]">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-5">
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </td>

                                <td class="px-6 py-5 text-gray-700">
                                    {{ $user->nim_nip ?? '-' }}
                                </td>

                                <td class="px-6 py-5 text-gray-700">
                                    {{ $user->no_telepon ?? '-' }}
                                </td>

                                <td class="px-6 py-5">
                                    @if ($user->status_verifikasi === 'terverifikasi')
                                        <span class="inline-flex items-center gap-2 font-semibold text-green-500">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>Terverifikasi
                                        </span>
                                    @elseif ($user->status_verifikasi === 'ditolak')
                                        <span class="inline-flex items-center gap-2 font-semibold text-red-500">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 font-semibold text-yellow-500">
                                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    @if ($user->status_verifikasi === 'menunggu')

                                        <div class="flex items-center gap-2">

                                            <form action="{{ route('admin.pengguna.verify', $user) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg bg-green-100 text-green-600 text-xs font-semibold hover:bg-green-200 transition">
                                                    Verifikasi
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.pengguna.reject', $user) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg bg-red-100 text-red-600 text-xs font-semibold hover:bg-red-200 transition">
                                                    Tolak
                                                </button>
                                            </form>

                                        </div>

                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>