<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class DaftarKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required','string','max:150'],
            'email' => ['required','email','max:150'],
            'no_telephone' => ['required','string','max:32','regex:/^[+0-9][0-9\-\s]{8,15}$/'],
            'catatan' => ['nullable','string','max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'no_telephone.required' => 'Nomor telepon wajib diisi.',
            'no_telephone.regex' => 'Format nomor telepon tidak valid.',
        ];
    }
}
