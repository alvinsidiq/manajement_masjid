<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Ruangan;
use Carbon\CarbonImmutable;

class PemesananReportSeeder extends Seeder
{
    public function run(): void
    {
        if (Ruangan::count() === 0 || User::count() === 0) return;

        $statuses = ['menunggu_verifikasi','diterima','ditolak','dibatalkan','selesai'];
        $start = CarbonImmutable::now('Asia/Jakarta')->subDays(60);

        for ($i=0; $i<60; $i++) {
            $day = $start->addDays($i)->setTime(rand(8,20), rand(0,59));
            Pemesanan::factory()->create([
                'user_id' => User::inRandomOrder()->value('user_id'),
                'ruangan_id' => Ruangan::inRandomOrder()->value('ruangan_id'),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $day->clone()->utc(),
                'updated_at' => $day->clone()->utc(),
            ]);
        }
    }
}

