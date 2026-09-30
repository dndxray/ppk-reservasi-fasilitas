<x-app-layout>

<div class="min-h-screen bg-white py-8">

    <div class="max-w-5xl mx-auto px-8">

        @php
            $backUrl = isset($selectedFacilityId) && $selectedFacilityId 
                ? route('fasilitas.show', $selectedFacilityId) 
                : route('reports.index');

            $currentFacilityId = old('facility_id', $selectedFacilityId ?? null);

            $defaultKategoriOptions = [
                'AC',
                'Meja',
                'Kursi',
                'Proyektor / Layar',
                'Kelistrikan / Lampu',
                'Pintu / Jendela',
                'Sanitasi / Toilet',
                'Komputer / Lab',
                'Lainnya',
            ];

            $oldKategori = old('kategori');
            $oldKategoriLainnya = old('kategori_lainnya');
            $isCustomKategori = ($oldKategori === 'Lainnya') || ($oldKategori && !in_array($oldKategori, $defaultKategoriOptions));
            $selectedKategori = $isCustomKategori ? 'Lainnya' : $oldKategori;
            $customKategoriVal = $oldKategoriLainnya ?: ($isCustomKategori && $oldKategori !== 'Lainnya' ? $oldKategori : '');
        @endphp

        {{-- HEADER --}}
        <div class="flex items-center gap-5 mb-6">
            <a href="{{ $backUrl }}" class="flex items-center hover:opacity-75 transition">
                <img src="{{ asset('assets/icons/backward.png') }}" class="w-8 h-8" alt="Kembali">
            </a>

            <h1 class="text-2xl font-bold text-black">
                Formulir Pelaporan Kerusakan
            </h1>
        </div>

        {{-- SUCCESS NOTIFICATION --}}
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
                if (toast) {
                    toast.style.opacity = "0";
                    toast.style.transform = "translate(-50%, 20px)";
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }
            }, 4000);
            </script>
        @endif

        {{-- ERROR --}}
        @if (isset($errors) && $errors->any())
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
                    <p>{{ $error }}</p>
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
                id="reportForm"
                method="POST"
                action="{{ route('reports.store') }}"
                enctype="multipart/form-data"
            >
            @csrf

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

            {{-- KATEGORI & TANGGAL DITEMUKAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-6">

                {{-- KATEGORI KERUSAKAN --}}
                <div>
                    <label class="
                        block
                        text-[17px]
                        font-bold
                        text-black
                        mb-2
                    ">
                        Kategori Kerusakan
                    </label>

                    <select
                        name="kategori"
                        id="kategori"
                        required
                        onchange="handleKategoriChange()"
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
                        <option value="">Pilih kategori kerusakan</option>
                        <option value="AC" {{ $selectedKategori === 'AC' ? 'selected' : '' }}>AC</option>
                        <option value="Meja" {{ $selectedKategori === 'Meja' ? 'selected' : '' }}>Meja</option>
                        <option value="Kursi" {{ $selectedKategori === 'Kursi' ? 'selected' : '' }}>Kursi</option>
                        <option value="Proyektor / Layar" {{ $selectedKategori === 'Proyektor / Layar' ? 'selected' : '' }}>Proyektor / Layar</option>
                        <option value="Kelistrikan / Lampu" {{ $selectedKategori === 'Kelistrikan / Lampu' ? 'selected' : '' }}>Kelistrikan / Lampu</option>
                        <option value="Pintu / Jendela" {{ $selectedKategori === 'Pintu / Jendela' ? 'selected' : '' }}>Pintu / Jendela</option>
                        <option value="Sanitasi / Toilet" {{ $selectedKategori === 'Sanitasi / Toilet' ? 'selected' : '' }}>Sanitasi / Toilet</option>
                        <option value="Komputer / Lab" {{ $selectedKategori === 'Komputer / Lab' ? 'selected' : '' }}>Komputer / Lab</option>
                        <option value="Lainnya" {{ $selectedKategori === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>

                    {{-- WRAPPER JIKA PILIH LAINNYA --}}
                    <div id="wrapper_kategori_lainnya" class="{{ $selectedKategori === 'Lainnya' ? '' : 'hidden' }} mt-3">
                        <label class="block text-sm font-semibold text-black mb-1.5">
                            Sebutkan Kategori Lainnya <span class="text-[#BE433E]">*</span>
                        </label>
                        <input
                            type="text"
                            name="kategori_lainnya"
                            id="kategori_lainnya"
                            value="{{ $customKategoriVal }}"
                            placeholder="Contoh: Papan Tulis, Kipas Angin, Whiteboard..."
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
                    </div>
                </div>

                {{-- TANGGAL DITEMUKAN --}}
                <div>
                    <label class="
                        block
                        text-[17px]
                        font-bold
                        text-black
                        mb-2
                    ">
                        Tanggal Ditemukan
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

                        <img
                            src="{{ asset('assets/icons/tanggal.png') }}"
                            onclick="openCalendar()"
                            class="
                            absolute
                            right-4
                            top-3
                            w-6
                            h-6
                            cursor-pointer
                            "
                            alt="Pilih tanggal"
                        >

                        <input
                            type="hidden"
                            name="tanggal_ditemukan"
                            id="tanggal_ditemukan"
                            value="{{ old('tanggal_ditemukan', date('Y-m-d')) }}"
                        >
                    </div>
                </div>

            </div>

            {{-- DESKRIPSI KERUSAKAN --}}
            <div class="mb-5">
                <label class="
                    block
                    text-[17px]
                    font-bold
                    text-black
                    mb-2
                ">
                    Deskripsi Kerusakan
                </label>

                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="4"
                    required
                    placeholder="Jelaskan secara detail kerusakan fasilitas yang Anda temukan"
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
                    focus:ring-0
                    focus:border-[#BE433E]
                    "
                >{{ old('deskripsi') }}</textarea>
            </div>

            {{-- FOTO KONDISI KERUSAKAN --}}
            <div class="mb-6">
                <label class="
                    block
                    text-[17px]
                    font-bold
                    text-black
                    mb-2
                ">
                    Foto Kondisi Kerusakan
                    <span class="text-sm font-normal text-gray-500">(Opsional, maks. 2MB)</span>
                </label>

                <div
                    id="foto_dropzone"
                    onclick="document.getElementById('foto').click()"
                    class="
                    relative
                    border-2
                    border-dashed
                    border-[#D5C6BD]
                    rounded-xl
                    bg-[#F5F0ED]
                    hover:bg-[#EAE3DF]
                    transition
                    p-6
                    text-center
                    cursor-pointer
                    min-h-[140px]
                    flex
                    flex-col
                    items-center
                    justify-center
                    group
                    "
                >
                    {{-- Belum pilih foto --}}
                    <div id="foto_empty_state" class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#BE433E] shadow-sm group-hover:scale-105 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black">
                                Klik atau seret foto kondisi kerusakan ke sini
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Maksimal 2MB (Format: JPG, JPEG, PNG)
                            </p>
                        </div>
                    </div>

                    {{-- Preview foto terpilih --}}
                    <div id="foto_preview_state" class="hidden flex items-center gap-4 w-full px-2" onclick="event.stopPropagation()">
                        <img id="foto_preview_img" src="" alt="Preview Foto" class="w-20 h-20 object-cover rounded-xl border border-[#D5C6BD] shadow-sm">
                        <div class="flex-1 text-left min-w-0">
                            <p id="foto_preview_name" class="text-sm font-semibold text-black truncate"></p>
                            <p class="text-xs text-green-600 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Foto siap diunggah
                            </p>
                            <div class="mt-2 flex items-center gap-3">
                                <button type="button" onclick="document.getElementById('foto').click()" class="text-xs font-bold text-[#BE433E] hover:underline">
                                    Ganti Foto
                                </button>
                                <span class="text-[#D5C6BD]">|</span>
                                <button type="button" onclick="removePhoto()" class="text-xs font-semibold text-gray-600 hover:text-red-600">
                                    Hapus Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    <input
                        type="file"
                        name="foto"
                        id="foto"
                        accept="image/*"
                        onchange="handlePhotoChange(event)"
                        class="hidden"
                    >
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="
                flex
                justify-end
                items-center
                gap-8
                pt-2
            ">
                <button
                    type="reset"
                    onclick="resetReportForm()"
                    class="
                    font-semibold
                    text-black
                    hover:text-[#BE433E]
                    transition
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
                    transition
                    "
                >
                    Kirim Laporan
                </button>
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
            type="button"
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
            ingin mengirimkan<br>
            laporan kerusakan?
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
                hover:opacity-90
                transition
                "
            >
                Batal
            </button>

            <button
                type="button"
                onclick="submitReport()"
                class="
                flex-1
                py-3
                rounded-xl
                bg-[#F5F0ED]
                border
                border-[#D5C6BD]
                text-black
                font-semibold
                hover:bg-[#EAE3DF]
                transition
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
                type="button"
                onclick="changeMonth(-1)"
                class="text-xl px-2 hover:opacity-75"
            >
                ‹
            </button>

            <h2
                id="monthYear"
                class="
                font-bold
                text-lg
                text-black
                "
            >
            </h2>

            <button
                type="button"
                onclick="changeMonth(1)"
                class="text-xl px-2 hover:opacity-75"
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
                type="button"
                onclick="closeCalendar()"
                class="
                px-5
                py-2
                rounded-xl
                bg-[#6F3835]
                text-white
                font-semibold
                hover:opacity-90
                transition
                "
            >
                Batal
            </button>

            <button
                type="button"
                onclick="selectDate()"
                class="
                px-5
                py-2
                rounded-xl
                bg-[#BE433E]
                text-white
                font-semibold
                hover:opacity-90
                transition
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

function handleKategoriChange() {
    const select = document.getElementById('kategori');
    const wrapper = document.getElementById('wrapper_kategori_lainnya');
    const inputLainnya = document.getElementById('kategori_lainnya');

    if (!select || !wrapper || !inputLainnya) return;

    if (select.value === 'Lainnya') {
        wrapper.classList.remove('hidden');
        inputLainnya.required = true;
        inputLainnya.focus();
    } else {
        wrapper.classList.add('hidden');
        inputLainnya.required = false;
        inputLainnya.value = '';
    }
}

function handlePhotoChange(event) {
    const file = event.target.files[0];
    const emptyState = document.getElementById('foto_empty_state');
    const previewState = document.getElementById('foto_preview_state');
    const previewImg = document.getElementById('foto_preview_img');
    const previewName = document.getElementById('foto_preview_name');

    if (file) {
        previewName.innerText = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            emptyState.classList.add('hidden');
            previewState.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    const fileInput = document.getElementById('foto');
    const emptyState = document.getElementById('foto_empty_state');
    const previewState = document.getElementById('foto_preview_state');
    const previewImg = document.getElementById('foto_preview_img');
    const previewName = document.getElementById('foto_preview_name');

    if (fileInput) fileInput.value = '';
    if (previewImg) previewImg.src = '';
    if (previewName) previewName.innerText = '';

    if (previewState) previewState.classList.add('hidden');
    if (emptyState) emptyState.classList.remove('hidden');
}

function resetReportForm() {
    setTimeout(() => {
        updateLokasi();
        handleKategoriChange();
        removePhoto();

        // Kembalikan tanggal ke hari ini
        const today = new Date();
        selectedDate = today;
        let y = today.getFullYear();
        let m = String(today.getMonth() + 1).padStart(2, '0');
        let d = String(today.getDate()).padStart(2, '0');
        document.getElementById('tanggal_ditemukan').value = `${y}-${m}-${d}`;
        document.getElementById('tanggal_display').value = today.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }, 50);
}

function openCalendar() {
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

        // Tanggal pelaporan kerusakan tidak boleh di masa depan
        let isFuture = cellDate > today;
        let isSelected = selectedDate &&
            selectedDate.getDate() === day &&
            selectedDate.getMonth() === month &&
            selectedDate.getFullYear() === year;

        if (isFuture) {
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
    if (targetDate > today) return;

    selectedDate = targetDate;
    renderCalendar();
}

function selectDate() {
    if (!selectedDate) return;

    let y = selectedDate.getFullYear();
    let m = String(selectedDate.getMonth() + 1).padStart(2, '0');
    let d = String(selectedDate.getDate()).padStart(2, '0');

    document.getElementById('tanggal_ditemukan').value = `${y}-${m}-${d}`;
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
    const form = document.getElementById('reportForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    closeCalendar();
    const modal = document.getElementById('confirmModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function submitReport() {
    document.getElementById('reportForm').submit();
}

// Drag & drop support untuk foto
document.addEventListener('DOMContentLoaded', function() {
    updateLokasi();

    const dateVal = document.getElementById('tanggal_ditemukan').value;
    if (dateVal) {
        const parts = dateVal.split('-');
        if (parts.length === 3) {
            selectedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            currentDate = new Date(selectedDate);
            document.getElementById('tanggal_display').value = selectedDate.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    }

    handleKategoriChange();

    const dropzone = document.getElementById('foto_dropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-[#BE433E]', 'bg-[#EAE3DF]');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-[#BE433E]', 'bg-[#EAE3DF]');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                const fileInput = document.getElementById('foto');
                fileInput.files = files;
                handlePhotoChange({ target: { files: files } });
            }
        }, false);
    }
});
</script>

</x-app-layout>