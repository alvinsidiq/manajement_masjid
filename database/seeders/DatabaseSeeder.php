<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            InitSeeder::class,
            UserExtraSeeder::class,
            UserSettingSeeder::class,
            RuanganSeeder::class,
            RuanganPriceSeeder::class,
            KegiatanSeeder::class,
            KegiatanArchivedSeeder::class,
            JadwalGridDemoSeeder::class,
            PemesananSeeder::class,
        ]);
    }
}
