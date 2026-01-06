<?php

use App\Models\{User, Ruangan, Booking};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\post;

it('prevents double booking on the same slot', function(){
    $u1 = User::factory()->create()->assignRole('user');
    $u2 = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create(['status'=>'aktif']);
    $tanggal = now('Asia/Jakarta')->addDays(1)->format('Y-m-d');

    actingAs($u1);
    post(route('user.booking.store'), [
        'ruangan_id'=>$r->ruangan_id, 'hari_tanggal'=>$tanggal, 'jam'=>'11:00', 'tujuan'=>'A'
    ])->assertRedirect();

    actingAs($u2);
    post(route('user.booking.store'), [
        'ruangan_id'=>$r->ruangan_id, 'hari_tanggal'=>$tanggal, 'jam'=>'11:00', 'tujuan'=>'B'
    ])->assertSessionHasErrors();
});