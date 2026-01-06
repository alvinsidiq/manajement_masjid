<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pemesanan;
use App\Notifications\Channels\WhatsAppChannel;

class PemesananCancelled extends Notification
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
            ->subject('Pemesanan Dibatalkan')
            ->greeting('Assalamualaikum, '.$notifiable->username)
            ->line('Pemesanan Anda telah dibatalkan oleh admin.')
            ->line('Ruangan: '.$this->p->ruangan->nama_ruangan)
            ->line('Tujuan: '.$this->p->tujuan_pemesanan)
            ->line('Alasan Pembatalan: '.($this->p->alasan_pembatalan ?: '-'))
            ->line('Waktu: '.optional($this->p->cancelled_at)->timezone('Asia/Jakarta')->format('d M Y H:i'));
    }

    public function toWhatsApp($notifiable): array
    {
        return [
            'jenis_referensi' => 'pemesanan',
            'referensi_id' => $this->p->pemesanan_id,
            'text' => 'Pemesanan DIBATALKAN: '.$this->p->ruangan->nama_ruangan.' — Alasan: '.($this->p->alasan_pembatalan ?: '-')
        ];
    }
}

