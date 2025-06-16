<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_mapel',
        'deskripsi',
        'bobot_id',
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }

    public function bobotPenilaian()
    {
        return $this->belongsTo(BobotPenilaian::class, 'bobot_id');
    }

}
