<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Booking;
use Illuminate\Support\Facades\Notification as FacadeNotification;
use function Pest\Laravel\artisan;

it('expires holds and notifies users', function(){
    FacadeNotification::fake();

    $user = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create();
    $b = Booking::factory()->create([
        'user_id' => $user->user_id,
        'ruangan_id' => $r->ruangan_id,
        'hold_expires_at' => now()->subMinute(),
        'status' => 'hold',
    ]);

    artisan('booking:expire-holds')->assertExitCode(0);

    $b->refresh();
    expect($b->status->value)->toBe('expired');
});

