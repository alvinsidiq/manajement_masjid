<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payment;
use App\Notifications\Channels\WhatsAppChannel;

class PaymentPaidReceipt extends Notification
{
    use Queueable; public function __construct(public Payment $payment) {}

    public function via($notifiable): array
    { return ['mail', WhatsAppChannel::class]; }

    public function toMail($n): MailMessage
    {
        $p = $this->payment; $pem = $p->pemesanan;
        return (new MailMessage)
            ->subject('Kwitansi Pembayaran #'.$p->payment_id)
            ->greeting('Assalamualaikum, '.$n->username)
            ->line('Pembayaran Anda telah diterima ('.$p->gateway->value.')')
            ->line('Pemesanan: #'.$pem->pemesanan_id.' — '.$pem->tujuan_pemesanan)
            ->line('Jumlah: '.number_format($p->amount,2,',','.').' '.$p->currency)
            ->line('Waktu bayar: '.optional($p->paid_at)->timezone('Asia/Jakarta')->format('d M Y H:i'));
    }

    public function toWhatsApp($n): array
    {
        $p = $this->payment;
        return ['text' => 'Kwitansi: Pembayaran #'.$p->payment_id.' diterima. Jumlah '.number_format($p->amount,2,',','.').' '.$p->currency];
    }
}

