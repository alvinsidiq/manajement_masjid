<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKegiatanRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        return [
            'nama_kegiatan' => ['required','string','max:150'],
            'deskripsi' => ['nullable','string'],
            'jenis_kegiatan' => ['required','in:rutin,berkala,khusus'],
            'approval_status' => ['nullable','in:pending,approved,rejected'],
            'penanggung_jawab' => ['nullable','string','max:120'],
            'no_telephone' => ['nullable','string','max:32','regex:/^[+0-9][0-9\-\s]{8,15}$/'],
            'foto' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'dokumen' => ['nullable','file','mimes:pdf,doc,docx,odt,xls,xlsx,ods','max:5120'],
        ];
    }
}
