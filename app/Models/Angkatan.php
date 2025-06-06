<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tahun_ajaran',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'angkatan_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'angkatan_id');
    }

    public function semester()
    {
        return $this->hasMany(Semester::class, 'angkatan_id');
    }
}
