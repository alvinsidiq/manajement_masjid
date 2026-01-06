<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApprovePemesananRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('takmir') ?? false; }

    public function rules(): array
    {
        return [
            'catatan' => ['nullable','string','max:1000'],
        ];
    }
}
