<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BobotPenilaian extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'bobot_uh',
        'bobot_uts',
        'bobot_uas',
        'mapel_id',
        'semester_id',
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
