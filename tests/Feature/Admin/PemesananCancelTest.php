<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Notification as FacadeNotification;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('admin can cancel accepted pemesanan', function(){
    FacadeNotification::fake();

    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $pemesan = User::factory()->create(['role'=>'user'])->assignRole('user');
    $ruang = Ruangan::factory()->create();
    $p = Pemesanan::factory()->create([
        'user_id'=>$pemesan->user_id,
        'ruangan_id'=>$ruang->ruangan_id,
        'status'=>'diterima'
    ]);

    actingAs($admin);
    post(route('admin.pemesanan.cancel',$p), [
        'alasan_pembatalan'=>'Kendala teknis di lokasi.',
        'catatan'=>'Mohon maaf atas ketidaknyamanan.'
    ])->assertRedirect();

    $p->refresh();
    expect($p->status->value)->toBe('dibatalkan');
    expect($p->alasan_pembatalan)->toBe('Kendala teknis di lokasi.');
    expect($p->cancelled_at)->not()->toBeNull();
});

