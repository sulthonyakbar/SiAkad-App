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
        'nilai_akhir',
        'ks_id',
        'mapel_id',
    ];

    public function kartuStudi()
    {
        return $this->belongsTo(KartuStudi::class, 'ks_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
