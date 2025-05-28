<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesan',
    ];

    public function aktivitasHarian()
    {
        return $this->hasMany(AktivitasHarian::class, 'feedback_id');
    }
}
