<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PresensiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreatePresensiValid()
    {
        $userGuru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $userGuru->id,
        ]);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $siswaList = Siswa::factory()->count(3)->create();

        $presensiData = [
            'kelas_id' => $kelas->id,
            'tanggal'  => now()->toDateString(),
            'status'   => [],
        ];

        foreach ($siswaList as $siswa) {
            $presensiData['status'][$siswa->id] = 'Hadir';
        }

        $response = $this->post(route('presensi.store'), $presensiData);

        $response->assertRedirect(route('presensi.index'));

        $this->assertDatabaseHas('presensis', [
            'kelas_id' => $kelas->id,
            'tanggal' => $presensiData['tanggal'],
        ]);

        foreach ($siswaList as $siswa) {
            $this->assertDatabaseHas('detail_presensis', [
                'siswa_id' => $siswa->id,
                'status' => 'Hadir',
            ]);
        }
    }
}
