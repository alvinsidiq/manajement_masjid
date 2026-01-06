<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UnarchiveKegiatanRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }
    public function rules(): array
    {
        return [
            // no fields
        ];
    }
}

