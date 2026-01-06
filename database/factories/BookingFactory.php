<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Ruangan;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('+1 days', '+14 days');
        return [
            'user_id' => User::inRandomOrder()->value('user_id') ?? User::factory(),
            'ruangan_id' => Ruangan::inRandomOrder()->value('ruangan_id') ?? Ruangan::factory(),
            'hari_tanggal' => $date,
            'jam' => fake()->time('H:i').':00',
            'status' => 'hold',
            'hold_expires_at' => (clone $date)->modify('+60 minutes'),
        ];
    }
}
