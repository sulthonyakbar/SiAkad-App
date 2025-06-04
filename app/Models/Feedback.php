<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'pesan',
    ];

    public function aktivitasHarian()
    {
        return $this->hasOne(AktivitasHarian::class, 'feedback_id');
    }
}
