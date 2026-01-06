<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InformasiFactory extends Factory
{
    public function definition(): array
    {
        $judul = fake('id_ID')->sentence(4);
        return [
            'user_id' => null,
            'judul' => $judul,
            'slug' => Str::slug($judul.'-'.fake()->unique()->numberBetween(1,9999)),
            'ringkasan' => fake('id_ID')->sentence(10),
            'isi' => fake('id_ID')->paragraphs(3, true),
            'foto' => null,
            'is_published' => true,
            'published_at' => now()->subDays(rand(0,30)),
        ];
    }
}

