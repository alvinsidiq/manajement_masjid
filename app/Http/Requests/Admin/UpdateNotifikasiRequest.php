<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotifikasiRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }
    public function rules(): array
    {
        return [
            'pesan' => ['required','string','min:5','max:1000'],
            'status_pengiriman' => ['nullable','string','max:100'],
        ];
    }
}

