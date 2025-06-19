<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $role;

    public function __construct($role)
    {

        if (!in_array($role, ['guru', 'admin'])) {
            throw new \InvalidArgumentException("Role tidak valid");
        }

        $this->role = $role;
    }

    public function model(array $row)
    {
        $namaAwal = implode('', array_slice(explode(' ', strtolower($row['nama_guru'])), 0, 2));
        $username = $namaAwal . $row['nip'];
        $password = $username;

        $user = User::create([
            'username' => $username,
            'email' => $row['email'],
            'password' => Hash::make($password),
            'role' => $this->role,
        ]);

        return new Guru([
            'nama_guru' => $row['nama_guru'],
            'jabatan' => $row['jabatan'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']),
            'pendidikan' => $row['pendidikan'],
            'mulai_bekerja' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['mulai_bekerja']),
            'no_telp' => $row['no_telp'],
            'alamat' => $row['alamat'],
            'NIP' => $row['nip'],
            'pangkat' => $row['pangkat'],
            'NUPTK' => $row['nuptk'],
            'sertifikasi' => $row['sertifikasi'],
            'status' => 'Aktif',
            'user_id' => $user->id,
        ]);
    }
}
