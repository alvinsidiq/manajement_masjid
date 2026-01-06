<?php

use App\Models\User; use function Pest\Laravel\actingAs; use function Pest\Laravel\get;

it('blocks regular user from treasurer payment index', function(){
    $user = User::factory()->create()->assignRole('user');
    actingAs($user);
    get(route('bendahara.payment.index'))->assertForbidden();
});

