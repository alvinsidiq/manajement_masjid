<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('admin can create user', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    post(route('admin.users.store'), [
        'username'=>'newuser',
        'email'=>'newuser@masjid.local',
        'password'=>'password',
        'role'=>'user',
        'is_active'=>1,
    ])->assertRedirect(route('admin.users.index'));
});

