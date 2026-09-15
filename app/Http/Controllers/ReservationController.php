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
    public function create()
    {

        $facilities = Facility::where(
            'status',
            'aktif'
        )->get();


        return view(
            'reservations.create',
            compact('facilities')
        );

    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id'
                => 'required|exists:facilities,id',
            'tanggal'
                => [
                    'required',
                    'date',
                    'after_or_equal:today'],
            'waktu_mulai'
                => 'required',

            'waktu_selesai'
                => 'required',

            'tujuan_penggunaan'
                => 'required|min:5'
        ],[
            'tanggal.after_or_equal'
                =>
                'Tanggal reservasi tidak valid.',
            'tanggal.required'
                =>
                'Tanggal reservasi wajib diisi.'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi jam operasional
        |--------------------------------------------------------------------------
        */
        if(
            $request->waktu_mulai < "07:00"
            ||
            $request->waktu_selesai > "20:00"
        ){
            return back()->withErrors(
                'Reservasi hanya dapat dilakukan pukul 07.00 - 20.00'
            );
        }
        /*
        |--------------------------------------------------------------------------
        | Validasi slot minimal 30 menit
        |--------------------------------------------------------------------------
        */

        $mulai = strtotime(
            $request->waktu_mulai
        );

        $selesai = strtotime(
            $request->waktu_selesai
        );


        if( date('i',$mulai) % 30 != 0
            ||
            date('i',$selesai) % 30 != 0
        ){
            return back()->withErrors(
                'Waktu harus menggunakan slot 30 menit'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cek bentrok reservasi
        |--------------------------------------------------------------------------
        */

        $bentrok = Reservation::where(
            'facility_id',
            $request->facility_id
        )

        ->where(
            'tanggal',
            $request->tanggal
        )

        ->whereIn(
            'status',
            [
                'menunggu',
                'disetujui'
            ]
        )

        ->where(function($query) use ($request){

            $query

            ->where(
                'waktu_mulai',
                '<',
                $request->waktu_selesai
            )

            ->where(
                'waktu_selesai',
                '>',
                $request->waktu_mulai
            );

        })
        ->exists();

        if($bentrok){

            return back()->withErrors(
                'Fasilitas sudah digunakan pada waktu tersebut.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Reservasi
        |--------------------------------------------------------------------------
        */
        Reservation::create([

            'user_id'
                => Auth::id(),

            'facility_id'
                => $request->facility_id,

            'tanggal'
                => $request->tanggal,

            'waktu_mulai'
                => $request->waktu_mulai,

            'waktu_selesai'
                => $request->waktu_selesai,

            'tujuan_penggunaan'
                => $request->tujuan_penggunaan,

            'status'
                => 'menunggu'

        ]);

        return redirect()

        ->route('reservations.create')

        ->with(
            'success',
            'Reservasi berhasil diajukan.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Riwayat Reservasi Pengguna
    |--------------------------------------------------------------------------
    */

    public function history()
    {

        $reservations = Reservation::where(
            'user_id',
            auth()->id()
        )

        ->with('facility')

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