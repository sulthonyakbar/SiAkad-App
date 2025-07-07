<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\BobotPenilaian;
use App\Models\Guru;
use App\Models\KartuStudi;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NilaiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateNilaiValid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $siswa = Siswa::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $ks = KartuStudi::factory()->create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $mapel = MataPelajaran::factory()->create();

        $bobot = BobotPenilaian::factory()->create([
            'mapel_id' => $mapel->id,
            'bobot_uh' => 30,
            'bobot_uts' => 30,
            'bobot_uas' => 40,
        ]);

        $nilaiData = [
            'ks_id' => $ks->id,
            'nilai' => [
                $mapel->id => [
                    'uh' => 80,
                    'uts' => 85,
                    'uas' => 90,
                ],
            ],
        ];

        $response = $this->post(route('nilai.store'), $nilaiData);

        $response->assertRedirect(route('nilai.index'));

        $this->assertDatabaseHas('nilais', [
            'ks_id' => $ks->id,
            'mapel_id' => $mapel->id,
            'nilai_uh' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
            'nilai_akhir' => round(80 * 0.3 + 85 * 0.3 + 90 * 0.4),
        ]);
    }

    public function testCreateNilaiInvalid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $siswa = Siswa::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $ks = KartuStudi::factory()->create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $mapel = MataPelajaran::factory()->create();

        $nilaiData = [
            'ks_id' => $ks->id,
            'nilai' => [
                $mapel->id => [
                    'uh' => 110,
                    'uts' => 85,
                    'uas' => 90,
                ],
            ],
        ];

        $response = $this->post(route('nilai.store'), $nilaiData);

        $response->assertSessionHasErrors(['nilai.' . $mapel->id . '.uh']);
    }

    public function testEditNilaiValid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $siswa = Siswa::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $ks = KartuStudi::factory()->create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $mapel = MataPelajaran::factory()->create();

        $bobot = BobotPenilaian::factory()->create([
            'mapel_id' => $mapel->id,
            'bobot_uh' => 30,
            'bobot_uts' => 30,
            'bobot_uas' => 40,
        ]);

        $nilai = Nilai::factory()->create([
            'ks_id' => $ks->id,
            'mapel_id' => $mapel->id,
            'nilai_uh' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
        ]);

        $updateData = [
            'nilai' => [
                $mapel->id => [
                    'uh' => 85,
                    'uts' => 90,
                    'uas' => 95,
                ],
            ],
        ];

        $response = $this->put(route('nilai.update', $ks->id), $updateData);

        $response->assertRedirect(route('nilai.index'));

        $this->assertDatabaseHas('nilais', [
            'ks_id' => $ks->id,
            'mapel_id' => $mapel->id,
            'nilai_uh' => 85,
            'nilai_uts' => 90,
            'nilai_uas' => 95,
            'nilai_akhir' => round(85 * 0.3 + 90 * 0.3 + 95 * 0.4),
        ]);
    }

    public function testEditNilaiInvalid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $siswa = Siswa::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $ks = KartuStudi::factory()->create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $mapel = MataPelajaran::factory()->create();

        $nilai = Nilai::factory()->create([
            'ks_id' => $ks->id,
            'mapel_id' => $mapel->id,
            'nilai_uh' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
        ]);

        $updateData = [
            'nilai' => [
                $mapel->id => [
                    'uh' => 110,
                    'uts' => 90,
                    'uas' => 95,
                ],
            ],
        ];

        $response = $this->put(route('nilai.update', $ks->id), $updateData);

        $response->assertSessionHasErrors(['nilai.' . $mapel->id . '.uh']);
    }

    public function testViewNilaiList()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $siswa = Siswa::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        KartuStudi::factory()->count(3)->create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $response = $this->get(route('nilai.index'));

        $response->assertSeeText('Data Nilai Siswa');
    }

    public function testShowDetailNilai()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $angkatan = Angkatan::factory()->create();

        $siswa = Siswa::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $semester = Semester::factory()->create([
            'angkatan_id' => $angkatan->id,
        ]);

        $ks = KartuStudi::factory()->create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $mapel = MataPelajaran::factory()->create();

        $nilai = Nilai::factory()->create([
            'ks_id' => $ks->id,
            'mapel_id' => $mapel->id,
            'nilai_uh' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
        ]);

        $response = $this->get(route('nilai.detail', $ks->id));

        $response->assertSeeText('Detail Nilai Siswa');
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

        $response = $this->get(route('nilai.index'));

        $response->assertRedirect(route('login'));
    }
}
