<?php

use App\Models\User;
use App\Models\Kegiatan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('filters by jenis and query', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    Kegiatan::factory()->create(['nama_kegiatan'=>'Alpha Kajian','jenis_kegiatan'=>'rutin']);
    Kegiatan::factory()->create(['nama_kegiatan'=>'Beta Khataman','jenis_kegiatan'=>'khusus']);

    get(route('admin.kegiatan.index',[ 'q'=>'Alpha','jenis_kegiatan'=>'rutin']))
        ->assertOk()
        ->assertSee('Alpha Kajian')
        ->assertDontSee('Beta Khataman');
});

