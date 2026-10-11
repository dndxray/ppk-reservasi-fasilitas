<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Support\Facades\Schema; 

class Facility extends Model
{
    use HasFactory; use SoftDeletes; 

    protected $fillable = [
        'nama_fasilitas',
        'tipe',
        'lokasi',
        'kapasitas',
        'kuantitas',
        'deskripsi',
        'status',
        'foto',
    ];

    protected $guarded = [];

    /**
     * Satu fasilitas memiliki banyak reservasi
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Satu fasilitas memiliki banyak laporan kerusakan
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Laporan kerusakan yang sedang berlangsung (menunggu / diproses)
     */
    public function laporanKerusakanAktif()
    {
        return $this->reports()->whereIn('status', ['menunggu', 'baru', 'diproses'])->get();
    }

    /**
     * Mengecek fasilitas aktif
     */
    public function sedangAktif(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Mengecek fasilitas dalam perbaikan
     */
    public function dalamPerbaikan(): bool
    {
        return $this->status === 'dalam_perbaikan';
    }

    public function tipeLabel()
    {
        return ucfirst($this->tipe);
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/login-bg.jpg');
    }


    public function reservasiAktif()
    {
        $query = $this->reservations()
            ->whereIn('status', ['menunggu', 'disetujui']);

        // Reservasi alat punya tanggal kembali; ruangan hanya punya tanggal
        if (Schema::hasColumn('reservations', 'tanggal_kembali')) {
            return $query->whereRaw(
                'COALESCE(reservations.tanggal_kembali, reservations.tanggal) >= ?',
                [now()->toDateString()]
            );
        }

        return $query->whereDate('tanggal', '>=', now()->toDateString());
    }
}