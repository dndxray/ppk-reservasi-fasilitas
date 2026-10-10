<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{

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
        $facility = Facility::where('id', $request->facility_id)
            ->where('status', 'aktif')
            ->first();

        if (!$facility) {
            return back()->withErrors('Fasilitas yang dipilih tidak valid atau sedang tidak aktif.')->withInput();
        }

        $isAlat = ($facility->tipe === 'alat');
        $maxStok = $facility->kuantitas ?? 1;

        $rules = [
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
        ];

        $messages = [
            'facility_id.required' => 'Fasilitas wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak valid atau sedang tidak aktif.',
            'tanggal.required' => 'Tanggal reservasi wajib diisi.',
            'tanggal.date' => 'Format tanggal reservasi tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'tujuan_penggunaan.required' => 'Tujuan penggunaan wajib diisi.',
            'tujuan_penggunaan.min' => 'Tujuan penggunaan minimal 5 karakter.'
        ];

        if ($isAlat) {
            $rules['jumlah'] = ['required', 'integer', 'min:1', 'max:' . $maxStok];
            $messages['jumlah.required'] = 'Jumlah alat wajib diisi.';
            $messages['jumlah.integer'] = 'Jumlah alat harus berupa angka.';
            $messages['jumlah.min'] = 'Jumlah alat minimal 1 unit.';
            $messages['jumlah.max'] = 'Jumlah alat melebihi stok yang tersedia (' . $maxStok . ' unit).';
        }

        $request->validate($rules, $messages);

        $allowedSlots = [
            '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
            '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
            '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
            '19:00', '19:30', '20:00'
        ];

        $waktuMulai = substr($request->waktu_mulai, 0, 5);
        $waktuSelesai = substr($request->waktu_selesai, 0, 5);

        if (!in_array($waktuMulai, $allowedSlots) || !in_array($waktuSelesai, $allowedSlots)) {
            return back()->withErrors('Waktu harus menggunakan slot 30 menit pada jam operasional (07.00 - 20.00).')->withInput();
        }

        if ($waktuMulai < "07:00" || $waktuSelesai > "20:00") {
            return back()->withErrors('Reservasi hanya dapat dilakukan pukul 07.00 - 20.00.')->withInput();
        }

        if ($waktuSelesai <= $waktuMulai) {
            return back()->withErrors('Waktu selesai harus lebih besar dari waktu mulai.')->withInput();
        }

        $zonaWaktu = 'Asia/Jakarta';
        $waktuMulaiLengkap = Carbon::parse($request->tanggal . ' ' . $waktuMulai, $zonaWaktu);

        if ($waktuMulaiLengkap->lte(Carbon::now($zonaWaktu))) {
            return back()->withErrors('Waktu mulai sudah lewat. Silakan pilih jam berikutnya.')->withInput();
        }

        if ($isAlat) {
            $jumlahDiajukan = (int) $request->jumlah;

            $overlappingReservations = Reservation::where('facility_id', $facility->id)
                ->where('tanggal', $request->tanggal)
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->where(function($query) use ($waktuMulai, $waktuSelesai) {
                    $query->where('waktu_mulai', '<', $waktuSelesai)
                          ->where('waktu_selesai', '>', $waktuMulai);
                })
                ->get();

            $totalTerpakai = $overlappingReservations->sum(function ($r) {
                return $r->jumlah ?? 1;
            });

            if (($totalTerpakai + $jumlahDiajukan) > $maxStok) {
                $sisa = max(0, $maxStok - $totalTerpakai);
                return back()->withErrors("Stok alat tidak mencukupi pada jam tersebut. (Stok total: {$maxStok} unit, sisa tersedia: {$sisa} unit).")->withInput();
            }

            Reservation::create([
                'user_id' => Auth::id(),
                'facility_id' => $facility->id,
                'jumlah' => $jumlahDiajukan,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'tujuan_penggunaan' => $request->tujuan_penggunaan,
                'status' => 'menunggu'
            ]);

            return redirect()
                ->route('reservations.create')
                ->with('success', 'Permohonan peminjaman alat berhasil diajukan.');
        }

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
            ->paginate(10)
            ->withQueryString();



        return view(
            'reservations.history',
            compact('reservations')
        );

    }



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