<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows pemesanan report html for admin', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $ruang = Ruangan::factory()->create();
    Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id,'status'=>'diterima']);

    actingAs($admin);
    get(route('admin.reports.index',['report'=>'pemesanan']))->assertOk()->assertSee('Laporan Pemesanan');
});

