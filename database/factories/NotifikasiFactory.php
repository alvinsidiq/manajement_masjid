<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class NotifikasiFactory extends Factory
{
    public function definition(): array
    {
        $uid = User::inRandomOrder()->value('user_id') ?? User::factory();
        return [
            'user_id' => $uid,
            'referensi_id' => null,
            'jenis_referensi' => $this->faker->randomElement(['umum','pemesanan','payment','kegiatan']),
            'pesan' => fake('id_ID')->sentence(8),
            'terkirim' => $this->faker->boolean(70),
            'waktu_kirim' => now()->subMinutes(rand(1,300)),
            'status_pengiriman' => $this->faker->randomElement(['sent:mail','sent:App\\Notifications\\Channels\\WhatsAppChannel','queued','failed:mail']),
        ];
    }
}
