<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Pemesanan;
use Illuminate\Support\Carbon;

class JadwalService
{
    /**
     * Validasi konflik jadwal dan pemesanan (surrogate created_at)
     * @throws \InvalidArgumentException
     */
    public function assertNoConflicts(?int $ruanganId, string $mulai, string $selesai, ?int $ignoreJadwalId = null): void
    {
        $start = Carbon::parse($mulai, 'Asia/Jakarta')->utc();
        $end   = Carbon::parse($selesai, 'Asia/Jakarta')->utc();
        if ($end->lessThanOrEqualTo($start)) {
            throw new \InvalidArgumentException('Tanggal selesai harus setelah tanggal mulai.');
        }
        if (!$ruanganId) return;

        $overlap = Jadwal::query()
            ->when($ignoreJadwalId, fn($q)=>$q->where('jadwal_id','!=',$ignoreJadwalId))
            ->where('ruangan_id', $ruanganId)
            ->where(function($w) use ($start,$end){
                $w->where(function($q) use ($start,$end){
                    $q->where('tanggal_mulai','<=',$end)->where('tanggal_selesai','>=',$start);
                });
            })
            ->exists();
        if ($overlap) throw new \InvalidArgumentException('Konflik dengan jadwal lain pada ruangan yang sama.');

        $pemesananConflict = Pemesanan::query()
            ->where('ruangan_id', $ruanganId)
            ->whereIn('status', ['menunggu_verifikasi','diterima'])
            ->whereBetween('created_at', [$start, $end])
            ->exists();
        if ($pemesananConflict) throw new \InvalidArgumentException('Ada pemesanan aktif pada rentang tersebut.');
    }

    public function create(array $data): Jadwal
    {
        $this->assertNoConflicts($data['ruangan_id'] ?? null, $data['tanggal_mulai'], $data['tanggal_selesai']);
        return Jadwal::create($data);
    }

    public function update(Jadwal $jadwal, array $data): Jadwal
    {
        $this->assertNoConflicts($data['ruangan_id'] ?? null, $data['tanggal_mulai'], $data['tanggal_selesai'], $jadwal->getKey());
        $jadwal->update($data);
        return $jadwal;
    }
}

