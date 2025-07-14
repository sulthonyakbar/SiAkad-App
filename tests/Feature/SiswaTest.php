<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use App\Models\Angkatan;
use App\Models\OrangTua;

class SiswaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateSiswaValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create();
        session(['angkatan_aktif' => $angkatan->id]);

        $siswaData = Siswa::factory()->make([
            'nama_siswa' => 'Test Create Siswa',
            'nomor_induk' => '12345',
            'NISN' => '67890',
            'foto' => UploadedFile::fake()->image('foto-siswa.jpg'),
            'email' => 'test.create.siswa@example.com',
            'angkatan_id' => $angkatan->id,
        ])->toArray();

        $ortuData = OrangTua::factory()->make([
            'nama_ayah' => 'Test Create Ayah',
            'nama_ibu' => 'Test Create Ibu',
        ])->toArray();

        $response = $this->post(route('siswa.store'), array_merge($siswaData, $ortuData));

        $response->assertRedirect(route('siswa.index'));

        $this->assertDatabaseHas('siswas', [
            'nama_siswa' => 'Test Create Siswa',
            'nomor_induk' => '12345',
            'NISN' => '67890',
        ]);

        $this->assertDatabaseHas('orang_tuas', [
            'nama_ayah' => 'Test Create Ayah',
            'nama_ibu' => 'Test Create Ibu',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test.create.siswa@example.com',
            'role' => 'orangtua',
        ]);
    }

    public function testCreateSiswaInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create();
        session(['angkatan_aktif' => $angkatan->id]);

        $siswaData = Siswa::factory()->make([
            'nama_siswa' => '12345678',
            'nomor_induk' => 'invalid-nomor-induk',
            'NISN' => 'invalid-nisn',
            'angkatan_id' => $angkatan->id,
        ])->toArray();

        $ortuData = OrangTua::factory()->make([
            'nama_ayah' => '12345678',
            'nama_ibu' => '12345678',
        ])->toArray();

        $response = $this->post(route('siswa.store'), array_merge($siswaData, $ortuData));

        $response->assertSessionHasErrors(['nama_siswa', 'nomor_induk', 'NISN', 'nama_ayah', 'nama_ibu']);
    }

    public function testEditSiswaValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $siswa = Siswa::factory()->create([
            'nama_siswa' => 'Test Edit Siswa',
            'nomor_induk' => '12345',
            'NISN' => '67890',
        ]);

        $ortu = OrangTua::factory()->create([
            'nama_ayah' => 'Ayah Lama',
            'nama_ibu' => 'Ibu Lama',
        ]);

        $updateData = [
            'nama_siswa' => 'Test Edit Siswa Updated',
            'nomor_induk' => '54321',
            'NISN' => '09876',
            'NIK' => '1234567890123456',
            'tempat_lahir' => 'Kota Baru',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'no_telp_siswa' => '081234567890',
            'alamat_siswa' => 'Jl. Baru No. 123',
            'foto' => UploadedFile::fake()->image('foto-siswa-updated.jpg'),
            'tamatan' => 'SMP',
            'tanggal_lulus' => '2020-06-01',
            'STTB' => '123456',
            'lama_belajar' => '3',
            'pindahan' => 'Tidak',
            'alasan' => 'Alasan pindah sekolah',
            'orangtua_id' => $ortu->id,

            'nama_ayah' => 'Ayah Baru',
            'nama_ibu' => 'Ibu Baru',
            'alamat_ortu' => 'Jl. Baru No. 123',
            'no_telp_ortu' => '081234567890',
            'pekerjaan_ayah' => 'PNS',
            'pendidikan_ayah' => 'S1',
            'penghasilan_ayah' => '5000000',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'pendidikan_ibu' => 'SMA',
            'penghasilan_ibu' => '2000000',
        ];

        $response = $this->put(route('siswa.update', $siswa->id), $updateData);

        $response->assertRedirect(route('siswa.index'));

        $this->assertDatabaseHas('siswas', [
            'id' => $siswa->id,
            'nama_siswa' => 'Test Edit Siswa Updated',
            'nomor_induk' => '54321',
            'NISN' => '09876',
        ]);

        $this->assertDatabaseHas('orang_tuas', [
            'nama_ayah' => 'Ayah Baru',
            'nama_ibu' => 'Ibu Baru',
        ]);
    }

    public function testEditSiswaInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $siswa = Siswa::factory()->create([
            'nama_siswa' => 'Test Edit Siswa',
            'nomor_induk' => '12345',
            'NISN' => '67890',
        ]);

        $ortu = OrangTua::factory()->create([
            'nama_ayah' => 'Ayah Lama',
            'nama_ibu' => 'Ibu Lama',
        ]);

        $updateData = [
            'nama_siswa' => '12345678',
            'nomor_induk' => 'invalid-nomor-induk',
            'NISN' => 'invalid-nisn',

            'nama_ayah' => '12345678',
            'nama_ibu' => '12345678',
        ];

        $response = $this->put(route('siswa.update', $siswa->id), $updateData);

        $response->assertSessionHasErrors(['nama_siswa', 'nomor_induk', 'NISN', 'nama_ayah', 'nama_ibu']);
    }

    public function testViewSiswaList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        Siswa::factory()->count(3)->create();

        $response = $this->get(route('siswa.index'));

        $response->assertSeeText('Data Siswa');
    }

    public function testShowDetailSiswa()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $ortu = OrangTua::factory()->create([
            'nama_ayah' => 'Ayah Detail',
            'nama_ibu' => 'Ibu Detail',
        ]);

        $siswa = Siswa::factory()->create([
            'nama_siswa' => 'Test Show Detail Siswa',
            'nomor_induk' => '12345678',
            'NISN' => '87654321',
            'orangtua_id' => $ortu->id,
        ]);

        $response = $this->get(route('siswa.detail', $siswa->id));

        $response->assertSeeText('Detail Data Siswa');
    }

    public function testChangeStatusSiswa()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $siswa = Siswa::factory()->create([
            'status' => 'Aktif',
        ]);

        $response = $this->patch(route('siswa.status', ['id' => $siswa->id, 'status' => 'Nonaktif']));

        $response->assertRedirect();

        $response->assertSessionHas('success', 'Status siswa berhasil diubah menjadi Nonaktif.');

        $siswa->refresh();
        $this->assertEquals('Nonaktif', $siswa->status);
    }

    public function testUnauthorizedUserCannotAccess()
    {
        $siswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'guru'
        ]);

        $this->actingAs($siswa);

        $response = $this->get(route('siswa.index'));

        $response->assertRedirect(route('login'));
    }
}
