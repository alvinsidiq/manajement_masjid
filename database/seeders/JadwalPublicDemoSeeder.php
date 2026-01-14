<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\{User, Ruangan, Kegiatan, Jadwal, Booking, Pemesanan};

class JadwalPublicDemoSeeder extends Seeder
{
    public function run(): void
    {
        $tz = 'Asia/Jakarta';
        $now = Carbon::now($tz);

        $k1 = Kegiatan::factory()->create(['nama_kegiatan' => 'Kajian Ahad Pagi']);
        $k2 = Kegiatan::factory()->create(['nama_kegiatan' => 'Pelatihan Tahsin']);

        Jadwal::factory()->create([
            'kegiatan_id' => $k1->kegiatan_id,
            'tanggal_mulai' => $now->copy()->next('Sunday')->setTime(6, 0)->setTimezone('UTC'),
            'tanggal_selesai' => $now->copy()->next('Sunday')->setTime(8, 0)->setTimezone('UTC'),
            'status' => 'rutin',
            'catatan' => 'Terbuka umum',
        ]);

        Jadwal::factory()->create([
            'kegiatan_id' => $k2->kegiatan_id,
            'tanggal_mulai' => $now->copy()->addDays(10)->setTime(19, 30)->setTimezone('UTC'),
            'tanggal_selesai' => $now->copy()->addDays(10)->setTime(21, 0)->setTimezone('UTC'),
            'status' => 'khusus',
            'catatan' => null,
        ]);

        $user = User::first() ?? User::factory()->create();
        $room = Ruangan::first() ?? Ruangan::factory()->create(['status' => 'aktif']);

        $hari = $now->copy()->addDays(3)->setTime(9, 0);
        $booking = Booking::factory()->create([
            'user_id' => $user->user_id,
            'ruangan_id' => $room->ruangan_id,
            'hari_tanggal' => $hari->copy()->setTimezone('UTC'),
            'jam' => $hari->format('H:i:s'),
            'status' => 'setuju',
        ]);

        Pemesanan::factory()->create([
            'user_id' => $user->user_id,
            'ruangan_id' => $room->ruangan_id,
            'booking_id' => $booking->booking_id,
            'status' => 'diterima',
            'tujuan_pemesanan' => 'Rapat Remaja Masjid',
        ]);
    }
}
