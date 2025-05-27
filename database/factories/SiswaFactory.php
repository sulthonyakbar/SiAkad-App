<?php

namespace Database\Factories;

use App\Models\OrangTua;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_siswa' => $this->faker->name(),
            'nomor_induk' => $this->faker->unique()->numerify('########'),
            'NISN' => $this->faker->unique()->numerify('#########'),
            'NIK' => $this->faker->unique()->numerify('##########'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'no_telp_siswa' => $this->faker->numerify('08#############'),
            'alamat_siswa' => $this->faker->address(),
            'foto' => $this->faker->imageUrl(640, 480, 'people', true, 'FOTO SISWA'),
            'tamatan' => $this->faker->randomElement(['TK', 'SD', 'SMP', 'SMA']),
            'tanggal_lulus' => $this->faker->date(),
            'STTB' => $this->faker->unique()->numerify('#########'),
            'lama_belajar' => (string) $this->faker->numberBetween(1, 12),
            'pindahan' => $this->faker->word(),
            'alasan' => $this->faker->sentence(),
            'orangtua_id' => OrangTua::factory(),
            'user_id' => User::factory()->orangtua(),
            'angkatan_id' => null,
        ];
    }
}
