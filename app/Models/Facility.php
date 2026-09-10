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
     * Satu fasilitas bisa memiliki banyak reservasi
     */
    public function reservations()
    {
        return $this->hasMany(
            Reservation::class
        );
    }
}