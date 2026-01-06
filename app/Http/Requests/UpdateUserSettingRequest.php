<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'dark_mode' => ['sometimes','boolean'],
            'preferred_landing' => ['required','in:dashboard,home'],
        ];
    }

    public function messages(): array
    {
        return [
            'preferred_landing.required' => 'Preferensi landing wajib dipilih.',
            'preferred_landing.in' => 'Pilihan landing tidak valid.',
        ];
    }
}

