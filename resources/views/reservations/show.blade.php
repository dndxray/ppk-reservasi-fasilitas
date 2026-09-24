<x-app-layout>

<div class="min-h-screen bg-[#F8F7F7] py-8">


<div class="max-w-6xl mx-auto px-8">


{{-- JUDUL --}}

<div class="mb-8">


<div class="flex items-center gap-5 mb-8">

<a href="{{ route('reservations.history') }}">

<img
src="{{ asset('assets/icons/backward.png') }}"
class="w-8 h-8"
>

</a>


<h1 class="
text-3xl
font-bold
text-[#47201B]
">

Detail Reservasi

</h1>


</div>


<p class="text-gray-500 mt-2">

Informasi lengkap reservasi fasilitas

</p>


</div>





<div class="grid grid-cols-3 gap-6">





{{-- =========================
DETAIL RESERVASI
========================= --}}


<div class="col-span-2 bg-white rounded-2xl border border-[#E6D6CE] p-8">


<div class="flex justify-between items-center mb-8">


<h2 class="text-xl font-bold text-[#47201B]">

Detail Reservasi

</h2>



@if($reservation->status == 'menunggu')


<span class="
px-4 py-2
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
px-4 py-2
rounded-full
bg-green-100
text-green-700
text-sm
font-semibold
">

Diterima

</span>



@elseif($reservation->status == 'ditolak')


<span class="
px-4 py-2
rounded-full
bg-red-100
text-red-700
text-sm
font-semibold
">

Ditolak

</span>



@else


<span class="
px-4 py-2
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





{{-- INFORMASI USER --}}


<div class="space-y-6">



{{-- NAMA --}}

<div class="grid grid-cols-[40px_180px_1fr] items-center">


<img
src="{{ asset('assets/icons/user.png') }}"
class="w-6 h-6"
>


<p class="text-gray-500">

Nama Pemesan

</p>


<p class="font-semibold">

{{ $reservation->user->name }}

</p>


</div>





{{-- EMAIL --}}

<div class="grid grid-cols-[40px_180px_1fr] items-center">


<img
src="{{ asset('assets/icons/email.png') }}"
class="w-6 h-6"
>


<p class="text-gray-500">

Email

</p>


<p class="font-semibold">

{{ $reservation->user->email }}

</p>


</div>





{{-- TANGGAL --}}

<div class="grid grid-cols-[40px_180px_1fr] items-center">


<img
src="{{ asset('assets/icons/tanggal.png') }}"
class="w-6 h-6"
>


<p class="text-gray-500">

Tanggal

</p>


<p class="font-semibold">


{{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d F Y') }}


</p>


</div>






{{-- WAKTU --}}

<div class="grid grid-cols-[40px_180px_1fr] items-center">


<img
src="{{ asset('assets/icons/waktu.png') }}"
class="w-6 h-6"
>


<p class="text-gray-500">

Rentang Waktu

</p>


<p class="font-semibold">


{{ $reservation->waktu_mulai }}

-

{{ $reservation->waktu_selesai }}


</p>


</div>







{{-- TUJUAN --}}

<div class="grid grid-cols-[40px_180px_1fr] items-start">


<img
src="{{ asset('assets/icons/tujuan.png') }}"
class="w-6 h-6 mt-1"
>


<p class="text-gray-500">

Tujuan Penggunaan

</p>


<p class="font-semibold">

{{ $reservation->tujuan_penggunaan }}

</p>


</div>




</div>








{{-- SURAT DELEGASI --}}


<div class="mt-10">


<h3 class="font-bold text-[#47201B] mb-3">

Surat Delegasi

</h3>



<div class="
bg-[#47201B]
rounded-xl
p-5
text-white
flex
justify-between
items-center
">


<div>

<p class="font-semibold">

Surat Pendukung

</p>


<p class="text-sm opacity-70">

PDF

</p>


</div>



<button class="
bg-[#E8B7AD]
text-[#47201B]
px-4
py-2
rounded-lg
">


>

</button>


</div>



</div>



</div>










{{-- =========================
DETAIL FASILITAS
========================= --}}



<div class="
bg-white
rounded-2xl
border
border-[#E6D6CE]
p-6
">


{{-- FOTO --}}


<div class="
h-40
bg-[#E1D3C4]
rounded-xl
flex
items-center
justify-center
">


<p class="text-[#996561]">

Foto Fasilitas

</p>


</div>





<h2 class="
text-xl
font-bold
text-[#47201B]
mt-5
">


{{ $reservation->facility->nama_fasilitas }}


</h2>






<div class="mt-5 space-y-4">


<div class="flex items-center gap-3">


<img
src="{{ asset('assets/icons/lokasi.png') }}"
class="w-5 h-5"
>


<p>

{{ $reservation->facility->lokasi }}

</p>


</div>




<div class="flex items-center gap-3">


<img
src="{{ asset('assets/icons/kapasitas.png') }}"
class="w-5 h-5"
>


<p>

{{ $reservation->facility->kapasitas }}

Orang

</p>


</div>



<span class="
bg-[#47201B]
text-white
px-4
py-2
rounded-lg
text-sm
inline-block
">


{{ $reservation->facility->tipe }}


</span>



</div>









{{-- BATAS PEMBATALAN --}}


<div class="mt-8">


<h3 class="font-bold text-[#47201B] mb-3">

Keterangan Batas Pembatalan Reservasi

</h3>



<div class="
bg-[#F5EFEC]
rounded-xl
p-5
text-sm
leading-relaxed
">


Reservasi dapat dibatalkan maksimal:

<br>


<strong>

1 x 24 jam sebelum waktu penggunaan.

</strong>


<br><br>


Pembatalan setelah melewati batas waktu tidak dapat dilakukan.


</div>



</div>







{{-- BUTTON --}}

@if(
$reservation->status == 'menunggu'
||
$reservation->status == 'disetujui'
)

<button

onclick="openCancelModal()"

class="
w-full
bg-[#F8D8D5]
text-[#BE433E]
py-4
rounded-xl
font-bold
flex
justify-center
items-center
gap-2
hover:bg-[#BE433E]
hover:text-white
transition
"

>

<img
src="{{ asset('assets/icons/batalkan.png') }}"
class="w-5 h-5"
>

Batalkan Reservasi


</button>


@endif




</div>



</div>



</div>


</div>


{{-- MODAL BATAL RESERVASI --}}

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

onclick="closeCancelModal()"

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

ingin membatalkan<br>

reservasi?


</h2>



<div class="flex gap-3 w-full">


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

class=" flex-1 py-3 rounded-xl bg-[#F5F0ED] border border-[#D5C6BD] text-black font-semibold"

>

Ya, Batalkan

</button>



</div>


</div>


</div>

<form

id="cancelForm"

method="POST"

action="{{ route('reservations.cancel',$reservation->id) }}"

>

@csrf

@method('PATCH')


</form>

<script>

function openCancelModal(){

    const modal = document.getElementById('cancelModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

}



function closeCancelModal(){

    const modal = document.getElementById('cancelModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

}

function submitCancel(){

    document
    .getElementById('cancelForm')
    .submit();

}

</script>

</x-app-layout>