<x-app-layout>

<div class="min-h-screen bg-white py-8">


    <div class="max-w-5xl mx-auto px-8">


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

            <h1 class="text-2xl font-bold text-black">
                Formulir Pengajuan Reservasi
            </h1>
        </div>





        @if(session('success'))
        <div id="successToast"
        class="
        fixed
        bottom-8
        left-1/2
        -translate-x-1/2
        z-[100]
        flex
        items-center
        gap-3
        bg-[#22C55E]
        text-white
        px-6
        py-4
        rounded-2xl
        shadow-2xl
        font-semibold
        transition-all
        duration-500
        transform
        translate-y-0
        opacity-100
        "
        >
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>

        <script>
        setTimeout(() => {
            const toast = document.getElementById('successToast');
            if(toast){
                toast.style.opacity = "0";
                toast.style.transform = "translate(-50%, 20px)";
                setTimeout(()=>{
                    toast.remove();
                }, 500);
            }
        }, 4000);
        </script>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())

            <div class="
                mb-5
                p-4
                rounded-lg
                bg-[#FDDFDE]
                border
                border-[#950704]
                text-[#950704]
                font-semibold
                text-[15px]
            ">

                @foreach ($errors->all() as $error)

                    <p>
                        {{ $error }}
                    </p>

                @endforeach


            </div>

        @endif

        {{-- CARD FORM --}}
        <div class="
            bg-white
            border
            border-[#D5C6BD]
            rounded-2xl
            p-9
        ">

            <form
                id="reservationForm"
                method="POST"
                action="{{ route('reservations.store') }}"
                enctype="multipart/form-data"
            >
            @csrf

            @php
                $currentFacilityId = old('facility_id', $selectedFacilityId ?? null);
                $oldMulai = old('waktu_mulai');
                $oldSelesai = old('waktu_selesai');
                $mulaiSlots = [
                    '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
                    '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
                    '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
                    '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
                    '19:00', '19:30'
                ];
            @endphp

            {{-- NAMA FASILITAS --}}
            <div class="mb-5">
                <label class="
                    block
                    text-[17px]
                    font-bold
                    text-black
                    mb-2
                ">
                    Nama Fasilitas
                </label>


                <select
                    name="facility_id"
                    id="facility_id"
                    required
                    onchange="updateLokasi()"
                    class="
                    w-full
                    h-12
                    rounded-lg
                    bg-[#F5F0ED]
                    border
                    border-[#D5C6BD]
                    px-4
                    text-black
                    focus:ring-0
                    focus:border-[#BE433E]
                    "
                >
                    <option value="">
                        Pilih fasilitas
                    </option>

                    @foreach($facilities as $facility)
                        <option
                            value="{{ $facility->id }}"
                            data-lokasi="{{ $facility->lokasi }}"
                            {{ (string)$currentFacilityId === (string)$facility->id ? 'selected' : '' }}
                        >
                            {{ $facility->nama_fasilitas }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- LOKASI FASILITAS --}}
            <div class="mb-6">
                <label class="
                    block
                    text-[17px]
                    font-bold
                    text-black
                    mb-3
                ">
                    Lokasi Fasilitas
                </label>
                <input
                    type="text"
                    name="lokasi"
                    id="lokasi"
                    readonly
                    placeholder="Lokasi fasilitas akan otomatis terisi"
                    class="
                    w-full
                    h-12
                    rounded-lg
                    bg-[#EAE3DF]
                    border
                    border-[#D5C6BD]
                    px-4
                    text-black
                    cursor-not-allowed
                    "
                >
            </div>

            {{-- TANGGAL --}}
            <div class="mb-6">
                <label class="
                    block
                    text-[17px]
                    font-bold
                    text-black
                    mb-3
                ">
                    Tanggal
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="tanggal_display"
                        onclick="openCalendar()"
                        readonly
                        placeholder="Pilih tanggal"
                        class="
                        w-full
                        h-12
                        rounded-lg
                        bg-[#F5F0ED]
                        border
                        border-[#D5C6BD]
                        px-4
                        text-black
                        cursor-pointer
                        "
                    >

                    <img src="{{ asset('assets/icons/tanggal.png') }}"
                    onclick="openCalendar()"
                    class="
                    absolute
                    right-4
                    top-3
                    w-6
                    h-6
                    cursor-pointer
                    "
                    >

                    <input
                        type="hidden"
                        name="tanggal"
                        id="tanggal"
                        value="{{ old('tanggal') }}"
                    >
                </div>
            </div>

            {{-- WAKTU --}}
            <div class="grid grid-cols-2 gap-10 mb-6">
                {{-- MULAI --}}
                <div>
                    <label class="
                        block
                        text-[17px]
                        font-bold
                        text-black
                        mb-2
                    ">
                        Waktu Mulai
                    </label>

                    <input type="hidden" name="waktu_mulai" id="waktu_mulai" value="{{ substr($oldMulai, 0, 5) }}" required>

                    <div class="relative" id="wrapper_waktu_mulai">
                        <button
                            type="button"
                            id="btn_waktu_mulai"
                            onclick="toggleMenuMulai()"
                            class="
                            w-full
                            h-12
                            rounded-lg
                            bg-[#F5F0ED]
                            border
                            border-[#D5C6BD]
                            px-4
                            text-left
                            flex
                            items-center
                            justify-between
                            focus:outline-none
                            focus:border-[#BE433E]
                            "
                        >
                            <span id="label_waktu_mulai" class="{{ $oldMulai ? 'text-black font-medium' : 'text-gray-500' }}">
                                {{ $oldMulai ? substr($oldMulai, 0, 5) : 'Pilih waktu mulai' }}
                            </span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="arrow_waktu_mulai" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            id="menu_waktu_mulai"
                            class="hidden absolute left-0 right-0 top-14 z-30 max-h-48 overflow-y-auto bg-white border border-[#D5C6BD] rounded-xl shadow-lg p-1.5 space-y-1"
                        >
                            @foreach($mulaiSlots as $slot)
                                <button
                                    type="button"
                                    onclick="selectWaktuMulai('{{ $slot }}')"
                                    class="w-full text-left px-3 py-2 rounded-lg text-sm transition hover:bg-[#F5F0ED] text-black"
                                >
                                    {{ $slot }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SELESAI --}}
                <div>
                    <label class="
                        block
                        text-[17px]
                        font-bold
                        text-black
                        mb-2
                    ">
                        Waktu Selesai
                    </label>

                    <input type="hidden" name="waktu_selesai" id="waktu_selesai" value="{{ substr($oldSelesai, 0, 5) }}" required>

                    <div class="relative" id="wrapper_waktu_selesai">
                        <button
                            type="button"
                            id="btn_waktu_selesai"
                            onclick="toggleMenuSelesai()"
                            class="
                            w-full
                            h-12
                            rounded-lg
                            bg-[#F5F0ED]
                            border
                            border-[#D5C6BD]
                            px-4
                            text-left
                            flex
                            items-center
                            justify-between
                            focus:outline-none
                            focus:border-[#BE433E]
                            "
                        >
                            <span id="label_waktu_selesai" class="{{ $oldSelesai ? 'text-black font-medium' : 'text-gray-500' }}">
                                {{ $oldSelesai ? substr($oldSelesai, 0, 5) : 'Pilih waktu selesai' }}
                            </span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="arrow_waktu_selesai" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            id="menu_waktu_selesai"
                            class="hidden absolute left-0 right-0 top-14 z-30 max-h-48 overflow-y-auto bg-white border border-[#D5C6BD] rounded-xl shadow-lg p-1.5 space-y-1"
                        >
                            {{-- Dynamic options --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- TUJUAN --}}
            <div class="mb-5">
                <label class="
                    block
                    text-[17px]
                    font-bold
                    text-black
                    mb-2
                ">
                    Tujuan Penggunaan
                </label>

                <textarea
                    name="tujuan_penggunaan"
                    rows="4"
                    required
                    placeholder="Masukkan tujuan penggunaan"
                    class="
                    w-full
                    rounded-lg
                    bg-[#F5F0ED]
                    border
                    border-[#D5C6BD]
                    px-4
                    py-3
                    text-black
                    resize-none
                    "
                >{{ old('tujuan_penggunaan') }}</textarea>
            </div>

            {{-- BUTTON --}}
            <div class="
                flex
                justify-end
                items-center
                gap-8
            ">
                <button
                    type="reset"
                    onclick="setTimeout(() => { updateLokasi(); resetWaktuMulai(); resetWaktuSelesai(); }, 50)"
                    class="
                    font-semibold
                    text-black
                    "
                >
                    Reset
                </button>

                <button
                    type="button"
                    onclick="openConfirmModal()"
                    class="
                    bg-[#BE433E]
                    text-white
                    font-semibold
                    rounded-xl
                    px-10
                    py-3
                    hover:opacity-90
                    "
                >
                    Ajukan Reservasi
                </button>
            </div>
            </form>
            </div>
            </form>
        </div>
    </div>
</div>
{{-- MODAL KONFIRMASI --}}
<div
    id="confirmModal"
    class="
    fixed
    inset-0
    bg-black/50
    hidden
    items-center
    justify-center
    z-50
    "
>
    <div class="
        bg-white
        w-[390px]
        rounded-3xl
        p-8
        text-center
        relative
    ">
        <button
            onclick="closeConfirmModal()"
            class="
            absolute
            top-3
            right-5
            text-3xl
            text-black
            "
        >
            ×

        </button>

        <h2 class="
            text-2xl
            font-bold
            text-black
            leading-tight
            mb-8
        ">
            Apakah Anda yakin<br>
            ingin mengajukan<br>
            reservasi?
        </h2>
        <div class="flex gap-3">
            <button
                type="button"
                onclick="closeConfirmModal()"
                class="
                flex-1
                py-3
                rounded-xl
                bg-[#6F3835]
                text-white
                font-semibold
                "

            >

                Batal

            </button>

            <button
                type="button"
                onclick="submitReservation()"
                class="
                flex-1
                py-3
                rounded-xl
                bg-[#F5F0ED]
                border
                border-[#D5C6BD]
                text-black
                font-semibold
                "
            >
                Kirim
            </button>
        </div>
    </div>
</div>

{{-- CALENDAR MODAL --}}

<div

id="calendarModal"

class="
fixed
inset-0
bg-black/40
hidden
items-center
justify-center
z-50
"

>


<div class="
bg-white
rounded-3xl
w-[380px]
p-6
border
border-[#D5C6BD]
">



<div class="
flex
justify-between
items-center
mb-5
">


<button
onclick="changeMonth(-1)"
class="text-xl"
>
‹
</button>



<h2
id="monthYear"
class="
font-bold
text-lg
text-black
">
</h2>



<button
onclick="changeMonth(1)"
class="text-xl"
>
›
</button>


</div>





<div class="
grid
grid-cols-7
text-center
mb-3
font-semibold
text-sm
">


<div>Sen</div>
<div>Sel</div>
<div>Rab</div>
<div>Kam</div>
<div>Jum</div>
<div>Sab</div>
<div>Min</div>


</div>





<div

id="calendarDays"

class="
grid
grid-cols-7
gap-2
text-center
"

>

</div>





<div class="
flex
justify-end
gap-3
mt-6
">


<button

onclick="closeCalendar()"

class="
px-5
py-2
rounded-xl
bg-[#6F3835]
text-white
font-semibold
"

>

Batal

</button>



<button

onclick="selectDate()"

class="
px-5
py-2
rounded-xl
bg-[#BE433E]
text-white
font-semibold
"

>

Pilih

</button>


</div>


</div>


</div>

<script>
let currentDate = new Date();
let selectedDate = null;

const allTimeSlots = [
    '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
    '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
    '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
    '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
    '19:00', '19:30', '20:00'
];

function toggleMenuMulai() {
    const menu = document.getElementById('menu_waktu_mulai');
    const arrow = document.getElementById('arrow_waktu_mulai');
    closeMenuSelesai();
    if (menu) menu.classList.toggle('hidden');
    if (arrow) arrow.classList.toggle('rotate-180');
}

function closeMenuMulai() {
    const menu = document.getElementById('menu_waktu_mulai');
    const arrow = document.getElementById('arrow_waktu_mulai');
    if (menu) menu.classList.add('hidden');
    if (arrow) arrow.classList.remove('rotate-180');
}

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

function selectWaktuMulai(slot) {
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

function resetWaktuMulai() {
    document.getElementById('waktu_mulai').value = '';
    const label = document.getElementById('label_waktu_mulai');
    label.innerText = 'Pilih waktu mulai';
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

    const availableEndSlots = allTimeSlots.filter(slot => slot > selectedMulai);

    if (availableEndSlots.length === 0) {
        menu.innerHTML = '<div class="px-3 py-2 text-sm text-gray-400">Tidak ada slot selesai tersedia</div>';
        return;
    }

    availableEndSlots.forEach(slot => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.onclick = function() { selectWaktuSelesai(slot); };
        btn.className = 'w-full text-left px-3 py-2 rounded-lg text-sm transition hover:bg-[#F5F0ED] text-black ' +
            (currentSelesai === slot ? 'bg-[#BE433E] text-white font-semibold hover:bg-[#BE433E]' : '');
        btn.innerText = slot;
        menu.appendChild(btn);
    });
}

function updateLokasi() {
    const select = document.getElementById('facility_id');
    const lokasiInput = document.getElementById('lokasi');
    if (!select || !lokasiInput) return;

    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption && selectedOption.dataset && selectedOption.dataset.lokasi) {
        lokasiInput.value = selectedOption.dataset.lokasi;
    } else {
        lokasiInput.value = '';
    }
}

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
    let year = currentDate.getFullYear();
    let month = currentDate.getMonth();

    document.getElementById('monthYear').innerHTML = currentDate.toLocaleDateString('id-ID', {
        month: 'long',
        year: 'numeric'
    });

    let first = new Date(year, month, 1).getDay();
    let total = new Date(year, month + 1, 0).getDate();

    let html = '';
    let start = first === 0 ? 6 : first - 1;

    for (let i = 0; i < start; i++) {
        html += `<div></div>`;
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let day = 1; day <= total; day++) {
        let cellDate = new Date(year, month, day);
        cellDate.setHours(0, 0, 0, 0);

        let isPast = cellDate < today;
        let isSelected = selectedDate &&
            selectedDate.getDate() === day &&
            selectedDate.getMonth() === month &&
            selectedDate.getFullYear() === year;

        if (isPast) {
            html += `
            <button
                type="button"
                disabled
                class="h-10 rounded-full text-gray-300 cursor-not-allowed opacity-40 flex items-center justify-center w-full"
            >
                ${day}
            </button>
            `;
        } else {
            html += `
            <button
                type="button"
                onclick="chooseDate(${day})"
                class="h-10 rounded-full flex items-center justify-center w-full ${isSelected ? 'bg-[#BE433E] text-white font-bold' : 'text-black hover:bg-[#F5F0ED]'}"
            >
                ${day}
            </button>
            `;
        }
    }

    document.getElementById('calendarDays').innerHTML = html;
}

function chooseDate(day) {
    let targetDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (targetDate < today) return;

    selectedDate = targetDate;
    renderCalendar();
}

function selectDate() {
    if (!selectedDate) return;

    let y = selectedDate.getFullYear();
    let m = String(selectedDate.getMonth() + 1).padStart(2, '0');
    let d = String(selectedDate.getDate()).padStart(2, '0');

    document.getElementById('tanggal').value = `${y}-${m}-${d}`;
    document.getElementById('tanggal_display').value = selectedDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    closeCalendar();
}

function changeMonth(value) {
    currentDate.setMonth(currentDate.getMonth() + value);
    renderCalendar();
}

function openConfirmModal() {
    closeCalendar();
    closeMenuMulai();
    closeMenuSelesai();
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
    document.getElementById('reservationForm').submit();
}

document.addEventListener('click', function(event) {
    const wrapMulai = document.getElementById('wrapper_waktu_mulai');
    const wrapSelesai = document.getElementById('wrapper_waktu_selesai');

    if (wrapMulai && !wrapMulai.contains(event.target)) {
        closeMenuMulai();
    }
    if (wrapSelesai && !wrapSelesai.contains(event.target)) {
        closeMenuSelesai();
    }
});

document.addEventListener('DOMContentLoaded', function() {
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

    const initialMulai = "{{ substr($oldMulai ?? '', 0, 5) }}";
    const initialSelesai = "{{ substr($oldSelesai ?? '', 0, 5) }}";

    if (initialMulai) {
        selectWaktuMulai(initialMulai);
    }
    updateWaktuSelesaiMenu(initialSelesai);
});
</script>



</x-app-layout>