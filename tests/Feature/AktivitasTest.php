<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AktivitasHarian;
use App\Models\User;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class AktivitasTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateAktivitasValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $siswa = User::factory()->create([
            'nama_siswa' => 'Test Siswa',
        ]);

        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('admin123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $aktivitasData = AktivitasHarian::factory()->make([
            'kegiatan' => 'Belajar Matematika',
            'kendala' => 'Tidak ada',
            'deskripsi' => 'Belajar Matematika dengan baik',
            'foto' => UploadedFile::fake()->image('foto-aktivitas.jpg'),
            'siswa_id' => 1,
            'feedback_id' => null,
        ])->toArray();

        $response = $this->post(route('aktivitas.store'), $aktivitasData);

        $response->assertRedirect(route('aktivitas.index'));

        $this->assertDatabaseHas('aktivitas_harians', [
            'kegiatan' => 'Belajar Matematika',
            'kendala' => 'Tidak ada',
            'deskripsi' => 'Belajar Matematika dengan baik',
        ]);
    }

    // public function testCreateMapelInvalid()
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

    // public function testEditMapelValid()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $mapel = MataPelajaran::factory()->create([
    //         'nama_mapel' => 'Matematika Test',
    //     ]);

    //     $updateData = [
    //         'nama_mapel' => 'Matematika Updated',
    //     ];

    //     $response = $this->put(route('mapel.update', $mapel->id), $updateData);

    //     $response->assertRedirect(route('mapel.index'));

    //     $this->assertDatabaseHas('mata_pelajarans', [
    //         'id' => $mapel->id,
    //         'nama_mapel' => 'Matematika Updated',
    //     ]);
    // }

    // public function testEditMapelInvalid()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $mapel = MataPelajaran::factory()->create([
    //         'nama_mapel' => 'Matematika Test',
    //     ]);

    //     $updateData = [
    //         'nama_mapel' => 'Matematika 12345678',
    //     ];

    //     $response = $this->put(route('mapel.update', $mapel->id), $updateData);

    //     $response->assertSessionHasErrors(['nama_mapel']);
    // }

    // public function testViewMapelList()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     MataPelajaran::factory()->count(3)->create();

    //     $response = $this->get(route('mapel.index'));

    //     $response->assertSeeText('Data Mata Pelajaran');
    // }

    public function testUnauthorizedUserCannotAccess()
    {
        $siswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $this->actingAs($siswa);

        $response = $this->get(route('aktivitas.index'));

        $response->assertRedirect(route('login'));
    }
}
