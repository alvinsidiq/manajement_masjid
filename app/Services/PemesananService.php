<?php

namespace App\Services;

use App\Enums\StatusPemesanan;
use App\Enums\StatusBooking;
use App\Models\{Pemesanan, Ruangan, Booking};
use App\Notifications\{PemesananCreated, PemesananApproved, PemesananRejected, PemesananCancelled, PemesananCompleted};
use Illuminate\Support\Facades\DB;

class PemesananService
{
    public function create(array $data): Pemesanan
    {
        return DB::transaction(function() use ($data){
            $data['status'] = $data['status'] ?? StatusPemesanan::MENUNGGU->value;
            $p = Pemesanan::create($data);
            if (!empty($data['booking_id'])) {
                $b = Booking::find($data['booking_id']);
                if ($b && $b->status->value === StatusBooking::HOLD->value) {
                    $b->status = StatusBooking::SUBMITTED; $b->save();
                }
            }
            optional($p->user)->notify(new PemesananCreated($p));
            return $p;
        });
    }

    public function approve(Pemesanan $p, ?string $catatan = null): Pemesanan
    {
        $p->status = StatusPemesanan::DITERIMA;
        if ($catatan) $p->catatan = $catatan;
        $p->alasan_penolakan = null;
        $p->save();
        $this->updateSlotRuangan($p->ruangan);
        optional($p->user)->notify(new PemesananApproved($p));
        return $p;
    }

    public function reject(Pemesanan $p, string $alasan, ?string $catatan = null): Pemesanan
    {
        $p->status = StatusPemesanan::DITOLAK;
        $p->alasan_penolakan = $alasan;
        if ($catatan) $p->catatan = $catatan;
        $p->save();
        if ($p->booking) { $p->booking->status = StatusBooking::CANCELLED; $p->booking->save(); }
        optional($p->user)->notify(new PemesananRejected($p));
        return $p;
    }

    protected function updateSlotRuangan(Ruangan $ruangan): void
    {
        $ruangan->touch();
    }

    public function cancel(Pemesanan $p, ?string $alasan = null, ?string $catatan = null, ?int $byUserId = null, ?string $ip = null, ?string $ua = null): Pemesanan
    {
        $before = ['status' => $p->status->value];

        $p->status = StatusPemesanan::DIBATALKAN;
        if ($alasan) $p->alasan_pembatalan = $alasan;
        if ($catatan) $p->catatan = $catatan;
        $p->dibatalkan_oleh = $byUserId;
        $p->cancelled_at = now();
        $p->save();

        if ($p->booking) { $p->booking->status = StatusBooking::CANCELLED; $p->booking->save(); }

        // Audit log (schema existing in project)
        try {
            \App\Models\AuditLog::create([
                'user_id' => $byUserId,
                'action'  => 'pemesanan.cancel',
                'ip' => $ip,
                'user_agent' => $ua ? substr((string)$ua, 0, 255) : null,
                'context' => [
                    'entity_type' => Pemesanan::class,
                    'entity_id'   => $p->getKey(),
                    'changes' => [
                        'before' => $before,
                        'after'  => ['status' => $p->status->value, 'alasan_pembatalan' => $alasan],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {}

        optional($p->user)->notify(new PemesananCancelled($p));
        return $p;
    }

    public function complete(Pemesanan $p): Pemesanan
    {
        if ($p->status === StatusPemesanan::SELESAI) {
            return $p;
        }
        if ($p->status !== StatusPemesanan::DITERIMA) {
            return $p;
        }
        $p->status = StatusPemesanan::SELESAI;
        $p->save();
        optional($p->user)->notify(new PemesananCompleted($p));
        return $p;
    }
}
