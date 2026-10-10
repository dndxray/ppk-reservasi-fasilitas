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
    // Filter hanya dari URL. Tidak ada fallback cookie.
        $tipe   = $request->input('tipe');
        $lokasi = $request->input('lokasi');

        //  Filter ketersediaan (tanggal + jam) ----
        [$hanyaAktif, $idTerpakai, $errorWaktu] = $this->filterKetersediaan($request);

        $terapkanFilter = function ($q) use ($request, $tipe, $lokasi, $hanyaAktif, $idTerpakai) {
            if ($tipe) {
                $q->where('tipe', $tipe);
            }
            if ($lokasi) {
                $q->where('lokasi', 'like', '%' . $lokasi . '%');
            }
            if ($request->filled('kapasitas_minimal')) {
                $q->where('kapasitas', '>=', (int) $request->kapasitas_minimal);
            }
            if ($request->filled('kuantitas_minimal')) {
                $q->where('kuantitas', '>=', (int) $request->kuantitas_minimal);
            }
            if ($request->filled('cari')) {
                $kata = $request->cari;
                $q->where(function ($w) use ($kata) {
                    $w->where('nama_fasilitas', 'like', '%' . $kata . '%')
                    ->orWhere('lokasi', 'like', '%' . $kata . '%');
                });
            }
            if ($hanyaAktif) {
                $q->where('status', 'aktif');
            }
            if (!empty($idTerpakai)) {
                $q->whereNotIn('id', $idTerpakai);
            }
        };

        $daftarFasilitas = Facility::query()
        ->tap($terapkanFilter)
        ->orderBy('nama_fasilitas')
        ->paginate(12)
        ->withQueryString();

        // recently viewed...
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

        $daftarTipe   = Facility::select('tipe')->distinct()->pluck('tipe');
        $daftarLokasi = Facility::select('lokasi')->distinct()->pluck('lokasi');

        $riwayatReservasi = auth()->check()
        ? Reservation::with('facility')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get()
        : collect();

        return view('fasilitas.index', compact(
            'daftarFasilitas',
            'daftarTipe',
            'daftarLokasi',
            'tipe',
            'lokasi',
            'riwayatReservasi',
            'fasilitasPopuler',
            'errorWaktu'
        ));
    }

    /**
     * Validasi input tanggal + jam, lalu cari fasilitas yang sudah terpakai.
     * Return: [hanyaAktif (bool), idTerpakai (array), errorWaktu (string|null)]
     */
    private function filterKetersediaan(Request $request): array
    {
        $tanggalInput = $request->input('tanggal');
        $mulaiInput = $request->input('waktu_mulai');
        $selesaiInput = $request->input('waktu_selesai');

        // Tidak ada input waktu sama sekali -> tidak ada filter ketersediaan
        if (!$request->filled('tanggal') && !$request->filled('waktu_mulai') && !$request->filled('waktu_selesai')) {
            return [false, [], null];
        }

        if (!$request->filled('tanggal')) {
            return [false, [], 'Pilih tanggal reservasi dulu.'];
        }

        try {
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggalInput)->startOfDay();
        } catch (\Exception $e) {
            return [false, [], 'Format tanggal tidak valid.'];
        }

        if ($tanggal->lt(now()->startOfDay())) {
            return [false, [], 'Tanggal reservasi tidak boleh sudah lewat.'];
        }

        // Hanya tanggal diisi (jam kosong) -> cukup saring fasilitas aktif
        if (!$request->filled('waktu_mulai') && !$request->filled('waktu_selesai')) {
            return [true, [], null];
        }

        if (!$request->filled('waktu_mulai') || !$request->filled('waktu_selesai')) {
            return [false, [], 'Isi jam mulai dan jam selesai.'];
        }

        $slotValid = $this->jamOperasional();
        if (!in_array($mulaiInput, $slotValid, true) || !in_array($selesaiInput, $slotValid, true)) {
            return [false, [], 'Jam harus dalam jam operasional 07:00 - 20:00 (kelipatan 30 menit).'];
        }

        if ($mulaiInput >= $selesaiInput) {
            return [false, [], 'Jam mulai harus lebih kecil dari jam selesai.'];
        }

        if ($tanggal->isToday() && $mulaiInput <= now()->format('H:i')) {
            return [false, [], 'Jam mulai sudah lewat. Pilih jam yang masih tersedia hari ini.'];
        }

        $idTerpakai = [];
        if (Schema::hasTable('reservations')) {
            $idTerpakai = DB::table('reservations')
                ->where('tanggal', $tanggal->toDateString())
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->where('waktu_mulai', '<', $selesaiInput . ':00')
                ->where('waktu_selesai', '>', $mulaiInput . ':00')
                ->pluck('facility_id')
                ->unique()
                ->values()
                ->all();
        }

        return [true, $idTerpakai, null];
    }

    // Slot jam operasional 07:00 - 20:00, kelipatan 30 menit
    private function jamOperasional(): array
    {
        $slot = [];
        for ($menit = 7 * 60; $menit <= 20 * 60; $menit += 30) {
            $slot[] = sprintf('%02d:%02d', intdiv($menit, 60), $menit % 60);
        }

        return $slot;
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