<?php

namespace App\Models;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'facility_id',
        'kategori',
        'deskripsi',
        'foto',
        'status',
        'catatan_resolusi',
        'diproses_oleh',
        'diselesaikan_pada',
    ];

    protected $casts = [
        'diselesaikan_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}