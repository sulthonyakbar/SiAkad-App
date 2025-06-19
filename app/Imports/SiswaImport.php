<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Siswa;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private int $headerRow = 1;

    public function collection(Collection $rows): void
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {

                // Lewati header
                if ($index < $this->headerRow) {
                    continue;
                }

                $namaParts  = explode(' ', strtolower(trim($row[0])));
                $namaAwal   = implode('', array_slice($namaParts, 0, 2));
                $username   = $namaAwal . $row[2];
                $password   = $username;

                $userOrtu = User::create([
                    'username' => $username,
                    'email'    => $row[15],
                    'password' => Hash::make($password),
                    'role'     => 'orangtua',
                ]);

                $orangTua = OrangTua::create([
                    'nama_ayah'        => $row[16],
                    'pekerjaan_ayah'   => $row[17],
                    'pendidikan_ayah'  => $row[18],
                    'penghasilan_ayah' => $row[19],
                    'nama_ibu'         => $row[20],
                    'pekerjaan_ibu'    => $row[21],
                    'pendidikan_ibu'   => $row[22],
                    'penghasilan_ibu'  => $row[23],
                    'no_telp_ortu'     => $row[24],
                    'alamat_ortu'      => $row[25],
                ]);

                Siswa::create([
                    'nama_siswa'     => $row[0],
                    'nomor_induk'    => $row[1],
                    'NISN'           => $row[2],
                    'NIK'            => $row[3],
                    'tempat_lahir'   => $row[4],
                    'tanggal_lahir'  => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]),
                    'jenis_kelamin'  => $row[6],
                    'no_telp_siswa'  => $row[7],
                    'alamat_siswa'   => $row[8],
                    'tamatan'        => $row[9],
                    'tanggal_lulus'  => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10]),
                    'STTB'           => $row[11],
                    'lama_belajar'   => $row[12],
                    'pindahan'       => $row[13],
                    'alasan'         => $row[14],
                    'status'         => 'Aktif',
                    'orangtua_id'    => $orangTua->id,
                    'user_id'        => $userOrtu->id,
                    'angkatan_id'    => session('angkatan_aktif'),
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            '*.0'  => ['required', 'regex:/^[A-Za-z\s]+$/u', 'max:255'],
            '*.1'  => ['required', 'digits_between:1,20', 'unique:siswas,nomor_induk'],
            '*.2'  => ['required', 'digits_between:1,20', 'unique:siswas,NISN'],
            '*.3'  => ['required', 'digits_between:1,20', 'unique:siswas,NIK'],
            '*.4'  => ['required', 'regex:/^[A-Za-z\s]+$/u', 'max:255'],
            '*.5'  => ['required', 'date'],
            '*.6'  => ['required'],
            '*.7'  => ['required', 'digits_between:1,20', 'unique:siswas,no_telp_siswa'],
            '*.8'  => ['required', 'max:255'],
            '*.9'  => ['required', 'max:255'],
            '*.10' => ['required', 'date'],
            '*.11' => ['required', 'digits_between:1,255', 'unique:siswas,STTB'],
            '*.12' => ['required', 'digits_between:1,2'],
            '*.15' => ['required', 'email', 'unique:users,email'],
            '*.24' => ['required', 'digits_between:1,20', 'unique:orang_tuas,no_telp_ortu'],
        ];
    }
}
