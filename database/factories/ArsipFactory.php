<?php

namespace Database\Factories;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArsipFactory extends Factory
{
    protected $model = Arsip::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'judul' => $title,
            'deskripsi' => $this->faker->paragraph(),
            'dokumen' => 'arsip/'.$this->faker->slug().'.pdf',
            'uploaded_by' => User::factory(),
        ];
    }
}
