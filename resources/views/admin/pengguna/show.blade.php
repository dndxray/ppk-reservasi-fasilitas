<x-app-layout>
    @php
        $status = $pengguna->status_verifikasi;
    @endphp

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('success'))
            <div id="banner-sukses"
                 role="status"
                 class="mb-5 flex items-center justify-between gap-4 rounded-xl border border-[#86EFAC] bg-[#F0FDF4] px-6 py-4 text-base font-semibold text-[#15803D] shadow-sm transition-opacity duration-500">
                <span class="flex items-center gap-3">
                    <svg class="h-6 w-6 shrink-0 text-[#16A34A]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </span>

                <button type="button"
                        onclick="document.getElementById('banner-sukses').remove()"
                        class="text-[#15803D] hover:text-[#166534]"
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

        {{-- Header: tombol kembali + judul --}}
        <div class="mb-6 flex items-start gap-5">
            <a href="{{ route('admin.pengguna.index') }}"
               class="mt-1 text-[#4A1D1A] hover:text-[#7B1E1E] transition"
               aria-label="Kembali">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-[#4A1D1A]">Detail Pengguna</h1>
                <p class="mt-1 text-sm text-gray-500">Informasi lengkap akun pengguna</p>
            </div>
        </div>

        {{-- Header profil --}}
        <div class="mb-6 flex items-center gap-6 rounded-2xl bg-[#F5F0EA] p-8">
            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-[#B83C30] text-4xl font-bold text-white">
                {{ mb_strtoupper(mb_substr($pengguna->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <h2 class="truncate text-2xl font-extrabold uppercase text-gray-900">
                    {{ $pengguna->name }}
                </h2>
                <p class="text-gray-500">Pengguna</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Kolom kiri --}}
            <div class="rounded-2xl bg-white p-8 shadow-sm lg:col-span-2">

                <h2 class="mb-6 text-lg font-semibold text-gray-900">Identitas Pribadi</h2>

                <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                    <div>
                        <p class="mb-2 text-sm text-gray-600">Nama Lengkap</p>
                        <p class="rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $pengguna->name }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-600">NIM</p>
                        <p class="rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $pengguna->nim_nip ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-600">Email</p>
                        <p class="break-all rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $pengguna->email }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-600">Nomor Telepon</p>
                        <p class="rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $pengguna->no_telepon ?? '-' }}</p>
                    </div>
                </div>

                <div class="my-8 border-t border-gray-200"></div>

                <h2 class="mb-6 text-lg font-semibold text-gray-900">Informasi Akun</h2>

                <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                    <div class="rounded-lg bg-[#F5F0EA] px-5 py-4 shadow-sm">
                        <p class="text-sm text-gray-500">Bergabung Sejak</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">
                            {{ $pengguna->created_at->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-[#F5F0EA] px-5 py-4 shadow-sm">
                        <p class="text-sm text-gray-500">Status Akun</p>

                        @if ($status === 'terverifikasi')
                            <p class="mt-1 text-xl font-semibold text-green-600">Aktif</p>
                        @elseif ($status === 'nonaktif')
                            <p class="mt-1 text-xl font-semibold text-gray-500">Nonaktif</p>
                        @elseif ($status === 'ditolak')
                            <p class="mt-1 text-xl font-semibold text-red-600">Ditolak</p>
                        @else
                            <p class="mt-1 text-xl font-semibold text-yellow-600">Menunggu</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: pengaturan akun --}}
            <div class="h-fit rounded-2xl bg-white p-8 shadow-sm">

                <h2 class="mb-6 text-lg font-semibold text-gray-900">Pengaturan Akun</h2>

                @if ($status === 'menunggu')
                    {{-- Menunggu: Verifikasi / Tolak --}}
                    <h3 class="text-xl font-medium text-gray-900">Verifikasi Akun</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Pendaftaran akun ini masih menunggu keputusan. Verifikasi agar akun menjadi aktif
                        dan pengguna dapat menggunakan sistem, atau tolak jika data tidak sesuai.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button"
                                data-modal-open="modal-tolak"
                                class="rounded-xl border-2 border-[#B91C1C] px-6 py-3 text-base font-semibold text-[#B91C1C] hover:bg-[#B91C1C] hover:text-white transition">
                            Tolak
                        </button>

                        <form action="{{ route('admin.pengguna.verify', $pengguna) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="rounded-xl bg-[#16A34A] px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-[#15803D] hover:shadow-md transition">
                                Verifikasi
                            </button>
                        </form>
                    </div>

                @elseif ($status === 'terverifikasi')
                    {{-- Aktif: bisa dinonaktifkan --}}
                    <h3 class="text-xl font-medium text-gray-900">Nonaktifkan Akun</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Pengguna tidak akan bisa masuk ke sistem selama akun nonaktif.
                        Data pengguna tidak dihapus dan akun bisa diaktifkan kembali kapan saja.
                    </p>

                    <button type="button"
                            data-modal-open="modal-nonaktif"
                            class="rounded-xl bg-[#B91C1C] px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-[#991B1B] hover:shadow-md transition">
                        Nonaktifkan Akun
                    </button>

                @elseif ($status === 'nonaktif')
                    {{-- Nonaktif: bisa diaktifkan lagi (dengan popup konfirmasi) --}}
                    <h3 class="text-xl font-medium text-gray-900">Aktifkan Akun</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Akun ini sedang nonaktif, sehingga pengguna tidak dapat masuk ke sistem.
                        Aktifkan kembali agar pengguna dapat masuk lagi.
                    </p>

                    <button type="button"
                            data-modal-open="modal-aktifkan"
                            class="rounded-xl bg-[#16A34A] px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-[#15803D] hover:shadow-md transition">
                        Aktifkan Akun
                    </button>

                @else
                    {{-- Ditolak --}}
                    <h3 class="text-xl font-medium text-gray-900">Pendaftaran Ditolak</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Pendaftaran akun ini telah ditolak, sehingga pengguna tidak dapat menggunakan sistem.
                    </p>
                @endif
            </div>
        </div>

        {{-- Riwayat reservasi pengguna --}}
        @isset($reservations)
            <div id="riwayat-reservasi" class="mt-8">
                <h2 class="mb-3 text-lg font-bold text-[#4A1D1A]">Riwayat Reservasi</h2>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    @if ($reservations->count() > 0)

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-[#F5EBE9]">
                                    <tr>
                                        <th class="px-6 py-4 text-left font-bold text-[#A94438]">No</th>
                                        <th class="px-6 py-4 text-left font-bold text-[#A94438]">Fasilitas</th>
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

                                            {{-- fasilitas --}}
                                            <td class="px-6 py-5">
                                                <p class="font-medium text-gray-900">{{ $reservation->facility?->nama_fasilitas ?? '-' }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $reservation->facility?->lokasi ?? '-' }}</p>
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
                                Pengguna ini belum pernah melakukan reservasi.
                            </p>
                        </div>

                    @endif
                </div>
            </div>
        @endisset
    </div>

    {{-- Form tersembunyi untuk aksi yang memakai popup konfirmasi --}}
    @if ($status === 'menunggu')
        <form id="form-tolak" action="{{ route('admin.pengguna.reject', $pengguna) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>

        {{-- Popup konfirmasi tolak --}}
        <div id="modal-tolak" data-modal data-form="form-tolak"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
             role="dialog" aria-modal="true" aria-labelledby="judul-tolak">

            <div class="relative w-full max-w-md rounded-3xl bg-white px-8 pt-16 pb-8 shadow-2xl">
                <button type="button" data-modal-close
                        class="absolute top-5 right-5 text-black hover:text-[#7B1E1E] transition"
                        aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>

                <h2 id="judul-tolak" class="text-center text-2xl font-bold leading-snug text-black">
                    Anda yakin ingin menolak pendaftaran pengguna ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    Pendaftaran {{ $pengguna->name }} akan ditolak dan tidak bisa menggunakan sistem.
                </p>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <button type="button" data-modal-close
                            class="h-14 rounded-2xl bg-[#7B3A36] text-base font-bold text-white shadow-md hover:bg-[#5F1717] transition">
                        Batal
                    </button>

                    <button type="button" data-modal-confirm
                            class="h-14 rounded-2xl bg-[#F5F0F7] text-base font-bold text-black shadow-md hover:bg-[#EAE0EE] transition">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($status === 'terverifikasi')
        <form id="form-nonaktif" action="{{ route('admin.pengguna.deactivate', $pengguna) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>

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
                    Anda yakin ingin menonaktifkan akun pengguna ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    {{ $pengguna->name }} tidak akan bisa masuk ke sistem sampai akunnya diaktifkan kembali.
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

    @if ($status === 'nonaktif')
        <form id="form-aktifkan" action="{{ route('admin.pengguna.activate', $pengguna) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>

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
                    Anda yakin ingin mengaktifkan akun pengguna ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    {{ $pengguna->name }} akan dapat masuk ke sistem kembali setelah akunnya diaktifkan.
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