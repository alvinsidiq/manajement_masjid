<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array
    {
        return [
            'ruangan_id'   => ['required','integer','exists:ruangan,ruangan_id'],
            'hari_tanggal' => ['required','date','after_or_equal:today'],
            'jam'          => ['required','date_format:H:i'],
            'tujuan'       => ['required','string','min:5','max:200'], // akan diteruskan ke Pemesanan
        ];
    }
    public function messages(): array
    {
        return [
            'ruangan_id.required' => 'Pilih ruangan.',
            'hari_tanggal.after_or_equal' => 'Tanggal tidak boleh lampau.',
            'jam.date_format' => 'Format jam HH:ii.',
        ];
    }
}