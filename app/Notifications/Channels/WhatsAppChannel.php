<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Models\Notifikasi;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }
        $message = $notification->toWhatsApp($notifiable);

        Notifikasi::create([
            'user_id' => $notifiable->getKey(),
            'referensi_id' => $message['referensi_id'] ?? null,
            'jenis_referensi' => $message['jenis_referensi'] ?? 'umum',
            'pesan' => $message['text'] ?? '',
            'terkirim' => true,
            'waktu_kirim' => now(),
            'status_pengiriman' => 'sent_dummy',
        ]);
    }
}

