<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pemesanan;
use App\Notifications\Channels\WhatsAppChannel;

class PemesananApproved extends Notification
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
            ->subject('Pemesanan Diterima')
            ->greeting('Assalamualaikum, '.$notifiable->username)
            ->line('Pemesanan Anda telah *diterima*.')
            ->line('Ruangan: '.$this->p->ruangan->nama_ruangan)
            ->line('Tujuan: '.$this->p->tujuan_pemesanan)
            ->line('Catatan: '.($this->p->catatan ?: '-'))
            ->line('Terima kasih.');
    }

    public function toWhatsApp($notifiable): array
    {
        return [
            'jenis_referensi' => 'pemesanan',
            'referensi_id' => $this->p->pemesanan_id,
            'text' => 'Pemesanan DITERIMA: '.$this->p->ruangan->nama_ruangan.' — '.$this->p->tujuan_pemesanan,
        ];
    }
}

