<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Ruangan;

class PemesananFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('user_id') ?? User::factory(),
            'ruangan_id' => Ruangan::inRandomOrder()->value('ruangan_id') ?? Ruangan::factory(),
            'jadwal_id' => null,
            'booking_id' => null,
            'tujuan_pemesanan' => fake('id_ID')->randomElement([
                'Rapat Komite Zakat','Kajian Tamu','Pelatihan Relawan','Acara Keluarga','Khataman Qur\'an'
            ]),
            'status' => 'menunggu_verifikasi',
            'catatan' => null,
            'alasan_penolakan' => null,
        ];
    }
}

