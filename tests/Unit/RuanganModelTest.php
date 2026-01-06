<?php

use App\Models\Ruangan;

it('casts fasilitas to array', function(){
    $r = new Ruangan(['fasilitas' => ['AC','Wifi']]);
    expect($r->fasilitas)->toBeArray();
});

