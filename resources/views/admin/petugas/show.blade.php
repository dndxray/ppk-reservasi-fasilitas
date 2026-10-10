<x-app-layout>
    @php
        $nonaktif = $petugas->status_verifikasi === 'nonaktif';
    @endphp

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if (session('success'))
            <div id="banner-sukses"
                 role="status"
                 class="mb-5 flex items-center justify-between gap-4 rounded-lg border border-[#166534] bg-[#DCFCE7] px-6 py-4 text-base font-semibold text-[#166534] transition-opacity duration-500">
                <span>{{ session('success') }}</span>

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

        {{-- Header: tombol kembali + judul --}}
        <div class="mb-6 flex items-start gap-5">
            <a href="{{ route('admin.petugas.index') }}"
               class="mt-1 text-[#4A1D1A] hover:text-[#7B1E1E] transition"
               aria-label="Kembali">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-[#4A1D1A]">Detail Petugas</h1>
                <p class="mt-1 text-sm text-gray-500">Informasi lengkap akun petugas</p>
            </div>
        </div>

        {{-- Header profil --}}
        <div class="mb-6 flex items-center gap-6 rounded-2xl bg-[#F5F0EA] p-8">
            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-[#B83C30] text-4xl font-bold text-white">
                {{ mb_strtoupper(mb_substr($petugas->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <h2 class="truncate text-2xl font-extrabold uppercase text-gray-900">
                    {{ $petugas->name }}
                </h2>
                <p class="text-gray-500">Petugas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Kolom kiri --}}
            <div class="rounded-2xl bg-white p-8 shadow-sm lg:col-span-2">

                <h2 class="mb-6 text-lg font-semibold text-gray-900">Identitas Pribadi</h2>

                <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                    <div>
                        <p class="mb-2 text-sm text-gray-600">Nama Lengkap</p>
                        <p class="rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $petugas->name }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-600">NIM / NIP</p>
                        <p class="rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $petugas->nim_nip ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-600">Email</p>
                        <p class="break-all rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $petugas->email }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-600">Nomor Telepon</p>
                        <p class="rounded-lg bg-[#F5F0EA] px-4 py-3 text-base text-gray-900 shadow-sm">{{ $petugas->no_telepon ?? '-' }}</p>
                    </div>
                </div>

                <div class="my-8 border-t border-gray-200"></div>

                <h2 class="mb-6 text-lg font-semibold text-gray-900">Informasi Akun</h2>

                <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                    <div class="rounded-lg bg-[#F5F0EA] px-5 py-4 shadow-sm">
                        <p class="text-sm text-gray-500">Bergabung Sejak</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">
                            {{ $petugas->created_at->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-[#F5F0EA] px-5 py-4 shadow-sm">
                        <p class="text-sm text-gray-500">Status Akun</p>

                        @if ($petugas->status_verifikasi === 'terverifikasi')
                            <p class="mt-1 text-xl font-semibold text-green-600">Aktif</p>
                        @elseif ($nonaktif)
                            <p class="mt-1 text-xl font-semibold text-gray-500">Nonaktif</p>
                        @elseif ($petugas->status_verifikasi === 'ditolak')
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

                @if ($nonaktif)
                    <h3 class="text-xl font-medium text-gray-900">Aktifkan Akun</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Akun ini sedang nonaktif, sehingga petugas tidak dapat masuk ke sistem.
                        Aktifkan kembali agar petugas dapat masuk lagi.
                    </p>

                    {{-- Form tersembunyi, dikirim lewat popup konfirmasi --}}
                    <form id="form-aktifkan" action="{{ route('admin.petugas.activate', $petugas) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>

                    <button type="button"
                            data-modal-open="modal-aktifkan"
                            class="rounded-xl bg-[#166534] px-6 py-3 text-base font-semibold text-white hover:bg-[#14532D] transition">
                        Aktifkan Akun
                    </button>
                @else
                    <h3 class="text-xl font-medium text-gray-900">Nonaktifkan Akun</h3>
                    <p class="mt-2 mb-6 text-base text-gray-500">
                        Petugas tidak akan bisa masuk ke sistem selama akun nonaktif.
                        Data petugas tidak dihapus dan akun bisa diaktifkan kembali kapan saja.
                    </p>

                    {{-- Form tersembunyi, dikirim lewat popup konfirmasi --}}
                    <form id="form-nonaktif" action="{{ route('admin.petugas.deactivate', $petugas) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>

                    <button type="button"
                            data-modal-open="modal-nonaktif"
                            class="rounded-xl bg-[#B91C1C] px-6 py-3 text-base font-semibold text-white hover:bg-[#991B1B] transition">
                        Nonaktifkan Akun
                    </button>
                @endif
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
                    Anda yakin ingin mengaktifkan akun petugas ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    {{ $petugas->name }} akan dapat masuk ke sistem kembali setelah akunnya diaktifkan.
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
                    Anda yakin ingin menonaktifkan akun petugas ini?
                </h2>

                <p class="mt-3 text-center text-sm text-gray-500">
                    {{ $petugas->name }} tidak akan bisa masuk ke sistem sampai akunnya diaktifkan kembali.
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