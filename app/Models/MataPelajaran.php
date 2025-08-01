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
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }

    public function bobotPenilaian()
    {
        return $this->hasOne(BobotPenilaian::class, 'mapel_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mapels', 'mapel_id', 'kelas_id');
    }
}
