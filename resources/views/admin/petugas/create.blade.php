<x-app-layout>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-6">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">
                    Tambah Petugas
                </h1>

                <p class="mt-1 text-gray-500">
                    Lengkapi data petugas untuk menambahkan akun baru.
                </p>
            </div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 p-4 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <div class="rounded-2xl bg-white p-8 shadow-sm">

                <form
                    action="{{ route('admin.petugas.store') }}"
                    method="POST"
                >
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-6">
                        <label
                            for="name"
                            class="mb-2 block text-base font-semibold text-gray-700"
                        >
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-xl border border-gray-300 px-4 py-4 text-base outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                    </div>

                    {{-- Email --}}
                    <div class="mb-6">
                        <label
                            for="email"
                            class="mb-2 block text-base font-semibold text-gray-700"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="petugas@contoh.com"
                            class="w-full rounded-xl border border-gray-300 px-4 py-4 text-base outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                    </div>

                    {{-- NIM/NIP + Telepon --}}
                    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label
                                for="nim_nip"
                                class="mb-2 block text-base font-semibold text-gray-700"
                            >
                                NIM / NIP
                            </label>

                            <input
                                type="text"
                                id="nim_nip"
                                name="nim_nip"
                                value="{{ old('nim_nip') }}"
                                placeholder="Masukkan NIM atau NIP"
                                class="w-full rounded-xl border border-gray-300 px-4 py-4 text-base outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100"
                                required
                            >
                        </div>

                        <div>
                            <label
                                for="no_telepon"
                                class="mb-2 block text-base font-semibold text-gray-700"
                            >
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                id="no_telepon"
                                name="no_telepon"
                                value="{{ old('no_telepon') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border border-gray-300 px-4 py-4 text-base outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100"
                                required
                            >
                        </div>

                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label
                            for="password"
                            class="mb-2 block text-base font-semibold text-gray-700"
                        >
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            class="w-full rounded-xl border border-gray-300 px-4 py-4 text-base outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-8">
                        <label
                            for="password_confirmation"
                            class="mb-2 block text-base font-semibold text-gray-700"
                        >
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            class="w-full rounded-xl border border-gray-300 px-4 py-4 text-base outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                    </div>

                    {{-- Garis --}}
                    <div class="mb-6 border-t border-gray-200"></div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-4">

                        <a
                            href="{{ route('admin.petugas.index') }}"
                            class="rounded-xl border border-gray-300 px-7 py-3 text-base font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="rounded-xl bg-red-700 px-7 py-3 text-base font-semibold text-white transition hover:bg-red-800"
                        >
                            Simpan Petugas
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>