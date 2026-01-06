<?php

use App\Models\Ruangan;
use function Pest\Laravel\get;

it('shows active rooms and supports status filter', function(){
    Ruangan::factory()->create(['nama_ruangan'=>'Aula Utama','status'=>'aktif']);
    Ruangan::factory()->create(['nama_ruangan'=>'Gudang','status'=>'nonaktif']);

    get(route('public.ruangan.index'))
        ->assertOk()
        ->assertSee('Aula Utama')
        ->assertDontSee('Gudang');

    get(route('public.ruangan.index',['status'=>'nonaktif']))
        ->assertOk()
        ->assertSee('Gudang');
});

