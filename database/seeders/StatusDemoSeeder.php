<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Ruangan, Booking, Pemesanan};
use App\Enums\StatusBooking;

class StatusDemoSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::first() ?? User::factory()->create()->assignRole('user');
        $r = Ruangan::first() ?? Ruangan::factory()->create(['status'=>'aktif']);

        // Booking beragam status
        Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDays(1)->utc(),'jam'=>'08:00:00','status'=>StatusBooking::HOLD->value]);
        Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDays(2)->utc(),'jam'=>'09:00:00','status'=>StatusBooking::PROSES->value]);
        Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDays(3)->utc(),'jam'=>'10:00:00','status'=>StatusBooking::SETUJU->value]);
        Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDays(4)->utc(),'jam'=>'11:00:00','status'=>StatusBooking::TOLAK->value]);

        // Pemesanan menunggu & diterima
        $b = Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDays(5)->utc(),'jam'=>'12:00:00','status'=>StatusBooking::PROSES->value]);
        Pemesanan::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'booking_id'=>$b->booking_id,'status'=>'menunggu_verifikasi','tujuan_pemesanan'=>'Rapat Remaja']);
    }
}
