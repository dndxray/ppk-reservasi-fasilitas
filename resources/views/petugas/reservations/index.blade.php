<x-app-layout>


<div class="min-h-screen bg-[#F8F7F7] py-4 sm:py-8">


<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">



{{-- HEADER --}}

<div class="mb-5 sm:mb-8">

<h1 class="
text-xl sm:text-3xl
font-bold
text-[#47201B]
">
Riwayat Reservasi
</h1>

<p class="text-gray-500 text-xs sm:text-base mt-1 sm:mt-2">
Daftar seluruh reservasi yang telah diproses.
</p>

</div>


{{-- SEARCH FILTER --}}

<div x-data="{showFilter:false}" class="mb-5 sm:mb-8">


<form
method="GET"
action="{{ route('petugas.reservations.index') }}"
class="flex flex-col sm:flex-row gap-2 sm:gap-3"
>

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="Cari fasilitas atau pemesan..."
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

<button
type="button"
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
action="{{ route('petugas.reservations.index') }}"
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

<select
name="status"
class="
w-full
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
DAFTAR - MOBILE (CARD)
======================== --}}

<div class="md:hidden space-y-3">

@forelse($reservations as $index=>$reservation)

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

<span class="
px-4
py-2
rounded-full
bg-[#E1D3C4]
text-[#47201B]
text-sm
font-semibold
">

Menunggu

</span>


@elseif($reservation->status == 'disetujui')

<span class="
px-4
py-2
rounded-full
bg-green-100
text-green-700
text-sm
font-semibold
">

Disetujui

</span>


@elseif($reservation->status == 'ditolak')

<span class="
px-4
py-2
rounded-full
bg-red-100
text-red-700
text-sm
font-semibold
">

Ditolak

</span>


@elseif($reservation->status == 'dibatalkan')

<span class="
px-4
py-2
rounded-full
bg-gray-200
text-gray-700
text-sm
font-semibold
">

Dibatalkan

</span>

@endif

</div>

<div class="text-sm mb-2 pt-2 border-t border-[#F0E9E4]">
<p class="text-xs text-gray-400 mb-0.5">Pemesan</p>
<p class="font-semibold break-words">{{ $reservation->user->name }}</p>
<p class="text-xs text-gray-500 break-all">{{ $reservation->user->email }}</p>
</div>


<div class="text-sm mb-4 pt-2 border-t border-[#F0E9E4]">

<p class="text-xs text-gray-400 mb-0.5">
Diajukan Pada
</p>

<p class="font-semibold">
{{ $reservation->created_at->translatedFormat('d M Y') }}
</p>

<p class="text-xs text-gray-500">
{{ $reservation->created_at->format('H:i') }} WIB
</p>

</div>

<a
href="{{ route('petugas.reservations.show',$reservation->id) }}"
class="
block
text-center
w-full
px-5
py-2
rounded-lg
bg-[#F5F0ED]
text-[#47201B]
font-semibold
text-sm
"
>
Detail
</a>

</div>

@empty

<div class="bg-white rounded-2xl border border-[#E6D6CE] text-center py-10 text-gray-500 text-sm">
Belum ada riwayat reservasi.
</div>

@endforelse

</div>


{{-- ========================
DAFTAR - DESKTOP (TABLE)
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

<table class="w-full min-w-[820px]">

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
Fasilitas
</th>

<th class="px-6 py-4 text-left">
Pemesan
</th>


<th class="px-6 py-4 text-left whitespace-nowrap">
Diajukan Pada
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

@forelse($reservations as $index=>$reservation)

<tr class="
border-b
border-[#E6D6CE]
hover:bg-[#F8F7F7]
">

{{-- NO --}}
<td class="px-6 py-5 whitespace-nowrap">
{{ $index+1 }}.
</td>

{{-- FASILITAS --}}
<td class="px-6 py-5">
<p class="font-semibold break-words">
{{ $reservation->facility->nama_fasilitas }}
</p>
<p class="text-sm text-gray-500 break-words">
{{ $reservation->facility->lokasi }}
</p>
</td>

{{-- PEMESAN --}}
<td class="px-6 py-5">
<p class="font-semibold break-words">
{{ $reservation->user->name }}
</p>
<p class="text-sm text-gray-500 break-all">
{{ $reservation->user->email }}
</p>
</td>


{{-- DIBUAT --}}
<td class="px-6 py-5 whitespace-nowrap">

<p class="font-semibold">

{{ $reservation->created_at->translatedFormat('d M Y') }}

</p>

<p class="text-sm text-gray-500">

{{ $reservation->created_at->format('H:i') }} WIB

</p>

</td>

{{-- STATUS --}}
<td class="px-6 py-5 whitespace-nowrap">

@if($reservation->status == 'disetujui')
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
@elseif($reservation->status == 'menunggu')
<span class="px-4 py-2 rounded-full bg-[#E1D3C4] text-[#47201B] text-sm font-semibold">
Menunggu
</span>
@endif

</td>

{{-- AKSI --}}
<td class="px-6 py-5 text-center whitespace-nowrap">

<a
href="{{ route('petugas.reservations.show',$reservation->id) }}"
class="
px-5
py-2
rounded-lg
bg-[#F5F0ED]
text-[#47201B]
font-semibold
text-sm
"
>
Detail
</a>

</td>

</tr>

@empty

<tr>
<td colspan="7"
class="
text-center
py-10
text-gray-500
">
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


</x-app-layout>