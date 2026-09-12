<x-app-layout>

<div class="min-h-screen bg-[#F8F7F7]">


<!-- HEADER -->

<div class="bg-[#47201B] text-white px-8 py-5">

<h1 class="text-2xl font-bold">
← Reservasi Fasilitas
</h1>

</div>




<div class="p-8">


<div class="grid grid-cols-3 gap-6">



<!-- DETAIL RESERVASI -->

<div class="col-span-2 bg-white rounded-2xl shadow p-8">


<h2 class="text-xl font-bold text-[#47201B] mb-6">

Detail Reservasi

</h2>



<div class="space-y-5">


<div>
<p class="text-gray-500">
Nama Pemesan
</p>

<p class="font-semibold">
{{ $reservation->user->name }}
</p>

</div>



<div>

<p class="text-gray-500">
Email/NIM
</p>

<p class="font-semibold">
{{ $reservation->user->email }}
</p>

</div>




<div>

<p class="text-gray-500">
Nama Fasilitas
</p>

<p class="font-semibold">
{{ $reservation->facility->nama }}
</p>

</div>




<div>

<p class="text-gray-500">
Tanggal
</p>

<p class="font-semibold">

{{ $reservation->tanggal }}

</p>

</div>



<div>

<p class="text-gray-500">
Rentang Waktu
</p>

<p class="font-semibold">

{{ $reservation->waktu_mulai }}
-
{{ $reservation->waktu_selesai }}

</p>

</div>




<div>

<p class="text-gray-500">
Tujuan Penggunaan
</p>

<p class="font-semibold">

{{ $reservation->tujuan_penggunaan }}

</p>

</div>



</div>


</div>






<!-- PANEL KANAN -->


<div class="bg-white rounded-2xl shadow p-6">


<div 
class="h-40 bg-[#E1D3C4] rounded-xl flex items-center justify-center">

<span class="text-[#996561]">

Foto Fasilitas

</span>

</div>



<h2 class="mt-5 text-xl font-bold text-[#47201B]">

{{ $reservation->facility->nama }}

</h2>



<div class="mt-3 space-y-2">


<p>

Lokasi :
{{ $reservation->facility->lokasi ?? '-' }}

</p>


<p>

Kapasitas :
{{ $reservation->facility->kapasitas ?? '-' }}

orang

</p>


</div>




<hr class="my-6">



@if($reservation->status=="menunggu")



<form method="POST"

action="{{route(
'petugas.reservations.approve',
$reservation->id
)}}"

onsubmit="return confirm('Apakah yakin ingin menyetujui reservasi ini?')">


@csrf

@method('PATCH')


<button

class="
w-full
bg-[#CA734D]
hover:bg-[#47201B]
text-white
py-3
rounded-xl
font-semibold">

Setujui

</button>


</form>




<form method="POST"

action="{{route(
'petugas.reservations.reject',
$reservation->id
)}}"

onsubmit="return confirm('Apakah yakin ingin menolak reservasi ini?')">


@csrf

@method('PATCH')


<button

class="
mt-3
w-full
border
border-[#47201B]
text-[#47201B]
py-3
rounded-xl
font-semibold">

Tolak

</button>


</form>



@endif




</div>



</div>


</div>


</div>


</x-app-layout>