<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_semester',
        'angkatan_id',
    ];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'semester_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'semester_id');
    }
}
