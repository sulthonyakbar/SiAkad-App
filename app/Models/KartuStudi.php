<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStudi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'siswa_id',
        'nilai_id',
        'kelas_id',
        'presensi_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function nilai()
    {
        return $this->belongsTo(Nilai::class, 'nilai_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'presensi_id');
    }
}
