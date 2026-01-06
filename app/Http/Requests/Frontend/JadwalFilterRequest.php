<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class JadwalFilterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'month' => ['nullable','integer','between:1,12'],
            'year'  => ['nullable','integer','between:2000,'. $currentYear],
            'view'  => ['nullable','in:calendar,list'],
            'q'     => ['nullable','string','max:100'],
        ];
    }
}
