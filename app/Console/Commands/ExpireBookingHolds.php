<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Enums\StatusBooking;
use App\Notifications\BookingHoldExpired;

class ExpireBookingHolds extends Command
{
    protected $signature = 'booking:expire-holds';
    protected $description = 'Menandai booking hold yang melewati hold_expires_at menjadi expired dan kirim notifikasi.';

    public function handle(): int
    {
        $now = now();
        $expired = Booking::query()
            ->with(['user','ruangan'])
            ->where('status', StatusBooking::HOLD)
            ->whereNotNull('hold_expires_at')
            ->where('hold_expires_at','<=',$now)
            ->get();

        foreach ($expired as $b) {
            $b->status = StatusBooking::EXPIRED;
            $b->save();
            if ($b->user) {
                $b->user->notify(new BookingHoldExpired($b));
            }
        }

        $this->info('Expired holds: '.$expired->count());
        return self::SUCCESS;
    }
}

