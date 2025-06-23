<?php

namespace Database\Factories;

use App\Models\Angkatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_semester' => $this->faker->word(),
            'angkatan_id' => Angkatan::factory(),
        ];
    }
}
