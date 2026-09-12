<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tipe',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'status',
    ];


    /**
     * Satu fasilitas memiliki banyak reservasi
     */
    public function reservations()
    {
        return $this->hasMany(
            Reservation::class
        );
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
}