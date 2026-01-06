<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;
use Xendit\Exceptions\ApiException;
use Xendit\Invoice;
use Xendit\Xendit;

class XenditService
{
    public function __construct(private ?string $secretKey = null)
    {
    }

    protected function setApiKey(): void
    {
        $key = $this->secretKey ?? config('services.xendit.secret_key');
        if (!$key) {
            throw new \RuntimeException('Xendit secret key is not configured.');
        }
        Xendit::setApiKey($key);
    }

    /**
     * Create invoice and return the raw Xendit response.
     *
     * @param array{external_id:string,payer_email:?string,description:?string,amount:int,success_redirect_url:string,failure_redirect_url:string} $payload
     * @return array{id:?string,invoice_url:?string,status:?string}
     * @throws ApiException
     */
    public function createInvoice(array $payload): array
    {
        $this->setApiKey();

        try {
            $invoice = Invoice::create($payload);
        } catch (ApiException $e) {
            Log::error('Xendit invoice creation failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            throw $e;
        }

        return [
            'id' => $invoice['id'] ?? null,
            'invoice_url' => $invoice['invoice_url'] ?? null,
            'status' => $invoice['status'] ?? null,
        ];
    }

    public function mapInvoiceStatus(?string $status): PaymentStatus
    {
        return match (strtolower((string) $status)) {
            'paid', 'settled' => PaymentStatus::PAID,
            'failed' => PaymentStatus::FAILED,
            'expired' => PaymentStatus::EXPIRED,
            default => PaymentStatus::PENDING,
        };
    }
}
