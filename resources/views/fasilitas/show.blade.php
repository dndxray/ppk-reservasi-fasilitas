@php
    use Carbon\Carbon;
    use Illuminate\Support\Js;

    $tglAktif = Carbon::parse($tanggal);
@endphp

<x-app-layout>
    <div class="py-6 px-6 lg:px-10" x-data="{ showLoginModal: false }">

        <div class="-mx-6 lg:-mx-10 -mt-6 px-6 lg:px-10 py-5 bg-[#F5F0ED] flex items-center gap-3 mb-6">
            <a href="{{ route('fasilitas.index') }}" class="text-[#4a1a24] hover:opacity-70">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-[#4a1a24]">Cari Fasilitas</h1>
        </div>

        <!-- header -->
        <div class="bg-[#F5F0ED] rounded-xl p-5 flex gap-5 mb-6">
            <img src="{{ asset('images/login-bg.jpg') }}" alt="{{ $fasilitas->nama_fasilitas }}"
                 class="w-40 h-32 object-cover rounded-lg flex-shrink-0">

            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-bold text-gray-900">{{ $fasilitas->nama_fasilitas }}</h2>

                    @if($fasilitas->sedangAktif())
                        @auth
                            <a href="{{ route('reservations.create', ['facility_id' => $fasilitas->id]) }}"
                               class="flex items-center gap-2 px-4 py-2 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition whitespace-nowrap">
                                Reservasi Sekarang
                            </a>
                        @else
                            <button type="button" @click="showLoginModal = true"
                                    class="flex items-center gap-2 px-4 py-2 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition whitespace-nowrap">
                                Reservasi Sekarang
                            </button>
                        @endauth
                    @endif
                </div>

                <p class="text-sm text-gray-600 flex items-center gap-1.5 mt-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $fasilitas->lokasi }}
                </p>
                <p class="text-sm text-gray-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ $fasilitas->kapasitas ?? '-' }} Orang
                </p>

                @if($fasilitas->deskripsi)
                    <p class="text-sm text-black mt-2" style="font-family: 'Poppins', sans-serif;">{{ $fasilitas->deskripsi }}</p>
                @endif

                @if($fasilitas->dalamPerbaikan())
                    <p class="mt-2 text-sm text-yellow-700 bg-yellow-50 inline-block px-3 py-1 rounded">
                        Sedang dalam perbaikan — belum bisa direservasi.
                    </p>
                @elseif(!$fasilitas->sedangAktif())
                    <p class="mt-2 text-sm text-gray-700 bg-gray-200 inline-block px-3 py-1 rounded">
                        Fasilitas nonaktif.
                    </p>
                @endif
            </div>
        </div>

    
        <div x-show="showLoginModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
             @click.self="showLoginModal = false">
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center relative">
                <button type="button" @click="showLoginModal = false"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <p class="text-lg font-bold text-gray-900 mt-4 mb-6">
                    Anda perlu masuk ke akun untuk mengajukan reservasi
                </p>

                <div class="flex gap-3">
                    <button type="button" @click="showLoginModal = false"
                            class="flex-1 py-3 bg-[#4a1a24] text-white font-semibold rounded-xl hover:bg-[#3a141c] transition">
                        Batal
                    </button>
                    <a href="{{ route('login') }}"
                       class="flex-1 py-3 bg-[#F5EFE9] text-[#4a1a24] font-semibold rounded-xl hover:bg-[#efe4da] transition flex items-center justify-center">
                        Masuk
                    </a>
                </div>
            </div>
        </div>

        <h2 class="font-semibold text-gray-800">Jadwal Ketersediaan</h2>
        <p class="text-sm text-[#B23A2E] mb-4">Lihat jadwal yang tersedia untuk fasilitas ini.</p>

        <!-- kalender -->
        <div
            x-data="{
                todayYear: {{ now()->year }},
                todayMonth: {{ now()->month - 1 }},
                viewYear: {{ $tglAktif->year }},
                viewMonth: {{ $tglAktif->month - 1 }},
                selectedDate: '{{ $tanggal }}',
                slots: {{ Js::from($daftarSlot) }},
                loading: false,
                cekMulai: '',
                cekSelesai: '',
                hasilCek: null,
                monthNames: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                dayNames: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],

                fmt(d) {
                    return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                },
                daysGrid() {
                    const firstOfMonth = new Date(this.viewYear, this.viewMonth, 1);
                    const lastOfMonth = new Date(this.viewYear, this.viewMonth + 1, 0);
                    const start = new Date(firstOfMonth);
                    start.setDate(start.getDate() - start.getDay());
                    const end = new Date(lastOfMonth);
                    end.setDate(end.getDate() + (6 - end.getDay()));
                    const days = [];
                    let cur = new Date(start);
                    while (cur <= end) {
                        days.push(new Date(cur));
                        cur.setDate(cur.getDate() + 1);
                    }
                    return days;
                },
                isPast(d) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    return d < today;
                },
                isSameMonth(d) {
                    return d.getMonth() === this.viewMonth && d.getFullYear() === this.viewYear;
                },
                isSelected(d) {
                    return this.fmt(d) === this.selectedDate;
                },
                get isViewingCurrentMonth() {
                    return this.viewYear === this.todayYear && this.viewMonth === this.todayMonth;
                },
                prevMonth() {
                    if (this.isViewingCurrentMonth) return;
                    this.viewMonth--;
                    if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; }
                },
                nextMonth() {
                    this.viewMonth++;
                    if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; }
                },
                async selectDate(d) {
                    if (this.isPast(d) && !this.isSelected(d)) return;
                    this.selectedDate = this.fmt(d);
                    this.cekMulai = '';
                    this.cekSelesai = '';
                    this.hasilCek = null;
                    this.loading = true;
                    try {
                        const res = await fetch(`{{ route('fasilitas.slots', $fasilitas) }}?tanggal=${this.selectedDate}`);
                        const data = await res.json();
                        this.slots = data.slots;
                    } finally {
                        this.loading = false;
                    }
                },
                get slotTerisi() {
                    return this.slots.filter(s => s.terisi);
                },
                get selectedLabel() {
                    const d = new Date(this.selectedDate + 'T00:00:00');
                    return this.dayNames[d.getDay()] + ', ' + d.getDate() + ' ' + this.monthNames[d.getMonth()] + ' ' + d.getFullYear();
                },
                get monthLabel() {
                    return this.monthNames[this.viewMonth] + ' ' + this.viewYear;
                },
                checkAvailability() {
                    if (!this.cekMulai || !this.cekSelesai) { this.hasilCek = null; return; }
                    const bentrok = this.slotTerisi.some(s => this.cekMulai < s.selesai && this.cekSelesai > s.mulai);
                    this.hasilCek = bentrok ? 'bentrok' : 'tersedia';
                }
            }"
            class="grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-6"
        >

            <!-- kalender -->
            <div class="bg-white rounded-xl shadow-sm p-5 relative border border-[#D5C6BD]">
                <div x-show="loading" class="absolute inset-0 bg-white/60 flex items-center justify-center rounded-xl z-10">
                    <span class="text-sm text-gray-500">Memuat...</span>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <button type="button" @click="prevMonth()" x-show="!isViewingCurrentMonth"
                            class="text-gray-500 hover:text-gray-800 px-2">‹</button>
                    <span x-show="isViewingCurrentMonth" class="w-6"></span>
                    <span class="font-semibold text-gray-800" x-text="monthLabel"></span>
                    <button type="button" @click="nextMonth()" class="text-gray-500 hover:text-gray-800 px-2">›</button>
                </div>

                <div class="grid grid-cols-7 text-center text-xs text-gray-500 mb-2">
                    <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                    <template x-for="(d, idx) in daysGrid()" :key="idx">
                        <button type="button"
                                @click="selectDate(d)"
                                class="py-2 rounded-lg"
                                :class="{
                                    'bg-[#511E1D] text-white font-semibold': isSelected(d),
                                    'hover:bg-[#F5EFE9] text-gray-800 cursor-pointer': !isSelected(d) && isSameMonth(d) && !isPast(d),
                                    'text-gray-300 cursor-default': !isSameMonth(d) || (isPast(d) && !isSelected(d))
                                }"
                                x-text="d.getDate()">
                        </button>
                    </template>
                </div>
            </div>

            <!-- cek ketersediaan -->
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm p-5 border border-[#D5C6BD]">
                    <h3 class="font-semibold text-gray-800 mb-3" x-text="selectedLabel"></h3>

                    <template x-if="slotTerisi.length === 0">
                        <p class="text-sm text-gray-500">Belum ada jadwal terisi pada tanggal ini.</p>
                    </template>

                    <div class="space-y-2">
                        <template x-for="slot in slotTerisi" :key="slot.mulai">
                            <div class="flex items-center justify-between bg-[#F5EFE9] rounded-lg px-4 py-2.5">
                                <span class="text-sm text-gray-800" x-text="slot.mulai + ' - ' + slot.selesai"></span>
                                <span class="text-xs font-medium px-3 py-1 rounded-full bg-red-100 text-red-600">Terpesan</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 border border-[#D5C6BD]">
                    <h3 class="font-semibold text-gray-800">Cek Ketersediaan</h3>
                    <p class="text-sm text-gray-500 mb-3">Masukkan estimasi waktu yang Anda inginkan.</p>

                    <div class="flex items-center gap-2">
                        <input type="time" x-model="cekMulai" step="1800" class="rounded-lg border-gray-300 text-sm">
                        <span class="text-gray-400">—</span>
                        <input type="time" x-model="cekSelesai" step="1800" class="rounded-lg border-gray-300 text-sm">
                        <button type="button" @click="checkAvailability()"
                                class="px-4 py-2 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] whitespace-nowrap">
                            Cek
                        </button>
                    </div>

                    <template x-if="hasilCek === 'tersedia'">
                        <p class="mt-3 text-sm font-medium text-green-700 bg-green-50 px-3 py-2 rounded-lg">
                            <span x-text="'Slot ' + cekMulai + '–' + cekSelesai + ' tersedia.'"></span>
                        </p>
                    </template>
                    <template x-if="hasilCek === 'bentrok'">
                        <p class="mt-3 text-sm font-medium text-red-700 bg-red-50 px-3 py-2 rounded-lg">
                            <span x-text="'Slot ' + cekMulai + '–' + cekSelesai + ' bentrok dengan reservasi lain.'"></span>
                        </p>
                    </template>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>