<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $userId = (int) auth()->user()->user_id;
        return [
            'username' => ['required','string','min:3','max:50', Rule::unique('users','username')->ignore($userId, 'user_id')],
            'email' => ['required','email', Rule::unique('users','email')->ignore($userId, 'user_id')],
            'no_telephone' => ['nullable','string','max:25','regex:/^\+?[0-9\- ]{8,20}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'no_telephone.regex' => 'Nomor telepon hanya boleh angka, spasi, tanda + atau -.',
        ];
    }
}