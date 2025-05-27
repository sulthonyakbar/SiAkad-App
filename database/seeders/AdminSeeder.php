<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gurus')->insert([
            [
                'nama_guru' => 'Admin Satu',
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
                'user_id' => DB::table('users')->insertGetId([
                    'email' => 'admin@siakad-slbdwsidoarjo.com',
                    'username' => 'admin',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
