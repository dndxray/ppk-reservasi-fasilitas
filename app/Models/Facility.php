<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_fasilitas', 'tipe', 'lokasi', 'kapasitas', 'deskripsi', 'status'])]
class Facility extends Model
{
    use HasFactory;
    public function sedangAktif(): bool
    {
        return $this->status === 'aktif';
    }
    public function dalamPerbaikan(): bool
    {
        return $this->status === 'dalam_perbaikan';
    }
}