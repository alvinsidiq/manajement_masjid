<?php

namespace App\Http\Requests\Bendahara;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasAnyRole(['admin','bendahara']) ?? false; }
    public function rules(): array
    {
        return [
            'pemesanan_id' => ['required','integer','exists:pemesanan,pemesanan_id'],
            'gateway' => ['required','in:manual,midtrans,xendit'],
            'method' => ['nullable','string','max:100'],
            'amount' => ['required','numeric','min:0'],
            'currency' => ['required','string','max:8'],
        ];
    }
}

