<?php

namespace Tests\Feature;

use App\Models\KartuStudi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Angkatan;
use App\Models\Semester;

use Illuminate\Support\Facades\Hash;

class KartuStudiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateKartuStudiValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create();

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $siswa = Siswa::factory()->create();

        $ksData = KartuStudi::factory()->make([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->pluck('id')->toArray(),
        ])->toArray();

        $response = $this->post(route('kartu.studi.store'), $ksData);

        $response->assertRedirect(route('kartu.studi.index'));

        // foreach ($siswas as $siswa) {
        $this->assertDatabaseHas('kartu_studis', [
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'semester_id' => $semester->id,
        ]);
        // }
    }

    // public function testCreateKartuStudiInvalid()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $mapelData = MataPelajaran::factory()->make([
    //         'nama_mapel' => '12345678',
    //     ])->toArray();

    //     $response = $this->post(route('mapel.store'), $mapelData);

    //     $response->assertSessionHasErrors(['nama_mapel']);
    // }
}
