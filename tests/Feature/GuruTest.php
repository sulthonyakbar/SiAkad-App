<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class GuruTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateGuruValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $guruData = Guru::factory()->make([
            'role' => 'guru',
            'nama_guru' => 'Test Create Guru',
            'NIP' => '12345678',
            'foto' => UploadedFile::fake()->image('foto-guru.jpg'),
            'email' => 'test.create.guru@example.com',
        ])->toArray();

        $response = $this->post(route('pegawai.store'), $guruData);

        $response->assertRedirect(route('pegawai.guru.index'));

        $this->assertDatabaseHas('gurus', [
            'nama_guru' => 'Test Create Guru',
            'NIP' => '12345678',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test.create.guru@example.com',
            'role' => 'guru',
        ]);
    }

    public function testCreateGuruInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $guruData = Guru::factory()->make([
            'role' => 'guru',
            'nama_guru' => '12345678',
            'NIP' => 'abcdefgh',
            'email' => 'invalid-email',
        ])->toArray();

        $response = $this->post(route('pegawai.store'), $guruData);

        $response->assertSessionHasErrors(['nama_guru', 'NIP', 'email']);
    }

    public function testEditGuruValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Edit Guru',
            'NIP' => '12345678',
        ]);

        $updateData = [
            'nama_guru' => 'Test Guru Updated',
            'jabatan' => 'Guru Fisika',
            'jenis_kelamin' => 'Laki-laki',
            'NIP' => '987654321',
            'pangkat' => 'III/C',
            'NUPTK' => '64278127391',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1985-05-10',
            'pendidikan' => 'Strata 2',
            'mulai_bekerja' => '2010-07-15',
            'sertifikasi' => 'Ya',
            'no_telp' => '08843617628398',
            'alamat' => 'Jl. Merdeka No. 20, Surabaya',
            'foto' => UploadedFile::fake()->image('foto-guru.jpg'),
        ];

        $response = $this->put(route('pegawai.update', $guru->id), $updateData);

        $response->assertRedirect(route('pegawai.guru.index'));

        $this->assertDatabaseHas('gurus', [
            'id' => $guru->id,
            'nama_guru' => 'Test Guru Updated',
            'NIP' => '987654321',
        ]);
    }


    public function testEditGuruInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Edit Guru',
            'NIP' => '12345678',
        ]);

        $updateData = [
            'nama_guru' => '12345678',
            'NIP' => 'abcdefgh'
        ];

        $response = $this->put(route('pegawai.update', $guru->id), $updateData);

        $response->assertSessionHasErrors(['nama_guru', 'NIP']);
    }

    public function testViewGuruList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        Guru::factory()->count(3)->withUserRole('guru')->create();

        $response = $this->get(route('pegawai.guru.index'));

        $response->assertSeeText('Data Guru');
    }

    public function testShowDetailGuru()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Show Detail Guru',
            'NIP' => '12345678',
        ]);

        $response = $this->get(route('pegawai.detail', $guru->id));

        $response->assertSeeText('Detail Data Guru');
    }

    // public function testChangeStatusGuru()
    // {
    //      $admin = User::factory()->create([
    //         'email' => 'admin@siakad-slbdwsidoarjo.com',
    //         'username' => 'admin',
    //         'password' => Hash::make('admin123'),
    //         'role' => 'admin'
    //     ]);

    //     $this->actingAs($admin);

    //     $guru = Guru::factory()->create([
    //         'nama_guru' => 'Test Status Guru',
    //         'NIP' => '12345678',
    //         'status' => 'Aktif',
    //     ]);

    //     $response = $this->put(route('pegawai.status', $guru->id));

    //     $response->assertRedirect(route('pegawai.guru.index'));

    //     $guru->refresh();

    //     $this->assertEquals('Nonaktif', $guru->status);
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

        $response = $this->get(route('pegawai.guru.index'));

        $response->assertRedirect(route('login'));
    }
}
