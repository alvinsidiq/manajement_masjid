<?php

use App\Models\User;

it('blocks non-admin from archive list', function(){
    $user = User::factory()->create(['role'=>'user'])->assignRole('user');
    $this->actingAs($user);
    $this->get(route('admin.arsip.index'))->assertForbidden();
});
