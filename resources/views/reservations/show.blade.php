<x-app-layout>


<div class="p-6">


<h2 class="text-xl font-bold">

Detail Reservasi

</h2>




<div class="mt-5 border p-5">


<p>

Fasilitas:

{{ $reservation->facility->nama }}

</p>



<p>

Tipe:

{{ $reservation->facility->tipe }}

</p>



<p>

Lokasi:

{{ $reservation->facility->lokasi }}

</p>




<p>

Tanggal:

{{ $reservation->tanggal }}

</p>




<p>

Waktu:

{{ $reservation->waktu_mulai }}

-

{{ $reservation->waktu_selesai }}

</p>




<p>

Tujuan:

{{ $reservation->tujuan_penggunaan }}

</p>




<p>

Status:

{{ $reservation->status }}

</p>



<p>

Pemesan:

{{ $reservation->user->name }}

</p>



</div>


</div>


</x-app-layout>