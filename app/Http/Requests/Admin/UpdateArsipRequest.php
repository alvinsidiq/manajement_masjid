<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArsipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required','string','max:150'],
            'deskripsi' => ['nullable','string'],
            'dokumen' => ['nullable','file','mimes:pdf,doc,docx,odt,xls,xlsx,ods,ppt,pptx','max:10240'],
        ];
    }
}
