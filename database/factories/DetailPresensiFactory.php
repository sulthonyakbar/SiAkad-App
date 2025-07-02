<?php

namespace Database\Factories;

use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPresensi>
 */
class DetailPresensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'presensi_id' => Presensi::factory(),
            'status' => $this->faker->randomElement(['Hadir', 'Izin', 'Sakit', 'Alpa']),
        ];
    }
}
