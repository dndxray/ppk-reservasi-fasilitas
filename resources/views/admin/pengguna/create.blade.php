<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">

        <div class="mb-6">
            <a href="{{ route('admin.pengguna.index') }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                ← Kembali
            </a>

            <h1 class="text-2xl font-bold text-gray-900 mt-3">
                Tambah Pengguna
            </h1>

            <p class="text-gray-600 mt-1">
                Tambahkan akun pengguna baru.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <form action="{{ route('admin.pengguna.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="name"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan nama lengkap">
                </div>

                <div class="mb-5">
                    <label for="email"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="contoh@email.com">
                </div>

                <div class="mb-5">
                    <label for="nim_nip"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        NIM / NIP
                    </label>

                    <input
                        type="text"
                        id="nim_nip"
                        name="nim_nip"
                        value="{{ old('nim_nip') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan NIM atau NIP">
                </div>

                <div class="mb-5">
                    <label for="no_telepon"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        id="no_telepon"
                        name="no_telepon"
                        value="{{ old('no_telepon') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div class="mb-5">
                    <label for="password"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan password">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Ulangi password">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.pengguna.index') }}"
                       class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                        Simpan Pengguna
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>