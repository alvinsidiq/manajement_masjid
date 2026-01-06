<?php

namespace App\Http\Requests\Bendahara;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasAnyRole(['admin','bendahara']) ?? false; }
    public function rules(): array
    {
        return [
            'method' => ['nullable','string','max:100'],
            'amount' => ['required','numeric','min:0'],
            'currency' => ['required','string','max:8'],
            'status' => ['required','in:pending,paid,failed,expired,refunded'],
        ];
    }
}

