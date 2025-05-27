<?php

namespace Tests\Feature;

use App\Models\MataPelajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;


class MapelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateMapelValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapelData = MataPelajaran::factory()->make([
            'nama_mapel' => 'Matematika Test',
        ])->toArray();

        $response = $this->post(route('mapel.store'), $mapelData);

        $response->assertRedirect(route('mapel.index'));

        $this->assertDatabaseHas('mata_pelajarans', [
            'nama_mapel' => 'Matematika Test',
        ]);
    }

    public function testCreateMapelInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapelData = MataPelajaran::factory()->make([
            'nama_mapel' => '12345678',
        ])->toArray();

        $response = $this->post(route('mapel.store'), $mapelData);

        $response->assertSessionHasErrors(['nama_mapel']);
    }

    public function testEditMapelValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create([
            'nama_mapel' => 'Matematika Test',
        ]);

        $updateData = [
            'nama_mapel' => 'Matematika Updated',
        ];

        $response = $this->put(route('mapel.update', $mapel->id), $updateData);

        $response->assertRedirect(route('mapel.index'));

        $this->assertDatabaseHas('mata_pelajarans', [
            'id' => $mapel->id,
            'nama_mapel' => 'Matematika Updated',
        ]);
    }

    public function testEditMapelInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create([
            'nama_mapel' => 'Matematika Test',
        ]);

        $updateData = [
            'nama_mapel' => 'Matematika 12345678',
        ];

        $response = $this->put(route('mapel.update', $mapel->id), $updateData);

        $response->assertSessionHasErrors(['nama_mapel']);
    }

    public function testViewMapelList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        MataPelajaran::factory()->count(3)->create();

        $response = $this->get(route('mapel.index'));

        $response->assertSeeText('Data Mata Pelajaran');
    }

    public function testDeleteMapel()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create([
            'nama_mapel' => 'Matematika Test',
        ]);

        $response = $this->delete(route('mapel.destroy', $mapel->id));

        $response->assertRedirect(route('mapel.index'));
        $this->assertDatabaseMissing('mata_pelajarans', ['id' => $mapel->id]);
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

        $response = $this->get(route('mapel.index'));

        $response->assertRedirect(route('login'));
    }
}
