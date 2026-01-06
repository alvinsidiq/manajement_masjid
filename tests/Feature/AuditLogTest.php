<?php

use App\Models\User;
use App\Models\AuditLog;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('writes audit log on page hit', function () {
    $u = User::factory()->create(['role'=>'user'])->assignRole('user');
    actingAs($u);

    get(route('user.dashboard'))->assertOk();
    expect(AuditLog::query()->where('user_id',$u->getKey())->exists())->toBeTrue();
});

