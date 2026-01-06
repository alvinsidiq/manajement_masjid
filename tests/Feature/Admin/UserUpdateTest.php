<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('admin updates user and syncs role', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $u = User::factory()->create(['role'=>'user'])->assignRole('user');

    actingAs($admin);
    put(route('admin.users.update', $u), [
        'username'=>$u->username,
        'email'=>$u->email,
        'role'=>'takmir',
        'is_active'=>1,
    ])->assertRedirect(route('admin.users.index'));

    $u->refresh();
    expect($u->hasRole('takmir'))->toBeTrue();
});

