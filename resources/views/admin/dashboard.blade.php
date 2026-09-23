<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Admin') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                Selamat datang, Admin {{ Auth::user()->name }}!
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <a href="{{ route('admin.pengguna.index') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Kelola Pengguna
                </h3>
                <p class="text-sm text-gray-500">
                    Kelola data pengguna atau mahasiswa
                </p>
            </a>

            <a href="{{ route('admin.petugas.index') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Kelola Petugas
                </h3>
                <p class="text-sm text-gray-500">
                    Kelola data petugas sistem
                </p>
            </a>

            <a href="{{ route('admin.fasilitas.index') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Kelola Fasilitas
                </h3>
                <p class="text-sm text-gray-500">
                    Tambah, ubah, dan kelola fasilitas kampus
                </p>
            </a>

            <a href="{{ route('admin.rekap.index') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Rekap Laporan
                </h3>
                <p class="text-sm text-gray-500">
                    Lihat rekap laporan dan aktivitas fasilitas
                </p>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Profil Saya
                </h3>
                <p class="text-sm text-gray-500">
                    Kelola data akun admin
                </p>
            </a>

        </div>
    </div>
</div>
</x-app-layout>
