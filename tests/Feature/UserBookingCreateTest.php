<?php

use App\Models\{User, Ruangan};
use function Pest\Laravel\actingAs; use function Pest\Laravel\post; use function Pest\Laravel\get;

it('allows user to create HOLD booking on free slot', function(){
    $u = User::factory()->create()->assignRole('user');
    $r = Ruangan::factory()->create(['status'=>'aktif']);

    actingAs($u);
    post(route('user.booking.store'), [
        'ruangan_id' => $r->ruangan_id,
        'hari_tanggal' => now('Asia/Jakarta')->addDays(1)->format('Y-m-d'),
        'jam' => '10:00',
        'tujuan' => 'Rapat Uji'
    ])->assertRedirect();
});