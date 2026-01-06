<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Notifications\Messages\MailMessage;

class GenericInfo extends Notification
{
    use Queueable;

    public function __construct(public string $subject, public string $content, public array $meta = []) {}

    public function via($notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->content);
    }

    public function toWhatsApp($notifiable): array
    {
        return [
            'referensi_id' => $this->meta['ref_id'] ?? null,
            'jenis_referensi' => $this->meta['jenis'] ?? 'umum',
            'text' => $this->content,
        ];
    }
}

