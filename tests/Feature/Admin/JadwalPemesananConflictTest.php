<?php

use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('rejects when pemesanan exists in that time range on same room', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $k = Kegiatan::factory()->create();
    $r = Ruangan::factory()->create();

    Pemesanan::factory()->create([
        'ruangan_id' => $r->ruangan_id,
        'status' => 'diterima',
        'created_at' => now()->addDays(5)->utc(),
        'updated_at' => now()->addDays(5)->utc(),
    ]);

    actingAs($admin);
    post(route('admin.jadwal.store'), [
        'kegiatan_id' => $k->kegiatan_id,
        'ruangan_id' => $r->ruangan_id,
        'tanggal_mulai' => now()->addDays(5)->subHour()->format('Y-m-d H:i:s'),
        'tanggal_selesai' => now()->addDays(5)->addHour()->format('Y-m-d H:i:s'),
        'status' => 'khusus',
    ])->assertSessionHasErrors();
});

