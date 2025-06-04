<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nilai_uh',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir'
    ];

    public function kartuStudi()
    {
        return $this->hasOne(KartuStudi::class, 'nilai_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'nilai_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
