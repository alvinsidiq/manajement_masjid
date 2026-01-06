<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RejectPemesananRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('takmir') ?? false; }

    public function rules(): array
    {
        return [
            'alasan_penolakan' => ['required','string','min:5','max:1000'],
            'catatan' => ['nullable','string','max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_penolakan.required' => 'Mohon isi alasan penolakan.',
        ];
    }
}
