<?php

use App\Models\User;
use function Pest\Laravel\actingAs; use function Pest\Laravel\get;

it('allows authenticated user to view profile page', function(){
    $u = User::factory()->create()->assignRole('user');
    actingAs($u);
    get(route('user.profile.edit'))->assertOk()->assertSee('Informasi Profil');
});