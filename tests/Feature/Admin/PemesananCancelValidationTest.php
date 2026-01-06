<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires reason to cancel', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $pemesan = User::factory()->create(['role'=>'user'])->assignRole('user');
    $ruang = Ruangan::factory()->create();
    $p = Pemesanan::factory()->create([
        'user_id'=>$pemesan->user_id,
        'ruangan_id'=>$ruang->ruangan_id,
        'status'=>'diterima'
    ]);

    actingAs($admin);
    post(route('admin.pemesanan.cancel',$p), [])->assertSessionHasErrors(['alasan_pembatalan']);
});

