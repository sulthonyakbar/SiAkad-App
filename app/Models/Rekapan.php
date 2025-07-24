<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Rekapan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ks_id',
        'keterangan',
    ];

    public function kartuStudi()
    {
        return $this->belongsTo(KartuStudi::class, 'ks_id');
    }
}
