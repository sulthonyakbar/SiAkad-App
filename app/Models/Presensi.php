<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    public function kartuStudi()
    {
        return $this->hasOne(KartuStudi::class, 'presensi_id');
    }
}
