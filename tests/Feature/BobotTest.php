<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\BobotPenilaian;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BobotTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateBobotValid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $mapel = MataPelajaran::factory()->create();

        $bobotData = BobotPenilaian::factory()->make([
            'bobot_uh' => '20',
            'bobot_uts' => '30',
            'bobot_uas' => '50',
            'mapel_id' => $mapel->id,
        ])->toArray();

        $response = $this->post(route('bobot.store'), $bobotData);

        $response->assertRedirect(route('bobot.index'));

        $this->assertDatabaseHas('bobot_penilaians', [
            'bobot_uh' => 20,
            'bobot_uts' => 30,
            'bobot_uas' => 50,
            'mapel_id' => $mapel->id,
        ]);
    }

    public function testCreateBobotInvalid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $mapel = MataPelajaran::factory()->create();

        $bobotData = BobotPenilaian::factory()->make([
            'mapel_id' => $mapel->id,
            'bobot_uh' => '20',
            'bobot_uts' => '30',
            'bobot_uas' => '40',
        ])->toArray();

        $response = $this->post(route('bobot.store'), $bobotData);

        $response->assertSessionHasErrors(['total']);
    }

    public function testEditBobotValid()
    {

        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $bobot = BobotPenilaian::factory()->create([
            'bobot_uh' => '40',
            'bobot_uts' => '30',
            'bobot_uas' => '30',
        ]);

        $updateData = [
            'bobot_uh' => '20',
            'bobot_uts' => '30',
            'bobot_uas' => '50',
        ];

        $response = $this->put(route('bobot.update', $bobot->id), $updateData);

        $response->assertRedirect(route('bobot.index'));

        $this->assertDatabaseHas('bobot_penilaians', [
            'id' => $bobot->id,
            'bobot_uh' => '20',
            'bobot_uts' => '30',
            'bobot_uas' => '50',
        ]);
    }

    public function testEditBobotInvalid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $bobot = BobotPenilaian::factory()->create([
            'bobot_uh' => '40',
            'bobot_uts' => '30',
            'bobot_uas' => '30',
        ]);

        $updateData = [
            'bobot_uh' => '20',
            'bobot_uts' => '30',
            'bobot_uas' => '40',
        ];

        $response = $this->put(route('bobot.update', $bobot->id), $updateData);

        $response->assertSessionHasErrors(['error']);
    }

    public function testViewMapelList()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $mapel = MataPelajaran::factory()->create();

        BobotPenilaian::factory()->create([
            'mapel_id' => $mapel->id,
            'bobot_uh' => 20,
            'bobot_uts' => 30,
            'bobot_uas' => 50,
        ]);

        $response = $this->get(route('bobot.index'));

        $response->assertSeeText('Data Bobot Penilaian');
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

        $response = $this->get(route('bobot.index'));

        $response->assertRedirect(route('login'));
    }
}
