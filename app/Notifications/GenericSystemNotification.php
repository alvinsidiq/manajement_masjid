<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Channels\WhatsAppChannel;

class GenericSystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $message,
        public string $jenis = 'umum',
        public ?int $referensiId = null,
        public ?int $logId = null
    ){}

    public function via($notifiable): array
    { return ['mail', WhatsAppChannel::class]; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notifikasi Sistem')
            ->greeting('Assalamualaikum, '.$notifiable->username)
            ->line($this->message);
    }

    public function toWhatsApp($notifiable): array
    { return ['text' => $this->message]; }
}

