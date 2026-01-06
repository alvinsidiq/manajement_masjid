<?php

use App\Models\User;
use function Pest\Laravel\actingAs; use function Pest\Laravel\post;

it('allows admin to create manual broadcast', function(){
    $admin = User::factory()->create()->assignRole('admin');
    $target = User::factory()->create()->assignRole('user');

    actingAs($admin);
    post(route('admin.notifikasi.store'), [
        'user_id' => $target->user_id,
        'jenis_referensi' => 'umum',
        'pesan' => 'Pengumuman kajian ba\'da isya',
    ])->assertRedirect();
});

