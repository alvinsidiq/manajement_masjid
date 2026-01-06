<?php

use App\Models\User;
use App\Services\NotifikasiService;
use App\Enums\JenisReferensi;
use Illuminate\Support\Facades\Queue;

it('creates a notifikasi log and queues notification', function(){
    Queue::fake();
    $u = User::factory()->create()->assignRole('user');
    $n = app(NotifikasiService::class)->sendGeneric($u->user_id, 'Pesan uji', JenisReferensi::UMUM, null);

    expect($n->exists)->toBeTrue();
    expect($n->status_pengiriman)->toBe('queued');
});

