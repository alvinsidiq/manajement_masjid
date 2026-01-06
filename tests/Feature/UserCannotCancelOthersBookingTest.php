<?php

use App\Models\{User, Ruangan, Booking};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\post;

it('forbids cancelling others booking', function(){
    $owner=User::factory()->create()->assignRole('user');
    $other=User::factory()->create()->assignRole('user');
    $r=Ruangan::factory()->create(['status'=>'aktif']);
    $b=Booking::factory()->create([
        'user_id'=>$owner->user_id,'ruangan_id'=>$r->ruangan_id,
        'hari_tanggal'=>now('Asia/Jakarta')->addDays(3)->utc(),'jam'=>'13:00:00',
        'status'=>StatusBooking::HOLD->value
    ]);

    actingAs($other);
    post(route('user.booking.cancel',$b), ['reason'=>'Iseng'])->assertForbidden();
});