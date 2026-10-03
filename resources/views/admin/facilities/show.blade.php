<x-app-layout>
    @php
        $nonaktif    = $facility->status === 'nonaktif';
        $pesanSukses = session('success') ?? session('status');
    @endphp

    <div class="min-h-screen bg-[#F8F7F7]">

        {{-- header bar --}}
        <div class="flex items-center gap-4 bg-[#F5F0ED] px-6 sm:px-10 py-5">
            <a href="{{ route('admin.fasilitas.index') }}" class="text-[#47201B] hover:opacity-70 transition" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-[#47201B]">Detail Fasilitas</h1>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">

            @if ($pesanSukses)
                <div id="banner-sukses"
                     role="status"
                     class="flex items-center justify-between gap-4 rounded-lg border border-[#166534] bg-[#DCFCE7] px-6 py-4 text-base font-semibold text-[#166534] transition-opacity duration-500">
                    <span>{{ $pesanSukses }}</span>

                    <button type="button"
                            onclick="document.getElementById('banner-sukses').remove()"
                            class="text-[#166534] hover:text-[#14532D]"
                            aria-label="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>

                <script>
                    setTimeout(function () {
                        const el = document.getElementById('banner-sukses');
                        if (!el) return;
                        el.classList.add('opacity-0');
                        setTimeout(function () { el.remove(); }, 500);
                    }, 4000);
                </script>
            @endif

            {{-- kartu detail fasilitas --}}
            <div class="flex flex-col sm:flex-row gap-5 bg-[#F5F0ED] rounded-2xl p-5 sm:p-7">
                <img src="{{ $facility->foto_url }}" alt="{{ $facility->nama_fasilitas }}"
                     class="w-full sm:w-60 h-48 sm:h-44 rounded-xl object-cover flex-shrink-0">

                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $facility->nama_fasilitas }}</h2>

                            <div class="mt-2">
                                @if ($facility->status === 'aktif')
                                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-green-600">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>Tersedia
                                    </span>
                                @elseif ($facility->status === 'dalam_perbaikan')
                                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-yellow-600">
                                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>Dalam Perbaikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('admin.facilities.edit', $facility) }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C0453F] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#A83B32] transition whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2.414a2 2 0 01.586-1.414z" />
                            </svg>
                            Edit Fasilitas
                        </a>
                    </div>

                    <div class="mt-3 space-y-1 text-gray-600">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $facility->lokasi }}
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $facility->kapasitas ?? '-' }} Orang
                        </p>
                        <p class="text-xs text-gray-500">Tipe: {{ $facility->tipeLabel() }}</p>
                    </div>

                    <p class="mt-3 text-gray-900">
                        {{ $facility->deskripsi ?: 'Belum ada deskripsi.' }}
                    </p>
                </div>
            </div>

            {{-- pengaturan fasilitas: aktifkan / nonaktifkan --}}
            <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm border border-gray-100">
                <h2 class="mb-4 text-lg font-bold text-[#47201B]">Pengaturan Fasilitas</h2>

                @if ($nonaktif)
                    <h3 class="text-xl font-medium text-gray-900">Aktifkan Fasilitas</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Fasilitas ini sedang nonaktif, sehingga tidak dapat dipesan.
                        Aktifkan kembali agar fasilitas dapat dipesan lagi.
                    </p>

                    <form id="form-aktifkan" action="{{ route('admin.facilities.activate', $facility) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>

                    <button type="button"
                            data-modal-open="modal-aktifkan"
                            class="rounded-xl bg-[#166534] px-6 py-3 text-base font-semibold text-white hover:bg-[#14532D] transition">
                        Aktifkan Fasilitas
                    </button>
                @else
                    <h3 class="text-xl font-medium text-gray-900">Nonaktifkan Fasilitas</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Fasilitas tidak akan bisa dipesan selama nonaktif.
                        Data fasilitas tidak dihapus dan bisa diaktifkan kembali kapan saja.
                    </p>

                    <form id="form-nonaktif" action="{{ route('admin.facilities.deactivate', $facility) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>

                    <button type="button"
                            data-modal-open="modal-nonaktif"
                            class="rounded-xl bg-[#B91C1C] px-6 py-3 text-base font-semibold text-white hover:bg-[#991B1B] transition">
                        Nonaktifkan Fasilitas
                    </button>
                @endif
            </div>

            {{-- riwayat reservasi fasilitas ini --}}
            <div id="riwayat-reservasi">
                <h2 class="text-lg font-bold text-[#47201B] mb-3">Riwayat Reservasi</h2>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    @if ($reservations->count() > 0)

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-[#F5EBE9]">
                                    <tr>
                                        <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                        <th class="px-6 py-4 text-left font-bold text-[#A94438]">Pemesan</th>
                                        <th class="px-6 py-4 text-left font-bold text-[#A94438]">Jadwal</th>
                                        <th class="px-6 py-4 text-left font-bold text-[#A94438]">Status</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($reservations as $reservation)
                                        <tr class="hover:bg-gray-50 transition">

                                            {{-- no --}}
                                            <td class="px-6 py-5 text-gray-700 font-medium">
                                                {{ $reservations->firstItem() + $loop->index }}.
                                            </td>

                                            {{-- pemesan --}}
                                            <td class="px-6 py-5">
                                                <p class="font-medium text-gray-900">{{ $reservation->user?->name ?? '-' }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $reservation->user?->email ?? '-' }}</p>
                                            </td>

                                            {{-- jadwal --}}
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <p class="font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($reservation->tanggal)->locale('id')->translatedFormat('d M Y') }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ substr($reservation->waktu_mulai, 0, 5) }} - {{ substr($reservation->waktu_selesai, 0, 5) }}
                                                </p>
                                            </td>

                                            {{-- status & warna --}}
                                            <td class="px-6 py-5">
                                                @php
                                                    $statusText  = ucfirst($reservation->status);
                                                    $statusColor = 'text-red-600';
                                                    $dotColor    = 'bg-red-500';

                                                    if ($reservation->status === 'menunggu') {
                                                        $statusText  = 'Menunggu';
                                                        $statusColor = 'text-yellow-600';
                                                        $dotColor    = 'bg-yellow-500';
                                                    } elseif ($reservation->status === 'disetujui') {
                                                        $statusText  = 'Disetujui';
                                                        $statusColor = 'text-green-600';
                                                        $dotColor    = 'bg-green-500';
                                                    } elseif ($reservation->status === 'dibatalkan') {
                                                        $statusText  = 'Dibatalkan';
                                                        $statusColor = 'text-gray-500';
                                                        $dotColor    = 'bg-gray-400';
                                                    }
                                                @endphp
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                                    <span class="text-sm font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- jumlah data + nomor halaman --}}
                        @include('partials.pagination-tabel', ['paginator' => $reservations, 'fragment' => 'riwayat-reservasi'])

                    @else

                        <div class="px-6 py-16 text-center">
                            <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-[#F5EBE9] mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#A94438]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h4m2-10h.01M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800">Belum Ada Reservasi</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Belum ada reservasi untuk fasilitas ini.
                            </p>
                        </div>

                    @endif
                </div>
            </div>

        </div>
    </div>

    @if ($nonaktif)
        {{-- Popup konfirmasi aktifkan --}}
        <div id="modal-aktifkan" data-modal data-form="form-aktifkan"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
             role="dialog" aria-modal="true" aria-labelledby="judul-aktifkan">

            <div class="relative w-full max-w-md rounded-3xl bg-white px-8 pt-16 pb-8 shadow-2xl">
                <button type="button" data-modal-close
                        class="absolute top-5 right-5 text-black hover:text-[#7B1E1E] transition"
                        aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>

                <h2 id="judul-aktifkan" class="text-center text-2xl font-bold leading-snug text-black">
                    Anda yakin ingin mengaktifkan fasilitas ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    {{ $facility->nama_fasilitas }} akan dapat dipesan kembali setelah diaktifkan.
                </p>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <button type="button" data-modal-close
                            class="h-14 rounded-2xl bg-[#7B3A36] text-base font-bold text-white shadow-md hover:bg-[#5F1717] transition">
                        Batal
                    </button>

                    <button type="button" data-modal-confirm
                            class="h-14 rounded-2xl bg-[#F5F0F7] text-base font-bold text-black shadow-md hover:bg-[#EAE0EE] transition">
                        Ya, Aktifkan
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Popup konfirmasi nonaktifkan --}}
        <div id="modal-nonaktif" data-modal data-form="form-nonaktif"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
             role="dialog" aria-modal="true" aria-labelledby="judul-nonaktif">

            <div class="relative w-full max-w-md rounded-3xl bg-white px-8 pt-16 pb-8 shadow-2xl">
                <button type="button" data-modal-close
                        class="absolute top-5 right-5 text-black hover:text-[#7B1E1E] transition"
                        aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>

                <h2 id="judul-nonaktif" class="text-center text-2xl font-bold leading-snug text-black">
                    Anda yakin ingin menonaktifkan fasilitas ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    {{ $facility->nama_fasilitas }} tidak akan bisa dipesan sampai diaktifkan kembali.
                </p>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <button type="button" data-modal-close
                            class="h-14 rounded-2xl bg-[#7B3A36] text-base font-bold text-white shadow-md hover:bg-[#5F1717] transition">
                        Batal
                    </button>

                    <button type="button" data-modal-confirm
                            class="h-14 rounded-2xl bg-[#F5F0F7] text-base font-bold text-black shadow-md hover:bg-[#EAE0EE] transition">
                        Ya, Nonaktifkan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        (function () {
            function buka(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Fokus ke tombol Batal (tombol tutup terakhir di dalam popup)
                const tombolTutup = modal.querySelectorAll('[data-modal-close]');
                if (tombolTutup.length) tombolTutup[tombolTutup.length - 1].focus();
            }

            function tutup(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const modal = document.getElementById(btn.dataset.modalOpen);
                    if (modal) buka(modal);
                });
            });

            document.querySelectorAll('[data-modal]').forEach(function (modal) {
                modal.querySelectorAll('[data-modal-close]').forEach(function (btn) {
                    btn.addEventListener('click', function () { tutup(modal); });
                });

                modal.querySelector('[data-modal-confirm]').addEventListener('click', function () {
                    this.disabled = true;
                    this.textContent = 'Memproses...';
                    document.getElementById(modal.dataset.form).submit();
                });

                // Klik area gelap di luar kartu = tutup
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) tutup(modal);
                });
            });

            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape') return;
                document.querySelectorAll('[data-modal]').forEach(tutup);
            });
        })();
    </script>
</x-app-layout>