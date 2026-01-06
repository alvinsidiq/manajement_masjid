<?php

namespace Database\Factories;

use App\Models\KegiatanPendaftaran;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KegiatanPendaftaranFactory extends Factory
{
    protected $model = KegiatanPendaftaran::class;

    public function definition(): array
    {
        return [
            'kegiatan_id' => Kegiatan::factory(),
            'user_id' => User::factory(),
            'nama' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'no_telephone' => '+62'.$this->faker->numerify('8##########'),
            'catatan' => $this->faker->sentence(),
        ];
    }
}
