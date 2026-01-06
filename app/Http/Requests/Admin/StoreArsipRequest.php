<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreArsipRequest extends FormRequest
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
            'dokumen' => ['required','file','mimes:pdf,doc,docx,odt,xls,xlsx,ods,ppt,pptx','max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul arsip wajib diisi.',
            'dokumen.required' => 'Dokumen arsip wajib diunggah.',
        ];
    }
}
