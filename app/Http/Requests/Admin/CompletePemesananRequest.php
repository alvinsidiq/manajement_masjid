<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CompletePemesananRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasAnyRole(['admin','takmir']) ?? false; }
    public function rules(): array { return []; }
}

