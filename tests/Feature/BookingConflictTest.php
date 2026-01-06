<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Booking;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('rejects conflicting holds on same slot', function(){
    $admin = User::factory()->create()->assignRole('admin');
    $u = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create();

    $date = now()->addDays(2)->format('Y-m-d');

    Booking::factory()->create([
        'user_id' => $u->user_id,
        'ruangan_id' => $r->ruangan_id,
        'hari_tanggal' => $date,
        'jam' => '10:00',
        'status' => 'hold',
        'hold_expires_at' => now()->addHour(),
    ]);

    actingAs($admin);
    post(route('admin.booking.store'), [
        'user_id' => $u->user_id,
        'ruangan_id' => $r->ruangan_id,
        'hari_tanggal' => $date,
        'jam' => '10:00',
        'status' => 'hold',
        'hold_expires_at' => now()->addHours(2)->format('Y-m-d H:i:s')
    ])->assertSessionHasErrors();
});

