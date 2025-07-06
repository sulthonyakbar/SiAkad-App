<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\KartuStudi;
use App\Models\MataPelajaran;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nilai>
 */
class NilaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nilai_uh' => $this->faker->numberBetween(0, 100),
            'nilai_uts' => $this->faker->numberBetween(0, 100),
            'nilai_uas' => $this->faker->numberBetween(0, 100),
            'nilai_akhir' => $this->faker->numberBetween(0, 100),
            'ks_id' => KartuStudi::factory(),
            'mapel_id' => MataPelajaran::factory(),
        ];
    }
}
