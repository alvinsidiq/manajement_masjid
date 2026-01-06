<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required','integer','exists:booking,booking_id','unique:pemesanan,booking_id'],
            'tujuan_pemesanan' => ['required','string','min:5','max:255'],
            'catatan' => ['nullable','string','max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_id.unique' => 'Booking ini sudah memiliki pemesanan.',
        ];
    }
}
