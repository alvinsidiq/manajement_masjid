<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use Carbon\Carbon;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('filters pemesanan by date range', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    $ruang = Ruangan::factory()->create();

    $p1 = Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id,'created_at'=>Carbon::parse('2025-01-01 09:00','Asia/Jakarta')->utc()]);
    $p2 = Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id,'created_at'=>Carbon::parse('2025-01-10 09:00','Asia/Jakarta')->utc()]);

    get(route('admin.pemesanan.index',['date_from'=>'2025-01-01','date_to'=>'2025-01-05']))
        ->assertOk()
        ->assertSee((string)$p1->pemesanan_id)
        ->assertDontSee((string)$p2->pemesanan_id);
});

