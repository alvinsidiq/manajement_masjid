<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CancelPemesananRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array
    { return [ 'reason' => ['required','string','min:5','max:300'] ]; }
}