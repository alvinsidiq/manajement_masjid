<?php

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pemesanan;
use App\Models\AuditLog;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('writes audit log on cancel', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $ruang = Ruangan::factory()->create();
    $p = Pemesanan::factory()->create(['ruangan_id'=>$ruang->ruangan_id,'status'=>'diterima']);

    actingAs($admin);
    post(route('admin.pemesanan.cancel',$p), ['alasan_pembatalan'=>'Perbaikan listrik.'])->assertRedirect();

    $log = AuditLog::where('action','pemesanan.cancel')->orderByDesc('id')->first();
    expect($log)->not()->toBeNull();
});

