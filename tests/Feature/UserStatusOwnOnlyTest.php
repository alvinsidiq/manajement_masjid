<?php

use App\Models\{User, Ruangan, Booking};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\get;

it('shows only own timeline items', function(){
    $me = User::factory()->create()->assignRole('user');
    $other = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create(['status'=>'aktif']);

    Booking::factory()->create(['user_id'=>$me->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDay()->utc(),'jam'=>'08:00:00','status'=>StatusBooking::HOLD->value]);
    Booking::factory()->create(['user_id'=>$other->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDay()->utc(),'jam'=>'11:00:00','status'=>StatusBooking::HOLD->value]);

    actingAs($me);
    get(route('user.status.index'))
        ->assertOk()
        ->assertSee('Booking #')
        ->assertDontSee('11:00'); // milik user lain (jam berbeda)
});