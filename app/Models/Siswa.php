<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_siswa',
        'nomor_induk',
        'NISN',
        'NIK',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telp_siswa',
        'alamat_siswa',
        'foto',
        'tamatan',
        'tanggal_lulus',
        'STTB',
        'lama_belajar',
        'pindahan',
        'alasan',
        'orangtua_id',
        'user_id',
        'angkatan_id',
    ];

    public function orang_tuas()
    {
        return $this->belongsTo(OrangTua::class, 'orangtua_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function aktivitasHarian()
    {
        return $this->hasMany(AktivitasHarian::class, 'siswa_id');
    }

    public function kartuStudi()
    {
        return $this->hasMany(KartuStudi::class, 'siswa_id');
    }

    public function detailPresensi()
    {
        return $this->hasMany(DetailPresensi::class, 'siswa_id');
    }
}
