<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use function Pest\Laravel\actingAs; use function Pest\Laravel\put;

it('resets verification and sends verification mail on email change', function(){
    Notification::fake();
    $u = User::factory()->create(['email_verified_at'=>now()])->assignRole('user');
    actingAs($u);

    put(route('user.profile.update'), [
        'username'=>$u->username,
        'email'=>'baru@example.test',
        'no_telephone'=>'+62 812-9999-0000'
    ])->assertRedirect();

    $u->refresh();
    expect($u->email)->toBe('baru@example.test');
    expect($u->email_verified_at)->toBeNull();
    Notification::assertSentTo($u, VerifyEmail::class);
});