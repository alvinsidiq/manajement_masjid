<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('blocks non-admin from jadwal index', function(){
    $user = User::factory()->create(['role'=>'user'])->assignRole('user');
    actingAs($user);
    get(route('admin.jadwal.index'))->assertForbidden();
});

