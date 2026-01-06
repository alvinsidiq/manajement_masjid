<?php

use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Ruangan;
use App\Models\Jadwal;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('rejects overlapping jadwal on same room', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $k = Kegiatan::factory()->create();
    $r = Ruangan::factory()->create();

    $j1 = Jadwal::factory()->create([
        'kegiatan_id'=>$k->kegiatan_id,
        'ruangan_id'=>$r->ruangan_id,
        'tanggal_mulai'=>now()->addDays(2),
        'tanggal_selesai'=>now()->addDays(2)->addHours(2),
    ]);

    actingAs($admin);
    post(route('admin.jadwal.store'), [
        'kegiatan_id' => $k->kegiatan_id,
        'ruangan_id' => $r->ruangan_id,
        'tanggal_mulai' => now()->addDays(2)->addHour()->format('Y-m-d H:i:s'),
        'tanggal_selesai' => now()->addDays(2)->addHours(3)->format('Y-m-d H:i:s'),
        'status' => 'khusus',
    ])->assertSessionHasErrors();
});

