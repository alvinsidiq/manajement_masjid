<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Notifikasi};
use App\Services\NotifikasiService;
use App\Enums\JenisReferensi;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        Notifikasi::factory()->count(10)->create();

        // Note: skip actual sending in seeder to avoid SMTP issues in dev
    }
}
