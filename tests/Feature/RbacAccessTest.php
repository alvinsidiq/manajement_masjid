<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('prevents normal user accessing admin routes', function(){
    $u = User::factory()->create(['role' => 'user'])->assignRole('user');
    actingAs($u);
    get(route('admin.dashboard'))->assertForbidden();
});

