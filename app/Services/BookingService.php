<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Enums\StatusBooking;
use Illuminate\Support\Carbon;

class BookingService
{
    public function assertNoConflicts(int $ruanganId, string $hariTanggal, string $jam, ?int $ignoreId = null): void
    {
        $slot = Carbon::parse(\Carbon\Carbon::parse($hariTanggal, 'Asia/Jakarta')->format('Y-m-d').' '.$jam, 'Asia/Jakarta')->utc();

        $exists = Booking::query()
            ->when($ignoreId, fn($q)=>$q->where('booking_id','!=',$ignoreId))
            ->where('ruangan_id', $ruanganId)
            ->whereIn('status', ['hold','submitted'])
            ->where(function($w) use ($slot){
                $w->where(function($q) use ($slot){
                    $q->whereDate('hari_tanggal', $slot->toDateString())
                      ->where('jam', $slot->timezone('Asia/Jakarta')->format('H:i'));
                })
                ->orWhere(function($q){
                    $q->where('status','hold')->where('hold_expires_at','>', now());
                });
            })
            ->exists();
        if ($exists) throw new \InvalidArgumentException('Slot sudah terpegang/aktif.');

        $inJadwal = Jadwal::query()
            ->where('ruangan_id',$ruanganId)
            ->where('tanggal_mulai','<=',$slot)
            ->where('tanggal_selesai','>=',$slot)
            ->exists();
        if ($inJadwal) throw new \InvalidArgumentException('Slot berada dalam rentang jadwal kegiatan.');

        $inPemesanan = Pemesanan::query()
            ->where('ruangan_id',$ruanganId)
            ->whereIn('status',['menunggu_verifikasi','diterima'])
            ->whereDate('created_at', $slot->toDateString())
            ->exists();
        if ($inPemesanan) throw new \InvalidArgumentException('Ada pemesanan aktif pada hari tersebut.');
    }

    public function create(array $data): Booking
    {
        $this->assertNoConflicts($data['ruangan_id'], $data['hari_tanggal'], $data['jam']);
        if (empty($data['hold_expires_at'])) {
            $data['hold_expires_at'] = Carbon::parse($data['hari_tanggal'].' '.$data['jam'], 'Asia/Jakarta')->addMinutes(60)->utc();
        }
        return Booking::create($data);
    }

    public function update(Booking $b, array $data): Booking
    {
        $this->assertNoConflicts($data['ruangan_id'], $data['hari_tanggal'], $data['jam'], $b->booking_id);
        $b->update($data);
        return $b;
    }

    public function expire(Booking $b): Booking
    {
        $b->status = StatusBooking::EXPIRED;
        $b->hold_expires_at = now();
        $b->save();
        return $b;
    }

    public function cancel(Booking $b): Booking
    {
        $b->status = StatusBooking::CANCELLED;
        $b->save();
        return $b;
    }
}

