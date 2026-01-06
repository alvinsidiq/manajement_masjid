<?php

use App\Models\{User, Ruangan, Booking, Pemesanan};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\get;

it('shows aggregated show page by kind-key', function(){
    $u = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create(['status'=>'aktif']);
    $b = Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDay()->utc(),'jam'=>'08:00:00','status'=>StatusBooking::HOLD->value]);

    actingAs($u);
    get(route('user.status.show', 'booking-'.$b->booking_id))
        ->assertOk()
        ->assertSee('Booking #'.$b->booking_id);
});