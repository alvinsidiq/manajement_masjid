<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows pemesanan detail to admin', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    $ruang = Ruangan::factory()->create();
    $p = Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id]);

    get(route('admin.pemesanan.show',$p))->assertOk()->assertSee((string)$p->pemesanan_id);
});

