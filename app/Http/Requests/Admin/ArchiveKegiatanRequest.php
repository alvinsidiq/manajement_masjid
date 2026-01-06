<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArchiveKegiatanRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }
    public function rules(): array
    {
        return [
            'archive_reason' => ['required','string','min:5','max:1000'],
        ];
    }
    public function messages(): array
    {
        return [ 'archive_reason.required' => 'Alasan arsip wajib diisi.' ];
    }
}

