<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        <div class="px-6 py-8">

            <!-- Breadcrumb -->
            <div class="mb-6">
                <p class="text-sm text-gray-500">
                    Dashboard
                    <span class="mx-2">/</span>
                    Pelaporan
                    <span class="mx-2">/</span>
                    Antrian Laporan
                </p>
            </div>

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Antrian Laporan
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola laporan kerusakan fasilitas yang masuk.
                </p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-800">
                        Daftar Laporan Kerusakan
                    </h2>
                </div>

                @if($reports->count() > 0)

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-50">
                                <tr>

                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                        No
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                        Pelapor
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                        Fasilitas
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                        Kategori
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                        Tanggal
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                        Aksi
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @foreach($reports as $report)

                                    <tr class="hover:bg-gray-50 transition">

                                        <!-- No -->
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $loop->iteration }}
                                        </td>

                                        <!-- Reporter -->
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800">
                                                {{ $report->user->name ?? '-' }}
                                            </p>
                                        </td>

                                        <!-- Facility -->
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $report->facility->nama_fasilitas ?? '-' }}
                                        </td>

                                        <!-- Category -->
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $report->kategori }}
                                        </td>

                                        <!-- Date -->
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $report->created_at?->format('d/m/Y') }}
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">

                                            @if($report->status === 'baru')

                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                                    Baru
                                                </span>

                                            @elseif($report->status === 'diproses')

                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                                    Diproses
                                                </span>

                                            @elseif($report->status === 'selesai')

                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                    Selesai
                                                </span>

                                            @elseif($report->status === 'ditolak')

                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                                    Ditolak
                                                </span>

                                            @endif

                                        </td>

                                        <!-- Action -->
                                        <td class="px-6 py-4 text-center">

                                            <a href="{{ route('reports.show', $report) }}"
                                               class="inline-flex items-center px-4 py-2 text-xs font-semibold text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition">
                                                Proses
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    <!-- Mobile Card -->
                    <div class="md:hidden divide-y divide-gray-100">

                        @foreach($reports as $report)

                            <div class="p-5">

                                <div class="flex items-start justify-between gap-4">

                                    <div>

                                        <p class="text-xs text-gray-400 mb-1">
                                            Laporan #{{ $report->id }}
                                        </p>

                                        <h3 class="font-semibold text-gray-800">
                                            {{ $report->facility->nama_fasilitas ?? '-' }}
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $report->kategori }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Pelapor:
                                            {{ $report->user->name ?? '-' }}
                                        </p>

                                    </div>


                                    @if($report->status === 'baru')

                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                            Baru
                                        </span>

                                    @elseif($report->status === 'diproses')

                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                            Diproses
                                        </span>

                                    @elseif($report->status === 'selesai')

                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            Selesai
                                        </span>

                                    @elseif($report->status === 'ditolak')

                                        <span class="shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                            Ditolak
                                        </span>

                                    @endif

                                </div>


                                <div class="flex items-center justify-between mt-4">

                                    <p class="text-xs text-gray-400">
                                        {{ $report->created_at?->format('d/m/Y') }}
                                    </p>

                                    <a href="{{ route('reports.show', $report) }}"
                                       class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                        Proses →
                                    </a>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <!-- Empty State -->
                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-gray-100 mb-4">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-7 h-7 text-gray-400"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M9 12h6m-6 4h4m2-10h.01M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>

                            </svg>

                        </div>

                        <h3 class="text-base font-semibold text-gray-800">
                            Belum Ada Laporan
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada laporan kerusakan yang masuk.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>