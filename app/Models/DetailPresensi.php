<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DetailPresensi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'presensi_id',
        'siswa_id',
        'status',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'presensi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
