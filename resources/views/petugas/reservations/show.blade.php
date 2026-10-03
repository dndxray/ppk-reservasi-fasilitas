@php
    use Carbon\Carbon;

    $statusMap = [
        'menunggu'   => ['label' => 'Menunggu',   'class' => 'bg-[#E1D3C4] text-[#47201B]'],
        'disetujui'  => ['label' => 'Diterima',   'class' => 'bg-green-100 text-green-700'],
        'ditolak'    => ['label' => 'Ditolak',    'class' => 'bg-red-100 text-red-700'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-200 text-gray-700'],
    ];
    $status = $statusMap[$reservation->status] ?? $statusMap['dibatalkan'];
@endphp

<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7] py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="mb-4 sm:mb-6">
                <div class="flex items-center gap-3 sm:gap-4 mb-1.5">
                    @php
                        $backRoute = (request('from') === 'beranda' || url()->previous() === route('beranda'))
                            ? route('beranda')
                            : route('petugas.reservations.index');
                    @endphp
                    <a href="{{ $backRoute }}"
                       class="shrink-0 rounded-full p-1 hover:bg-[#E1D3C4] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#996561]"
                       aria-label="Kembali">
                        <img src="{{ asset('assets/icons/backward.png') }}" class="w-6 h-6 sm:w-8 sm:h-8" alt="">
                    </a>
                    <h1 class="text-xl sm:text-3xl font-bold text-[#47201B]">Detail Reservasi</h1>
                </div>
                <p class="text-gray-500 text-xs sm:text-base sm:ml-14">Informasi lengkap reservasi fasilitas</p>
            </div>

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="mb-4 sm:mb-6 rounded-xl border border-green-200 bg-green-50 px-4 sm:px-5 py-3 sm:py-4 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 sm:mb-6 rounded-xl border border-red-200 bg-red-50 px-4 sm:px-5 py-3 sm:py-4 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Error validasi selain alasan (alasan ditampilkan di dalam modal) --}}
            @php
                $otherErrors = collect($errors->getMessages())->except('alasan_pembatalan')->flatten();
            @endphp
            @if ($otherErrors->isNotEmpty())
                <div class="mb-4 sm:mb-6 rounded-xl border border-red-200 bg-red-50 px-4 sm:px-5 py-3 sm:py-4 text-red-700 text-sm space-y-1">
                    @foreach ($otherErrors as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 items-start">

                {{-- =========================
                     DETAIL RESERVASI
                ========================= --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E6D6CE] p-4 sm:p-8">

                    <div class="flex justify-between items-center gap-2 mb-5 sm:mb-8">
                        <h2 class="text-base sm:text-xl font-bold text-[#47201B]">Detail Reservasi</h2>

                        <span class="shrink-0 px-2.5 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    <dl class="space-y-4 sm:space-y-6">

                        {{-- NAMA --}}
                        <div class="grid grid-cols-[20px_1fr] sm:grid-cols-[40px_180px_1fr] gap-x-2 sm:gap-x-0 gap-y-0.5 items-center">
                            <img src="{{ asset('assets/icons/user.png') }}" class="w-4 h-4 sm:w-6 sm:h-6" alt="">
                            <div class="sm:contents">
                                <dt class="text-gray-500 text-xs sm:text-base">Nama pemesan</dt>
                                <dd class="font-semibold text-sm sm:text-base text-[#47201B] break-words">{{ $reservation->user->name }}</dd>
                            </div>
                        </div>

                        {{-- EMAIL --}}
                        <div class="grid grid-cols-[20px_1fr] sm:grid-cols-[40px_180px_1fr] gap-x-2 sm:gap-x-0 gap-y-0.5 items-center">
                            <img src="{{ asset('assets/icons/email.png') }}" class="w-4 h-4 sm:w-6 sm:h-6" alt="">
                            <div class="sm:contents">
                                <dt class="text-gray-500 text-xs sm:text-base">Email</dt>
                                <dd class="font-semibold text-sm sm:text-base text-[#47201B] break-all">{{ $reservation->user->email }}</dd>
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="grid grid-cols-[20px_1fr] sm:grid-cols-[40px_180px_1fr] gap-x-2 sm:gap-x-0 gap-y-0.5 items-center">
                            <img src="{{ asset('assets/icons/tanggal.png') }}" class="w-4 h-4 sm:w-6 sm:h-6" alt="">
                            <div class="sm:contents">
                                <dt class="text-gray-500 text-xs sm:text-base">Tanggal</dt>
                                <dd class="font-semibold text-sm sm:text-base text-[#47201B]">
                                    {{ Carbon::parse($reservation->tanggal)->translatedFormat('d F Y') }}
                                </dd>
                            </div>
                        </div>

                        {{-- WAKTU --}}
                        <div class="grid grid-cols-[20px_1fr] sm:grid-cols-[40px_180px_1fr] gap-x-2 sm:gap-x-0 gap-y-0.5 items-center">
                            <img src="{{ asset('assets/icons/waktu.png') }}" class="w-4 h-4 sm:w-6 sm:h-6" alt="">
                            <div class="sm:contents">
                                <dt class="text-gray-500 text-xs sm:text-base">Rentang waktu</dt>
                                <dd class="font-semibold text-sm sm:text-base text-[#47201B]">
                                    {{ Carbon::parse($reservation->waktu_mulai)->format('H:i') }}
                                    &ndash;
                                    {{ Carbon::parse($reservation->waktu_selesai)->format('H:i') }}
                                </dd>
                            </div>
                        </div>

                        {{-- TUJUAN --}}
                        <div class="grid grid-cols-[20px_1fr] sm:grid-cols-[40px_180px_1fr] gap-x-2 sm:gap-x-0 gap-y-0.5 items-start">
                            <img src="{{ asset('assets/icons/tujuan.png') }}" class="w-4 h-4 sm:w-6 sm:h-6 mt-0.5" alt="">
                            <div class="sm:contents">
                                <dt class="text-gray-500 text-xs sm:text-base">Tujuan penggunaan</dt>
                                <dd class="font-semibold text-sm sm:text-base text-[#47201B] leading-relaxed break-words">
                                    {{ $reservation->tujuan_penggunaan }}
                                </dd>
                            </div>
                        </div>

                        {{-- ALASAN PEMBATALAN (tampil jika dibatalkan) --}}
                        @if ($reservation->status == 'dibatalkan')
                            <div class="grid grid-cols-[20px_1fr] sm:grid-cols-[40px_180px_1fr] gap-x-2 sm:gap-x-0 gap-y-0.5 items-start">
                                <img src="{{ asset('assets/icons/batalkan.png') }}" class="w-4 h-4 sm:w-6 sm:h-6 mt-0.5" alt="">
                                <div class="sm:contents">
                                    <dt class="text-gray-500 text-xs sm:text-base">Alasan pembatalan</dt>
                                    <dd class="font-semibold text-sm sm:text-base text-[#47201B] leading-relaxed break-words">
                                        {{ $reservation->alasan_pembatalan ?: 'Dibatalkan oleh pemesan' }}
                                    </dd>
                                </div>
                            </div>
                        @endif

                    </dl>
                </div>

                {{-- =========================
                     DETAIL FASILITAS
                ========================= --}}
                <div class="bg-white rounded-2xl border border-[#E6D6CE] p-4 sm:p-6">

                    {{-- FOTO --}}
                    <img
                        src="{{ $reservation->facility->foto_url }}"
                        alt="Foto {{ $reservation->facility->nama_fasilitas }}"
                        class="h-28 sm:h-40 w-full rounded-xl object-cover"
                    >

                    {{-- NAMA + TIPE (teks biasa, bukan tombol) --}}
                    <h2 class="text-base sm:text-xl font-bold text-[#47201B] mt-3 sm:mt-5 break-words">
                        {{ $reservation->facility->nama_fasilitas }}
                    </h2>
                    <p class="text-xs sm:text-sm text-[#996561] mt-1">
                        {{ $reservation->facility->tipe }}
                    </p>

                    <ul class="mt-3 sm:mt-5 space-y-2.5 sm:space-y-3 text-[#47201B]">
                        <li class="flex items-center gap-2.5 sm:gap-3">
                            <img src="{{ asset('assets/icons/lokasi.png') }}" class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" alt="">
                            <span class="text-sm sm:text-base break-words">{{ $reservation->facility->lokasi }}</span>
                        </li>
                        <li class="flex items-center gap-2.5 sm:gap-3">
                            <img src="{{ asset('assets/icons/kapasitas.png') }}" class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" alt="">
                            <span class="text-sm sm:text-base">{{ $reservation->facility->kapasitas }} orang</span>
                        </li>
                    </ul>

                    {{-- AKSI PETUGAS --}}
                    @if ($reservation->status == 'menunggu')

                        <div class="grid grid-cols-2 gap-2.5 sm:gap-3 mt-6 sm:mt-8">

                            {{-- TOLAK --}}
                            <button type="button" onclick="openActionModal('reject')"
                                    class="group flex items-center justify-center gap-2 rounded-xl border-2 border-[#BE433E] bg-white
                                           text-[#BE433E] py-2.5 sm:py-3 text-sm sm:text-base font-bold
                                           hover:bg-[#BE433E] hover:text-white active:scale-[0.98] transition
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-[#BE433E] focus-visible:ring-offset-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tolak
                            </button>

                            {{-- SETUJUI --}}
                            <button type="button" onclick="openActionModal('approve')"
                                    class="flex items-center justify-center gap-2 rounded-xl bg-green-600 text-white
                                           py-2.5 sm:py-3 text-sm sm:text-base font-bold shadow-sm
                                           hover:bg-green-700 active:scale-[0.98] transition
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setujui
                            </button>
                        </div>

                    @elseif ($reservation->status == 'disetujui')

                        <button type="button" onclick="openActionModal('cancel')"
                                class="mt-6 sm:mt-8 w-full bg-[#F8D8D5] text-[#BE433E] py-3.5 sm:py-4 text-sm sm:text-base rounded-xl font-bold
                                       flex items-center justify-center gap-2 hover:bg-[#BE433E] hover:text-white transition
                                       focus:outline-none focus-visible:ring-2 focus-visible:ring-[#BE433E] focus-visible:ring-offset-2">
                            <img src="{{ asset('assets/icons/tanggal.png') }}" class="w-4 h-4 sm:w-5 sm:h-5" alt="">
                            Batalkan mendesak
                        </button>

                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI (tampilan sama dengan halaman pengguna) --}}
    <div id="actionModal"
         class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4"
         role="dialog" aria-modal="true" aria-labelledby="actionTitle">

        <div class="bg-white w-full max-w-[390px] rounded-3xl p-8 text-center relative">

            <button type="button" onclick="closeActionModal()"
                    class="absolute top-3 right-5 text-3xl text-black leading-none"
                    aria-label="Tutup">
                &times;
            </button>

            <h2 id="actionTitle" class="text-2xl font-bold text-black leading-tight mb-8"></h2>

            {{-- ALASAN PEMBATALAN (hanya untuk batalkan mendesak) --}}
            @if ($reservation->status == 'disetujui')
                <div id="alasanWrap" class="hidden text-left mb-6">
                    <label for="alasan" class="block text-sm font-bold text-black mb-2">Alasan pembatalan</label>

                    <textarea id="alasan" rows="3" maxlength="500"
                              placeholder="Tulis alasan pembatalan"
                              class="w-full rounded-lg bg-[#F5F0ED] border border-[#D5C6BD] px-4 py-3 text-black text-sm resize-none
                                     focus:ring-0 focus:border-[#BE433E]">{{ old('alasan_pembatalan') }}</textarea>

                    <p id="alasanError" class="hidden mt-1.5 text-sm text-[#950704] font-semibold">
                        Alasan pembatalan wajib diisi.
                    </p>

                    @error('alasan_pembatalan')
                        <p class="mt-1.5 text-sm text-[#950704] font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="flex gap-3 w-full">
                <button type="button" onclick="closeActionModal()"
                        class="flex-1 py-3 rounded-xl bg-[#6F3835] text-white font-semibold">
                    Tidak
                </button>

                <button type="button" id="actionConfirmBtn" onclick="confirmAction()"
                        class="flex-1 py-3 rounded-xl bg-[#F5F0ED] border border-[#D5C6BD] text-black font-semibold disabled:opacity-60">
                </button>
            </div>
        </div>
    </div>

    {{-- FORM AKSI (dikirim lewat modal di atas) --}}
    @if ($reservation->status == 'menunggu')
        <form id="approveForm" method="POST" action="{{ route('petugas.reservations.approve', $reservation->id) }}" class="hidden">
            @csrf
            @method('PATCH')
        </form>

        <form id="rejectForm" method="POST" action="{{ route('petugas.reservations.reject', $reservation->id) }}" class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @elseif ($reservation->status == 'disetujui')
        <form id="cancelForm" method="POST" action="{{ route('petugas.reservations.cancel', $reservation->id) }}" class="hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" name="alasan_pembatalan" id="alasanHidden" value="">
        </form>
    @endif

    <script>
        const actionModal = document.getElementById('actionModal');
        const actionTitle = document.getElementById('actionTitle');
        const confirmBtn  = document.getElementById('actionConfirmBtn');
        const alasanWrap  = document.getElementById('alasanWrap');
        const alasanInput = document.getElementById('alasan');
        const alasanError = document.getElementById('alasanError');

        const actions = {
            approve: {
                title:   'Apakah Anda yakin<br>ingin menyetujui<br>reservasi?',
                confirm: 'Ya, Setujui',
                form:    'approveForm',
                reason:  false
            },
            reject: {
                title:   'Apakah Anda yakin<br>ingin menolak<br>reservasi?',
                confirm: 'Ya, Tolak',
                form:    'rejectForm',
                reason:  false
            },
            cancel: {
                title:   'Apakah Anda yakin<br>ingin membatalkan<br>reservasi?',
                confirm: 'Ya, Batalkan',
                form:    'cancelForm',
                reason:  true
            }
        };

        let activeAction = null;

        function openActionModal(key) {
            const cfg = actions[key];
            if (!cfg || !document.getElementById(cfg.form)) return;

            activeAction = key;
            actionTitle.innerHTML = cfg.title;
            confirmBtn.innerText = cfg.confirm;
            confirmBtn.disabled = false;

            // Kolom alasan hanya muncul untuk pembatalan
            if (alasanWrap) {
                alasanWrap.classList.toggle('hidden', !cfg.reason);
                if (alasanError) alasanError.classList.add('hidden');
            }
            actionTitle.classList.toggle('mb-8', !cfg.reason);
            actionTitle.classList.toggle('mb-5', cfg.reason);

            actionModal.classList.remove('hidden');
            actionModal.classList.add('flex');

            if (cfg.reason && alasanInput) alasanInput.focus();
        }

        function closeActionModal() {
            actionModal.classList.add('hidden');
            actionModal.classList.remove('flex');
            activeAction = null;
        }

        function confirmAction() {
            if (!activeAction) return;

            const cfg = actions[activeAction];
            const form = document.getElementById(cfg.form);
            if (!form) return;

            // Pembatalan wajib disertai alasan
            if (cfg.reason && alasanInput && alasanInput.value.trim() === '') {
                alasanError.classList.remove('hidden');
                alasanInput.focus();
                return;
            }

            // Salin alasan ke input hidden di dalam form agar pasti ikut terkirim
            const hidden = document.getElementById('alasanHidden');
            if (cfg.reason && hidden && alasanInput) {
                hidden.value = alasanInput.value.trim();
            }

            confirmBtn.disabled = true;
            confirmBtn.innerText = 'Memproses...';
            form.submit();
        }

        if (alasanInput) {
            alasanInput.addEventListener('input', () => alasanError.classList.add('hidden'));
        }

        // Tutup modal saat klik area gelap atau tekan Esc
        actionModal.addEventListener('click', (e) => {
            if (e.target === actionModal) closeActionModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeActionModal();
        });

        // Jika server menolak alasan (validasi gagal), buka lagi modalnya
        @if ($errors->has('alasan_pembatalan'))
            openActionModal('cancel');
        @endif
    </script>

</x-app-layout>