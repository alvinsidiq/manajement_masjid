<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Informasi;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        Informasi::factory()->count(12)->create();
        Informasi::factory()->create(['is_published'=>false,'published_at'=>null]);
    }
}

