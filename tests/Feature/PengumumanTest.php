<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kategori;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class PengumumanTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreatePengumumanValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumumanData = Pengumuman::factory()->make([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.png'),
            'guru_id' => $admin->id,
            'kategori_id' => $kategori->id,
        ])->toArray();

        $response = $this->post(route('pengumuman.store'), $pengumumanData);

        $response->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseHas('pengumumen', [
            'judul' => 'Pengumuman Test',
            'guru_id' => $admin->id,
            'kategori_id' => $kategori->id,
        ]);
    }

    public function testCreatePengumumanInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumumanData = Pengumuman::factory()->make([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.jpg'),
            'guru_id' => $admin->id,
            'kategori_id' => $kategori->id,
        ])->toArray();

        $response = $this->post(route('pengumuman.store'), $pengumumanData);

        $response->assertSessionHasErrors(['gambar']);
    }

    // public function testEditPengumumanValid()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $user = User::factory()->create([
    //         'username' => 'Guru Wali Kelas',
    //         'email' => 'guru@example.com',
    //         'password' => Hash::make('guru123'),
    //         'role' => 'guru',
    //     ]);

    //     $guru = Guru::factory()->create([
    //         'user_id' => $user->id,
    //     ]);

    //     $angkatan = Angkatan::factory()->create();

    //     $kelas = Kelas::factory()->create([
    //         'nama_kelas' => 'D1 Test',
    //         'ruang' => 'Ruang A',
    //         'guru_id' => $guru->id,
    //         'angkatan_id' => $angkatan->id,
    //     ]);

    //     session(['angkatan_aktif' => $angkatan->id]);

    //     $newUser = User::factory()->create([
    //         'username' => 'Guru Wali Kelas Baru',
    //         'email' => 'gurubaru@example.com',
    //         'password' => Hash::make('guru123'),
    //         'role' => 'guru',
    //     ]);

    //     $newGuru = Guru::factory()->create([
    //         'user_id' => $newUser->id,
    //     ]);

    //     $updateKelas = [
    //         'nama_kelas' => 'D1 Updated',
    //         'ruang' => 'Ruang B',
    //         'guru_id' => $newGuru->id,
    //     ];

    //     $response = $this->put(route('kelas.update', $kelas->id), $updateKelas);

    //     $response->assertRedirect(route('kelas.index'));

    //     $this->assertDatabaseHas('kelas', [
    //         'id' => $kelas->id,
    //         'nama_kelas' => 'D1 Updated',
    //         'ruang' => 'Ruang B',
    //         'guru_id' => $newGuru->id,
    //     ]);
    // }

    // public function testEditPengumumanInvalid()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $userA = User::factory()->create([
    //         'username' => 'Guru Wali Kelas A',
    //         'email' => 'gurua@example.com',
    //         'password' => Hash::make('guru123'),
    //         'role' => 'guru',
    //     ]);

    //     $guruB = Guru::factory()->create([
    //         'user_id' => $userA->id,
    //     ]);

    //     $userB = User::factory()->create([
    //         'username' => 'Guru Wali Kelas B',
    //         'email' => 'gurub@example.com',
    //         'password' => Hash::make('guru123'),
    //         'role' => 'guru',
    //     ]);

    //     $guruA = Guru::factory()->create([
    //         'user_id' => $userB->id,
    //     ]);

    //     $angkatan = Angkatan::factory()->create();

    //     $kelasA = Kelas::factory()->create([
    //         'nama_kelas' => 'A Test',
    //         'ruang' => 'Ruang A',
    //         'guru_id' => $guruA->id,
    //         'angkatan_id' => $angkatan->id,
    //     ]);

    //     $kelasB = Kelas::factory()->create([
    //         'nama_kelas' => 'B Test',
    //         'ruang' => 'Ruang B',
    //         'guru_id' => $guruB->id,
    //         'angkatan_id' => $angkatan->id,
    //     ]);

    //     $updateKelas = [
    //         'nama_kelas' => 'B Updated',
    //         'ruang' => 'Ruang A',
    //         'guru_id' => $guruA->id,
    //     ];

    //     $response = $this->put(route('kelas.update', $kelasB->id), $updateKelas);

    //     $response->assertSessionHasErrors(['ruang']);
    // }

    public function testViewPengumumanList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        Pengumuman::factory()->count(3)->create([
            'guru_id' => $admin->id,
            'kategori_id' => $kategori->id,
        ]);

        $response = $this->get(route('pengumuman.index'));

        $response->assertSeeText('Data Pengumuman');
    }

    // public function testShowDetailPengumuman()
    // {
    //     $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $kategori = Kategori::factory()->create();

    //     $pengumuman = Pengumuman::factory()->create([
    //         'judul' => 'Pengumuman Test',
    //         'isi' => 'Ini adalah isi pengumuman test.',
    //         'gambar' => UploadedFile::fake()->image('foto-pengumuman.png'),
    //         'guru_id' => $admin->id,
    //         'kategori_id' => $kategori->id,
    //     ]);

    //     $response = $this->get(route('pengumuman.detail', $pengumuman->id));

    //     $response->assertSeeText('Detail Data Pengumuman');
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

        $response = $this->get(route('pengumuman.index'));

        $response->assertRedirect(route('login'));
    }
}
