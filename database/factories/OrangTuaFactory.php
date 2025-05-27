<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrangTua>
 */
class OrangTuaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_ayah' => $this->faker->name(),
            'nama_ibu' => $this->faker->name(),
            'alamat_ortu' => $this->faker->address(),
            'no_telp_ortu' => $this->faker->numerify('08#############'),
            'pekerjaan_ayah' => $this->faker->jobTitle(),
            'pendidikan_ayah' => $this->faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'penghasilan_ayah' => (string) $this->faker->numberBetween(2000000, 10000000),
            'pekerjaan_ibu' => $this->faker->jobTitle(),
            'pendidikan_ibu' => $this->faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'penghasilan_ibu' => (string) $this->faker->numberBetween(2000000, 10000000),
        ];
    }
}
