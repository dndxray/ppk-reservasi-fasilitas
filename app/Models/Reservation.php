<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'facility_id',
        'jumlah',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'tujuan_penggunaan',
        'status',
        'alasan_pembatalan',
        'diproses_oleh',
    ];

    public function isAlat(): bool
    {
        return $this->facility && $this->facility->tipe === 'alat';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}