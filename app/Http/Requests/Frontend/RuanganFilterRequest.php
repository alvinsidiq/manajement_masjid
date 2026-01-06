<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RuanganFilterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'q' => ['nullable','string','max:100'],
            'status' => ['nullable','in:aktif,nonaktif,maintenance'],
            'fasilitas' => ['nullable','string','max:50'],
            'sort' => ['nullable','in:nama_ruangan,created_at'],
            'dir' => ['nullable','in:asc,desc'],
        ];
    }
}

