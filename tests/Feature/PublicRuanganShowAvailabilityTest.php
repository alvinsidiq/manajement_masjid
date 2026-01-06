<?php

use App\Models\{User, Ruangan, Booking, Pemesanan};
use function Pest\Laravel\get;

it('shows upcoming blocked slots from accepted reservations', function(){
    $user = User::factory()->create()->assignRole('user');
    $room = Ruangan::factory()->create(['status'=>'aktif']);
    $hari = now()->addDays(2)->setTime(9,0);
    $b = Booking::factory()->create([
        'user_id'=>$user->user_id,
        'ruangan_id'=>$room->ruangan_id,
        'hari_tanggal'=>$hari,
        'jam'=>$hari->format('H:i:s'),
        'status'=>'submitted',
    ]);
    Pemesanan::factory()->create([
        'user_id'=>$user->user_id,
        'ruangan_id'=>$room->ruangan_id,
        'booking_id'=>$b->booking_id,
        'status'=>'diterima',
        'tujuan_pemesanan'=>'Rapat Remaja',
    ]);

    get(route('public.ruangan.show',$room->ruangan_id))
        ->assertOk()
        ->assertSee('Rapat Remaja')
        ->assertSee($hari->timezone('Asia/Jakarta')->format('d M Y'));
});

