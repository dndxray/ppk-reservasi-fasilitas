<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        // Baca filter dari cookie kalau user ga isi filter baru
        $tipe = $request->filled('tipe') ? $request->tipe : $request->cookie('filter_tipe');
        $lokasi = $request->filled('lokasi') ? $request->lokasi : $request->cookie('filter_lokasi');

        // Filter dipakai bersama oleh daftar utama dan "Paling Banyak Direservasi"
        $terapkanFilter = function ($q) use ($request, $tipe, $lokasi) {
            if ($tipe) {
                $q->where('tipe', $tipe);
            }

            if ($lokasi) {
                $q->where('lokasi', 'like', '%' . $lokasi . '%');
            }

            if ($request->filled('kapasitas_minimal')) {
                $q->where('kapasitas', '>=', $request->kapasitas_minimal);
            }

            if ($request->filled('cari')) {
                $q->where('nama_fasilitas', 'like', '%' . $request->cari . '%');
            }
        };

        // Daftar utama
        $daftarFasilitas = Facility::query()
            ->tap($terapkanFilter)
            ->orderBy('nama_fasilitas')
            ->paginate(12)
            ->withQueryString();

        // 3 fasilitas terakhir dilihat (jika belum ada, fallback 3 fasilitas aktif)
        $recentlyViewed = session('recently_viewed_facilities', []);
        if (!empty($recentlyViewed)) {
            $fasilitasPopuler = Facility::query()
                ->tap($terapkanFilter)
                ->whereIn('id', $recentlyViewed)
                ->where('status', 'aktif')
                ->get()
                ->sortBy(fn($item) => array_search($item->id, $recentlyViewed))
                ->take(3);
        } else {
            $fasilitasPopuler = Facility::query()
                ->tap($terapkanFilter)
                ->where('status', 'aktif')
                ->orderBy('nama_fasilitas')
                ->take(3)
                ->get();
        }

        $daftarTipe = Facility::select('tipe')->distinct()->pluck('tipe');
        $daftarLokasi = Facility::select('lokasi')->distinct()->pluck('lokasi');

        // 5 reservasi terakhir milik user yang login
        $riwayatReservasi = Reservation::with('facility')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $response = response()->view('fasilitas.index', compact(
            'daftarFasilitas',
            'daftarTipe',
            'daftarLokasi',
            'tipe',
            'lokasi',
            'riwayatReservasi',
            'fasilitasPopuler'
        ));

        // Simpan filter ke cookie (30 hari)
        if ($request->filled('tipe')) {
            $response->cookie('filter_tipe', $request->tipe, 60 * 24 * 30);
        }
        if ($request->filled('lokasi')) {
            $response->cookie('filter_lokasi', $request->lokasi, 60 * 24 * 30);
        }

        return $response;
    }

    public function show(Request $request, Facility $facility): View
    {
        // Simpan ID fasilitas ke session "recently_viewed_facilities" (maksimal 6 fasilitas)
        $recentlyViewed = session()->get('recently_viewed_facilities', []);
        $recentlyViewed = array_values(array_diff($recentlyViewed, [$facility->id]));
        array_unshift($recentlyViewed, $facility->id);
        $recentlyViewed = array_slice($recentlyViewed, 0, 6);
        session()->put('recently_viewed_facilities', $recentlyViewed);

        $tanggal = $this->parseTanggal($request);

        $daftarSlot = $this->daftarSlot($facility, $tanggal);

        // Alias supaya view yang memakai $fasilitas maupun $facility sama-sama jalan
        $fasilitas = $facility;

        return view('fasilitas.show', compact('facility', 'fasilitas', 'tanggal', 'daftarSlot'));
    }

    // Dipakai route fasilitas.slots (mis. untuk memuat jadwal per tanggal via JavaScript)
    public function slots(Request $request, Facility $facility)
    {
        $tanggal = $this->parseTanggal($request);

        return response()->json([
            'tanggal' => $tanggal,
            'slots'   => $this->daftarSlot($facility, $tanggal),
        ]);
    }

    private function parseTanggal(Request $request): string
    {
        try {
            return Carbon::parse($request->input('tanggal', now()->toDateString()))->toDateString();
        } catch (\Exception $e) {
            return now()->toDateString();
        }
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