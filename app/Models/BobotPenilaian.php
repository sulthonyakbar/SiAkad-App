<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotPenilaian extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'bobot_uh',
        'bobot_uts',
        'bobot_uas',
    ];
}
