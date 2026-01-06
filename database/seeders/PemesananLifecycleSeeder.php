<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Pemesanan,User,Ruangan,Booking};

class PemesananLifecycleSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count()===0 || Ruangan::count()===0) return;
        Pemesanan::factory()->count(12)->create();

        $b = Booking::factory()->create(['status'=>'hold']);
        Pemesanan::factory()->create([
            'user_id'=>$b->user_id,
            'ruangan_id'=>$b->ruangan_id,
            'booking_id'=>$b->booking_id,
            'status'=>'menunggu_verifikasi',
        ]);
    }
}

