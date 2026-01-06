<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Ruangan, Booking};
use App\Enums\StatusBooking;

class BookingUserDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create()->assignRole('user');
        $room = Ruangan::first() ?? Ruangan::factory()->create(['status'=>'aktif']);
        Booking::factory()->create(
            [
                'user_id' => $user->user_id,
                'ruangan_id' => $room->ruangan_id,
                'hari_tanggal' => now('Asia/Jakarta')->addDays(1)->startOfDay()->utc(),
                'jam' => '09:00:00',
                'status' => StatusBooking::HOLD->value,
                'hold_expires_at' => now()->addMinutes(30),
            ]
        );
    }
}