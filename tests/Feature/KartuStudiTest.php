<?php

namespace Tests\Feature;

use App\Models\KartuStudi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Angkatan;
use App\Models\Semester;

use Illuminate\Support\Facades\Hash;

class KartuStudiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateKartuStudiValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create([
            'tahun_ajaran' => '2025/2026',
        ]);

        $semester = Semester::factory()->create([
            'nama_semester' => 'Ganjil',
            'angkatan_id' => $angkatan->id,
        ]);

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $siswas = Siswa::factory()->count(3)->create();

        $ksData = KartuStudi::factory()->make([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswas->pluck('id')->toArray(),
        ])->toArray();

        $response = $this->post(route('kartu.studi.store'), $ksData);

        $response->assertRedirect(route('kartu.studi.index'));

        foreach ($siswas as $siswa) {
            $this->assertDatabaseHas('kartu_studis', [
                'kelas_id' => $kelas->id,
                'siswa_id' => $siswa->id,
                'semester_id' => $semester->id,
            ]);
        }
    }

    public function testCreateKartuStudiInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create([
            'tahun_ajaran' => '2025/2026',
        ]);

        $semester = Semester::factory()->create([
            'nama_semester' => 'Ganjil',
            'angkatan_id' => $angkatan->id,
        ]);

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelas1 = Kelas::factory()->create([
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $kelas2 = Kelas::factory()->create([
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $siswa = Siswa::factory()->create();

        KartuStudi::factory()->create([
            'kelas_id' => $kelas1->id,
            'siswa_id' => $siswa->id,
            'semester_id' => $semester->id,
        ]);

        $response = $this->post(route('kartu.studi.store'), [
            'kelas_id' => $kelas2->id,
            'siswa_id' => $siswa->id,
            'semester_id' => $semester->id,
        ]);

        $response->assertSessionHasErrors('siswa_id');
    }

    public function testEditKartuStudiValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create([
            'tahun_ajaran' => '2025/2026',
        ]);

        $semester = Semester::factory()->create([
            'nama_semester' => 'Ganjil',
            'angkatan_id' => $angkatan->id,
        ]);

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
            'angkatan_id' => $angkatan->id,
        ]);

        $siswaList = Siswa::factory()->count(2)->create();

        foreach ($siswaList as $siswa) {
            KartuStudi::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'semester_id' => $semester->id,
            ]);
        }

        $siswaBaru = Siswa::factory()->create();

        $response = $this->put(route('kartu.studi.update', $kelas->id), [
            'siswa_id' => [$siswaBaru->id],
        ]);

        $response->assertRedirect(route('kartu.studi.index'));

        $this->assertDatabaseHas('kartu_studis', [
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswaBaru->id,
            'semester_id' => $semester->id,
        ]);

        foreach ($siswaList as $siswaLama) {
            $this->assertDatabaseMissing('kartu_studis', [
                'kelas_id' => $kelas->id,
                'siswa_id' => $siswaLama->id,
                'semester_id' => $semester->id,
            ]);
        }
    }

    public function testEditKartuStudiInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create([
            'tahun_ajaran' => '2025/2026',
        ]);

        $semester = Semester::factory()->create([
            'nama_semester' => 'Ganjil',
            'angkatan_id' => $angkatan->id,
        ]);

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelasA = Kelas::factory()->create(['angkatan_id' => $angkatan->id, 'guru_id' => $guru->id]);
        $kelasB = Kelas::factory()->create(['angkatan_id' => $angkatan->id, 'guru_id' => $guru->id]);

        $siswaTerdaftar = Siswa::factory()->create();
        $siswaBaru = Siswa::factory()->create();

        KartuStudi::create([
            'siswa_id' => $siswaTerdaftar->id,
            'kelas_id' => $kelasA->id,
            'semester_id' => $semester->id,
        ]);

        $response = $this->put(route('kartu.studi.update', $kelasB->id), [
            'siswa_id' => [$siswaTerdaftar->id, $siswaBaru->id],
        ]);

        $response->assertRedirect(route('kartu.studi.index'));

        $response->assertSessionHas('warning');

        $this->assertDatabaseHas('kartu_studis', [
            'kelas_id' => $kelasB->id,
            'siswa_id' => $siswaBaru->id,
            'semester_id' => $semester->id,
        ]);

        $this->assertDatabaseHas('kartu_studis', [
            'kelas_id' => $kelasA->id,
            'siswa_id' => $siswaTerdaftar->id,
            'semester_id' => $semester->id,
        ]);

        $this->assertDatabaseMissing('kartu_studis', [
            'kelas_id' => $kelasB->id,
            'siswa_id' => $siswaTerdaftar->id,
        ]);
    }

    public function testViewKartuStudiList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('kartu.studi.index'));

        $response->assertSeeText('Data Kartu Studi Siswa');
    }

    public function testShowDetailKartuStudi()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $angkatan = Angkatan::factory()->create([
            'tahun_ajaran' => '2025/2026',
        ]);

        $semester = Semester::factory()->create([
            'nama_semester' => 'Ganjil',
            'angkatan_id' => $angkatan->id,
        ]);

        $guru = Guru::factory()->withUserRole('guru')->create();

        $kelas = Kelas::factory()->create([
            'angkatan_id' => $angkatan->id,
            'guru_id' => $guru->id
        ]);

        $siswa = Siswa::factory()->create();

        KartuStudi::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'semester_id' => $semester->id,
        ]);

        $response = $this->get(route('kartu.studi.detail', $kelas->id));

        $response->assertSeeText('Detail Kartu Studi Siswa');
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

        $response = $this->get(route('kartu.studi.index'));

        $response->assertRedirect(route('login'));
    }
}
