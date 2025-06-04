<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_guru',
        'jabatan',
        'status',
        'jenis_kelamin',
        'NIP',
        'pangkat',
        'NUPTK',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'mulai_bekerja',
        'sertifikasi',
        'no_telp',
        'alamat',
        'foto',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class);
    }

    public function jadwalPelajaran()
    {
        return $this->hasOne(JadwalPelajaran::class, 'guru_id');
    }

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'guru_id');
    }
}
