<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('blocks regular user from admin booking index', function(){
    $user = User::factory()->create()->assignRole('user');
    actingAs($user);
    get(route('admin.booking.index'))->assertForbidden();
});

