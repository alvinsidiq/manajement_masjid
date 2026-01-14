<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        $id = $this->route('user')->user_id ?? null;
        return [
            'username' => ['required','string','min:3','max:50','unique:users,username,'.$id.',user_id'],
            'email' => ['required','email','unique:users,email,'.$id.',user_id'],
            'password' => ['nullable','string','min:8'],
            'role' => ['required','in:admin,user,bendahara,takmir'],
            'is_active' => ['required','boolean'],
            'is_verified' => ['nullable','boolean'],
        ];
    }
}
