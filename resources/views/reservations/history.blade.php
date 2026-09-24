<x-app-layout>


<div class="min-h-screen bg-[#F8F7F7] py-4 sm:py-8">


<div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">



{{-- HEADER --}}

<div class="mb-5 sm:mb-8">

<h1 class="text-xl sm:text-3xl font-bold text-[#47201B]">
Riwayat Reservasi
</h1>

<p class="text-gray-500 text-xs sm:text-base mt-1 sm:mt-2
">
Lihat seluruh riwayat pengajuan reservasi fasilitas Anda.
</p>

</div>


{{-- SEARCH FILTER --}}

<div x-data="{showFilter:false}" class="mb-5 sm:mb-8">


<form method="GET" action="{{ route('reservations.history') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-3"
>

<input type="text"
    name="search"
    value="{{ request('search') }}"
    placeholder="Cari fasilitas..."
    class="
    flex-1
    w-full
    min-w-0
    box-border
    px-4
    py-2.5 sm:py-3
    rounded-lg
    bg-white
    border
    border-[#D5C6BD]
    text-sm
    focus:outline-none
    focus:ring-2
    focus:ring-[#47201B]
    "
>

<button type="button"
@click="showFilter=!showFilter"
class="
w-full
sm:w-auto
shrink-0
px-6
py-2.5 sm:py-3
rounded-lg
bg-[#47201B]
text-white
font-semibold
text-sm
"
>
Filter
</button>

</form>


<div
x-show="showFilter"
x-transition
x-cloak
class="
mt-3 sm:mt-4
bg-white
rounded-xl
border
border-[#E6D6CE]
p-4 sm:p-5
w-full
box-border
"
>

<form
method="GET"
action="{{ route('reservations.history') }}"
>

<input
type="hidden"
name="search"
value="{{ request('search') }}"
>

<label class="
block
text-xs sm:text-sm
text-gray-600
mb-1.5 sm:mb-2
">
Status Reservasi
</label>

<select name="status" class="w-full
min-w-0
box-border
rounded-lg
border-gray-300
text-sm
py-2
"
>

<option value="">
Semua Status
</option>

<option value="disetujui"
@selected(request('status')=='disetujui')
>
Disetujui
</option>

<option value="ditolak"
@selected(request('status')=='ditolak')
>
Ditolak
</option>

<option value="dibatalkan"
@selected(request('status')=='dibatalkan')
>
Dibatalkan
</option>

</select>

<div class="mt-4 sm:mt-5 flex gap-2 sm:gap-3">

<button
type="submit"
class="
flex-1
px-5
py-2
rounded-lg
bg-[#47201B]
text-white
text-sm
font-semibold
text-center
"
>
Terapkan
</button>

<a
href="{{ route('petugas.reservations.index') }}"
class="
flex-1
px-5
py-2
rounded-lg
border
text-sm
font-semibold
text-center
"
>
Reset
</a>

</div>

</form>

</div>

</div>


{{-- ========================
DAFTAR RIWAYAT - MOBILE (CARD)
======================== --}}

<div class="md:hidden space-y-3">

@forelse($reservations as $index => $reservation)

<div class="
bg-white
rounded-2xl
border
border-[#E6D6CE]
p-4
">

<div class="flex justify-between items-start gap-3 mb-3">

<div class="min-w-0">
<p class="font-semibold text-[#47201B] break-words">
{{ $reservation->facility->nama_fasilitas }}
</p>
<p class="text-xs text-gray-500 break-words">
{{ $reservation->facility->lokasi }}
</p>
</div>

@if($reservation->status == 'menunggu')
<span class="shrink-0 px-2.5 py-1 rounded-full bg-[#E1D3C4] text-[#47201B] text-xs font-semibold">
Menunggu
</span>
@elseif($reservation->status == 'disetujui')
<span class="shrink-0 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
Disetujui
</span>
@elseif($reservation->status == 'ditolak')
<span class="shrink-0 px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
Ditolak
</span>
@elseif($reservation->status == 'dibatalkan')
<span class="shrink-0 px-2.5 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-semibold">
Dibatalkan
</span>
@endif

</div>

<div class="text-sm mb-4">
<p class="font-semibold">
{{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
</p>
<p class="text-xs text-gray-500">
{{ $reservation->waktu_mulai }} - {{ $reservation->waktu_selesai }}
</p>
</div>

<div class="flex gap-2">

<a
href="{{ route('reservations.show',$reservation->id) }}"
class="
flex-1
text-center
px-4
py-2
rounded-lg
bg-[#F5F0ED]
text-[#47201B]
text-sm
font-semibold
"
>
Detail
</a>

@if($reservation->status == 'menunggu' || $reservation->status == 'disetujui')

<button
onclick="openCancelModal({{ $reservation->id }})"
class="
flex-1
px-4
py-2
rounded-lg
bg-[#F8D8D5]
text-[#950704]
text-sm
font-semibold
"
>
Batalkan
</button>

@endif

</div>

</div>

@empty

<div class="bg-white rounded-2xl border border-[#E6D6CE] text-center py-10 text-gray-500 text-sm">
Belum ada riwayat reservasi.
</div>

@endforelse

</div>


{{-- ========================
DAFTAR RIWAYAT - DESKTOP 
======================== --}}

<div class="
hidden
md:block
bg-white
rounded-2xl
border
border-[#E6D6CE]
overflow-hidden
">

<div class="overflow-x-auto">

<table class="w-full min-w-[720px]">

<thead
class="
bg-[#47201B]
text-white
"
>

<tr>

<th class="px-6 py-4 text-left whitespace-nowrap">
No
</th>

<th class="px-6 py-4 text-left">
Nama Fasilitas
</th>

<th class="px-6 py-4 text-left whitespace-nowrap">
Jadwal Reservasi
</th>

<th class="px-6 py-4 text-left whitespace-nowrap">
Status
</th>

<th class="px-6 py-4 text-center whitespace-nowrap">
Aksi
</th>

</tr>

</thead>


<tbody>

@forelse($reservations as $index => $reservation)

<tr
class="
border-b
border-[#E6D6CE]
hover:bg-[#F8F7F7]
"
>

{{-- NOMOR --}}
<td class="px-6 py-5 whitespace-nowrap">
{{ $index + 1 }}.
</td>

{{-- FASILITAS --}}
<td class="px-6 py-5">
<p class="font-semibold text-[#47201B] break-words">
{{ $reservation->facility->nama_fasilitas }}
</p>
<p class="text-sm text-gray-500 break-words">
{{ $reservation->facility->lokasi }}
</p>
</td>

{{-- TANGGAL DAN WAKTU --}}
<td class="px-6 py-5 whitespace-nowrap">
<p class="font-semibold">
{{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
</p>
<p class="text-sm text-gray-500">
{{ $reservation->waktu_mulai }} - {{ $reservation->waktu_selesai }}
</p>
</td>

{{-- STATUS --}}
<td class="px-6 py-5 whitespace-nowrap">

@if($reservation->status == 'menunggu')
<span class="px-4 py-2 rounded-full bg-[#E1D3C4] text-[#47201B] text-sm font-semibold">
Menunggu
</span>
@elseif($reservation->status == 'disetujui')
<span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
Disetujui
</span>
@elseif($reservation->status == 'ditolak')
<span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
Ditolak
</span>
@elseif($reservation->status == 'dibatalkan')
<span class="px-4 py-2 rounded-full bg-gray-200 text-gray-700 text-sm font-semibold">
Dibatalkan
</span>
@endif

</td>

{{-- AKSI --}}
<td class="px-6 py-5">

<div class="flex justify-center gap-2">

<a
href="{{ route('reservations.show',$reservation->id) }}"
class="
px-4
py-2
rounded-lg
bg-[#F5F0ED]
text-[#47201B]
text-sm
font-semibold
whitespace-nowrap
"
>
Detail
</a>

@if($reservation->status == 'menunggu' || $reservation->status == 'disetujui')

<button
onclick="openCancelModal({{ $reservation->id }})"
class="
px-4
py-2
rounded-lg
bg-[#F8D8D5]
text-[#950704]
text-sm
font-semibold
whitespace-nowrap
"
>
Batalkan
</button>

@endif

</div>

</td>

</tr>

@empty

<tr>
<td
colspan="5"
class="
text-center
py-10
text-gray-500
"
>
Belum ada riwayat reservasi.
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>


</div>


</div>


{{-- MODAL BATALKAN RESERVASI --}}
<div
    id="cancelModal"
    class="
    fixed
    inset-0
    bg-black/50
    hidden
    items-center
    justify-center
    z-50
    px-4
    "
>

    <div class="
        bg-white
        w-full
        max-w-[390px]
        rounded-3xl
        p-8
        text-center
        relative
    ">

        {{-- Tombol close --}}
        <button
            type="button"
            onclick="closeCancelModal()"
            class="
            absolute
            top-3
            right-5
            text-3xl
            text-black
            leading-none
            "
        >
            ×
        </button>

        {{-- Judul --}}
        <h2 class="
            text-2xl
            font-bold
            text-black
            leading-tight
            mb-8
        ">
            Apakah Anda yakin<br>
            ingin membatalkan<br>
            reservasi?
        </h2>

        {{-- Tombol aksi --}}
        <div class="flex gap-3">
            <button
                type="button"
                onclick="closeCancelModal()"
                class="
                flex-1
                py-3
                rounded-xl
                bg-[#6F3835]
                text-white
                font-semibold
                "
            >
                Tidak
            </button>

            <button
                type="button"
                onclick="submitCancel()"
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
                Ya, Batalkan
            </button>
        </div>

    </div>

</div>

<form 
id="cancelForm"
method="POST"
>

@csrf
@method('PATCH')

</form>


<script>

let cancelId;


function openCancelModal(id)
{
    cancelId = id;

    document
    .getElementById('cancelModal')
    .classList
    .remove('hidden');


    document
    .getElementById('cancelModal')
    .classList
    .add('flex');
}



function closeCancelModal()
{

    document
    .getElementById('cancelModal')
    .classList
    .add('hidden');


    document
    .getElementById('cancelModal')
    .classList
    .remove('flex');

}



function submitCancel()
{

    let form =
    document.getElementById('cancelForm');


    form.action =
    "/reservations/"
    + cancelId
    + "/cancel";


    form.submit();

}

</script>

</x-app-layout>