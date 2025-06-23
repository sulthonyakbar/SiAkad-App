<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalPelajaran>
 */
class JadwalPelajaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hari' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
            'jam_mulai' => $this->faker->time(),
            'jam_selesai' => $this->faker->time(),
            'mapel_id' => MataPelajaran::factory(),
            'kelas_id' => Kelas::factory(),
            'guru_id' => Guru::factory(),
        ];
    }
}
