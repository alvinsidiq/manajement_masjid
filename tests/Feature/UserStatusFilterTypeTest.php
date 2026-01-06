<?php

use App\Models\{User, Ruangan, Booking, Pemesanan};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\get;

it('filters by type booking', function(){
    $u = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create(['status'=>'aktif']);
    Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'hari_tanggal'=>now('Asia/Jakarta')->addDay()->utc(),'jam'=>'08:00:00','status'=>StatusBooking::HOLD->value]);
    Pemesanan::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'status'=>'menunggu_verifikasi']);

    actingAs($u);
    get(route('user.status.index',['type'=>'booking']))
        ->assertOk()
        ->assertSee('Booking #')
        ->assertDontSee('Pemesanan #');
});