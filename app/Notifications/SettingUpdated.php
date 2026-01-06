<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\UserSetting;
use App\Notifications\Channels\WhatsAppChannel;

class SettingUpdated extends Notification
{
    use Queueable;

    public function __construct(public UserSetting $setting) {}

    public function via($notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengaturan akun diperbarui')
            ->greeting('Assalamualaikum, '.$notifiable->username)
            ->line('Pengaturan akun Anda telah diperbarui:')
            ->line('• Mode Gelap: '.($this->setting->dark_mode ? 'Aktif' : 'Nonaktif'))
            ->line('• Halaman Awal: '.$this->setting->preferred_landing)
            ->line('Terima kasih.');
    }

    public function toWhatsApp($notifiable): array
    {
        return [
            'jenis_referensi' => 'umum',
            'text' => 'Pengaturan diperbarui. Dark mode: '.($this->setting->dark_mode?'Aktif':'Nonaktif').', landing: '.$this->setting->preferred_landing,
        ];
    }
}

