<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'nama_fasilitas',
        'tipe',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'status',
    ];
}