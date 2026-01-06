<?php

use App\Models\{User, Pemesanan, Payment};
use Illuminate\Support\Facades\Notification as FacadeNotification;
use function Pest\Laravel\postJson; use function Pest\Laravel\actingAs;

it('processes simulated callback to paid and notifies', function(){
    FacadeNotification::fake();
    $admin = User::factory()->create()->assignRole('admin');
    $pem = Pemesanan::factory()->create();

    actingAs($admin);
    $payment = Payment::factory()->create(['pemesanan_id'=>$pem->pemesanan_id,'gateway'=>'midtrans','status'=>'pending']);
    postJson(route('callback.payment',[ 'gateway'=>'midtrans', 'externalRef'=>$payment->external_ref ]), ['status'=>'paid'])
        ->assertOk();

    $payment->refresh();
    expect($payment->status->value)->toBe('paid');
});

