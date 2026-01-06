<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Ruangan, Booking, Pemesanan};
use App\Enums\StatusBooking;

class CancelDemoSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::first() ?? User::factory()->create()->assignRole('user');
        $r = Ruangan::first() ?? Ruangan::factory()->create(['status'=>'aktif']);

        // 1 booking HOLD yang bisa dibatalkan
        Booking::factory()->create([
            'user_id'=>$u->user_id,
            'ruangan_id'=>$r->ruangan_id,
            'hari_tanggal'=>now('Asia/Jakarta')->addDays(1)->utc(),
            'jam'=>'10:00:00',
            'status'=>StatusBooking::HOLD->value,
        ]);

        // 1 pemesanan menunggu_verifikasi yang bisa dibatalkan user
        $b = Booking::factory()->create([
            'user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,
            'hari_tanggal'=>now('Asia/Jakarta')->addDays(2)->utc(),'jam'=>'11:00:00',
            'status'=>StatusBooking::SUBMITTED->value,
        ]);
        Pemesanan::factory()->create([
            'user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,
            'booking_id'=>$b->booking_id,'status'=>'menunggu_verifikasi',
            'tujuan_pemesanan'=>'Uji Cancel'
        ]);
    }
}