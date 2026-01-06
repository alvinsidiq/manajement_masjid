<?php

use App\Models\Ruangan;
use function Pest\Laravel\get;

it('filters by facility substring', function(){
    Ruangan::factory()->create(['nama_ruangan'=>'Ruang Rapat','status'=>'aktif','fasilitas'=>json_encode(['AC','Proyektor'])]);
    Ruangan::factory()->create(['nama_ruangan'=>'Serbaguna','status'=>'aktif','fasilitas'=>json_encode(['Kipas','Karpet'])]);

    get(route('public.ruangan.index',['fasilitas'=>'Proyektor']))
        ->assertOk()
        ->assertSee('Ruang Rapat')
        ->assertDontSee('Serbaguna');
});

