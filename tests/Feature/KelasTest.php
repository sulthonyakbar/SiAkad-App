<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Angkatan;
use Illuminate\Support\Facades\Hash;

class KelasTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateKelasValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $user = User::factory()->create([
            'username' => 'Guru Wali Kelas',
            'email' => 'guru@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $user->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $kelasData = Kelas::factory()->make([
            'nama_kelas' => 'D1 Test',
            'ruang' => 'Ruang A',
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ])->toArray();

        session(['angkatan_aktif' => $angkatan->id]);

        $response = $this->post(route('kelas.store'), $kelasData);

        $response->assertRedirect(route('kelas.index'));

        $this->assertDatabaseHas('kelas', [
            'nama_kelas' => 'D1 Test',
            'ruang' => 'Ruang A',
        ]);
    }

    public function testCreateKelasInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $user = User::factory()->create([
            'username' => 'Guru Wali Kelas',
            'email' => 'guru@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $user->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        Kelas::factory()->create([
            'nama_kelas' => 'D1 Test',
            'ruang' => 'Ruang A',
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        session(['angkatan_aktif' => $angkatan->id]);

        $existingKelas = Kelas::factory()->make([
            'nama_kelas' => 'D1 Test',
            'ruang' => 'Ruang A',
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ])->toArray();

        $response = $this->post(route('kelas.store'), $existingKelas);

        $response->assertSessionHasErrors(['ruang']);
    }

    public function testEditKelasValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $user = User::factory()->create([
            'username' => 'Guru Wali Kelas',
            'email' => 'guru@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $user->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $kelas = Kelas::factory()->create([
            'nama_kelas' => 'D1 Test',
            'ruang' => 'Ruang A',
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        session(['angkatan_aktif' => $angkatan->id]);

        $newUser = User::factory()->create([
            'username' => 'Guru Wali Kelas Baru',
            'email' => 'gurubaru@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $newGuru = Guru::factory()->create([
            'user_id' => $newUser->id,
        ]);

        $updateKelas = [
            'nama_kelas' => 'D1 Updated',
            'ruang' => 'Ruang B',
            'guru_id' => $newGuru->id,
        ];

        $response = $this->put(route('kelas.update', $kelas->id), $updateKelas);

        $response->assertRedirect(route('kelas.index'));

        $this->assertDatabaseHas('kelas', [
            'id' => $kelas->id,
            'nama_kelas' => 'D1 Updated',
            'ruang' => 'Ruang B',
            'guru_id' => $newGuru->id,
        ]);
    }

    public function testEditKelasInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $userA = User::factory()->create([
            'username' => 'Guru Wali Kelas A',
            'email' => 'gurua@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $guruB = Guru::factory()->create([
            'user_id' => $userA->id,
        ]);

        $userB = User::factory()->create([
            'username' => 'Guru Wali Kelas B',
            'email' => 'gurub@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $guruA = Guru::factory()->create([
            'user_id' => $userB->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $kelasA = Kelas::factory()->create([
            'nama_kelas' => 'A Test',
            'ruang' => 'Ruang A',
            'guru_id' => $guruA->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $kelasB = Kelas::factory()->create([
            'nama_kelas' => 'B Test',
            'ruang' => 'Ruang B',
            'guru_id' => $guruB->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $updateKelas = [
            'nama_kelas' => 'B Updated',
            'ruang' => 'Ruang A',
            'guru_id' => $guruA->id,
        ];

        $response = $this->put(route('kelas.update', $kelasB->id), $updateKelas);

        $response->assertSessionHasErrors(['ruang']);
    }

    public function testViewKelasList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $user = User::factory()->create([
            'username' => 'Guru Wali Kelas',
            'email' => 'guru@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $user->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        Kelas::factory()->create([
            'nama_kelas' => 'D1 Test',
            'ruang' => 'Ruang A',
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $response = $this->get(route('kelas.index'));

        $response->assertSeeText('Data Kelas');
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

        $response = $this->get(route('kelas.index'));

        $response->assertRedirect(route('login'));
    }
}
