<x-app-layout>

<div class="min-h-screen bg-white py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-8">

        @php
            $backUrl = isset($selectedFacilityId) && $selectedFacilityId
                ? route('fasilitas.show', $selectedFacilityId)
                : route('fasilitas.index');
        @endphp

        {{-- HEADER --}}
        <div class="flex items-center gap-5 mb-6">
            <a href="{{ $backUrl }}" class="flex items-center hover:opacity-75 transition">
                <img src="{{ asset('assets/icons/backward.png') }}" class="w-8 h-8" alt="Kembali">
            </a>
            <h1 class="text-2xl font-bold text-black">Formulir Pengajuan Reservasi</h1>
        </div>

        @if(session('success'))
            <div id="successToast"
                 class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] flex items-center gap-3 bg-[#22C55E] text-white px-6 py-4 rounded-2xl shadow-2xl font-semibold transition-all duration-500 transform translate-y-0 opacity-100">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>

            <script>
                setTimeout(() => {
                    const toast = document.getElementById('successToast');
                    if (toast) {
                        toast.style.opacity = "0";
                        toast.style.transform = "translate(-50%, 20px)";
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 4000);
            </script>
        @endif

        {{-- ERROR DARI SERVER --}}
        @if ($errors->any())
            <div class="mb-5 p-4 rounded-lg bg-[#FDDFDE] border border-[#950704] text-[#950704] font-semibold text-[15px]">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- ERROR DARI SISI KLIEN --}}
        <div id="clientError"
             class="hidden mb-5 p-4 rounded-lg bg-[#FDDFDE] border border-[#950704] text-[#950704] font-semibold text-[15px]"
             role="alert"></div>

        {{-- CARD FORM --}}
        <div class="bg-white border border-[#D5C6BD] rounded-2xl p-6 sm:p-9">

            <form id="reservationForm" method="POST" action="{{ route('reservations.store') }}" enctype="multipart/form-data">
                @csrf

                @php
                    $currentFacilityId = old('facility_id', $selectedFacilityId ?? null);
                    $oldMulai   = substr(old('waktu_mulai', ''), 0, 5);
                    $oldSelesai = substr(old('waktu_selesai', ''), 0, 5);
                @endphp

                {{-- NAMA FASILITAS --}}
                <div class="mb-5">
                    <label class="block text-[17px] font-bold text-black mb-2">Nama Fasilitas</label>

                    <select name="facility_id" id="facility_id" required onchange="updateLokasi(); refreshSlots()"
                            class="w-full h-12 rounded-lg bg-[#F5F0ED] border border-[#D5C6BD] px-4 text-black focus:ring-0 focus:border-[#BE433E]">
                        <option value="">Pilih fasilitas</option>

                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}"
                                    data-lokasi="{{ $facility->lokasi }}"
                                    {{ (string)$currentFacilityId === (string)$facility->id ? 'selected' : '' }}>
                                {{ $facility->nama_fasilitas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- LOKASI FASILITAS --}}
                <div class="mb-6">
                    <label class="block text-[17px] font-bold text-black mb-3">Lokasi Fasilitas</label>
                    <input type="text" name="lokasi" id="lokasi" readonly
                           placeholder="Lokasi fasilitas akan otomatis terisi"
                           class="w-full h-12 rounded-lg bg-[#EAE3DF] border border-[#D5C6BD] px-4 text-black cursor-not-allowed">
                </div>

                {{-- TANGGAL --}}
                <div class="mb-6">
                    <label class="block text-[17px] font-bold text-black mb-3">Tanggal</label>

                    <div class="relative">
                        <input type="text" id="tanggal_display" onclick="openCalendar()" readonly
                               placeholder="Pilih tanggal"
                               class="w-full h-12 rounded-lg bg-[#F5F0ED] border border-[#D5C6BD] px-4 text-black cursor-pointer">

                        <img src="{{ asset('assets/icons/tanggal.png') }}" onclick="openCalendar()"
                             class="absolute right-4 top-3 w-6 h-6 cursor-pointer" alt="">

                        <input type="hidden" name="tanggal" id="tanggal" value="{{ old('tanggal') }}">
                    </div>
                </div>

                {{-- WAKTU --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-10 mb-6">

                    {{-- MULAI --}}
                    <div>
                        <label class="block text-[17px] font-bold text-black mb-2">Waktu Mulai</label>

                        <input type="hidden" name="waktu_mulai" id="waktu_mulai" value="{{ $oldMulai }}" required>

                        <div class="relative" id="wrapper_waktu_mulai">
                            <button type="button" id="btn_waktu_mulai" onclick="toggleMenuMulai()"
                                    class="w-full h-12 rounded-lg bg-[#F5F0ED] border border-[#D5C6BD] px-4 text-left flex items-center justify-between focus:outline-none focus:border-[#BE433E]">
                                <span id="label_waktu_mulai" class="{{ $oldMulai ? 'text-black font-medium' : 'text-gray-500' }}">
                                    {{ $oldMulai ?: 'Pilih waktu mulai' }}
                                </span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="arrow_waktu_mulai" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Opsi dibuat lewat JavaScript (renderMulaiMenu) --}}
                            <div id="menu_waktu_mulai"
                                 class="hidden absolute left-0 right-0 top-14 z-30 max-h-48 overflow-y-auto bg-white border border-[#D5C6BD] rounded-xl shadow-lg p-1.5 space-y-1"></div>
                        </div>
                    </div>

                    {{-- SELESAI --}}
                    <div>
                        <label class="block text-[17px] font-bold text-black mb-2">Waktu Selesai</label>

                        <input type="hidden" name="waktu_selesai" id="waktu_selesai" value="{{ $oldSelesai }}" required>

                        <div class="relative" id="wrapper_waktu_selesai">
                            <button type="button" id="btn_waktu_selesai" onclick="toggleMenuSelesai()"
                                    class="w-full h-12 rounded-lg bg-[#F5F0ED] border border-[#D5C6BD] px-4 text-left flex items-center justify-between focus:outline-none focus:border-[#BE433E]">
                                <span id="label_waktu_selesai" class="{{ $oldSelesai ? 'text-black font-medium' : 'text-gray-500' }}">
                                    {{ $oldSelesai ?: 'Pilih waktu selesai' }}
                                </span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="arrow_waktu_selesai" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="menu_waktu_selesai"
                                 class="hidden absolute left-0 right-0 top-14 z-30 max-h-48 overflow-y-auto bg-white border border-[#D5C6BD] rounded-xl shadow-lg p-1.5 space-y-1"></div>
                        </div>
                    </div>
                </div>

                {{-- TUJUAN --}}
                <div class="mb-5">
                    <label class="block text-[17px] font-bold text-black mb-2">Tujuan Penggunaan</label>

                    <textarea name="tujuan_penggunaan" id="tujuan_penggunaan" rows="4" required
                              placeholder="Masukkan tujuan penggunaan"
                              class="w-full rounded-lg bg-[#F5F0ED] border border-[#D5C6BD] px-4 py-3 text-black resize-none">{{ old('tujuan_penggunaan') }}</textarea>
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end items-center gap-8">
                    <button type="reset" onclick="setTimeout(resetForm, 50)" class="font-semibold text-black">
                        Reset
                    </button>

                    <button type="button" onclick="openConfirmModal()"
                            class="bg-[#BE433E] text-white font-semibold rounded-xl px-10 py-3 hover:opacity-90">
                        Ajukan Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="confirmModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">
    <div class="bg-white w-full max-w-[390px] rounded-3xl p-8 text-center relative">
        <button type="button" onclick="closeConfirmModal()" class="absolute top-3 right-5 text-3xl text-black leading-none">&times;</button>

        <h2 class="text-2xl font-bold text-black leading-tight mb-8">
            Apakah Anda yakin<br>ingin mengajukan<br>reservasi?
        </h2>

        <div class="flex gap-3">
            <button type="button" onclick="closeConfirmModal()"
                    class="flex-1 py-3 rounded-xl bg-[#6F3835] text-white font-semibold">Batal</button>

            <button type="button" id="submitBtn" onclick="submitReservation()"
                    class="flex-1 py-3 rounded-xl bg-[#F5F0ED] border border-[#D5C6BD] text-black font-semibold disabled:opacity-60">Kirim</button>
        </div>
    </div>
