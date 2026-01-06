<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class InformasiFilterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'q' => ['nullable','string','max:100'],
            'month' => ['nullable','integer','between:1,12'],
            'year' => ['nullable','integer','between:2000,'.date('Y')],
        ];
    }
}

