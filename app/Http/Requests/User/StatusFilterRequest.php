<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StatusFilterRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'type' => ['nullable','in:all,booking,pemesanan'],
            'status' => ['nullable','string','max:30'], // nilai enum akan divalidasi di query
            'q' => ['nullable','string','max:100'],
            'date_from' => ['nullable','date'],
            'date_to' => ['nullable','date','after_or_equal:date_from'],
            'page' => ['nullable','integer','min:1']
        ];
    }

    public function messages(): array
    {
        return [
            'date_to.after_or_equal' => 'Sampai tanggal tidak boleh lebih awal dari mulai tanggal.'
        ];
    }
}