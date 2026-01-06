<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects admin to admin dashboard', function () {
    $u = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ])->assignRole('admin');

    actingAs($u);

    $res = get('/user/dashboard');
    $res->assertOk();
    $res2 = get(route('admin.dashboard'));
    $res2->assertOk()->assertSee('Dashboard Admin');
});

it('user can reach user dashboard', function () {
    $u = User::factory()->create(['role' => 'user'])->assignRole('user');
    actingAs($u);
    $res = get(route('user.dashboard'));
    $res->assertOk()->assertSee('Dashboard Pengguna');
});

