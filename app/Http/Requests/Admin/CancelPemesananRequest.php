<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CancelPemesananRequest extends FormRequest
{
    public function authorize(): bool {
        $user = auth()->user();
        $p = $this->route('pemesanan');
        return $user ? ($user->hasAnyRole(['admin','takmir']) || ($p && $p->user_id === $user->user_id)) : false;
    }

    public function rules(): array
    {
        return [
            'alasan_pembatalan' => ['nullable','string','min:5','max:1000'],
            'catatan' => ['nullable','string','max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ];
    }
}
