<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('filters by ruangan and status', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    $r1 = Ruangan::factory()->create(['nama_ruangan'=>'Alpha Hall']);
    $r2 = Ruangan::factory()->create(['nama_ruangan'=>'Beta Room']);

    $a = Pemesanan::factory()->create(['ruangan_id'=>$r1->ruangan_id,'status'=>'diterima']);
    $b = Pemesanan::factory()->create(['ruangan_id'=>$r2->ruangan_id,'status'=>'ditolak']);

    get(route('admin.pemesanan.index',['ruangan_id'=>$r1->ruangan_id,'status'=>'diterima']))
        ->assertOk()
        ->assertSee('Alpha Hall')
        ->assertDontSee('Beta Room');
});

