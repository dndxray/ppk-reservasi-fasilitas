<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Facility::query();
        //tipe fasilitas
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        // kapasitas min
        if ($request->filled('kapasitas_minimal')) {
            $query->where('kapasitas', '>=', $request->kapasitas_minimal);
        }

        // nama fasilitas
        if ($request->filled('cari')) {
            $query->where('nama_fasilitas', 'like', '%' . $request->cari . '%');
        }

        $daftarFasilitas = $query->orderBy('nama_fasilitas')->paginate(12)->withQueryString();

        $daftarTipe = Facility::select('tipe')->distinct()->pluck('tipe');
        $daftarLokasi = Facility::select('lokasi')->distinct()->pluck('lokasi');

        return view('fasilitas.index', compact('daftarFasilitas', 'daftarTipe', 'daftarLokasi'));
    }
 // detai fasilitas
    public function show(Facility $facility, Request $request): View
    {
        $tanggal = now()->toDateString();

        if ($request->filled('tanggal') && strtotime($request->tanggal)) {
            $tanggal = date('Y-m-d', strtotime($request->tanggal));
        }

        return view('fasilitas.show', [
            'fasilitas' => $facility,
            'tanggal' => $tanggal,
            'daftarSlot' => $this->daftarSlot($facility, $tanggal),
        ]);
    }

    private function daftarSlot(Facility $facility, string $tanggal): array
    {
        $reservasi = collect();

        if (Schema::hasTable('reservations')) {
            $reservasi = DB::table('reservations')
                ->where('facility_id', $facility->id)
                ->where('tanggal', $tanggal)
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->get(['waktu_mulai', 'waktu_selesai']);
        }

        $daftarSlot = [];
        $jamBuka = Carbon::parse($tanggal . ' 07:00:00');

        for ($i = 0; $i < 26; $i++) {
            $mulai = $jamBuka->copy()->addMinutes($i * 30);
            $selesai = $mulai->copy()->addMinutes(30);

            $terisi = $reservasi->contains(function ($item) use ($mulai, $selesai) {
                return $item->waktu_mulai < $selesai->format('H:i:s')
                    && $item->waktu_selesai > $mulai->format('H:i:s');
            });

            $daftarSlot[] = [
                'mulai' => $mulai->format('H:i'),
                'selesai' => $selesai->format('H:i'),
                'terisi' => $terisi,
            ];
        }

        return $daftarSlot;
    }
}