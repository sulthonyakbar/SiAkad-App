<?php

namespace Database\Factories;

use App\Models\Angkatan;
use App\Models\Guru;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kelas' => $this->faker->word(),
            'ruang' => $this->faker->unique()->word(),
            'angkatan_id' => Angkatan::factory(),
            'guru_id' => Guru::factory(),
        ];
    }
}
