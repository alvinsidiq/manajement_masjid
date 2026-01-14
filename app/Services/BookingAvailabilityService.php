<?php

namespace App\Services;

use App\Models\{Booking, Pemesanan};
use App\Enums\{StatusBooking, StatusPemesanan};
use Illuminate\Support\Carbon;

class BookingAvailabilityService
{
    /**
     * Periksa apakah slot (ruangan, hari_tanggal, jam) tersedia.
     * Menganggap jadwal konflik bila:
     *  - Ada booking lain status HOLD/PROSES/SETUJU pada slot yang sama dan belum expired
     *  - Ada pemesanan DITERIMA/SELESAI pada tanggal/jam yang sama (booking/jadwal)
     */
    public function isAvailable(int $ruanganId, Carbon $tanggal, string $jam): bool
    {
        $start = (clone $tanggal)->copy()->setTimeFromTimeString($jam);
        // Cek booking aktif
        $existsBooking = Booking::query()
            ->where('ruangan_id', $ruanganId)
            ->whereIn('status', [StatusBooking::HOLD->value, StatusBooking::PROSES->value, StatusBooking::SETUJU->value])
            ->where('hari_tanggal', $tanggal->clone()->utc())
            ->where('jam', $jam)
            ->where(function($q){
                $q->where('status','!=', StatusBooking::HOLD->value)
                  ->orWhereNull('hold_expires_at')
                  ->orWhere('hold_expires_at','>', now());
            })
            ->exists();
        if ($existsBooking) return false;

        // Cek pemesanan diterima pada hari yang sama (jam sederhana: exact match / window sama)
        $existsAccepted = Pemesanan::query()
            ->where('ruangan_id', $ruanganId)
            ->whereIn('status', [StatusPemesanan::DITERIMA->value, StatusPemesanan::SELESAI->value])
            ->whereHas('booking', function($b) use ($tanggal,$jam){
                $b->where('hari_tanggal', $tanggal->clone()->utc())->where('jam',$jam);
            })
            ->exists();
        return !$existsAccepted;
    }
}
