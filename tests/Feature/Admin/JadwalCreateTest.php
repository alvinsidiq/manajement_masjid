<?php

use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Ruangan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('allows admin to create jadwal', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $k = Kegiatan::factory()->create();
    $r = Ruangan::factory()->create();

    actingAs($admin);
    post(route('admin.jadwal.store'), [
        'kegiatan_id' => $k->kegiatan_id,
        'ruangan_id' => $r->ruangan_id,
        'tanggal_mulai' => now()->addDay()->format('Y-m-d H:i:s'),
        'tanggal_selesai' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
        'status' => 'khusus',
        'catatan' => 'Uji coba',
    ])->assertRedirect();
});

