<?php

use App\Models\{User,Ruangan,Pemesanan,Booking};
use function Pest\Laravel\actingAs; use function Pest\Laravel\post;

it('rejects pemesanan with reason and marks booking tolak', function(){
    $admin = User::factory()->create()->assignRole('admin');
    $u = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create();
    $b = Booking::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'status'=>'hold']);
    $p = Pemesanan::factory()->create(['user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,'booking_id'=>$b->booking_id,'status'=>'menunggu_verifikasi']);

    actingAs($admin);
    post(route('admin.pemesanan.reject',$p), ['alasan_penolakan'=>'Bentrok jadwal'])->assertRedirect();

    $p->refresh(); $b->refresh();
    expect($p->status->value)->toBe('ditolak');
    expect($p->alasan_penolakan)->toBe('Bentrok jadwal');
    expect($b->status->value)->toBe('tolak');
});
