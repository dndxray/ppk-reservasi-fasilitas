<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | US 3 - Menampilkan Form Reservasi
    |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $facilities = Facility::where('status', 'aktif')
            ->orderBy('nama_fasilitas')
            ->get();

        $selectedFacilityId = null;
        if ($request->filled('facility_id')) {
            $facility = Facility::where('id', $request->facility_id)
                ->where('status', 'aktif')
                ->first();
            if ($facility) {
                $selectedFacilityId = $facility->id;
            }
        }

        return view('reservations.create', compact('facilities', 'selectedFacilityId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('facilities', 'id')->where(function ($query) {
                    $query->where('status', 'aktif');
                })
            ],
            'tanggal' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'waktu_mulai' => [
                'required',
            ],
            'waktu_selesai' => [
                'required',
            ],
            'tujuan_penggunaan' => [
                'required',
                'min:5'
            ]
        ], [
            'facility_id.required' => 'Fasilitas wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak valid atau sedang tidak aktif.',
            'tanggal.required' => 'Tanggal reservasi wajib diisi.',
            'tanggal.date' => 'Format tanggal reservasi tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'tujuan_penggunaan.required' => 'Tujuan penggunaan wajib diisi.',
            'tujuan_penggunaan.min' => 'Tujuan penggunaan minimal 5 karakter.'
        ]);

        $allowedSlots = [
            '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
            '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
            '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
            '19:00', '19:30', '20:00'
        ];

        $waktuMulai = substr($request->waktu_mulai, 0, 5);
        $waktuSelesai = substr($request->waktu_selesai, 0, 5);

        /*
        |--------------------------------------------------------------------------
        | Validasi slot 30 menit & Jam Operasional (07:00 - 20:00)
        |--------------------------------------------------------------------------
        */
        if (!in_array($waktuMulai, $allowedSlots) || !in_array($waktuSelesai, $allowedSlots)) {
            return back()->withErrors('Waktu harus menggunakan slot 30 menit pada jam operasional (07.00 - 20.00).')->withInput();
        }

        if ($waktuMulai < "07:00" || $waktuSelesai > "20:00") {
            return back()->withErrors('Reservasi hanya dapat dilakukan pukul 07.00 - 20.00.')->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi waktu selesai harus lebih besar dari waktu mulai
        |--------------------------------------------------------------------------
        */
        if ($waktuSelesai <= $waktuMulai) {
            return back()->withErrors('Waktu selesai harus lebih besar dari waktu mulai.')->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Cek bentrok reservasi
        |--------------------------------------------------------------------------
        */
        $bentrok = Reservation::where('facility_id', $request->facility_id)
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->where(function($query) use ($waktuMulai, $waktuSelesai){
                $query->where('waktu_mulai', '<', $waktuSelesai)
                      ->where('waktu_selesai', '>', $waktuMulai);
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors('Fasilitas sudah digunakan pada waktu tersebut.')->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Reservasi
        |--------------------------------------------------------------------------
        */
        Reservation::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'tujuan_penggunaan' => $request->tujuan_penggunaan,
            'status' => 'menunggu'
        ]);

        return redirect()
            ->route('reservations.create')
            ->with('success', 'Reservasi berhasil diajukan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Riwayat Reservasi Pengguna
    |--------------------------------------------------------------------------
    */

    public function history(Request $request)
{

        $query = Reservation::where(
            'user_id',
            auth()->id()
        )
        ->with('facility');



        // LOGIKA PENCARIAN
        if($request->search){

            $search = $request->search;


            $query->whereHas(
                'facility',
                function($q) use ($search){

                    $q->where(
                        'nama_fasilitas',
                        'LIKE',
                        "%$search%"
                    )
                    ->orWhere(
                        'lokasi',
                        'LIKE',
                        "%$search%"
                    );

                }
            );


        }

        // FILTER STATUS
        if($request->status){

            $query->where(
                'status',
                $request->status
            );

        }


        $reservations = $query
            ->latest()
            ->get();



        return view(
            'reservations.history',
            compact('reservations')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Pengguna Membatalkan Reservasi
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Reservation $reservation
    )
    {
        if(
            $reservation->user_id != auth()->id()
        ){
            abort(403);
        }
        if(
            $reservation->status != 'menunggu'
            &&
            $reservation->status != 'disetujui'
        ){

            return back()->withErrors(
                'Reservasi tidak dapat dibatalkan.'
            );
        }

        $waktuMulai = Carbon::parse(

            $reservation->tanggal
            .' '
            .$reservation->waktu_mulai

        );

        $batasBatal = $waktuMulai->subHour();

        if(now()->greaterThan($batasBatal))
        {

            return back()->withErrors(
                'Reservasi hanya dapat dibatalkan maksimal 1 jam sebelum penggunaan.'
            );
        }

        $reservation->update([

            'status'
                =>
                'dibatalkan'
        ]);

        return back()->with(
            'success',
            'Reservasi berhasil dibatalkan.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Detail Reservasi
    |--------------------------------------------------------------------------
    */
    public function show(
        Reservation $reservation
    )
    {
        if(
            $reservation->user_id != auth()->id()
        ){
            abort(403);
        }
        $reservation->load([

            'facility',
            'user'

        ]);
        return view(
            'reservations.show',
            compact('reservation')
        );
    }
}