</div>

{{-- CALENDAR MODAL --}}
<div id="calendarModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 px-4">
    <div class="bg-white rounded-3xl w-full max-w-[380px] p-6 border border-[#D5C6BD]">

        <div class="flex justify-between items-center mb-5">
            <button type="button" onclick="changeMonth(-1)" class="text-xl px-2">‹</button>
            <h2 id="monthYear" class="font-bold text-lg text-black"></h2>
            <button type="button" onclick="changeMonth(1)" class="text-xl px-2">›</button>
        </div>

        <div class="grid grid-cols-7 text-center mb-3 font-semibold text-sm">
            <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
        </div>

        <div id="calendarDays" class="grid grid-cols-7 gap-2 text-center"></div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeCalendar()"
                    class="px-5 py-2 rounded-xl bg-[#6F3835] text-white font-semibold">Batal</button>
            <button type="button" onclick="selectDate()"
                    class="px-5 py-2 rounded-xl bg-[#BE433E] text-white font-semibold">Pilih</button>
        </div>
    </div>
</div>

<script>
let currentDate  = new Date();
let selectedDate = null;

const allTimeSlots = [
    '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
    '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
    '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
    '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
    '19:00', '19:30', '20:00'
];

// Slot yang boleh dipilih sebagai waktu mulai (20:00 hanya untuk waktu selesai)
const startSlots = allTimeSlots.slice(0, -1);
const lastStartSlot = startSlots[startSlots.length - 1];

