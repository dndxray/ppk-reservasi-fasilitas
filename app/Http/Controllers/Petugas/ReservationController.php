<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;


class ReservationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Antrian Reservasi
    |--------------------------------------------------------------------------
    */
    public function queue()
    {

        $reservations = Reservation::with([
        'user',
        'facility'])
        ->whereIn(
            'status',
            [
            'menunggu',
            'disetujui'
            ]
        )
        ->latest()
        ->get();

        return view(
            'petugas.reservations.queue',
            compact('reservations')
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Setujui Reservasi
    |--------------------------------------------------------------------------
    */
    public function approve(
        Reservation $reservation
    )
    {
        $bentrok = Reservation::where(
            'facility_id',
            $reservation->facility_id
        )
        ->where(
            'tanggal',
            $reservation->tanggal
        )
        ->where(
            'status',
            'disetujui'
        )
        ->where(function($query) use ($reservation){
            $query
            ->where(
                'waktu_mulai',
                '<',
                $reservation->waktu_selesai
            )
            ->where(
                'waktu_selesai',
                '>',
                $reservation->waktu_mulai
            );
        })
        ->exists();

        if($bentrok)
        {
            return back()->withErrors(
                'Reservasi bentrok dengan jadwal lain.'
            );
        }

        $reservation->update([
            'status'=>'disetujui',
            'diproses_oleh'=>auth()->id()
        ]);

        return back()->with(
            'success',
            'Reservasi berhasil disetujui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Tolak Reservasi
    |--------------------------------------------------------------------------
    */
    public function reject(
        Reservation $reservation
    )
    {
        $reservation->update([
            'status'=>'ditolak',
            'diproses_oleh'=>auth()->id()
        ]);
        return back()->with(
            'success',
            'Reservasi berhasil ditolak.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Batalkan Reservasi Mendesak
    |--------------------------------------------------------------------------
    */
    public function cancel(
        Request $request,
        Reservation $reservation
    )
    {
        $request->validate([
            'alasan_pembatalan'
            =>
            'required|string|max:255'
        ]);

        if($reservation->status !== 'disetujui')
        {
            return back()->withErrors(
                'Hanya reservasi yang sudah disetujui yang dapat dibatalkan.'
            );
        }

        $reservation->update([

            'status'=>'dibatalkan',

            'alasan_pembatalan'
            =>
            $request->alasan_pembatalan,

            'diproses_oleh'
            =>
            auth()->id()
        ]);

        return back()->with(
            'success',
            'Reservasi berhasil dibatalkan oleh petugas.'
        );
    }
    public function show(Reservation $reservation)
{
    $reservation->load([
        'user',
        'facility'
    ]);

    return view(
        'petugas.reservations.show',
        compact('reservation')
    );
}
}