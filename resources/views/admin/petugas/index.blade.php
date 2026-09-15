<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Kelola Petugas
        </h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('status'))
            <div class="bg-emerald-50 text-emerald-700 text-sm rounded-md px-4 py-2 mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4 gap-3">

            <form method="GET" class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau email petugas"
                    class="w-full max-w-sm border-stone-300 rounded-md text-sm"
                >
            </form>

            <a
                href="{{ route('admin.petugas.create') }}"
                class="bg-slate-800 text-white text-sm font-semibold rounded-md px-4 py-2 hover:bg-slate-900 whitespace-nowrap"
            >
                + Tambah Petugas
            </a>

        </div>

        <div class="bg-white border border-stone-200 rounded-lg overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-slate-800 text-white text-xs uppercase">
                    <tr>
                        <th class="text-left px-4 py-3">Petugas</th>
                        <th class="text-left px-4 py-3">NIM/NIP</th>
                        <th class="text-left px-4 py-3">No. Telepon</th>
                        <th class="text-left px-4 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($staff as $user)

                        <tr class="border-t border-stone-100">

                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800">
                                    {{ $user->name }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    {{ $user->email }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->nim_nip ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->no_telepon ?? '-' }}
                            </td>

                            <td class="px-4 py-3">

                                @if ($user->status_verifikasi === 'terverifikasi')

                                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        Aktif
                                    </span>

                                @elseif ($user->status_verifikasi === 'ditolak')

                                    <span class="text-xs font-semibold text-red-700 bg-red-50 px-2 py-0.5 rounded-full">
                                        Ditolak
                                    </span>

                                @else

                                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                                        Menunggu
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="mt-6">
            {{ $staff->links() }}
        </div>

    </div>
</x-app-layout>