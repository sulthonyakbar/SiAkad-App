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
    
    public function kartuStudi()
    {
        return $this->hasMany(KartuStudi::class, 'semester_id');
    }
}
