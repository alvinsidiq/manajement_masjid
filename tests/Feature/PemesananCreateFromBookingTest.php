<?php

use App\Models\{User,Ruangan,Booking};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\post;

it('creates pemesanan from booking and submits booking', function(){
    $admin = User::factory()->create()->assignRole('admin');
    $user = User::factory()->create()->assignRole('user');
    $ruang = Ruangan::factory()->create();
    $booking = Booking::factory()->create(['user_id'=>$user->user_id,'ruangan_id'=>$ruang->ruangan_id,'status'=>'hold']);

    actingAs($admin);
    post(route('admin.pemesanan.store'), [
        'user_id'=>$user->user_id,
        'ruangan_id'=>$ruang->ruangan_id,
        'booking_id'=>$booking->booking_id,
        'tujuan_pemesanan'=>'Acara kajian',
    ])->assertRedirect();

    $booking->refresh();
    expect($booking->status)->toBe(StatusBooking::SUBMITTED);
});

