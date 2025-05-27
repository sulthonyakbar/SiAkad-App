<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_guru' => $this->faker->name(),
            'jabatan' => $this->faker->jobTitle(),
            'status' => $this->faker->randomElement(['Aktif', 'Nonaktif']),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'NIP' => $this->faker->unique()->numerify('##########'),
            'pangkat' => $this->faker->randomElement(['III/A', 'III/B', 'III/C', 'IV/A', 'IV/B']),
            'NUPTK' => $this->faker->unique()->numerify('##########'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'pendidikan' => $this->faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'mulai_bekerja' => $this->faker->date(),
            'sertifikasi' => $this->faker->randomElement(['Ya', 'Tidak']),
            'no_telp' => $this->faker->numerify('08#############'),
            'alamat' => $this->faker->address(),
            'foto' => $this->faker->imageUrl(640, 480, 'people', true, 'FOTO GURU'),
            'user_id' => User::factory()->guru(),
        ];
    }

    public function withUserRole(string $role = 'guru')
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'user_id' => User::factory()->state(['role' => $role]),
            ];
        });
    }
}
