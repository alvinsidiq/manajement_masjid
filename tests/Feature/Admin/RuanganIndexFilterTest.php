<?php

use App\Models\User;
use App\Models\Ruangan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('filters by status and search query', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    Ruangan::factory()->create(['nama_ruangan'=>'Ruang Alpha','status'=>'aktif']);
    Ruangan::factory()->create(['nama_ruangan'=>'Ruang Beta','status'=>'nonaktif']);

    get(route('admin.ruangan.index', ['q'=>'Alpha','status'=>'aktif']))
        ->assertOk()
        ->assertSee('Ruang Alpha')
        ->assertDontSee('Ruang Beta');
});

