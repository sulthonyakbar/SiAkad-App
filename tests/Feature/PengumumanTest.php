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

        $guru = Guru::factory()->create([
            'user_id' => $admin->id,
            'nama_guru' => 'Admin Sebagai Guru'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumumanData = Pengumuman::factory()->make([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.png'),
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ])->toArray();

        $response = $this->post(route('pengumuman.store'), $pengumumanData);

        $response->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseHas('pengumumen', [
            'judul' => 'Pengumuman Test',
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
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

        $guru = Guru::factory()->create([
            'user_id' => $admin->id,
            'nama_guru' => 'Admin Sebagai Guru'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumumanData = Pengumuman::factory()->make([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.jpg'),
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ])->toArray();

        $response = $this->post(route('pengumuman.store'), $pengumumanData);

        $response->assertSessionHasErrors(['gambar']);
    }

    public function testEditPengumumanValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $admin->id,
            'nama_guru' => 'Admin Sebagai Guru'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumuman = Pengumuman::factory()->create([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.png'),
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ]);

        $updatePengumuman = [
            'judul' => 'Pengumuman Updated',
            'isi' => 'Ini adalah isi pengumuman updated.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman-updated.png'),
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ];

        $response = $this->put(route('pengumuman.update', $pengumuman->id), $updatePengumuman);

        $response->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseHas('pengumumen', [
            'id' => $pengumuman->id,
            'judul' => 'Pengumuman Updated',
            'isi' => 'Ini adalah isi pengumuman updated.',
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ]);
    }

    public function testEditPengumumanInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $admin->id,
            'nama_guru' => 'Admin Sebagai Guru'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumuman = Pengumuman::factory()->create([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.png'),
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ]);

        $updatePengumuman = [
            'judul' => '',
            'isi' => '',
            'kategori_id' => 'invalid_kategori',
        ];

        $response = $this->put(route('pengumuman.update', $pengumuman->id), $updatePengumuman);

        $response->assertSessionHasErrors(['judul', 'isi', 'kategori_id']);
    }

    public function testViewPengumumanList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $admin->id,
            'nama_guru' => 'Admin Sebagai Guru'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        Pengumuman::factory()->count(3)->create([
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ]);

        $response = $this->get(route('pengumuman.index'));

        $response->assertSeeText('Data Pengumuman');
    }

    public function testShowDetailPengumuman()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $guru = Guru::factory()->create([
            'user_id' => $admin->id,
            'nama_guru' => 'Admin Sebagai Guru'
        ]);

        $this->actingAs($admin);

        $kategori = Kategori::factory()->create();

        $pengumuman = Pengumuman::factory()->create([
            'judul' => 'Pengumuman Test',
            'isi' => 'Ini adalah isi pengumuman test.',
            'gambar' => UploadedFile::fake()->image('foto-pengumuman.png'),
            'kategori_id' => $kategori->id,
            'guru_id' => $guru->id,
        ]);

        $response = $this->get(route('pengumuman.detail', $pengumuman->id));

        $response->assertSeeText('Detail Data Pengumuman');
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

        $response = $this->get(route('pengumuman.index'));

        $response->assertRedirect(route('login'));
    }
}
