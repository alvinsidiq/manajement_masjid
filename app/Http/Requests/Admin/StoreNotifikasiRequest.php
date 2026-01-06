<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotifikasiRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasAnyRole(['admin','takmir']) ?? false; }
    public function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,user_id'],
            'jenis_referensi' => ['required','in:pemesanan,payment,kegiatan,umum'],
            'referensi_id' => ['nullable','integer'],
            'pesan' => ['required','string','min:5','max:1000'],
        ];
    }
}

