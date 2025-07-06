<?php

namespace Tests\Feature;

use App\Models\BobotPenilaian;
use App\Models\Guru;
use App\Models\KartuStudi;
use App\Models\MataPelajaran;
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

        $mapel = MataPelajaran::factory()->create();

        $bobot = BobotPenilaian::factory()->create([
            'mapel_id' => $mapel->id,
            'nilai_uh' => 30,
            'nilai_uts' => 30,
            'nilai_uas' => 40,
        ]);

        $ks = KartuStudi::factory()->create();

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

        $this->assertDatabaseHas('nilai', [
            'ks_id' => $ks->id,
            'mapel_id' => $mapel->id,
            'nilai_uh' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
            'nilai_akhir' => round(80 * 0.3 + 85 * 0.3 + 90 * 0.4, 2),
        ]);
    }
}
