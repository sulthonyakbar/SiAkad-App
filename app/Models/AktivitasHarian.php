<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan',
        'kendala',
        'deskripsi',
        'foto',
        'siswa_id',
        'feedback_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
}
