<x-app-layout>


<div class="py-6">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        <h2 class="text-xl font-semibold mb-5">
            Ajukan Reservasi Fasilitas
        </h2>

        {{-- Pesan Error --}}
        @if ($errors->any())
            <div class="bg-red-100 p-3 mb-4">
                @foreach ($errors->all() as $error)
                    <p>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="bg-green-100 p-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form 
            method="POST"
            action="{{ route('reservations.store') }}"
            id="reservationForm"
        >
            @csrf

            <div class="mb-4">
                <label>
                    Pilih Fasilitas
                </label>


                <select 
                    name="facility_id"
                    class="border rounded w-full"
                    required
                >
                    <option value="">
                        -- Pilih Fasilitas --
                    </option>

                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">
                            {{ $facility->nama_fasilitas }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">
                <label>
                    Tanggal
                </label>
                <input
                    type="date"
                    name="tanggal"
                    class="border rounded w-full"
                    required
                >
            </div>


            <div class="mb-4">
                <label>
                    Waktu Mulai
                </label>

                <input
                    type="time"
                    name="waktu_mulai"
                    class="border rounded w-full"
                    step="1800"
                    required
                >

            </div>

            <div class="mb-4">

                <label>
                    Waktu Selesai
                </label>

                <input
                    type="time"
                    name="waktu_selesai"
                    class="border rounded w-full"
                    step="1800"
                    required
                >

            </div>

            <div class="mb-4">

                <label>
                    Tujuan Penggunaan
                </label>

                <textarea
                    name="tujuan_penggunaan"
                    class="border rounded w-full"
                    required
                ></textarea>

            </div>


            <button
                type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded"
            >

                Ajukan Reservasi

            </button>

        </form>

    </div>
</div>

<script>

document
.getElementById('reservationForm')
.addEventListener('submit', function(e){

    let mulai =
    document.querySelector(
        '[name="waktu_mulai"]'
    ).value;

    let selesai =
    document.querySelector(
        '[name="waktu_selesai"]'
    ).value;

    if(mulai >= selesai){

        alert(
            "Waktu selesai harus setelah waktu mulai"
        );


        e.preventDefault();

    }


});

</script>

</x-app-layout>