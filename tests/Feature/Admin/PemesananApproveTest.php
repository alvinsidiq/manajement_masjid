<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Notification as FacadeNotification;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('admin can approve pemesanan and send notifications', function(){
    FacadeNotification::fake();

    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $pemesan = User::factory()->create(['role'=>'user'])->assignRole('user');
    $ruang = Ruangan::factory()->create();
    $p = Pemesanan::factory()->create([
        'user_id'=>$pemesan->user_id,
        'ruangan_id'=>$ruang->ruangan_id,
        'status'=>'menunggu_verifikasi'
    ]);

    actingAs($admin);
    post(route('admin.pemesanan.approve',$p), ['catatan'=>'Silakan hadir 15 menit lebih awal.'])
        ->assertRedirect();

    $p->refresh();
    expect($p->status->value)->toBe('diterima');
});

