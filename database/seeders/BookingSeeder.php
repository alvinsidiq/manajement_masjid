<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        Booking::factory()->count(15)->create();

        Booking::factory()->create([
            'hold_expires_at' => now()->subDay(),
            'status' => 'hold',
        ]);
    }
}