/* =========================
   HELPER WAKTU
========================= */
function pad(n) {
    return String(n).padStart(2, '0');
}

function formatYmd(d) {
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

function nowTime() {
    const d = new Date();
    return `${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

function isTodaySelected() {
    return document.getElementById('tanggal').value === formatYmd(new Date());
}

// Slot dianggap lewat jika tanggal yang dipilih hari ini dan jamnya sudah terlampaui
function isSlotPassed(slot) {
    return isTodaySelected() && slot <= nowTime();
}

/* =========================
   SLOT YANG SUDAH DIPESAN
========================= */
let slotTerisi = [];

function terisiMap() {
    const map = {};
    slotTerisi.forEach(s => { map[s.mulai] = true; });
    return map;
}

// Daftar jam selesai yang masih boleh dipilih, berhenti di jam yang sudah dipesan
function jamSelesaiTersedia(mulai) {
    const terisi = terisiMap();
    const daftar = [];
    const mulaiIndex = allTimeSlots.indexOf(mulai);

    if (mulaiIndex < 0) return daftar;

    for (let i = mulaiIndex; i < allTimeSlots.length - 1; i++) {
        if (terisi[allTimeSlots[i]]) break;
        daftar.push(allTimeSlots[i + 1]);
    }

    return daftar;
}

async function refreshSlots() {
    const facilityId = document.getElementById('facility_id').value;
    const tanggal = document.getElementById('tanggal').value;

    slotTerisi = [];

    if (facilityId && tanggal) {
        try {
            const res = await fetch("{{ url('/fasilitas') }}/" + facilityId + "/slots?tanggal=" + tanggal);
            const data = await res.json();
            slotTerisi = data.slots.filter(s => s.terisi);
        } catch (e) {
            slotTerisi = [];
        }
    }

    renderMulaiMenu();
    updateWaktuSelesaiMenu();
}

/* =========================
   DROPDOWN WAKTU MULAI
========================= */
function renderMulaiMenu() {
    const menu = document.getElementById('menu_waktu_mulai');
    if (!menu) return;

    const facilityId = document.getElementById('facility_id').value;
    const tanggal = document.getElementById('tanggal').value;

    if (!facilityId || !tanggal) {
        menu.innerHTML = '<div class="px-3 py-2 text-sm text-gray-400">Pilih fasilitas dan tanggal terlebih dahulu</div>';
        return;
    }

    const current = document.getElementById('waktu_mulai').value;
    const terisi = terisiMap();
    menu.innerHTML = '';

    const available = startSlots.filter(s => !isSlotPassed(s) && !terisi[s]);
    if (available.length === 0) {
        menu.innerHTML = '<div class="px-3 py-2 text-sm text-gray-400">Tidak ada slot tersedia di tanggal ini</div>';
        return;
    }

    startSlots.forEach(slot => {
        if (terisi[slot]) return;

        const passed = isSlotPassed(slot);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerText = slot;

        if (passed) {
            btn.disabled = true;
            btn.title = 'Waktu ini sudah lewat';
            btn.className = 'w-full text-left px-3 py-2 rounded-lg text-sm text-gray-300 line-through cursor-not-allowed';
        } else {
            btn.onclick = () => selectWaktuMulai(slot);
            btn.className = 'w-full text-left px-3 py-2 rounded-lg text-sm transition ' +
                (current === slot ? 'bg-[#BE433E] text-white font-semibold' : 'text-black hover:bg-[#F5F0ED]');
        }

        menu.appendChild(btn);
    });
}

function toggleMenuMulai() {
    const menu = document.getElementById('menu_waktu_mulai');
    const arrow = document.getElementById('arrow_waktu_mulai');
    closeMenuSelesai();
    renderMulaiMenu(); // selalu segarkan, supaya jam yang baru lewat ikut nonaktif
    if (menu) menu.classList.toggle('hidden');
    if (arrow) arrow.classList.toggle('rotate-180');
}

function closeMenuMulai() {
    const menu = document.getElementById('menu_waktu_mulai');
    const arrow = document.getElementById('arrow_waktu_mulai');
    if (menu) menu.classList.add('hidden');
    if (arrow) arrow.classList.remove('rotate-180');
}

function selectWaktuMulai(slot) {
    if (isSlotPassed(slot)) return;

    document.getElementById('waktu_mulai').value = slot;
    const label = document.getElementById('label_waktu_mulai');
    label.innerText = slot;
    label.classList.remove('text-gray-500');
    label.classList.add('text-black', 'font-medium');
    closeMenuMulai();

    const currentSelesai = document.getElementById('waktu_selesai').value;
    if (currentSelesai && currentSelesai <= slot) {
        resetWaktuSelesai();
    }

    updateWaktuSelesaiMenu();
}

function resetWaktuMulai() {
    document.getElementById('waktu_mulai').value = '';
    const label = document.getElementById('label_waktu_mulai');
    label.innerText = 'Pilih waktu mulai';
    label.classList.remove('text-black', 'font-medium');
    label.classList.add('text-gray-500');
}

/* =========================
   DROPDOWN WAKTU SELESAI
========================= */
function toggleMenuSelesai() {
    const menu = document.getElementById('menu_waktu_selesai');
    const arrow = document.getElementById('arrow_waktu_selesai');
    closeMenuMulai();
    if (menu) menu.classList.toggle('hidden');
    if (arrow) arrow.classList.toggle('rotate-180');
}

function closeMenuSelesai() {
    const menu = document.getElementById('menu_waktu_selesai');
    const arrow = document.getElementById('arrow_waktu_selesai');
    if (menu) menu.classList.add('hidden');
    if (arrow) arrow.classList.remove('rotate-180');
}

function selectWaktuSelesai(slot) {
    document.getElementById('waktu_selesai').value = slot;
    const label = document.getElementById('label_waktu_selesai');
    label.innerText = slot;
    label.classList.remove('text-gray-500');
    label.classList.add('text-black', 'font-medium');
    closeMenuSelesai();
}

function resetWaktuSelesai() {
    document.getElementById('waktu_selesai').value = '';
    const label = document.getElementById('label_waktu_selesai');
    label.innerText = 'Pilih waktu selesai';
    label.classList.remove('text-black', 'font-medium');
    label.classList.add('text-gray-500');
}

function updateWaktuSelesaiMenu(preferredSelesai = null) {
    const menu = document.getElementById('menu_waktu_selesai');
    if (!menu) return;

    const selectedMulai = document.getElementById('waktu_mulai').value;
    const currentSelesai = preferredSelesai || document.getElementById('waktu_selesai').value;

    menu.innerHTML = '';

    if (!selectedMulai) {
        menu.innerHTML = '<div class="px-3 py-2 text-sm text-gray-400">Pilih waktu mulai terlebih dahulu</div>';
        return;
    }

    const availableEndSlots = jamSelesaiTersedia(selectedMulai);

    if (availableEndSlots.length === 0) {
        resetWaktuSelesai();
        menu.innerHTML = '<div class="px-3 py-2 text-sm text-gray-400">Tidak ada slot selesai tersedia</div>';
        return;
    }

    if (currentSelesai && !availableEndSlots.includes(currentSelesai)) {
        resetWaktuSelesai();
    }

    availableEndSlots.forEach(slot => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.onclick = () => selectWaktuSelesai(slot);
        btn.className = 'w-full text-left px-3 py-2 rounded-lg text-sm transition ' +
            (currentSelesai === slot
                ? 'bg-[#BE433E] text-white font-semibold'
                : 'text-black hover:bg-[#F5F0ED]');
        btn.innerText = slot;
        menu.appendChild(btn);
    });
}

// Dipanggil setiap kali tanggal berubah
function refreshWaktuByDate() {
    const mulai = document.getElementById('waktu_mulai').value;

    if (mulai && isSlotPassed(mulai)) {
        resetWaktuMulai();
        resetWaktuSelesai();
    }

    refreshSlots();
}

/* =========================
   LOKASI
========================= */
function updateLokasi() {
    const select = document.getElementById('facility_id');
    const lokasiInput = document.getElementById('lokasi');
    if (!select || !lokasiInput) return;

    const opt = select.options[select.selectedIndex];
    lokasiInput.value = (opt && opt.dataset && opt.dataset.lokasi) ? opt.dataset.lokasi : '';
}

/* =========================
   KALENDER
========================= */
function openCalendar() {
    closeMenuMulai();
    closeMenuSelesai();
    const modal = document.getElementById('calendarModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    renderCalendar();
}

function closeCalendar() {
    const modal = document.getElementById('calendarModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    document.getElementById('monthYear').innerHTML = currentDate.toLocaleDateString('id-ID', {
        month: 'long',
        year: 'numeric'
    });

    const first = new Date(year, month, 1).getDay();
    const total = new Date(year, month + 1, 0).getDate();
    const start = first === 0 ? 6 : first - 1;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Hari ini tidak bisa dipilih lagi bila semua slot mulai sudah lewat
    const todayFull = nowTime() >= lastStartSlot;

    let html = '';
    for (let i = 0; i < start; i++) {
        html += '<div></div>';
    }

    for (let day = 1; day <= total; day++) {
        const cellDate = new Date(year, month, day);
        cellDate.setHours(0, 0, 0, 0);

        const isToday = cellDate.getTime() === today.getTime();
        const isPast = cellDate < today || (isToday && todayFull);
        const isSelected = selectedDate &&
            selectedDate.getDate() === day &&
            selectedDate.getMonth() === month &&
            selectedDate.getFullYear() === year;

        if (isPast) {
            html += `<button type="button" disabled
                class="h-10 rounded-full text-gray-300 cursor-not-allowed opacity-40 flex items-center justify-center w-full">${day}</button>`;
        } else {
            html += `<button type="button" onclick="chooseDate(${day})"
                class="h-10 rounded-full flex items-center justify-center w-full ${isSelected ? 'bg-[#BE433E] text-white font-bold' : 'text-black hover:bg-[#F5F0ED]'}">${day}</button>`;
        }
    }

    document.getElementById('calendarDays').innerHTML = html;
}

function chooseDate(day) {
    const target = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (target < today) return;

    selectedDate = target;
    renderCalendar();
}

function selectDate() {
    if (!selectedDate) return;

    document.getElementById('tanggal').value = formatYmd(selectedDate);
    document.getElementById('tanggal_display').value = selectedDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    closeCalendar();
    refreshWaktuByDate();
}

function changeMonth(value) {
    currentDate.setMonth(currentDate.getMonth() + value);
    renderCalendar();
}

/* =========================
   VALIDASI & SUBMIT
========================= */
function showClientError(msg) {
    const box = document.getElementById('clientError');
    box.innerText = msg;
    box.classList.remove('hidden');
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function hideClientError() {
    document.getElementById('clientError').classList.add('hidden');
}

function validateForm() {
    if (!document.getElementById('facility_id').value) return 'Pilih fasilitas terlebih dahulu.';
    if (!document.getElementById('tanggal').value) return 'Pilih tanggal reservasi.';

    const mulai = document.getElementById('waktu_mulai').value;
    const selesai = document.getElementById('waktu_selesai').value;

    if (!mulai) return 'Pilih waktu mulai.';
    if (isSlotPassed(mulai)) return 'Waktu mulai sudah lewat. Pilih waktu yang masih tersedia.';
    if (terisiMap()[mulai]) return 'Waktu mulai sudah dipesan. Pilih waktu lain.';
    if (!selesai) return 'Pilih waktu selesai.';
    if (selesai <= mulai) return 'Waktu selesai harus setelah waktu mulai.';
    if (!document.getElementById('tujuan_penggunaan').value.trim()) return 'Isi tujuan penggunaan.';

    return null;
}

function openConfirmModal() {
    closeCalendar();
    closeMenuMulai();
    closeMenuSelesai();

    const error = validateForm();
    if (error) {
        showClientError(error);
        return;
    }
    hideClientError();

    const modal = document.getElementById('confirmModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function submitReservation() {
    // Cek ulang saat kirim, karena jam bisa berganti selagi modal terbuka
    const error = validateForm();
    if (error) {
        closeConfirmModal();
        refreshWaktuByDate();
        showClientError(error);
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerText = 'Mengirim...';
    document.getElementById('reservationForm').submit();
}

function resetForm() {
    selectedDate = null;
    document.getElementById('tanggal').value = '';
    document.getElementById('tanggal_display').value = '';
    hideClientError();
    updateLokasi();
    resetWaktuMulai();
    resetWaktuSelesai();
    refreshSlots();
}

document.addEventListener('click', function (event) {
    const wrapMulai = document.getElementById('wrapper_waktu_mulai');
    const wrapSelesai = document.getElementById('wrapper_waktu_selesai');

    if (wrapMulai && !wrapMulai.contains(event.target)) closeMenuMulai();
    if (wrapSelesai && !wrapSelesai.contains(event.target)) closeMenuSelesai();
});

document.addEventListener('DOMContentLoaded', async function () {
    updateLokasi();

    const dateVal = document.getElementById('tanggal').value;
    if (dateVal) {
        const parts = dateVal.split('-');
        if (parts.length === 3) {
            selectedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            document.getElementById('tanggal_display').value = selectedDate.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    }

    const initialMulai = "{{ $oldMulai }}";
    const initialSelesai = "{{ $oldSelesai }}";

    // Data lama yang sudah lewat jamnya (mis. setelah gagal validasi) dibuang
    if (initialMulai && isSlotPassed(initialMulai)) {
        resetWaktuMulai();
        resetWaktuSelesai();
    }

    await refreshSlots();
    updateWaktuSelesaiMenu(initialSelesai);
});
</script>

</x-app-layout>