<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Siswa;
use App\Models\Feedback;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AktivitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kegiatan' => $this->faker->sentence(),
            'kendala' => $this->faker->sentence(),
            'deskripsi' => $this->faker->paragraph(),
            'foto' => $this->faker->imageUrl(640, 480, 'nature', true, 'Aktivitas', true),
            'siswa_id' => Siswa::factory(),
            'feedback_id' => Feedback::factory(),
        ];
    }
}
