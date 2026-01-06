<?php

use App\Models\{User,Pemesanan};
use function Pest\Laravel\actingAs; use function Pest\Laravel\post;

it('allows treasurer to create manual payment', function(){
    $bend = User::factory()->create()->assignRole('bendahara');
    $pem = Pemesanan::factory()->create();

    actingAs($bend);
    post(route('bendahara.payment.store'), [
        'pemesanan_id' => $pem->pemesanan_id,
        'gateway' => 'manual',
        'method' => 'transfer',
        'amount' => 100000,
        'currency' => 'IDR',
    ])->assertRedirect();
});

