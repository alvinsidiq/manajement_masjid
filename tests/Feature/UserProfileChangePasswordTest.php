<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs; use function Pest\Laravel\put;

it('changes password with valid current password', function(){
    $u = User::factory()->create(['password'=>Hash::make('secret')])->assignRole('user');
    actingAs($u);

    // Salah current password → gagal
    put(route('user.profile.password.update'), [
        'current_password'=>'wrong','password'=>'newpassword','password_confirmation'=>'newpassword'
    ])->assertSessionHasErrors('current_password');

    // Benar → berhasil
    put(route('user.profile.password.update'), [
        'current_password'=>'secret','password'=>'newpassword','password_confirmation'=>'newpassword'
    ])->assertRedirect();

    $u->refresh();
    expect(Hash::check('newpassword', $u->password))->toBeTrue();
});