<?php

namespace App\Http\Controllers\Payment;

use App\Enums\{PaymentGateway, PaymentStatus};
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\{PaymentService, XenditService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Xendit\Exceptions\ApiException;

class XenditController extends Controller
{
    public function __construct(
        private XenditService $xendit,
        private PaymentService $payments,
    ) {
    }

    public function pay(Request $request, string $externalRef)
    {
        $payment = Payment::with('pemesanan.user')
            ->where('external_ref', $externalRef)
            ->where('gateway', PaymentGateway::XENDIT->value)
            ->firstOrFail();

        $user = $request->user();
        $isOwner = $user && $payment->pemesanan && $payment->pemesanan->user_id === $user->user_id;
        if (!$user || (!$isOwner && !$user->hasAnyRole(['admin', 'bendahara']))) {
            abort(403);
        }

        if ($payment->status === PaymentStatus::PAID && $payment->invoice_url) {
            return redirect()->away($payment->invoice_url);
        }

        if ($payment->xendit_transaction_id && $payment->invoice_url) {
            return redirect()->away($payment->invoice_url);
        }

        $amount = (int) round($payment->amount);
        if ($amount <= 0) {
            $amount = (int) round($payment->pemesanan?->ruangan?->harga ?? 100000);
            $payment->amount = $amount;
            $payment->save();
        }

        $pemesanan = $payment->pemesanan;
        $payerEmail = $pemesanan?->user?->email ?? $user->email ?? null;

        try {
            $invoice = $this->xendit->createInvoice([
                'external_id' => $payment->external_ref ?? ('payment-'.$payment->payment_id),
                'payer_email' => $payerEmail,
                'description' => $pemesanan
                    ? 'Pembayaran pemesanan #'.$pemesanan->pemesanan_id.' - '.$pemesanan->tujuan_pemesanan
                    : 'Pembayaran booking',
                'amount' => $amount,
                'success_redirect_url' => route('payment.success', $externalRef),
                'failure_redirect_url' => route('payment.failed', $externalRef),
            ]);
        } catch (ApiException $e) {
            Log::error('Xendit create invoice error', ['error' => $e->getMessage(), 'payment_id' => $payment->payment_id]);
            return back()->withErrors(['payment' => 'Gagal membuat invoice. Silakan coba lagi.']);
        }

        $statusPembayaran = strtoupper((string) ($invoice['status'] ?? PaymentStatus::PENDING->value));

        $payment->fill([
            'xendit_transaction_id' => $invoice['id'],
            'invoice_url' => $invoice['invoice_url'],
            'status_pembayaran' => $statusPembayaran,
            'snap_url_or_qris' => $invoice['invoice_url'] ?? $payment->snap_url_or_qris,
        ]);
        $payment->save();

        if ($pemesanan) {
            $pemesanan->fill([
                'xendit_transaction_id' => $invoice['id'],
                'invoice_url' => $invoice['invoice_url'],
                'status_pembayaran' => $statusPembayaran,
            ])->save();
        }

        return redirect()->away($invoice['invoice_url']);
    }

    public function success(string $externalRef)
    {
        return view('payment.status', [
            'title' => 'Pembayaran diproses',
            'message' => 'Terima kasih, pembayaran Anda sedang diverifikasi. Kode: '.$externalRef,
        ]);
    }

    public function failed(string $externalRef)
    {
        return view('payment.status', [
            'title' => 'Pembayaran gagal',
            'message' => 'Pembayaran tidak berhasil atau dibatalkan. Silakan coba lagi atau hubungi pengelola. Kode: '.$externalRef,
        ]);
    }

    public function webhook(Request $request)
    {
        $token = config('services.xendit.webhook_token');
        $headerToken = $request->header('X-Callback-Token') ?? $request->header('x-callback-token');
        if ($token && $headerToken !== $token) {
            return response()->json(['message' => 'invalid signature'], 401);
        }

        $payload = $request->all();
        $invoiceId = data_get($payload, 'id');
        $externalId = data_get($payload, 'external_id');
        $rawStatus = strtoupper((string) data_get($payload, 'status'));

        if (!$invoiceId && !$externalId) {
            return response()->json(['message' => 'invalid payload'], 400);
        }

        $payment = Payment::with('pemesanan.user')
            ->where(function ($q) use ($invoiceId, $externalId) {
                if ($invoiceId) {
                    $q->where('xendit_transaction_id', $invoiceId);
                }
                if ($externalId) {
                    $invoiceId
                        ? $q->orWhere('external_ref', $externalId)
                        : $q->where('external_ref', $externalId);
                }
            })
            ->first();

        if (!$payment) {
            return response()->json(['message' => 'not found'], 404);
        }

        $mappedStatus = $this->xendit->mapInvoiceStatus($rawStatus);
        $normalizedForCallback = match ($mappedStatus) {
            PaymentStatus::PAID => 'paid',
            PaymentStatus::FAILED => 'failed',
            PaymentStatus::EXPIRED => 'expired',
            default => 'pending',
        };

        $statusChanged = $payment->status !== $mappedStatus || $payment->status_pembayaran !== $rawStatus;
        if ($statusChanged) {
            $payment = $this->payments->processCallback(
                PaymentGateway::XENDIT->value,
                $payment->external_ref,
                $normalizedForCallback,
                $payload
            ) ?? $payment;
        } else {
            $payment->payload_raw = $payload;
            $payment->save();
        }

        $payment->xendit_transaction_id = $payment->xendit_transaction_id ?? $invoiceId;
        $payment->invoice_url = $payload['invoice_url'] ?? $payment->invoice_url;
        $payment->status_pembayaran = $rawStatus ?: $payment->status_pembayaran;
        $payment->snap_url_or_qris = $payment->invoice_url ?? $payment->snap_url_or_qris;
        $payment->save();

        if ($payment->pemesanan) {
            $payment->pemesanan->fill([
                'xendit_transaction_id' => $payment->xendit_transaction_id,
                'invoice_url' => $payment->invoice_url,
                'status_pembayaran' => $payment->status_pembayaran,
            ])->save();
        }

        return response()->json(['message' => 'ok']);
    }
}
