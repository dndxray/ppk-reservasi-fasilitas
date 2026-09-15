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

        <div class="flex justify-between items-center mb-4 gap-3">
            <form method="GET" class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau email pengguna"
                    class="w-full max-w-sm border-stone-300 rounded-md text-sm"
                >
            </form>

            <a
                href="{{ route('admin.pengguna.create') }}"
                class="bg-slate-800 text-white text-sm font-semibold rounded-md px-4 py-2 hover:bg-slate-900 whitespace-nowrap"
            >
                + Tambah Pengguna
            </a>
        </div>

        <div class="bg-white border border-stone-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">

                <thead class="bg-slate-800 text-white text-xs uppercase">
                    <tr>
                        <th class="text-left px-4 py-3">Pengguna</th>
                        <th class="text-left px-4 py-3">NIM/NIP</th>
                        <th class="text-left px-4 py-3">No. Telepon</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
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
                                        Terverifikasi
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

                            <td class="px-4 py-3">
                                @if ($user->status_verifikasi === 'menunggu')

                                    <div class="flex gap-3">

                                        <form
                                            action="{{ route('admin.pengguna.verify', $user) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="text-emerald-600 text-xs font-semibold hover:underline"
                                            >
                                                Verifikasi
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.pengguna.reject', $user) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="text-red-600 text-xs font-semibold hover:underline"
                                            >
                                                Tolak
                                            </button>
                                        </form>

                                    </div>

                                @else
                                    <span class="text-xs text-gray-400">
                                        -
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>