<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;  

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
        return $this->reservations()
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->whereDate(\DB::raw('COALESCE(tanggal_kembali, tanggal)'), '>=', now()->toDateString());
    }
}