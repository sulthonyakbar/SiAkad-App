<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guru;
use App\Models\Kategori;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengumuman>
 */
class PengumumanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(),
            'isi' => $this->faker->paragraph(),
            'gambar' => $this->faker->imageUrl(640, 480, 'nature', true, 'GAMBAR PENGUMUMAN'),
            'kategori_id' => Kategori::factory(),
            'guru_id' => Guru::factory(),
        ];
    }
}
