<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kegiatan;
use App\Models\Ruangan;

class JadwalFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 days', '+30 days');
        $end   = (clone $start)->modify('+2 hours');
        return [
            'kegiatan_id' => Kegiatan::inRandomOrder()->value('kegiatan_id') ?? Kegiatan::factory(),
            'ruangan_id'  => Ruangan::inRandomOrder()->value('ruangan_id'),
            'tanggal_mulai' => $start,
            'tanggal_selesai'=> $end,
            'status' => fake()->randomElement(['rutin','berkala','khusus']),
            'catatan'=> fake('id_ID')->sentence(),
        ];
    }
}

