<x-app-layout>
    @php
        $pesanSukses = session('success') ?? session('status');

        // Tipe "alat" memakai kuantitas (unit); tipe lain memakai kapasitas (orang)
        $isAlat = $facility->tipe === 'alat';

        $opsiStatus = [
            'aktif'           => 'Aktif',
            'dalam_perbaikan' => 'Dalam Perbaikan',
            'nonaktif'        => 'Nonaktif',
        ];
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

                        {{-- alat: kuantitas (unit) | tipe lain: kapasitas (orang) --}}
                        @if ($isAlat)
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span>Kuantitas: {{ $facility->kuantitas ?? '-' }} Unit</span>
                            </p>
                        @else
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Kapasitas: {{ $facility->kapasitas ?? '-' }} Orang</span>
                            </p>
                        @endif

                        <p class="text-xs text-gray-500">Tipe: {{ $facility->tipeLabel() }}</p>
                    </div>

                    <p class="mt-3 text-gray-900">
                        {{ $facility->deskripsi ?: 'Belum ada deskripsi.' }}
                    </p>
                </div>
            </div>

            {{-- pengaturan fasilitas: ubah status lewat dropdown --}}
            <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-[#47201B]">Status Fasilitas</h2>
                <p class="mt-1 mb-6 text-sm text-gray-500">
                    Pilih status baru lalu klik "Perbarui Status". Data fasilitas tidak dihapus saat status diubah.
                </p>

                <form id="form-status" action="{{ route('admin.facilities.status', $facility) }}" method="POST"
                      class="flex flex-col sm:flex-row sm:items-end gap-4">
                    @csrf
                    @method('PATCH')

                    <div class="w-full sm:max-w-xs">
                        <label for="status-select" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select id="status-select" name="status" data-awal="{{ $facility->status }}"
                                class="w-full rounded-xl border border-[#E6D6CE] bg-[#F5EFE9] px-4 py-3 text-base text-gray-900 focus:border-[#511E1D] focus:ring-2 focus:ring-[#511E1D]">
                            @foreach ($opsiStatus as $nilai => $label)
                                <option value="{{ $nilai }}" @selected($facility->status === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm font-medium text-[#9B0000]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="button" id="btn-ubah-status" disabled
                            class="rounded-xl bg-[#C0453F] px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-[#A83B32] transition disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-[#C0453F]">
                        Perbarui Status
                    </button>
                </form>

                <p id="keterangan-status" class="mt-4 text-sm text-gray-500"></p>
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

    {{-- Popup konfirmasi ubah status --}}
    <div id="modal-status"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
         role="dialog" aria-modal="true" aria-labelledby="judul-status">

        <div class="relative w-full max-w-md rounded-3xl bg-white px-8 pt-16 pb-8 shadow-2xl">
            <button type="button" id="modal-tutup"
                    class="absolute top-5 right-5 text-black hover:text-[#7B1E1E] transition"
                    aria-label="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <h2 id="judul-status" class="text-center text-2xl font-bold leading-snug text-black">
                Anda yakin ingin mengubah status fasilitas ini?
            </h2>

            <p class="mt-3 text-center text-sm text-gray-500">
                Status {{ $facility->nama_fasilitas }} akan diubah menjadi
                <span id="modal-status-label" class="font-semibold text-gray-800"></span>.
            </p>

            <div class="mt-8 grid grid-cols-2 gap-4">
                <button type="button" id="modal-batal"
                        class="h-14 rounded-2xl bg-[#7B3A36] text-base font-bold text-white shadow-md hover:bg-[#5F1717] transition">
                    Batal
                </button>

                <button type="button" id="modal-ya"
                        class="h-14 rounded-2xl bg-[#F5F0F7] text-base font-bold text-black shadow-md hover:bg-[#EAE0EE] transition">
                    Ya, Ubah
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form      = document.getElementById('form-status');
            const select    = document.getElementById('status-select');
            const btnUbah   = document.getElementById('btn-ubah-status');
            const modal     = document.getElementById('modal-status');
            const labelBaru = document.getElementById('modal-status-label');
            const btnYa     = document.getElementById('modal-ya');
            const catatan   = document.getElementById('keterangan-status');
            const awal      = select.dataset.awal;

            const keterangan = {
                aktif:           'Fasilitas tampil dan dapat dipesan oleh pengguna.',
                dalam_perbaikan: 'Fasilitas sedang diperbaiki dan tidak dapat dipesan.',
                nonaktif:        'Fasilitas dinonaktifkan dan tidak dapat dipesan.'
            };

            function perbarui() {
                btnUbah.disabled = (select.value === awal);
                catatan.textContent = keterangan[select.value] || '';
            }

            function buka() {
                labelBaru.textContent = select.options[select.selectedIndex].text;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.getElementById('modal-batal').focus();
            }

            function tutup() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            select.addEventListener('change', perbarui);
            btnUbah.addEventListener('click', buka);
            document.getElementById('modal-batal').addEventListener('click', tutup);
            document.getElementById('modal-tutup').addEventListener('click', tutup);

            btnYa.addEventListener('click', function () {
                this.disabled = true;
                this.textContent = 'Memproses...';
                form.submit();
            });

            // Klik area gelap di luar kartu = tutup
            modal.addEventListener('click', function (e) {
                if (e.target === modal) tutup();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) tutup();
            });

            // Kalau kembali lewat tombol "Kembali" browser, pulihkan kondisi awal
            window.addEventListener('pageshow', function () {
                select.value = awal;
                btnYa.disabled = false;
                btnYa.textContent = 'Ya, Ubah';
                tutup();
                perbarui();
            });

            perbarui();
        })();
    </script>
</x-app-layout>