<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;

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
}
