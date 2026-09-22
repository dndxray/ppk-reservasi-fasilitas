<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Petugas') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                Selamat datang, Petugas {{ Auth::user()->name }}!
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <a href="{{ route('petugas.reservations.queue') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Antrian Reservasi
                </h3>
                <p class="text-sm text-gray-500">
                    Lihat dan kelola antrian reservasi fasilitas
                </p>
            </a>

            <a href="{{ route('reports.antrian') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Antrian Laporan
                </h3>
                <p class="text-sm text-gray-500">
                    Lihat laporan kerusakan yang masuk
                </p>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-1">
                    Profil Saya
                </h3>
                <p class="text-sm text-gray-500">
                    Kelola data akun petugas
                </p>
            </a>

        </div>
    </div>
</div>
</x-app-layout>
