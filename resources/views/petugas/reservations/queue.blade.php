<x-app-layout>

<div class="min-h-screen bg-[#F8F7F7] p-8">


<h1 class="text-2xl font-bold text-[#47201B] mb-6">

Antrian Reservasi

</h1>



<div class="bg-white rounded-2xl shadow overflow-hidden">


<table class="w-full">


<thead class="bg-[#47201B] text-white">


<tr>

<th class="p-4 text-left">
Pemohon
</th>


<th class="p-4 text-left">
Fasilitas
</th>


<th class="p-4 text-left">
Tanggal
</th>


<th class="p-4 text-left">
Status
</th>


<th class="p-4 text-center">
Aksi
</th>


</tr>


</thead>



<tbody>



@foreach($reservations as $reservation)


<tr class="border-b">


<td class="p-4">

{{ $reservation->user->name }}

</td>



<td class="p-4">

{{ $reservation->facility->nama }}

</td>



<td class="p-4">

{{ $reservation->tanggal }}

<br>

{{ $reservation->waktu_mulai }}
-
{{ $reservation->waktu_selesai }}

</td>



<td class="p-4">


@if($reservation->status == 'menunggu')

<span class="px-3 py-1 rounded-full bg-[#E1D3C4] text-[#47201B]">

Menunggu

</span>


@elseif($reservation->status == 'disetujui')


<span class="px-3 py-1 rounded-full bg-green-100 text-green-700">

Disetujui

</span>


@elseif($reservation->status == 'ditolak')


<span class="px-3 py-1 rounded-full bg-red-100 text-red-700">

Ditolak

</span>


@endif


</td>




<td class="p-4 text-center">


<a href="{{ route(
'petugas.reservations.show',
$reservation->id
)}}"

class="bg-[#CA734D] text-white px-4 py-2 rounded-lg">


Detail


</a>


</td>


</tr>



@endforeach



</tbody>


</table>


</div>


</div>


</x-app-layout>