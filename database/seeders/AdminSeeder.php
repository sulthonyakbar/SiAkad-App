<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = (string) Str::uuid();

        DB::table('users')->insert([
            'id' => $userId,
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $guruId = (string) Str::uuid();

        DB::table('gurus')->insert([
            [
            'id' => $guruId,
            'nama_guru' => 'Super Admin',
            'jabatan' => 'Administrator',
            'status' => 'Aktif',
            'jenis_kelamin' => 'Laki-laki',
            'NIP' => '123456789',
            'pangkat' => 'A',
            'NUPTK' => '987654321',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1980-01-01',
            'pendidikan' => 'S3',
            'mulai_bekerja' => '2010-01-01',
            'sertifikasi' => 'Tersertifikasi',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Pendidikan No.1, Surabaya',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
