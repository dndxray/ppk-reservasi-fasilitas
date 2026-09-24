<x-app-layout>

<div class="min-h-screen bg-white py-8">


    <div class="max-w-5xl mx-auto px-8">


        {{-- HEADER --}}
        <div class="flex items-center gap-5 mb-6">

            <a href="{{ url()->previous() }}"
            class="flex items-center">


            <img src="{{ asset('assets/icons/backward.png') }}"
            class="w-8 h-8"
            >


</a>


            <h1 class="text-2xl font-bold text-black">
                Formulir Pengajuan Reservasi
            </h1>

        </div>





        @if(session('success'))

        <div id="successToast"
        class="
        fixed
        top-5
        right-5
        z-50
        bg-[#22C55E]
        text-white
        px-6
        py-4
        rounded-xl
        shadow-lg
        font-semibold
        transition-all
        duration-500
        "
        >

            {{ session('success') }}

        </div>


        <script>

        setTimeout(() => {

            const toast =
            document.getElementById('successToast');


            if(toast){

                toast.style.opacity = "0";

                toast.style.transform =
                "translateY(-20px)";


                setTimeout(()=>{

                    toast.remove();

                },500);

            }

        },3000);


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
                    required
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
                        <option value="{{ $facility->id }}">
                            {{ $facility->nama_fasilitas }}
                        </option>
                    @endforeach

                </select>
            </div>

        {{-- LOKASI FASILITAS --}}
        <div   div class="mb-6">
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

                required

                placeholder="Masukkan lokasi fasilitas"

                class="
                w-full
                h-12
                rounded-lg
                bg-[#F5F0ED]
                border
                border-[#D5C6BD]
                px-4
                text-black
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


                <div class="relative">

                    <input
                        type="time"
                        name="waktu_mulai"
                        step="1800"
                        required
                        class="
                        w-full
                        h-12
                        rounded-lg
                        bg-[#F5F0ED]
                        border
                        border-[#D5C6BD]
                        px-4
                        pr-12
                        text-black
                        "
                    >
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



                    <input

                        type="time"

                        name="waktu_selesai"

                        step="1800"

                        required

                        class="
                        w-full
                        h-12
                        rounded-lg
                        bg-[#F5F0ED]
                        border
                        border-[#D5C6BD]
                        px-4
                        text-black
                        "

                    >


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

                ></textarea>



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


function openCalendar(){

    const modal =
    document.getElementById('calendarModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    renderCalendar();

}



function closeCalendar(){

    const modal =
    document.getElementById('calendarModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

}



function renderCalendar(){

    let year =
    currentDate.getFullYear();


    let month =
    currentDate.getMonth();


    document.getElementById('monthYear')
    .innerHTML =
    currentDate.toLocaleDateString(
        'id-ID',
        {
            month:'long',
            year:'numeric'
        }
    );


    let first =
    new Date(year,month,1)
    .getDay();


    let total =
    new Date(year,month+1,0)
    .getDate();



    let html='';


    let start =
    first === 0 ? 6 : first-1;


    for(let i=0;i<start;i++){

        html += `<div></div>`;

    }



    for(let day=1;day<=total;day++){


        let isSelected = 
        selectedDate &&
        selectedDate.getDate() === day &&
        selectedDate.getMonth() === month &&
        selectedDate.getFullYear() === year;



html += `

<button

onclick="chooseDate(${day})"

class="
h-10
rounded-full
${isSelected 
? 'bg-[#BE433E] text-white' 
: 'text-black hover:bg-[#F5F0ED]'
}
"

>

${day}

</button>

`;
    }


    document
    .getElementById('calendarDays')
    .innerHTML = html;


}




function chooseDate(day){

    selectedDate =
    new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        day
    );


    renderCalendar();

}



function selectDate(){

    if(!selectedDate)
        return;


    let y =
    selectedDate.getFullYear();


    let m =
    String(selectedDate.getMonth()+1)
    .padStart(2,'0');


    let d =
    String(selectedDate.getDate())
    .padStart(2,'0');



    document
    .getElementById('tanggal')
    .value =
    `${y}-${m}-${d}`;



    document
    .getElementById('tanggal_display')
    .value =
    selectedDate.toLocaleDateString(
        'id-ID',
        {
            day:'numeric',
            month:'long',
            year:'numeric'
        }
    );



    closeCalendar();

}


function changeMonth(value){

    currentDate.setMonth(
        currentDate.getMonth()+value
    );

    renderCalendar();

}


function openConfirmModal(){


    closeCalendar();


    const modal =
    document.getElementById('confirmModal');


    modal.classList.remove('hidden');

    modal.classList.add('flex');


}

function closeConfirmModal(){

    const modal =
    document.getElementById('confirmModal');


    modal.classList.add('hidden');

    modal.classList.remove('flex');

}


function submitReservation(){

    document
    .getElementById('reservationForm')
    .submit();

}

</script>



</x-app-layout>