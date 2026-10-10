<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            // Kartu ringkasan
            // CATATAN: sesuaikan nilai kolom 'role' dengan yang dipakai di aplikasi Anda
            'totalPengguna'     => User::where('role', 'pengguna')->count(),
            'totalPetugas'      => User::where('role', 'petugas')->count(),
            'totalFasilitas'    => Facility::count(),
            'reservasiBulanIni' => Reservation::whereYear('tanggal', now()->year)
                                        ->whereMonth('tanggal', now()->month)
                                        ->count(),

            // Kondisi fasilitas: tersedia (aktif) & nonaktif
            'fasilitasStatus' => [
                'aktif'    => Facility::where('status', 'aktif')->count(),
                'nonaktif' => Facility::where('status', 'nonaktif')->count(),
            ],

            // Fasilitas terpopuler (5 teratas berdasarkan jumlah reservasi)
            'fasilitasTerpopuler' => Facility::withCount('reservations')
                                        ->having('reservations_count', '>', 0)
                                        ->orderByDesc('reservations_count')
                                        ->take(5)
                                        ->get(),

            // Pendaftar terbaru
            'penggunaTerbaru' => User::where('role', 'pengguna')->latest()->take(5)->get(),
        ]);
    }
}