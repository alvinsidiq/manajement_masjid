<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Ruangan, User, Booking, Pemesanan};

class RuanganPublicDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Ruangan::count() < 3) {
            Ruangan::factory()->count(3)->create([
                'status' => 'aktif',
                'fasilitas' => json_encode(['AC','Proyektor','Karpet'])
            ]);
        }

        $user = User::first() ?? User::factory()->create();
        $room = Ruangan::inRandomOrder()->first();

        for ($i=1; $i<=3; $i++) {
            $hari = now()->addDays($i)->setTime(8 + $i, 0, 0);
            $b = Booking::factory()->create([
                'user_id' => $user->user_id,
                'ruangan_id' => $room->ruangan_id,
                'hari_tanggal' => $hari,
                'jam' => $hari->format('H:i:s'),
                'status' => 'submitted',
            ]);
            Pemesanan::factory()->create([
                'user_id' => $user->user_id,
                'ruangan_id' => $room->ruangan_id,
                'booking_id' => $b->booking_id,
                'status' => 'diterima',
                'tujuan_pemesanan' => 'Kegiatan '.$i,
            ]);
        }
    }
}

