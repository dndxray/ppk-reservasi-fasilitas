@php
    use Carbon\Carbon;

    // Batas pembatalan: maksimal 24 jam sebelum waktu penggunaan
    $waktuPenggunaan = Carbon::parse($reservation->tanggal . ' ' . $reservation->waktu_mulai);
    $bisaDibatalkan  = in_array($reservation->status, ['menunggu', 'disetujui'])
                       && now()->lt($waktuPenggunaan->copy()->subHours(24));
    $statusAktif     = in_array($reservation->status, ['menunggu', 'disetujui']);

    $statusMap = [
        'menunggu'   => ['label' => 'Menunggu',   'class' => 'bg-[#E1D3C4] text-[#47201B]'],
        'disetujui'  => ['label' => 'Diterima',   'class' => 'bg-green-100 text-green-700'],
        'ditolak'    => ['label' => 'Ditolak',    'class' => 'bg-red-100 text-red-700'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-200 text-gray-700'],
    ];
    $status = $statusMap[$reservation->status] ?? $statusMap['dibatalkan'];
@endphp

<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7] py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-8">

            {{-- HEADER --}}
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-2">
                    @php
                        $backRoute = (request('from') === 'beranda' || url()->previous() === route('beranda'))
                            ? route('beranda')
                            : route('reservations.history');
                    @endphp
                    <a href="{{ $backRoute }}"
                       class="shrink-0 rounded-full p-1 hover:bg-[#E1D3C4] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#996561]"
                       aria-label="Kembali">
                        <img src="{{ asset('assets/icons/backward.png') }}" class="w-8 h-8" alt="">
                    </a>
                    <h1 class="text-3xl font-bold text-[#47201B]">Detail Reservasi</h1>
                </div>
                <p class="text-gray-500 ml-14">Informasi lengkap reservasi fasilitas</p>
            </div>

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- =========================
                     DETAIL RESERVASI
                ========================= --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E6D6CE] p-6 sm:p-8">

                    <div class="flex justify-between items-center pb-6 mb-6 border-b border-[#F0E6E0]">
                        <h2 class="text-xl font-bold text-[#47201B]">Informasi Pemesan</h2>

                        <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    <dl class="divide-y divide-[#F0E6E0]">

                        {{-- NAMA --}}
                        <div class="grid grid-cols-[32px_140px_1fr] sm:grid-cols-[40px_180px_1fr] items-center gap-y-1 py-4">
                            <img src="{{ asset('assets/icons/user.png') }}" class="w-6 h-6" alt="">
                            <dt class="text-gray-500">Nama pemesan</dt>
                            <dd class="font-semibold text-[#47201B]">{{ $reservation->user->name }}</dd>
                        </div>

                        {{-- EMAIL --}}
                        <div class="grid grid-cols-[32px_140px_1fr] sm:grid-cols-[40px_180px_1fr] items-center gap-y-1 py-4">
                            <img src="{{ asset('assets/icons/email.png') }}" class="w-6 h-6" alt="">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-semibold text-[#47201B] break-all">{{ $reservation->user->email }}</dd>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="grid grid-cols-[32px_140px_1fr] sm:grid-cols-[40px_180px_1fr] items-center gap-y-1 py-4">
                            <img src="{{ asset('assets/icons/tanggal.png') }}" class="w-6 h-6" alt="">
                            <dt class="text-gray-500">Tanggal</dt>
                            <dd class="font-semibold text-[#47201B]">
                                {{ Carbon::parse($reservation->tanggal)->translatedFormat('d F Y') }}
                            </dd>
                        </div>

                        {{-- WAKTU --}}
                        <div class="grid grid-cols-[32px_140px_1fr] sm:grid-cols-[40px_180px_1fr] items-center gap-y-1 py-4">
                            <img src="{{ asset('assets/icons/waktu.png') }}" class="w-6 h-6" alt="">
                            <dt class="text-gray-500">Rentang waktu</dt>
                            <dd class="font-semibold text-[#47201B]">
                                {{ Carbon::parse($reservation->waktu_mulai)->format('H:i') }}
                                &ndash;
                                {{ Carbon::parse($reservation->waktu_selesai)->format('H:i') }}
                            </dd>
                        </div>

                        {{-- TUJUAN --}}
                        <div class="grid grid-cols-[32px_140px_1fr] sm:grid-cols-[40px_180px_1fr] items-start gap-y-1 py-4">
                            <img src="{{ asset('assets/icons/tujuan.png') }}" class="w-6 h-6 mt-0.5" alt="">
                            <dt class="text-gray-500">Tujuan penggunaan</dt>
                            <dd class="font-semibold text-[#47201B] leading-relaxed">
                                {{ $reservation->tujuan_penggunaan }}
                            </dd>
                        </div>

                        {{-- ALASAN PEMBATALAN --}}
                        @if ($reservation->status == 'dibatalkan')
                            <div class="grid grid-cols-[32px_140px_1fr] sm:grid-cols-[40px_180px_1fr] items-start gap-y-1 py-4">
                                <img src="{{ asset('assets/icons/batalkan.png') }}" class="w-6 h-6 mt-0.5" alt="">
                                <dt class="text-gray-500">Alasan pembatalan</dt>
                                <dd class="font-semibold text-[#47201B] leading-relaxed break-words">
                                    {{ $reservation->alasan_pembatalan ?: 'Dibatalkan oleh pemesan' }}
                                </dd>
                            </div>
                        @endif

                    </dl>
                </div>

                {{-- =========================
                     DETAIL FASILITAS
                ========================= --}}
                <div class="bg-white rounded-2xl border border-[#E6D6CE] p-6">

                    {{-- FOTO --}}
                    <div class="h-40 bg-[#E1D3C4] rounded-xl flex items-center justify-center overflow-hidden">
                        <p class="text-[#996561]">Foto fasilitas</p>
                    </div>

                    {{-- NAMA + TIPE (teks biasa, bukan tombol) --}}
                    <h2 class="text-xl font-bold text-[#47201B] mt-5">
                        {{ $reservation->facility->nama_fasilitas }}
                    </h2>
                    <p class="text-sm text-[#996561] mt-1">
                        {{ $reservation->facility->tipe }}
                    </p>

                    <ul class="mt-5 space-y-3 text-[#47201B]">
                        <li class="flex items-center gap-3">
                            <img src="{{ asset('assets/icons/lokasi.png') }}" class="w-5 h-5" alt="">
                            <span>{{ $reservation->facility->lokasi }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <img src="{{ asset('assets/icons/kapasitas.png') }}" class="w-5 h-5" alt="">
                            <span>{{ $reservation->facility->kapasitas }} orang</span>
                        </li>
                    </ul>

                    {{-- BATAS PEMBATALAN --}}
                    <div class="mt-8 pt-6 border-t border-[#F0E6E0]">
                        <h3 class="font-bold text-[#47201B] mb-3">Batas pembatalan</h3>

                        <div class="bg-[#F5EFEC] rounded-xl p-5 text-sm leading-relaxed text-[#47201B]">
                            Reservasi dapat dibatalkan maksimal
                            <strong>1 x 24 jam sebelum waktu penggunaan.</strong>
                            Setelah melewati batas waktu, pembatalan tidak dapat dilakukan.
                        </div>
                    </div>

                    {{-- TOMBOL BATALKAN --}}
                    @if ($statusAktif)
                        @if ($bisaDibatalkan)
                            <button type="button"
                                    onclick="openCancelModal()"
                                    class="mt-6 w-full bg-[#F8D8D5] text-[#BE433E] py-4 rounded-xl font-bold
                                           flex justify-center items-center gap-2
                                           hover:bg-[#BE433E] hover:text-white transition
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-[#BE433E]">
                                <img src="{{ asset('assets/icons/batalkan.png') }}" class="w-5 h-5" alt="">
                                Batalkan reservasi
                            </button>
                        @else
                            <button type="button" disabled
                                    class="mt-6 w-full bg-gray-100 text-gray-400 py-4 rounded-xl font-bold cursor-not-allowed">
                                Batas pembatalan sudah lewat
                            </button>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BATAL RESERVASI --}}
    <div id="cancelModal"
         class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4"
         role="dialog" aria-modal="true" aria-labelledby="cancelTitle">

        <div class="bg-white w-full max-w-[390px] rounded-3xl p-8 text-center relative">

            <button type="button" onclick="closeCancelModal()"
                    class="absolute top-3 right-5 text-3xl text-black leading-none"
                    aria-label="Tutup">
                &times;
            </button>

            <h2 id="cancelTitle" class="text-2xl font-bold text-black leading-tight mb-8">
                Apakah Anda yakin ingin membatalkan reservasi?
            </h2>

            <div class="flex gap-3 w-full">
                <button type="button" onclick="closeCancelModal()"
                        class="flex-1 py-3 rounded-xl bg-[#6F3835] text-white font-semibold">
                    Tidak
                </button>

                <button type="button" id="confirmCancelBtn" onclick="submitCancel()"
                        class="flex-1 py-3 rounded-xl bg-[#F5F0ED] border border-[#D5C6BD] text-black font-semibold
                               disabled:opacity-60">
                    Ya, batalkan
                </button>
            </div>
        </div>
    </div>

    {{-- FORM PEMBATALAN --}}
    <form id="cancelForm" method="POST" action="{{ route('reservations.cancel', $reservation->id) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <script>
        const cancelModal = document.getElementById('cancelModal');

        function openCancelModal() {
            cancelModal.classList.remove('hidden');
            cancelModal.classList.add('flex');
        }

        function closeCancelModal() {
            cancelModal.classList.add('hidden');
            cancelModal.classList.remove('flex');
        }

        function submitCancel() {
            const btn = document.getElementById('confirmCancelBtn');
            btn.disabled = true;
            btn.textContent = 'Memproses...';
            document.getElementById('cancelForm').submit();
        }

        // Tutup modal saat klik area gelap atau tekan Esc
        cancelModal.addEventListener('click', (e) => {
            if (e.target === cancelModal) closeCancelModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeCancelModal();
        });
    </script>

</x-app-layout>