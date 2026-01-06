<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pemesanan;
use App\Notifications\Channels\WhatsAppChannel;

class PemesananCreated extends Notification
{
    use Queueable;

    public function __construct(public Pemesanan $p) {}

    public function via($notifiable): array { return ['mail', WhatsAppChannel::class]; }

    public function toMail($n): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemesanan Dibuat')
            ->greeting('Assalamualaikum, '.$n->username)
            ->line('Pemesanan Anda menunggu verifikasi.')
            ->line('Ruangan: '.$this->p->ruangan->nama_ruangan)
            ->line('Tujuan: '.$this->p->tujuan_pemesanan);
    }

    public function toWhatsApp($n): array
    { return ['text'=>'Pemesanan dibuat: '.$this->p->ruangan->nama_ruangan.' (menunggu verifikasi).']; }
}

