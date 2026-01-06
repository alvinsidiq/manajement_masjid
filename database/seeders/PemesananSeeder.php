<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Ruangan;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        if (Ruangan::count() === 0 || User::count() === 0) return;

        Pemesanan::factory()->count(8)->create();

        Pemesanan::factory()->create(['status'=>'diterima','catatan'=>'Sudah diatur sound system.']);
        Pemesanan::factory()->create(['status'=>'ditolak','alasan_penolakan'=>'Bersinggungan dengan agenda masjid.']);
    }
}

