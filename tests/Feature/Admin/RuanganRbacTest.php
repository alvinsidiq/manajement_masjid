<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('prevents non-admin from accessing ruangan index', function(){
    $user = User::factory()->create(['role'=>'user'])->assignRole('user');
    actingAs($user);

    get(route('admin.ruangan.index'))->assertForbidden();
});

