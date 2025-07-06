<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\DetailPresensi;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PresensiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreatePresensiValid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $siswaList = Siswa::factory()->count(3)->create();

        $presensiData = [
            'kelas_id' => $kelas->id,
            'tanggal'  => now()->toDateString(),
            'status'   => [],
        ];

        foreach ($siswaList as $siswa) {
            $presensiData['status'][$siswa->id] = 'Hadir';
        }

        $response = $this->post(route('presensi.store'), $presensiData);

        $response->assertRedirect(route('presensi.index'));

        $this->assertDatabaseHas('presensis', [
            'kelas_id' => $kelas->id,
            'tanggal' => $presensiData['tanggal'],
        ]);

        $presensi = Presensi::where('kelas_id', $kelas->id)->where('tanggal', $presensiData['tanggal'])->first();
        
        foreach ($siswaList as $siswa) {
            $this->assertDatabaseHas('detail_presensis', [
                'presensi_id' => $presensi->id,
                'siswa_id'    => $siswa->id,
                'status'      => 'Hadir',
            ]);
        }
    }

    public function testCreatePresensiInvalid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $presensiData = [
            'kelas_id' => null,
            'tanggal'  => 'bukan-tanggal',
            'status'   => [],
        ];

        $response = $this->post(route('presensi.store'), $presensiData);

        $response->assertSessionHasErrors(['kelas_id', 'tanggal', 'status']);
    }

    public function testEditPresensiValid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $presensi = Presensi::factory()->create([
            'kelas_id' => $kelas->id,
            'tanggal'  => now()->toDateString(),
        ]);

        $siswaList = Siswa::factory()->count(3)->create();

        foreach ($siswaList as $siswa) {
            DetailPresensi::create([
                'presensi_id' => $presensi->id,
                'siswa_id' => $siswa->id,
                'status' => 'Alpa',
            ]);
        }

        $presensiData = [
            'siswa_id' => $siswaList->pluck('id')->toArray(),
            'status' => [
                $siswaList[0]->id => 'Hadir',
                $siswaList[1]->id => 'Sakit',
                $siswaList[2]->id => 'Izin',
            ],
        ];

        $response = $this->put(route('presensi.update', $presensi->id), $presensiData);

        $response->assertRedirect(route('presensi.index'));

        foreach ($siswaList as $siswa) {
            $expectedStatus = $presensiData['status'][$siswa->id] ?? 'Hadir';

            $this->assertDatabaseHas('detail_presensis', [
                'presensi_id' => $presensi->id,
                'siswa_id' => $siswa->id,
                'status' => $expectedStatus,
            ]);
        }
    }

    public function testEditPresensiInvalid()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $presensi = Presensi::factory()->create([
            'kelas_id' => $kelas->id,
            'tanggal'  => now()->toDateString(),
        ]);

        $presensiData = [
            'siswa_id' => [],
            'status' => [],
        ];

        $response = $this->put(route('presensi.update', $presensi->id), $presensiData);

        $response->assertSessionHasErrors(['siswa_id', 'status']);
    }

    public function testViewPresensiList()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        Presensi::factory()->count(3)->create([
            'kelas_id' => Kelas::factory()->create(['guru_id' => $guru->id])->id,
            'tanggal'  => now()->toDateString(),
        ]);

        $response = $this->get(route('presensi.index'));

        $response->assertSeeText('Data Presensi Siswa');
    }

    public function testShowDetailPresensi()
    {
        $guru = Guru::factory()->withUserRole('guru')->create([
            'nama_guru' => 'Test Guru',
            'NIP' => '12345678',
        ]);

        $this->actingAs($guru->user);

        $kelas = Kelas::factory()->create([
            'guru_id' => $guru->id,
        ]);

        $presensi = Presensi::factory()->create([
            'kelas_id' => $kelas->id,
            'tanggal'  => now()->toDateString(),
        ]);

        $siswaList = Siswa::factory()->count(3)->create();

        foreach ($siswaList as $siswa) {
            DetailPresensi::create([
                'presensi_id' => $presensi->id,
                'siswa_id' => $siswa->id,
                'status' => 'Hadir',
            ]);
        }

        $response = $this->get(route('presensi.detail', ['kelas_id' => $kelas->id, 'tanggal' => $presensi->tanggal]));

        $response->assertSeeText('Detail Presensi Siswa');
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

        $response = $this->get(route('presensi.index'));

        $response->assertRedirect(route('login'));
    }
}
