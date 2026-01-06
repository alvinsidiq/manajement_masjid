<?php

use App\Models\User;
use App\Models\UserSetting;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('prevents viewing others settings', function(){
    $me = User::factory()->create(['role' => 'user'])->assignRole('user');
    $other = User::factory()->create(['role' => 'user'])->assignRole('user');
    $setting = UserSetting::create(['user_id'=>$other->getKey(),'dark_mode'=>false,'preferred_landing'=>'dashboard']);

    actingAs($me);
    get(route('user.settings.show', $setting))->assertForbidden();
});

