<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use App\Models\User;

class PemesananAcceptedSeeder extends Seeder
{
    public function run(): void
    {
        if (Ruangan::count() === 0 || User::count() === 0) return;

        for ($i=0; $i<5; $i++) {
            Pemesanan::factory()->create([
                'ruangan_id' => Ruangan::inRandomOrder()->value('ruangan_id'),
                'user_id' => User::inRandomOrder()->value('user_id'),
                'status' => 'diterima',
                'catatan' => 'Sudah terjadwal.',
            ]);
        }
    }
}

