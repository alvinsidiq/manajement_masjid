<?php

use App\Models\{User, Ruangan, Booking, Pemesanan};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\post; use function Pest\Laravel\get;

it('cancels user reservation and releases slot', function(){
    $u=User::factory()->create()->assignRole('user');
    $r=Ruangan::factory()->create(['status'=>'aktif']);
    $b=Booking::factory()->create([
        'user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,
        'hari_tanggal'=>now('Asia/Jakarta')->addDays(2)->utc(),'jam'=>'10:30:00',
        'status'=>StatusBooking::PROSES->value
    ]);
    $p=Pemesanan::factory()->create([
        'user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'booking_id'=>$b->booking_id,
        'status'=>'menunggu_verifikasi'
    ]);

    actingAs($u);
    get(route('user.pemesanan.cancel.confirm',$p))->assertOk();
    post(route('user.pemesanan.cancel',$p), ['reason'=>'Berhalangan hadir'])
        ->assertRedirect();

    $p->refresh(); $b->refresh();
    expect($p->status)->toBe('dibatalkan');
    expect($b->status->value)->toBe('cancelled');
});
