<?php

namespace App\Services;

use App\Models\{Notifikasi, User};
use App\Enums\JenisReferensi;
use App\Notifications\GenericSystemNotification;

class NotifikasiService
{
    public function sendGeneric(int $userId, string $pesan, JenisReferensi $jenis = JenisReferensi::UMUM, ?int $referensiId = null): Notifikasi
    {
        $n = Notifikasi::create([
            'user_id' => $userId,
            'referensi_id' => $referensiId,
            'jenis_referensi' => $jenis->value,
            'pesan' => $pesan,
            'terkirim' => false,
            'status_pengiriman' => 'queued',
        ]);

        $user = User::findOrFail($userId);
        $notif = new GenericSystemNotification($pesan, $jenis->value, $referensiId, $n->notifikasi_id);
        $user->notify($notif);
        return $n;
    }
}

