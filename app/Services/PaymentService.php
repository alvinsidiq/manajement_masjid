<?php

namespace App\Services;

use App\Models\Payment;
use App\Enums\{PaymentGateway, PaymentStatus, StatusPemesanan};
use App\Notifications\PaymentPaidReceipt;
use App\Services\PemesananService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(private PemesananService $pemesananService)
    {
    }

    public function create(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $p = Payment::create([
                'pemesanan_id' => $data['pemesanan_id'],
                'gateway' => $data['gateway'],
                'method' => $data['method'] ?? null,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'IDR',
                'status' => PaymentStatus::PENDING,
                'external_ref' => Str::upper(Str::random(12)),
                'expired_at' => now()->addHours(3),
                'payload_raw' => null,
            ]);

            if ($p->gateway === PaymentGateway::MIDTRANS) {
                $p->snap_url_or_qris = url('/pay/midtrans/'.$p->external_ref);
                $p->save();
            } elseif ($p->gateway === PaymentGateway::XENDIT) {
                // Generate invoice link immediately; front-end will redirect to it.
                $p->snap_url_or_qris = url('/pay/xendit/'.$p->external_ref);
                $p->save();
            }
            return $p;
        });
    }

    public function markPaid(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {
            $payment->status = PaymentStatus::PAID;
            $payment->paid_at = now();
            $payment->status_pembayaran = $payment->status_pembayaran ?? strtoupper(PaymentStatus::PAID->value);
            $payment->save();
            $payment->loadMissing('pemesanan');
            $this->completePemesananIfPaid($payment);
            $this->syncPemesananPaymentStatus($payment);
            $this->notifyPaid($payment);
            return $payment;
        });
    }

    public function processCallback(string $gateway, string $externalRef, string $newStatus, array $payload = []): ?Payment
    {
        $payment = Payment::where('external_ref', $externalRef)->first();
        if (!$payment) return null;
        $previousStatus = $payment->status;
        $status = match($newStatus){
            'paid' => PaymentStatus::PAID,
            'failed' => PaymentStatus::FAILED,
            'expired' => PaymentStatus::EXPIRED,
            'refunded' => PaymentStatus::REFUNDED,
            default => PaymentStatus::PENDING,
        };
        $payment->status = $status;
        if ($status === PaymentStatus::PAID && $previousStatus !== PaymentStatus::PAID) {
            $payment->paid_at = now();
        }
        $payment->status_pembayaran = strtoupper($status->value);
        $payment->payload_raw = $payload;
        $payment->save();
        if ($status === PaymentStatus::PAID && $previousStatus !== PaymentStatus::PAID) {
            $payment->loadMissing('pemesanan');
            $this->completePemesananIfPaid($payment);
            $this->syncPemesananPaymentStatus($payment);
            $this->notifyPaid($payment);
        }
        return $payment;
    }

    protected function completePemesananIfPaid(Payment $payment): void
    {
        $p = $payment->pemesanan;
        if (!$p) return;
        if ($payment->status !== PaymentStatus::PAID) return;
        if ($p->status !== StatusPemesanan::DITERIMA) return;

        $this->pemesananService->complete($p);
    }

    protected function syncPemesananPaymentStatus(Payment $payment): void
    {
        $p = $payment->pemesanan;
        if (!$p) return;
        $p->status_pembayaran = $payment->status_pembayaran ?? strtoupper($payment->status->value);
        $p->save();
    }

    protected function notifyPaid(Payment $payment): void
    {
        try {
            optional($payment->pemesanan?->user)->notify(new PaymentPaidReceipt($payment));
        } catch (\Throwable $e) {
            Log::warning('payment.paid.notify_failed', [
                'payment_id' => $payment->payment_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
