<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pemesanan;
use App\Notifications\Channels\WhatsAppChannel;

class PemesananRejected extends Notification
{
    use Queueable;

    public function __construct(public Pemesanan $p) {}

    public function via($notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemesanan Ditolak')
            ->greeting('Assalamualaikum, '.$notifiable->username)
            ->line('Mohon maaf, pemesanan Anda *ditolak*.')
            ->line('Ruangan: '.$this->p->ruangan->nama_ruangan)
            ->line('Tujuan: '.$this->p->tujuan_pemesanan)
            ->line('Alasan: '.($this->p->alasan_penolakan ?: '-'))
            ->line('Catatan: '.($this->p->catatan ?: '-'))
            ->line('Silakan ajukan ulang dengan penyesuaian.');
    }

    public function toWhatsApp($notifiable): array
    {
        return [
            'jenis_referensi' => 'pemesanan',
            'referensi_id' => $this->p->pemesanan_id,
            'text' => 'Pemesanan DITOLAK: '.$this->p->ruangan->nama_ruangan.' — Alasan: '.($this->p->alasan_penolakan ?: '-'),
        ];
    }
}

