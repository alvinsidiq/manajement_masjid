<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('blocks non-admin from cancelling', function(){
    $user = User::factory()->create(['role'=>'user'])->assignRole('user');
    $ruang = Ruangan::factory()->create();
    $p = Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id,'user_id'=>$user->user_id,'status'=>'diterima']);

    actingAs($user);
    post(route('admin.pemesanan.cancel',$p))->assertForbidden();
});

