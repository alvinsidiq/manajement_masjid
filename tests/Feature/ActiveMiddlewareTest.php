<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('blocks inactive user', function(){
    $u = User::factory()->create(['role'=>'user','is_active'=>false])->assignRole('user');
    actingAs($u);
    $res = get(route('user.dashboard'));
    $res->assertRedirect(route('login'));
});

