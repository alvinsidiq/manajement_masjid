<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Booking;
use App\Notifications\Channels\WhatsAppChannel;

class BookingHoldExpired extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    { return ['mail', WhatsAppChannel::class]; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Hold Kedaluwarsa')
            ->greeting('Assalamualaikum, '.$notifiable->username)
            ->line('Booking hold Anda telah kedaluwarsa:')
            ->line('Ruangan: '.$this->booking->ruangan->nama_ruangan)
            ->line('Tanggal: '.$this->booking->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y').' '.$this->booking->jam)
            ->line('Silakan buat booking ulang bila masih diperlukan.');
    }

    public function toWhatsApp($notifiable): array
    { return ['text' => 'Hold kedaluwarsa utk '.$this->booking->ruangan->nama_ruangan.' pada '.$this->booking->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y').' '.$this->booking->jam]; }
}

