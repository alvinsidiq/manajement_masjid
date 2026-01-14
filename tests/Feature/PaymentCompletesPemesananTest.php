<?php

use App\Models\{User, Ruangan, Booking, Pemesanan, Payment};
use App\Services\{PaymentService, BookingAvailabilityService};
use App\Enums\{PaymentGateway, PaymentStatus, StatusPemesanan};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('marks pemesanan selesai after payment is paid and keeps the slot reserved', function () {
    $user = User::factory()->create();
    $ruangan = Ruangan::factory()->create(['status'=>'aktif']);
    $tanggal = Carbon::now('Asia/Jakarta')->addDay()->startOfDay();
    $jam = '10:00:00';

    $booking = Booking::factory()->create([
        'user_id' => $user->user_id,
        'ruangan_id' => $ruangan->ruangan_id,
        'hari_tanggal' => $tanggal->clone()->utc(),
        'jam' => $jam,
        'status' => 'setuju',
        'hold_expires_at' => Carbon::now()->subMinutes(10),
    ]);

    $pemesanan = Pemesanan::factory()->create([
        'user_id' => $user->user_id,
        'ruangan_id' => $ruangan->ruangan_id,
        'booking_id' => $booking->booking_id,
        'status' => StatusPemesanan::DITERIMA->value,
        'tujuan_pemesanan' => 'Uji Pembayaran',
    ]);

    $payment = Payment::factory()->create([
        'pemesanan_id' => $pemesanan->pemesanan_id,
        'gateway' => PaymentGateway::MANUAL->value,
        'status' => PaymentStatus::PENDING,
    ]);

    $paidPayment = app(PaymentService::class)->markPaid($payment);

    expect($paidPayment->status)->toBe(PaymentStatus::PAID);
    $pemesanan->refresh();
    expect($pemesanan->status)->toBe(StatusPemesanan::SELESAI);

    $availability = app(BookingAvailabilityService::class);
    expect($availability->isAvailable($ruangan->ruangan_id, $tanggal, $jam))->toBeFalse();
});
