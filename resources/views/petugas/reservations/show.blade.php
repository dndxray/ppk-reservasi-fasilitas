<x-app-layout>

<div class="min-h-screen bg-[#F8F7F7] py-4 sm:py-8">


<div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">


{{-- JUDUL --}}

<div class="mb-4 sm:mb-6">


<div class="flex items-center gap-3 sm:gap-5 mb-1.5 sm:mb-2">

<a href="{{ route('petugas.reservations.index') }}" class="shrink-0">

<img
src="{{ asset('assets/icons/backward.png') }}"
class="w-6 h-6 sm:w-8 sm:h-8"
>

</a>


<h1 class="
text-xl sm:text-3xl
font-bold
text-[#47201B]
">

Detail Reservasi

</h1>


</div>


<p class="text-gray-500 text-xs sm:text-base">

Informasi lengkap reservasi fasilitas

</p>


</div>




<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">


{{-- =========================
DETAIL RESERVASI
========================= --}}


<div class="lg:col-span-2 bg-white rounded-2xl border border-[#E6D6CE] p-4 sm:p-8">


<div class="flex justify-between items-center gap-2 mb-4 sm:mb-8">


<h2 class="text-base sm:text-xl font-bold text-[#47201B]">

Detail Reservasi

</h2>



@if($reservation->status == 'menunggu')


<span class="
shrink-0
px-2.5 py-1 sm:px-4 sm:py-2
rounded-full
bg-[#E1D3C4]
text-[#47201B]
text-xs sm:text-sm
font-semibold
whitespace-nowrap
">

Menunggu

</span>



@elseif($reservation->status == 'disetujui')


<span class="
shrink-0
px-2.5 py-1 sm:px-4 sm:py-2
rounded-full
bg-green-100
text-green-700
text-xs sm:text-sm
font-semibold
whitespace-nowrap
">

Diterima

</span>



@elseif($reservation->status == 'ditolak')


<span class="
shrink-0
px-2.5 py-1 sm:px-4 sm:py-2
rounded-full
bg-red-100
text-red-700
text-xs sm:text-sm
font-semibold
whitespace-nowrap
">

Ditolak

</span>



@else


<span class="
shrink-0
px-2.5 py-1 sm:px-4 sm:py-2
rounded-full
bg-gray-200
text-gray-700
text-xs sm:text-sm
font-semibold
whitespace-nowrap
">

Dibatalkan

</span>


@endif



</div>




{{-- INFORMASI USER --}}


<div class="space-y-3 sm:space-y-6">



{{-- NAMA --}}

<div class="grid grid-cols-[20px_1fr] sm:grid-cols-[24px_160px_1fr] gap-x-2 sm:gap-x-3 gap-y-0.5 sm:gap-y-0 items-center">


<img
src="{{ asset('assets/icons/user.png') }}"
class="w-4 h-4 sm:w-6 sm:h-6"
>


<div class="sm:contents">

<p class="text-gray-500 text-xs sm:text-base">

Nama Pemesan

</p>


<p class="font-semibold text-sm sm:text-base break-words">

{{ $reservation->user->name }}

</p>

</div>


</div>




{{-- EMAIL --}}

<div class="grid grid-cols-[20px_1fr] sm:grid-cols-[24px_160px_1fr] gap-x-2 sm:gap-x-3 gap-y-0.5 sm:gap-y-0 items-center">


<img
src="{{ asset('assets/icons/email.png') }}"
class="w-4 h-4 sm:w-6 sm:h-6"
>


<div class="sm:contents">

<p class="text-gray-500 text-xs sm:text-base">

Email

</p>


<p class="font-semibold text-sm sm:text-base break-all">

{{ $reservation->user->email }}

</p>

</div>


</div>




{{-- TANGGAL --}}

<div class="grid grid-cols-[20px_1fr] sm:grid-cols-[24px_160px_1fr] gap-x-2 sm:gap-x-3 gap-y-0.5 sm:gap-y-0 items-center">


<img
src="{{ asset('assets/icons/tanggal.png') }}"
class="w-4 h-4 sm:w-6 sm:h-6"
>


<div class="sm:contents">

<p class="text-gray-500 text-xs sm:text-base">

Tanggal

</p>


<p class="font-semibold text-sm sm:text-base break-words">


{{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('d F Y') }}


</p>

</div>


</div>





{{-- WAKTU --}}

<div class="grid grid-cols-[20px_1fr] sm:grid-cols-[24px_160px_1fr] gap-x-2 sm:gap-x-3 gap-y-0.5 sm:gap-y-0 items-center">


<img
src="{{ asset('assets/icons/waktu.png') }}"
class="w-4 h-4 sm:w-6 sm:h-6"
>


<div class="sm:contents">

<p class="text-gray-500 text-xs sm:text-base">

Rentang Waktu

</p>


<p class="font-semibold text-sm sm:text-base break-words">


{{ $reservation->waktu_mulai }}

-

{{ $reservation->waktu_selesai }}


</p>

</div>


</div>





{{-- TUJUAN --}}

<div class="grid grid-cols-[20px_1fr] sm:grid-cols-[24px_160px_1fr] gap-x-2 sm:gap-x-3 gap-y-0.5 sm:gap-y-0 items-start">


<img
src="{{ asset('assets/icons/tujuan.png') }}"
class="w-4 h-4 sm:w-6 sm:h-6 mt-0.5 sm:mt-1"
>


<div class="sm:contents">

<p class="text-gray-500 text-xs sm:text-base">

Tujuan Penggunaan

</p>


<p class="font-semibold text-sm sm:text-base break-words">

{{ $reservation->tujuan_penggunaan }}

</p>

</div>


</div>




</div>






{{-- SURAT DELEGASI --}}


<div class="mt-6 sm:mt-10">


<h3 class="font-bold text-[#47201B] text-sm sm:text-base mb-2 sm:mb-3">

Surat Delegasi

</h3>



<div class="
bg-[#47201B]
rounded-xl
p-3 sm:p-5
text-white
flex
justify-between
items-center
gap-3
">


<div class="min-w-0">

<p class="font-semibold text-sm sm:text-base truncate">

Surat Pendukung

</p>


<p class="text-xs sm:text-sm opacity-70">

PDF

</p>


</div>



<button class="
shrink-0
bg-[#E8B7AD]
text-[#47201B]
px-3 py-1.5 sm:px-4 sm:py-2
rounded-lg
text-sm
">


&gt;

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
p-4 sm:p-6
">


{{-- FOTO --}}


<div class="
h-28 sm:h-40
bg-[#E1D3C4]
rounded-xl
flex
items-center
justify-center
">


<p class="text-[#996561] text-xs sm:text-base">

Foto Fasilitas

</p>


</div>




<h2 class="
text-base sm:text-xl
font-bold
text-[#47201B]
mt-3 sm:mt-5
break-words
">


{{ $reservation->facility->nama_fasilitas }}


</h2>




<div class="mt-3 sm:mt-5 space-y-2.5 sm:space-y-4">


<div class="flex items-center gap-2.5 sm:gap-3">


<img
src="{{ asset('assets/icons/lokasi.png') }}"
class="w-4 h-4 sm:w-5 sm:h-5 shrink-0"
>


<p class="text-sm sm:text-base break-words">

{{ $reservation->facility->lokasi }}

</p>


</div>




<div class="flex items-center gap-2.5 sm:gap-3">


<img
src="{{ asset('assets/icons/kapasitas.png') }}"
class="w-4 h-4 sm:w-5 sm:h-5 shrink-0"
>


<p class="text-sm sm:text-base">

{{ $reservation->facility->kapasitas }}

Orang

</p>


</div>



<span class="
bg-[#47201B]
text-white
px-3 py-1.5 sm:px-4 sm:py-2
rounded-lg
text-xs sm:text-sm
inline-block
">


{{ $reservation->facility->tipe }}


</span>



</div>



{{-- AKSI PETUGAS --}}

@if($reservation->status == 'menunggu')

<div class="flex gap-2.5 sm:gap-3 mt-6 sm:mt-8">


<form
method="POST"
action="{{ route(
'petugas.reservations.approve',
$reservation->id
) }}"
class="flex-1"
>

@csrf
@method('PATCH')

<button
class="
w-full
bg-green-600
text-white
py-2.5 sm:py-3
text-sm sm:text-base
rounded-xl
font-bold
"
>

Setujui

</button>

</form>



<form
method="POST"
action="{{ route(
'petugas.reservations.reject',
$reservation->id
) }}"
class="flex-1"
>

@csrf
@method('PATCH')

<button
class="
w-full
bg-red-100
text-red-700
py-2.5 sm:py-3
text-sm sm:text-base
rounded-xl
font-bold
"
>

Tolak

</button>

</form>


</div>


@elseif($reservation->status == 'disetujui')


<button

onclick="openCancelModal()"

class="
w-full
bg-[#950704]
text-white
py-3 sm:py-4
text-sm sm:text-base
rounded-xl
font-bold
flex
items-center
justify-center
gap-2
hover:opacity-90
transition
mt-6 sm:mt-8
"

>

<img
src="{{ asset('assets/icons/batalkan.png') }}"
class="w-4 h-4 sm:w-5 sm:h-5"
>

Batalkan Mendesak

</button>


@endif

{{-- MODAL BATALKAN --}}

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
        max-w-[340px] sm:max-w-[390px]
        rounded-3xl
        p-5 sm:p-8
        text-center
        relative
    ">

        <button

            onclick="closeCancelModal()"

            class="
            absolute
            top-2 sm:top-3
            right-4 sm:right-5
            text-2xl sm:text-3xl
            text-black
            "

        >

            &times;

        </button>





        <h2 class="
            text-lg sm:text-2xl
            font-bold
            text-black
            leading-tight
            mb-5 sm:mb-8
        ">


            Apakah Anda yakin<br>

            ingin membatalkan<br>

            mendesak?


        </h2>


        <div class="flex flex-row gap-2 sm:gap-3">


            <button

                type="button"

                onclick="closeCancelModal()"

                class="
                flex-1
                py-2.5 sm:py-3
                text-sm sm:text-base
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

                onclick="cancelReservation()"

                class="
                flex-1
                py-2.5 sm:py-3
                text-sm sm:text-base
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



</div>


</div>


</div>


</div>


<script>

function openCancelModal(){

    let modal = document.getElementById(
        'cancelModal'
    );


    modal.classList.remove('hidden');

    modal.classList.add('flex');

}



function closeCancelModal(){

    let modal = document.getElementById(
        'cancelModal'
    );


    modal.classList.add('hidden');

    modal.classList.remove('flex');

}


</script>

</x-app-layout>