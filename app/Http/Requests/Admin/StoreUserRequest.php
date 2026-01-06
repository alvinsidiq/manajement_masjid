<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        return [
            'username' => ['required','string','min:3','max:50','unique:users,username'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','string','min:8'],
            'role' => ['required','in:admin,user,bendahara,takmir'],
            'is_active' => ['required','boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'role.in' => 'Peran tidak valid.',
        ];
    }
}

