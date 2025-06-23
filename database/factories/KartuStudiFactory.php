<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KartuStudi>
 */
class KartuStudiFactory extends Factory
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
            'kelas_id' => Kelas::factory(),
            'semester_id' => Semester::factory(),
        ];
    }
}
