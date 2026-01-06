<?php

use App\Models\{User, Ruangan, Booking};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\put;

it('allows cancelling own booking only', function(){
    $r = Ruangan::factory()->create(['status'=>'aktif']);
    $owner = User::factory()->create()->assignRole('user');
    $other = User::factory()->create()->assignRole('user');

    $b = Booking::factory()->create([
        'user_id'=>$owner->user_id, 'ruangan_id'=>$r->ruangan_id,
        'hari_tanggal'=>now('Asia/Jakarta')->addDays(2)->utc(), 'jam'=>'08:00:00',
        'status'=>StatusBooking::HOLD->value
    ]);

    actingAs($other);
    put(route('user.booking.update',$b), ['aksi'=>'cancel'])->assertForbidden();

    actingAs($owner);
    put(route('user.booking.update',$b), ['aksi'=>'cancel'])->assertRedirect();
});