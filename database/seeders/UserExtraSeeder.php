<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class UserExtraSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['username'=>'admin2','email'=>'admin2@masjid.local','role'=>'admin'],
            ['username'=>'bendahara2','email'=>'bendahara2@masjid.local','role'=>'bendahara'],
            ['username'=>'takmir2','email'=>'takmir2@masjid.local','role'=>'takmir'],
            ['username'=>'user2','email'=>'user2@masjid.local','role'=>'user'],
        ];
        foreach ($list as $u) {
            $user = User::updateOrCreate(
                ['email'=>$u['email']],
                [
                    'username'=>$u['username'],
                    'password'=>Hash::make('password'),
                    'role'=>$u['role'],
                    'is_active'=>true,
                    'email_verified_at'=>now(),
                ]
            );
            $user->syncRoles([$u['role']]);
        }

        User::factory()->count(20)->create()->each(function($u){
            $u->syncRoles([$u->role]);
        });

        // Pastikan ada minimal 1 ruangan untuk kebutuhan demo
        $room = Ruangan::first();
        if (!$room) {
            $room = Ruangan::factory()->create([
                'nama_ruangan' => 'Aula Utama',
                'status' => 'aktif',
            ]);
        }
        // Tambahkan beberapa data verifikasi jadwal (pemesanan menunggu verifikasi)
        $pemesan = User::where('role','user')->first();
        if ($room && $pemesan) {
            Pemesanan::factory()->create([
                'user_id' => $pemesan->user_id,
                'ruangan_id' => $room->ruangan_id,
                'tujuan_pemesanan' => 'Kajian Rutin Remaja',
                'status' => 'menunggu_verifikasi',
            ]);
            Pemesanan::factory()->create([
                'user_id' => $pemesan->user_id,
                'ruangan_id' => $room->ruangan_id,
                'tujuan_pemesanan' => 'Rapat Panitia Ramadhan',
                'status' => 'menunggu_verifikasi',
            ]);
            Pemesanan::factory()->create([
                'user_id' => $pemesan->user_id,
                'ruangan_id' => $room->ruangan_id,
                'tujuan_pemesanan' => 'Pelatihan Marbot',
                'status' => 'menunggu_verifikasi',
            ]);

            // Tambahkan beberapa data verifikasi booking (pemesanan dari booking hold yang menunggu verifikasi)
            if (Pemesanan::whereNotNull('booking_id')->count() === 0) {
                foreach ([
                    'Booking Acara Keluarga',
                    'Booking Rapat Komite',
                    'Booking Kajian Mingguan',
                ] as $tujuan) {
                    $booking = Booking::factory()->create([
                        'user_id' => $pemesan->user_id,
                        'ruangan_id' => $room->ruangan_id,
                    ]);
                    Pemesanan::create([
                        'user_id' => $pemesan->user_id,
                        'ruangan_id' => $room->ruangan_id,
                        'jadwal_id' => null,
                        'booking_id' => $booking->booking_id,
                        'tujuan_pemesanan' => $tujuan,
                        'status' => 'menunggu_verifikasi',
                        'catatan' => null,
                        'alasan_penolakan' => null,
                    ]);
                }
            }
        }
    }
}
