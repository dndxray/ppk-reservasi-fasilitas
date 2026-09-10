<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;

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
        'diselesaikan_pada'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}