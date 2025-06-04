<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_ayah',
        'nama_ibu',
        'alamat_ortu',
        'no_telp_ortu',
        'pekerjaan_ayah',
        'pendidikan_ayah',
        'penghasilan_ayah',
        'pekerjaan_ibu',
        'pendidikan_ibu',
        'penghasilan_ibu',
    ];

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }
}
