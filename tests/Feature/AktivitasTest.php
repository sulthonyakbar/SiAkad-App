<?php

namespace Tests\Feature;

use App\Models\AktivitasHarian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Siswa;
use Database\Factories\AktivitasFactory;
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
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $siswa = Siswa::factory()->create();

        $aktivitasData = AktivitasHarian::factory()->make([
            'kegiatan' => 'Belajar Matematika',
            'kendala' => 'Tidak ada',
            'deskripsi' => 'Belajar Matematika dengan baik',
            'foto' => UploadedFile::fake()->image('foto-aktivitas.jpg'),
            'siswa_id' => $siswa->id,
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

    public function testCreateAktivitasInvalid()
    {
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $siswa = Siswa::factory()->create();

        $aktivitasData = AktivitasHarian::factory()->make([
            'kegiatan' => '',
            'kendala' => '',
            'deskripsi' => '',
            'foto' => UploadedFile::fake()->image('foto-aktivitas.jpg'),
            'siswa_id' => $siswa->id,
            'feedback_id' => null,
        ])->toArray();

        $response = $this->post(route('aktivitas.store'), $aktivitasData);

        $response->assertSessionHasErrors(['kegiatan']);
    }

    public function testEditAktivitasValid()
    {
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $aktivitas = AktivitasHarian::factory()->create([
            'kegiatan' => 'Kegiatan Test',
            'kendala' => 'Tidak ada',
            'deskripsi' => 'Deskripsi Kegiatan Test',
            'foto' => UploadedFile::fake()->image('foto-aktivitas.jpg'),
            'siswa_id' => Siswa::factory()->create()->id,
            'feedback_id' => null,
        ]);

        $updateData = [
            'kegiatan' => 'Kegiatan Test Updated',
            'kendala' => 'Kendala Updated',
            'deskripsi' => 'Deskripsi Updated',
            'foto' => UploadedFile::fake()->image('foto-aktivitas-updated.jpg'),
            'siswa_id' => $aktivitas->siswa_id,
            'feedback_id' => null,
        ];

        $response = $this->put(route('aktivitas.update', $aktivitas->id), $updateData);

        $response->assertRedirect(route('aktivitas.index'));

        $this->assertDatabaseHas('aktivitas_harians', [
            'id' => $aktivitas->id,
            'kegiatan' => 'Kegiatan Test Updated',
        ]);
    }

    public function testEditAktivitasInvalid()
    {
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $aktivitas = AktivitasHarian::factory()->create([
            'kegiatan' => 'Kegiatan Test',
        ]);

        $updateData = [
            'kegiatan' => '',
        ];

        $response = $this->put(route('aktivitas.update', $aktivitas->id), $updateData);

        $response->assertSessionHasErrors(['kegiatan']);
    }

    public function testViewAktivitasList()
    {
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        AktivitasHarian::factory()->count(3)->create();

        $response = $this->get(route('aktivitas.index'));

        $response->assertSeeText('Data Aktivitas Harian Siswa');
    }

    public function testShowDetailAktivitas()
    {
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $aktivitas = AktivitasHarian::factory()->create();

        $response = $this->get(route('aktivitas.detail', $aktivitas->id));

        $response->assertSeeText('Detail Aktivitas Harian');
    }

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
