<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Kegiatan;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        if (Kegiatan::count() === 0) return;
        Jadwal::factory()->count(15)->create();
    }
}

