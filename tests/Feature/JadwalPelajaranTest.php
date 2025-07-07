<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class JadwalPelajaranTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateJadwalPelajaranValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create();

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id
        ]);

        $jadwalData = JadwalPelajaran::factory()->make([
            'jadwals' => [
                [
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '09:30',
                ],
                [
                    'hari' => 'Selasa',
                    'jam_mulai' => '10:00',
                    'jam_selesai' => '11:30',
                ],
                [
                    'hari' => 'Rabu',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '09:30',
                ],
            ],
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ])->toArray();

        $response = $this->post(route('jadwal.store'), $jadwalData);

        $response->assertRedirect(route('jadwal.index'));

        $this->assertDatabaseHas('jadwal_pelajarans', [
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:30:00',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ]);
    }

    public function testCreateJadwalPelajaranInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create();
        $guru = Guru::factory()->withUserRole('guru')->create();
        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id
        ]);

        $jadwalData = JadwalPelajaran::factory()->make([
            'jadwals' => [
                [
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '09:30',
                ],
                [
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '09:30',
                ],
            ],
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ])->toArray();

        $response = $this->post(route('jadwal.store'), $jadwalData);

        $response->assertSessionHasErrors();
    }

    public function testEditJadwalPelajaranValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create();
        $guru = Guru::factory()->withUserRole('guru')->create();
        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id
        ]);

        $jadwal = JadwalPelajaran::factory()->create([
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ]);

        $updateData = [
            'hari' => 'Selasa',
            'jam_mulai' => '10:00',
            'jam_selesai' => '11:30',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ];

        $response = $this->put(route('jadwal.update', $jadwal->id), $updateData);

        $response->assertRedirect(route('jadwal.index'));

        $this->assertDatabaseHas('jadwal_pelajarans', [
            'id' => $jadwal->id,
            'hari' => 'Selasa',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:30:00',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ]);
    }

    public function testEditJadwalPelajaranInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create();
        $guru = Guru::factory()->withUserRole('guru')->create();
        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id
        ]);

        $jadwalA = JadwalPelajaran::factory()->create([
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:30',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ]);

        $jadwal = JadwalPelajaran::factory()->create([
            'hari' => 'Selasa',
            'jam_mulai' => '13:00',
            'jam_selesai' => '14:30',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ]);

        $updateData = [
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:30',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ];

        $response = $this->put(route('jadwal.update', $jadwal->id), $updateData);

        $response->assertSessionHasErrors();
    }

    public function testViewJadwalPelajaranList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $mapel = MataPelajaran::factory()->create();
        $guru = Guru::factory()->withUserRole('guru')->create();
        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id
        ]);

        JadwalPelajaran::factory()->create([
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:30:00',
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
        ])->toArray();

        $response = $this->get(route('jadwal.index'));

        $response->assertSeeText('Data Jadwal Pelajaran');
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

        $response = $this->get(route('jadwal.index'));

        $response->assertRedirect(route('login'));
    }
}
