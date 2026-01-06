<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pemesanan;
use App\Notifications\Channels\WhatsAppChannel;

class PemesananCompleted extends Notification
{
    use Queueable;

    public function __construct(public Pemesanan $p) {}

    public function via($n): array { return ['mail', WhatsAppChannel::class]; }

    public function toMail($n): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemesanan Selesai')
            ->greeting('Assalamualaikum, '.$n->username)
            ->line('Terima kasih, pemesanan telah diselesaikan.');
    }

    public function toWhatsApp($n): array
    { return ['text'=>'Pemesanan SELESAI. Terima kasih.']; }
}

