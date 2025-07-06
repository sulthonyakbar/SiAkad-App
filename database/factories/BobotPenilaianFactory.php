<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BobotPenilaian>
 */
class BobotPenilaianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bobot_uh' => $this->faker->numberBetween(1, 100),
            'bobot_uts' => $this->faker->numberBetween(1, 100),
            'bobot_uas' => $this->faker->numberBetween(1, 100),
            'mapel_id' => MataPelajaran::factory(),
        ];
    }
}
