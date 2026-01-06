<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows pending pemesanan list for admin', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $ruang = Ruangan::factory()->create();
    Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id,'status'=>'menunggu_verifikasi']);

    actingAs($admin);
    get(route('admin.pemesanan.index'))
        ->assertOk()
        ->assertSee('Konfirmasi Pemesanan Ruangan');
});

