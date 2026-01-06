<?php

use App\Models\User;
use App\Models\Notifikasi;
use App\Notifications\GenericSystemNotification;
use Illuminate\Notifications\Events\NotificationSent;

it('marks log as sent when NotificationSent fired', function(){
    $u = User::factory()->create()->assignRole('user');
    $n = Notifikasi::create([
        'user_id'=>$u->user_id,
        'jenis_referensi'=>'umum',
        'pesan'=>'Halo',
        'status_pengiriman'=>'queued',
        'terkirim'=>false,
    ]);
    $notification = new GenericSystemNotification('Halo','umum',null,$n->notifikasi_id);

    event(new NotificationSent($u, $notification, 'mail', null));

    $n->refresh();
    expect($n->terkirim)->toBeTrue();
    expect($n->status_pengiriman)->toBe('sent:mail');
});

