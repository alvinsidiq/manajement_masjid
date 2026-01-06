<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasAnyRole(['admin','takmir']) ?? false; }

    public function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,user_id'],
            'ruangan_id' => ['required','integer','exists:ruangan,ruangan_id'],
            'jadwal_id' => ['nullable','integer','exists:jadwal,jadwal_id'],
            'booking_id' => ['nullable','integer','exists:booking,booking_id','unique:pemesanan,booking_id'],
            'tujuan_pemesanan' => ['required','string','min:5','max:255'],
            'catatan' => ['nullable','string','max:1000'],
            'status' => ['nullable','in:menunggu_verifikasi,diterima,ditolak,dibatalkan,selesai'],
        ];
    }
}

