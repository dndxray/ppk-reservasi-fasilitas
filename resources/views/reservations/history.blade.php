<x-app-layout>


<div class="p-6">


<h2>
Riwayat Reservasi Saya
</h2>



@foreach($reservations as $reservation)


<div class="border p-4 mt-3">


<p>
Fasilitas:
{{ $reservation->facility->nama }}
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
Status:
{{ $reservation->status }}
</p>

<a href="{{route(
'reservations.show',
$reservation->id
)}}">

Lihat Detail

</a>

@if(
$reservation->status == 'menunggu'
||
$reservation->status == 'disetujui'
)


<form method="POST"

action="{{route(
'reservations.cancel',
$reservation->id
)}}">


@csrf

@method('PATCH')


<button>

Batalkan

</button>


</form>


@endif



</div>


@endforeach



</div>


</x-app-layout>