<x-app-layout>
    {{-- Font Poppins seperti pada desain referensi --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="w-full px-6 sm:px-10 lg:px-12 py-8" style="font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;">

        {{-- Header: tombol kembali + judul sejajar --}}
        <div class="flex items-center gap-5 mb-6">
            <a href="{{ route('admin.petugas.index') }}"
               class="text-[#5A2A27] hover:text-[#7B1E1E] transition"
               aria-label="Kembali">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h1 class="text-3xl font-bold text-black">
                    Tambah Petugas
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi data petugas untuk menambahkan akun baru.
                </p>
            </div>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 p-4">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card full width --}}
        <div class="w-full bg-white rounded-3xl border border-gray-300 px-6 py-8 sm:px-12 sm:py-12">

            <form action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-6">
                    <label for="name" class="block text-base font-bold text-black mb-3">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        required
                        class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">
                </div>

                {{-- Email --}}
                <div class="mb-6">
                    <label for="email" class="block text-base font-bold text-black mb-3">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="petugas@contoh.com"
                        required
                        class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">
                </div>

                {{-- NIM/NIP + Nomor Telepon --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-[60px] gap-y-6 mb-6">
                    <div>
                        <label for="nim_nip" class="block text-base font-bold text-black mb-3">
                            NIM / NIP
                        </label>

                        <input
                            type="text"
                            id="nim_nip"
                            name="nim_nip"
                            value="{{ old('nim_nip') }}"
                            placeholder="Masukkan NIM atau NIP"
                            required
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-base font-bold text-black mb-3">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            id="no_telepon"
                            name="no_telepon"
                            value="{{ old('no_telepon') }}"
                            placeholder="08xxxxxxxxxx"
                            required
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">
                    </div>
                </div>

                {{-- Password + Konfirmasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-[60px] gap-y-6 mb-10">
                    <div>
                        <label for="password" class="block text-base font-bold text-black mb-3">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-base font-bold text-black mb-3">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            required
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.petugas.index') }}"
                       class="px-8 h-12 inline-flex items-center rounded-xl border border-[#DCC9C5] text-[#5A2A27] font-medium hover:bg-[#F5EEEB] transition">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-8 h-12 rounded-xl bg-[#7B1E1E] text-white font-medium hover:bg-[#5F1717] transition">
                        Simpan Petugas
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>