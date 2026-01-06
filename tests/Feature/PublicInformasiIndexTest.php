<?php

use App\Models\Informasi;
use function Pest\Laravel\get;

it('lists published informasi with filters', function(){
    Informasi::factory()->count(5)->create(['is_published'=>true]);
    Informasi::factory()->count(2)->create(['is_published'=>false]);

    get(route('public.informasi.index',[ 'q'=>'', 'month'=>null, 'year'=>null ]))
        ->assertOk()
        ->assertSee('Informasi');
});

