<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows users index with filters', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $u1 = User::factory()->create(['username'=>'alpha','email'=>'alpha@x.y','role'=>'user'])->assignRole('user');
    $u2 = User::factory()->create(['username'=>'beta','email'=>'beta@x.y','role'=>'takmir'])->assignRole('takmir');

    actingAs($admin);
    get(route('admin.users.index', ['q'=>'alp']))
        ->assertOk()
        ->assertSee('alpha')
        ->assertDontSee('beta');
});

